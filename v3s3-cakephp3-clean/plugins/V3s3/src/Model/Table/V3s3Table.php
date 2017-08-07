<?php
namespace V3s3\Model\Table;

use Cake\ORM\Table as CP3_Table;
use Cake\Validation\Validator as CP3_Validator;

use V3s3\Model\Entity\V3s3;

/**
 * V3s3 Model
 *
 * @method \App\Model\Entity\V3s3 get($primaryKey, $options = [])
 * @method \App\Model\Entity\V3s3 newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\V3s3[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\V3s3|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\V3s3 patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\V3s3[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\V3s3 findOrCreate($search, callable $callback = null, $options = [])
 */
class V3s3Table extends CP3_Table {

	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config) {
		parent::initialize($config);

		$this->setTable('store');
		$this->setDisplayField('name');
		$this->setPrimaryKey('id');
	}

	/**
	 * Default validation rules.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 * @return \Cake\Validation\Validator
	 */
	public function validationDefault(CP3_Validator $validator) {
		$validator
			->integer('id')
			->allowEmpty('id', 'create');

		$validator
			->integer('timestamp')
			->allowEmpty('timestamp');

		$validator
			->allowEmpty('date_time');

		$validator
			->allowEmpty('ip');

		$validator
			->allowEmpty('hash_name');

		$validator
			->allowEmpty('name');

		$validator
			->allowEmpty('data');

		$validator
			->allowEmpty('mime_type');

		$validator
			->integer('status')
			->allowEmpty('status');

		$validator
			->integer('timestamp_deleted')
			->allowEmpty('timestamp_deleted');

		$validator
			->allowEmpty('date_time_deleted');

		$validator
			->allowEmpty('ip_deleted_from');

		return $validator;
	}

	/**
	 * Returns the database connection name to use by default.
	 *
	 * @return string
	 */
	public static function defaultConnectionName() {
		return 'V3s3';
	}

	public function put(Array $attr) {
		$columns = $this->getSchema()->columns();
		$columns = array_combine($columns, $columns);

		$attr = array_intersect_key($attr, $columns);
		$attr['timestamp'] = ($attr['timestamp']??time());
		$attr['date_time'] = date('Y-m-d H:i:s O', $attr['timestamp']);
		if(isset($attr['name'])) {
			$attr['hash_name'] = sha1($attr['name']);
		} else {
			unset($attr['hash_name']);
		}
		$attr['status'] = ($attr['status']??V3s3::STATUS_ACTIVE);
		unset($attr['id']);

		return $this->save($this->newEntity($attr));
	}

	public function api_get(Array $attr) {
		$columns = $this->getSchema()->columns();
		$columns = array_combine($columns, $columns);

		$attr = array_intersect_key($attr, $columns);
		if(isset($attr['name'])) {
			$attr['hash_name'] = sha1($attr['name']);
		} else {
			unset($attr['hash_name']);
		}
		unset($attr['name']);

		$entityResultSet = $this->find()->where($attr)->order('id', 'DESC')->limit(1)->all();

		$rows_count = $entityResultSet->count();
		if(empty($rows_count)) {
			return false;
		}

		$entity = $entityResultSet->first();
		if($entity->isDeleted()) {
			return false;
		}
		/*
		foreach($row as $key=>&$value) {
			if(is_resource($value) && (get_resource_type($value) == 'stream')) {
				$value = stream_get_contents($value);
			}
		}
		*/

		return $entity;
	}

	public function api_delete(Array $attr) {
		$columns = $this->getSchema()->columns();
		$columns = array_combine($columns, $columns);

		$attr = array_intersect_key($attr, $columns);
		$attr['timestamp_deleted'] = ($attr['timestamp_deleted']??time());
		$attr['date_time_deleted'] = date('Y-m-d H:i:s O', $attr['timestamp_deleted']);
		if(isset($attr['name'])) {
			$attr['hash_name'] = sha1($attr['name']);
		} else {
			unset($attr['hash_name']);
		}
		$attr['status'] = ($attr['status']??V3s3::STATUS_DELETED);
		unset($attr['name']);

		$where = $attr;
		unset($where['status']);
		unset($where['timestamp_deleted']);
		unset($where['date_time_deleted']);
		unset($where['ip_deleted_from']);
		$entityResultSet = $this->find()->where($where)->order('id', 'DESC')->limit(1)->all();

		$rows_count = $entityResultSet->count();
		if(empty($rows_count)) {
			return false;
		}

		$entity = $entityResultSet->first();
		if($entity->isDeleted()) {
			return false;
		}

		return $this->save($this->newEntity(array_replace($entity->toArray(), $attr)));
	}

	public function post(Array $attr) {
		$columns = $this->getSchema()->columns();
		$columns = array_combine($columns, $columns);

		unset($attr['name']);

		$attr = array_intersect_key($attr, $columns);

		$rows = $this->find()->where($attr)->all();
		$rows_count = $rows->count();

		$rows =
			(
				!empty($rows_count)?
				$rows->map(
					function($a) {
						return $a->toArray();
					}
				)->toArray():
				[]
			);
		foreach($rows as &$row) {
			foreach($row as &$value) {
				if(is_resource($value) && (get_resource_type($value) == 'stream')) {
					$value = stream_get_contents($value);
				}
			}
		}

		return $rows;
	}
}
