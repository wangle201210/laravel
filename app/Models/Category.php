<?php

namespace App\Models;
use App\FilterAndSorting;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use FilterAndSorting, SoftDeletes;
	protected $table = 'categories';
}
