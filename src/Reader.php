<?php namespace Jhesyong\Excel;

use PHPExcel_IOFactory;
use Jhesyong\Excel\Reader\Sheet;

class Reader
{
	protected $excel;

	protected $sheetIndex = 0;

	public static function readFile($filename)
	{
		return new static($filename);
	}

	public function __construct($filename)
	{
		$filetype = PHPExcel_IOFactory::identify($filename);

		$this->excel = PHPExcel_IOFactory::createReader($filetype)->load($filename);
	}

	public function sheetCount()
	{
		return $this->excel->getSheetCount();
	}

	public function nextSheet()
	{
		if ($this->sheetIndex < $this->getSheetCount())
		{
			return Sheet::wrap($this->excel->getSheet($this->sheetIndex++));
		}

		return null;
	}

	public function __call($name, $args)
	{
		return call_user_func_array([$this->excel, $name], $args);
	}
}