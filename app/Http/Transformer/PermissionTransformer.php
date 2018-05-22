<?php
namespace App\Http\Transformer;

use App\Permission;
use League\Fractal\TransformerAbstract;

class PermissionTransformer extends TransformerAbstract
{
    protected $availableIncludes = [];
    protected $defaultIncludes = [];

    public function transform(Permission $item)
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'display_name' => $item->display_name,
            'description' => $item->description,
            'created_at' => $item->created_at,
        ];
    }
}
