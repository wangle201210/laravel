<?php
namespace App\Http\Transformer;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract {

	public function transform(Category $item) {
		return $item->toArray();
	}
}
