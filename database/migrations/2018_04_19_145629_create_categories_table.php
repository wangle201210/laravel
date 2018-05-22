<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('order')->default(0)->comment('排序');
            $table->unsignedInteger('user_id')->nullable()->comment('创建人');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->string('title');
            $table->string('type')->comment('新闻|视屏等分类');
            $table->integer('depth')->nullable()->comment('深度');
            $table->integer('parent_id')->default(0)->comment('父层id');
            $table->boolean('display')->default(1)->comment('是否隐藏');
            $table->string('picture')->nullable()->comment('栏目缩略图');
            $table->text('description')->nullable()->comment('栏目简介');
            $table->integer('point')->nullable()->default(0)->comment('点击量');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('categories');
    }
}
