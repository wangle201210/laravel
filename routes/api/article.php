<?php
$api->group([], function ($api) {
	$api->post('/articles', [
		'uses' => 'ArticleController@store',
		'as' => 'api.article.store',
	]);
	$api->get('/articles', [
		'uses' => 'ArticleController@index',
		'as' => 'api.article.index',
	]);
	$api->get('/article/{id}', [
		'uses' => 'ArticleController@show',
		'as' => 'api.article.show',
	]);
	$api->put('/article/{id}', [
		'uses' => 'ArticleController@update',
		'as' => 'api.article.update',
	]);
	$api->delete('/article/{id}', [
		'uses' => 'ArticleController@destroy',
		'as' => 'api.article.destroy',
	]);
});