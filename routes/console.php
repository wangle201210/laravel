<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('make:doc', function () {
	$this->comment(env('APP_NAME', 'wangle') . env('DOCFILE', 'index') . ' 文档生成中');
	$mdPath = 'public/apidoc/';
	Artisan::call('api:docs', [
		'--name' => env('APP_NAME', 'wangle'),
		'--use-version' => env('API_VERSION', 'v1'),
		'--output-file' => $mdPath . env('DOCFILE', 'wangle') . '.md',
	]);
	$cmd = 'pandoc --standalone -c "../css/pandoc.css" ' . $mdPath . env('DOCFILE', 'wangle') . '.md --output ' . $mdPath . env('DOCFILE', 'wangle') . '.html';
	$handle = popen($cmd, 'r');
	pclose($handle);
	$this->comment($cmd . PHP_EOL . '执行完毕!');
	// 不确定成功与否 因此仅写执行
	// stream_get_contents 获得文档内容才使用
})->describe('Make docs');