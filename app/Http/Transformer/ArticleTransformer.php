<?php
namespace App\Http\Transformer;

use App\Models\Article;
use League\Fractal\TransformerAbstract;

class ArticleTransformer extends TransformerAbstract {

	public function transform(Article $item) {
		return $item->toArray();
	}
}
