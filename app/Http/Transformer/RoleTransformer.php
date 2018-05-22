<?php
namespace App\Http\Transformer;

use App\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract {
	protected $availableIncludes = [];
	protected $defaultIncludes = [];

	public function transform(Role $item) {
		return [
			'id' => $item->id,
			'name' => $item->name,
			'display_name' => $item->display_name,
			'description' => $item->description,
			'created_at' => $item->created_at,
		];
	}
}
