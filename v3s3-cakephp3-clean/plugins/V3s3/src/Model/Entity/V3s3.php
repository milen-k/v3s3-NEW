<?php
namespace V3s3\Model\Entity;

use Cake\ORM\Entity;

/**
 * V3s3 Entity
 *
 * @property int $id
 * @property int $timestamp
 * @property string $date_time
 * @property string $ip
 * @property string $hash_name
 * @property string|resource $name
 * @property string|resource $data
 * @property string $mime_type
 * @property int $status
 * @property int $timestamp_deleted
 * @property string $date_time_deleted
 * @property string $ip_deleted_from
 */
class V3s3 extends Entity {
	const STATUS_DELETED = 0;
	const STATUS_ACTIVE = 1;

	/**
	 * Fields that can be mass assigned using newEntity() or patchEntity().
	 *
	 * Note that when '*' is set to true, this allows all unspecified fields to
	 * be mass assigned. For security purposes, it is advised to set '*' to false
	 * (or remove it), and explicitly make individual fields accessible as needed.
	 *
	 * @var array
	 */
	protected $_accessible = [
		'*' => true,
		'id' => false
	];

	private $getterFilterFunction = [
		'name'=>'stream_get_contents',
		'data'=>'stream_get_contents',
	];

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

		return ($this->{$key} = $value);
	}

	public function isDeleted() {
		return ((int)$this->status === self::STATUS_DELETED);
	}
}
