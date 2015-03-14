<?php

require __DIR__.'/../vendor/autoload.php';
date_default_timezone_set("Asia/Taipei");

use Jhesyong\Excel\Parser;

$parser = new Parser();

$parser->addHeader('Name');
$parser->addHeader('Phone', 'phone_1');
$parser->addHeader('Phone', 'phone_2');

$parser->loadFile(__DIR__.'/sample-2.xlsx')->parse(function($data)
{
	var_dump($data);
});