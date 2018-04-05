<?php
namespace App\Http\Transformer;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract {

	public function transform(User $item) {
		return $item->toArray();
	}
}
