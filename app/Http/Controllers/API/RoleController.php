<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformer\RoleTransformer;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class RoleController
 * @package App\Http\Controllers
 * @resource Role
 *
 * Controller for testing
 */
class RoleController extends Controller {
	/**
	 * Do no pipe
	 *
	 * return \Illuminate\Http\Response
	 */
	public function __construct() {
		$this->middleware('role:creater')->except(['index', 'show']);
	}
	/**
	 * 显示角色列表 role list
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		$per_page = getAnyData($request, 'per_page', 20);
		$filter = json_decode($request->filter, true);
		if (user()->roles->count() == 1 && user()->hasRole('hruser')) {
			$filter['id'] = [
				"operation" => "in",
				"value" => [6, 9, 10],
			];
			$request->merge(['filter' => json_encode($filter)]);
		} else if (user()->hasRole('cityer_leader')) {
			$filter['id'] = [
				"operation" => "in",
				"value" => [4, 5, 6, 9, 10],
			];
			$request->merge(['filter' => json_encode($filter)]);
		} else if (user()->hasRole('creater_leader')) {
			$filter['id'] = [
				"operation" => "in",
				"value" => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
			];
			$request->merge(['filter' => json_encode($filter)]);
		} else {
			$filter['id'] = [
				"operation" => "in",
				"value" => [2, 3, 4, 5, 6, 8, 9, 10],
				// 除了最高管理权限1 及财务主管7
			];
			$request->merge(['filter' => json_encode($filter)]);
		}
		$request->merge(['filter' => json_encode($filter)]);
		$roles = Role::setFilterAndRelationsAndSort($request)->paginate($per_page);
		return $this->response->paginator($roles, new RoleTransformer);
		// return response()->json($this->gtdPaginate($roles), 200); //列表数据 状态码 200
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		abort(401);
	}

	/**
	 * 增加角色Add role
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Requests\RolePermissionRequest $request) {
		$role = new Role();
		$role->name = jsonData($request, 'name');
		$role->display_name = jsonData($request, 'display_name'); // optional
		$role->description = jsonData($request, 'description'); // optional
		$role->save();
		return response()->json($role, 201); //添加数据,返回添加的行,状态码 201
		return $this->response->item($role, new RoleTransformer)->setStatusCode(201);
	}

	/**
	 * 显示角色信息Display role info
	 *
	 * @param  \App\Role  $role
	 * @return \Illuminate\Http\Response
	 */
	public function show(Role $role, $id) {
		$ids = getOneOrAll($id);
		if (is_array($ids)) {
			$role = Role::with(['permissions' => function ($q) {
				$q->select('id', 'name', 'display_name', 'description');
			}])->whereIn('id', $ids)->get();
			return $this->response->collection($role, new RoleTransformer); //列表数据 状态码 200
		} else {
			$role = Role::with(['permissions' => function ($q) {
				$q->select('id', 'name', 'display_name', 'description');
			}])->find($ids);
			return $this->response->item($role, new RoleTransformer); //列表数据 状态码 200
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Role  $role
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Role $role) {
		//
	}

	/**
	 * 更新角色信息Update role info
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Role  $role
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Role $role, $id) {
		$role = Role::find($id);
		foreach ($role->getOriginal() as $key => $value) {
			$role->{$key} = jsonData($request, $key, $value);
		}
		$role->save();
		return $this->response->item($role, new RoleTransformer)->setStatusCode(201);
	}

	/**
	 * 删除角色Remove role
	 *
	 * @param  \App\Role  $role
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Role $role) {
		abort(401);
	}
	/**
	 * 给角色添加权限attach permission for role
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function attachPermission(Requests\AttachPermissionRequest $request, $id) {
		$role = Role::findOrFail($id);
		$permissionId = jsonData($request, 'permission_id');
		if (is_null($permissionId)) {
			abort(402);
		}
		$permissionIds = getOneOrAll($permissionId);
		$role->permissions()->sync($permissionIds);
		$role = Role::with(['permissions' => function ($q) {
			$q->select('id', 'name', 'display_name', 'description');
		}])->find($id);
		// dd($role);
		return $this->response->item($role, new RoleTransformer)->setStatusCode(201);
	}
}
