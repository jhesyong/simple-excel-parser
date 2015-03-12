<?php namespace Jhesyong\Excel;

use Closure;
use Exception;

use Jhesyong\Excel\Parser\Header;

class Parser
{
	protected $headers = [];

	protected $reader = null;

	public function addHeader($raw, $field = null)
	{
		$header = Header::make($raw, $field);

		$this->headers[] = $header;

		return $header;
	}

	public function loadFile($filename)
	{
		$this->reader = Reader::readFile($filename);

		return $this;
	}

	public function parse(Closure $process)
	{
		if ( ! $this->reader)
		{
			throw new Exception('File is not loaded.');
		}

		while ($sheet = $this->reader->nextSheet())
		{
			$this->parseSheet($sheet, $process);
		}

		return $this;
	}

	protected function parseSheet($sheet, Closure $process)
	{
		$rawHeader = $sheet->header();

		$headers = $this->matchHeader($rawHeader);

		while ($rawRow = $sheet->nextRow())
		{
			$row = $this->matchRow($rawRow, $headers);

			$process($row);
		}
	}

	protected function matchHeader($rawHeader)
	{
		$match = function($title)
		{
			$headers = $this->headers;

			foreach ($headers as $key => $header)
			{
				if ($header->match($title))
				{
					if ($header->onlyOnce()) unset($headers[$key]);

					return $header->withTitle($title);
				}
			}

			return null;
		};

		return array_map($match, $rawHeader);
	}

	protected function matchRow($rawRow, $headers)
	{
		$entity = [];

		for ($index = 0; $index < count($rawRow); $index++)
		{
			if ( ! $header = $headers[$index]) continue;

			$entity[$header->getKey()] = $header->parseValue($rawRow[$index]);
		}

		return $entity;
	}
}