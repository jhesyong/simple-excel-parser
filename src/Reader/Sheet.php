<?php namespace Jhesyong\Excel\Reader;

class Sheet
{
	protected $sheet;

	protected $highestColumn;

	protected $highestRow;

	protected $rowIndex = 1;

	public static function wrap($sheet)
	{
		return new static($sheet);
	}

	public function __construct($sheet)
	{
		$this->sheet = $sheet;
		$this->highestRow = $sheet->getHighestRow();
		$this->highestColumn = $sheet->getHighestColumn();
	}

	public function header()
	{
		$this->rowIndex = 1;

		return $this->nextRow();
	}

	public function nextRow()
	{
		if ($this->rowIndex > $this->highestRow) return null;

		$range = "A{$this->rowIndex}:{$this->highestColumn}{$this->rowIndex}";
		$this->rowIndex++;

		$data = $this->sheet->rangeToArray($range, null, true, false);
		$row = $data[0];

		return $row;
	}

	public function __call($name, $args)
	{
		return call_user_func_array([$this->sheet, $name], $args);
	}
}