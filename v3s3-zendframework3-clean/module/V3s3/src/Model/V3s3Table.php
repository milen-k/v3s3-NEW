<?php

namespace V3s3\Model;

use Zend\Db\TableGateway\TableGatewayInterface as ZF3_TableGatewayInterface;
use Zend\Db\Metadata\Metadata as ZF3_Metadata;
use Zend\DB\Sql\Select as ZF3_Select;

use V3s3\Model\V3s3;

use V3s3\Exception\V3s3InputValidationException;

class V3s3Table {
	private $tableGateway;

	public function __construct(ZF3_TableGatewayInterface $tableGateway) {
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll() {
		return $this->tableGateway->select();
	}

	public function select($select) {
		return $this->tableGateway->select($select);
	}

	public function save(Array $attr) {
		$id = (!empty($attr['id'])?(int)$attr['id']:0);

		if ($id === 0) {
			$this->tableGateway->insert($attr);
			$id = $this->tableGateway->getLastInsertValue();
		} else if ($this->select(
			[
				'id'=>$id
			]
		)) {
			$this->tableGateway->update($attr, ['id' => $id]);
		} else {
			try {
				throw new V3s3InputValidationException($this->translator->translate('V3S3_EXCEPTION_DB_CANNOT_UPDATE_RECORD_NO_MATCH'), V3s3InputValidationException::DB_CANNOT_UPDATE_ROW_NO_MATCH);
			} catch(V3s3InputValidationException $e) {
				return false;
			}
		}

		$entity = new V3s3;
		$entity->fromArray(array_replace($attr, ['id'=>$id]));
		return $entity;
	}

	public function deleteRow($id) {
		$this->tableGateway->delete(
			[
				'id' => (int) $id
			]
		);
	}

	public function put(Array $attr) {
		$metadata = new ZF3_Metadata($this->tableGateway->getAdapter());
		$columns = $metadata->getColumnNames($this->tableGateway->getTable());
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

		return $this->save($attr);
	}

	public function get(Array $attr) {
		$metadata = new ZF3_Metadata($this->tableGateway->getAdapter());
		$columns = $metadata->getColumnNames($this->tableGateway->getTable());
		$columns = array_combine($columns, $columns);

		$attr = array_intersect_key($attr, $columns);
		if(isset($attr['name'])) {
			$attr['hash_name'] = sha1($attr['name']);
		} else {
			unset($attr['hash_name']);
		}
		unset($attr['name']);

		$entityResultSet = $this->select(
			function(ZF3_Select $select) use($attr) {
				$select->where($attr)->order('id DESC')->limit(1);
			}
		);

		$rows_count = $entityResultSet->count();
		if(empty($rows_count)) {
			return false;
		}

		$entity = $entityResultSet->current();
		if($entity->isDeleted()) {
			return false;
		}

		return $entity;
	}

	public function api_delete(Array $attr) {
		$metadata = new ZF3_Metadata($this->tableGateway->getAdapter());
		$columns = $metadata->getColumnNames($this->tableGateway->getTable());
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
		$entityResultSet = $this->select(
			function(ZF3_Select $select) use($where) {
				$select->where($where)->order('id DESC')->limit(1);
			}
		);

		$rows_count = $entityResultSet->count();
		if(empty($rows_count)) {
			return false;
		}

		$entity = $entityResultSet->current();
		if($entity->isDeleted()) {
			return false;
		}

		return $this->save(array_replace($entity->toArray(), $attr));
	}

	public function post(Array $attr) {
		$metadata = new ZF3_Metadata($this->tableGateway->getAdapter());
		$columns = $metadata->getColumnNames($this->tableGateway->getTable());
		$columns = array_combine($columns, $columns);

		unset($attr['name']);

		$attr = array_intersect_key($attr, $columns);

		$rows = $this->select(
			function(ZF3_Select $select) use($attr) {
				$select->where($attr);
			}
		);
		$rows_count = $rows->count();

		return (!empty($rows_count)?$rows->toArray():[]);
	}
}