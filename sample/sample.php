<?php

require __DIR__.'/../vendor/autoload.php';
date_default_timezone_set("Asia/Taipei");

use Jhesyong\Excel\Parser;

$parser = new Parser();

$parser->addHeader('Name', 'name');
$parser->addHeader('Age', 'age');
$parser->addHeader('Gender', 'gender')->withOptions(['m' => 'Male', 'f' => 'Female']);
$parser->addHeader('/^Phone \d+$/', function($title) { return str_replace(' ', '_', strtolower($title)); });

$parser->loadFile(__DIR__.'/sample.xlsx')->parse(function($data)
{
	var_dump($data);
});