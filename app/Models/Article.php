<?php

namespace App\Models;
use App\FilterAndSorting;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use FilterAndSorting, SoftDeletes;
	protected $table = 'articles';

	public function cate()
	{
		return $this->belongsTo('App\Models\Category','category_id')->withTrashed();
	}

}
