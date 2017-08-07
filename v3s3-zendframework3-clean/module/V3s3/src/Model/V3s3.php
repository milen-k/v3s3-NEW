<?php

namespace V3s3\Model;

use Zend\Stdlib\ArrayObject as ZF3_ArrayObject;

class V3s3 extends ZF3_ArrayObject {
	const STATUS_DELETED = 0;
	const STATUS_ACTIVE = 1;

	private $getterFilterFunction = [];

	public $id;
	public $timestamp;
	public $date_time;
	public $ip;
	public $hash_name;
	public $name;
	public $data;
	public $mime_type;
	public $status;
	public $timestamp_deleted;
	public $date_time_deleted;
	public $ip_deleted_from;

	public function __construct(Array $attr = []) {
		$this->fromArray($attr);
	}

	/**
	 * Populate from native PHP array
	 *
	 * @param  array $attr
	 * @return void
	 */
	public function fromArray(Array $attr) {
		if(!is_array($attr)) {
			return false;
		}

		foreach($attr as $key=>$value) {
			if(property_exists($this, $key)) {
				$this->{$key} = $value;
			}
		}

		parent::exchangeArray($attr);

		return $this;
	}

	public function exchangeArray($attr) {
		$this->fromArray($attr);
	}

	/**
	 * Populate from query string
	 *
	 * @param  string $string
	 * @return void
	 */
	public function fromString($string)
	{
		$array = [];
		parse_str($string, $array);
		$this->fromArray($array);
	}

	/**
	 * Serialize to native PHP array
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->getArrayCopy();
	}



	// TODO: V3s3EntityAccessInterface
	public function getProperty($key) {
		if(!is_string($key) || !property_exists($this, $key)) {
			// TODO: throw V3s3EntityGetterException
		}

		if(isset($this->getterFilterFunction[$key])) {
			return $this->getterFilterFunction[$key]($this->{$key});
		} else {
			return $this->{$key};
		}
	}

	public function setProperty($key, $value) {
		if(!is_string($key) || !property_exists($this, $key)) {
			// TODO: throw V3s3EntitySetterException
		}

		$this->{$key} = $value;
		return $this;
	}

	public function isDeleted() {
		return ((int)$this->status === self::STATUS_DELETED);
	}
}