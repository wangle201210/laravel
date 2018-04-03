<?php
namespace App\Http\Transformer;

use App\Models\Test;
use League\Fractal\TransformerAbstract;

class TestTransformer extends TransformerAbstract {

	public function transform(Test $item) {
		$item->title = $item->test.$item->id;
		return $item->toArray();
	}
}
