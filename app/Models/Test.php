<?php

namespace App\Models;
use App\FilterAndSorting;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use FilterAndSorting;
	protected $table = 'test';
}
