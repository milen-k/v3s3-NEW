<?php

namespace App\Modules\V3s3\Models;

use Illuminate\Database\Eloquent\Model as LV5_Model;

class V3s3 extends LV5_Model {
	const STATUS_DELETED = 0;
	const STATUS_ACTIVE = 1;

	private $getterFilterFunction = [];

	public $timestamps = false;

	protected $connection = 'mysql-v3s3';
	protected $table = 'store';
	protected $fillable = [
		'timestamp'=>'timestamp',
		'date_time'=>'date_time',
		'ip'=>'ip',
		'hash_name'=>'hash_name',
		'name'=>'name',
		'data'=>'data',
		'mime_type'=>'mime_type',
		'status'=>'status',
		'timestamp_deleted'=>'timestamp_deleted',
		'date_time_deleted'=>'date_time_deleted',
		'ip_deleted_from'=>'ip_deleted_from',
	];

	public function put(Array $attr) {
		$attr = array_intersect_key($attr, $this->fillable);
		$attr['timestamp'] = ($attr['timestamp']??time());
		$attr['date_time'] = date('Y-m-d H:i:s O', $attr['timestamp']);
		if(isset($attr['name'])) {
			$attr['hash_name'] = sha1($attr['name']);
		} else {
			unset($attr['hash_name']);
		}
		$attr['status'] = ($attr['status']??self::STATUS_ACTIVE);
		unset($attr['id']);

		$this->fill($attr);

		$this->save();

		return $this;
	}

	public function get(Array $attr) {
		$attr = array_intersect_key($attr, $this->fillable);
		if(isset($attr['name'])) {
			$attr['hash_name'] = sha1($attr['name']);
		} else {
			unset($attr['hash_name']);
		}
		unset($attr['name']);

		$entityResultSet = $this->where($attr)->orderBy('id', 'desc');

		$rows_count = $entityResultSet->count();
		if(empty($rows_count)) {
			return false;
		}

		$entity = $entityResultSet->first();
		if($entity->isDeleted()) {
			return false;
		}

		return $entity;
	}

	public function api_delete(Array $attr) {
		$attr = array_intersect_key($attr, $this->fillable);
		$attr['timestamp_deleted'] = ($attr['timestamp_deleted']??time());
		$attr['date_time_deleted'] = date('Y-m-d H:i:s O', $attr['timestamp_deleted']);
		if(isset($attr['name'])) {
			$attr['hash_name'] = sha1($attr['name']);
		} else {
			unset($attr['hash_name']);
		}
		$attr['status'] = ($attr['status']??self::STATUS_DELETED);
		unset($attr['name']);

		$where = $attr;
		unset($where['status']);
		unset($where['timestamp_deleted']);
		unset($where['date_time_deleted']);
		unset($where['ip_deleted_from']);
		$entityResultSet = $this->where($where)->orderBy('id', 'desc');

		$rows_count = $entityResultSet->count();
		if(empty($rows_count)) {
			return false;
		}

		$entity = $entityResultSet->first();
		if($entity->isDeleted()) {
			return false;
		}

		$entity->fill($attr);
		$entity->save();

		return $entity;
	}

	public function post(Array $attr) {
		$attr = array_intersect_key($attr, $this->fillable);
		if(isset($attr['name'])) {
			$attr['hash_name'] = sha1($attr['name']);
		} else {
			unset($attr['hash_name']);
		}
		unset($attr['name']);

		$rows = $this->where($attr)->get();
		$rows_count = $rows->count();

		return (!empty($rows_count)?$rows:[]);
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
