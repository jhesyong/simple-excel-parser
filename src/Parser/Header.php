<?php namespace Jhesyong\Excel\Parser;

use Closure;
use InvalidArgumentException;

class Header
{
	protected $raw;

	protected $field;

	protected $isRegex;

	protected $key = null;

	protected $options = null;

	public static function make($raw, $field = null, $options = [])
	{
		if ($field === null) $field = $raw;

		return new static($raw, $field, $options);
	}

	protected function __construct($raw, $field, $options)
	{
		$this->raw = $raw;
		$this->field = $field;
		$this->options = $options;

		$this->isRegex = preg_match('/^\/.+\/[a-z]*$/i', $raw);

		if ($this->isRegex and ! $this->field instanceof Closure)
		{
			throw new InvalidArgumentException("Closure is expected if the regex is given.");
		}
	}

	public function withOptions(array $options)
	{
		$this->options = array_flip($options);

		return $this;
	}

	public function match($str)
	{
		if ($this->isRegex and preg_match($this->raw, $str)
			or ! $this->isRegex and $this->raw === $str)
		{
			return true;
		}

		return null;
	}

	public function onlyOnce()
	{
		return ! $this->isRegex;
	}

	public function withTitle($title)
	{
		if ($this->isRegex)
		{
			$title = call_user_func($this->field, $title);
		}
		else
		{
			$title = $this->field;
		}

		return self::make($this->raw, $this->field, $this->options)->setKey($title);
	}

	public function setKey($key)
	{
		$this->key = $key;

		return $this;
	}

	public function getKey()
	{
		return $this->key;
	}

	public function parseValue($value)
	{
		if ( ! $this->options) return $value;

		return array_key_exists($value, $this->options) ? $this->options[$value] : null;
	}
}