<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('order')->default(0)->comment('排序');
            $table->unsignedInteger('user_id')->nullable()->comment('作者');;
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->unsignedInteger('user_id_edited')->nullable()->comment('最后一次修改人');;
            $table->foreign('user_id_edited')->references('id')->on('users')->onDelete('set null');
            $table->unsignedInteger('category_id')->nullable()->comment('分类');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content');
            $table->string('source_url')->nullable()->comment('来源链接');
            $table->string('source')->nullable()->comment('来源名称');
            $table->string('picture')->nullable()->comment('缩略图');
            $table->boolean('tops')->nullable()->default(0)->comment('置顶');
            $table->boolean('is_comment')->nullable()->default(1)->comment('是否允许评论');
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
        Schema::drop('articles');
    }
}
