<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformer\PermissionTransformer;
use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class PermissionController
 * @package App\Http\Controllers
 * @resource Permission
 *
 * Controller for testing
 */
class PermissionController extends Controller {
	public function __construct() {
		$this->middleware('role:creater')->except(['index', 'show']);
	}
	/**
	 * 获取权限列表permission list
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		$per_page = getAnyData($request, 'per_page', 20);
		$permissions = Permission::setFilterAndRelationsAndSort($request)->paginate($per_page);
		return $this->response->paginator($permissions, new PermissionTransformer);
		// return response()->json($this->gtdPaginate($permissions), 200); //列表数据 状态码 200
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
	 * 增加权限Add permission
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Requests\PermissionRequest $request) {
		$permission = new Permission();
		$permission->name = jsonData($request, 'name');
		$permission->display_name = jsonData($request, 'display_name'); // optional
		$permission->description = jsonData($request, 'description'); // optional
		$permission->save();
		return $this->response->item($permission, new PermissionTransformer)->setStatusCode(201);
	}

	/**
	 * 显示权限信息Display permission
	 *
	 * @param  \App\Permission  $permission
	 * @return \Illuminate\Http\Response
	 */
	public function show(Permission $permission, $id) {
		$ids = getOneOrAll($id);
		if (is_array($ids)) {
			$permission = Permission::select('id', 'name', 'display_name', 'description')->whereIn('id', $ids)->get();
			return $this->response->collection($permission, new PermissionTransformer); //列表数据 状态码 200
		} else {
			$permission = Permission::select('id', 'name', 'display_name', 'description')->find($ids);
			return $this->response->item($permission, new PermissionTransformer); //列表数据 状态码 200
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Permission  $permission
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Permission $permission) {
		//
	}

	/**
	 * 更新权限信息Update permission
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Permission  $permission
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Permission $permission, $id) {
		$permission = Permission::find($id);
		foreach ($permission->getOriginal() as $key => $value) {
			$permission->{$key} = jsonData($request, $key, $value);
		}
		$permission->save();
		return $this->response->item($permission, new PermissionTransformer)->setStatusCode(201);
	}

	/**
	 * 删除权限Remove permission
	 *
	 * @param  \App\Permission  $permission
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Permission $permission) {
		abort(401);
	}
}
