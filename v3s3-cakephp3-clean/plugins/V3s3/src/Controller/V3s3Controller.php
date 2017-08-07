<?php
namespace V3s3\Controller;

use finfo;

use App\Controller\AppController as CP3_AppController;

use V3s3\Model\Table\V3s3Table;

use V3s3\Helper\V3s3Html;
use V3s3\Helper\V3s3Xml;

use V3s3\Controller\Exception\V3s3ControllerRequestValidationException;

/**
 * V3s3 Controller
 *
 * @property \App\Model\Table\V3s3Table $V3s3
 */
class V3s3Controller extends CP3_AppController {
	public function initialize() {
		parent::initialize();
		$this->loadComponent('RequestHandler');
	}

	public function put() {
		$name = $this->request->getUri()->getPath();

		try {
			if (empty($name) || ($name == '/')) {
				throw new V3s3ControllerRequestValidationException(__d('V3s3', 'V3S3_EXCEPTION_CONTROLLER_REQUEST_VALIDATION_PUT_EMPTY_OBJECT_NAME'), V3s3ControllerRequestValidationException::PUT_EMPTY_OBJECT_NAME);
			} else if (strlen($name) > 1024) {
				throw new V3s3ControllerRequestValidationException(__d('V3s3', 'V3S3_EXCEPTION_CONTROLLER_REQUEST_VALIDATION_OBJECT_NAME_TOO_LONG'), V3s3ControllerRequestValidationException::OBJECT_NAME_TOO_LONG);
			}
		} catch(V3s3ControllerRequestValidationException $e) {
			$this->response = $this->response->withType('json');
			$this->response = $this->response->withStringBody(
				json_encode(
					[
						'status'=>0,
						'code'=>$e->getCode(),
						'message'=>$e->getMessage()
					]
				)
			);
			return $this->response;
		}

		$data = $this->request->getBody()->getContents();
		$content_type = $this->request->getHeader('Content-Type');
		$content_type = (!empty($content_type)?reset($content_type):null);
		$mime_type = (is_null($content_type)?(new finfo(FILEINFO_MIME))->buffer($data):$content_type);
		$v3s3 = $this->loadModel('V3s3.V3s3');
		$entity = $v3s3->put(
			[
				'ip'=>$this->request->clientIp(),
				'name'=>$name,
				'data'=>$data,
				'mime_type'=>$mime_type,
			]
		);

		$this->response = $this->response->withType('json');
		$this->response = $this->response->withHeader('v3s3-object-id', $entity->getProperty('id'));
		$this->response = $this->response->withStringBody(
			json_encode(
				[
					'status'=>1,
					'message'=>__d('V3s3', 'V3S3_MESSAGE_PUT_OBJECT_ADDED_SUCCESSFULLY'),
				]
			)
		);
		return $this->response;
	}

	public function get() {
		$name = $this->request->getUri()->getPath();

		try {
			if (strlen($name) > 1024) {
				throw new V3s3ControllerRequestValidationException(__d('V3s3', 'V3S3_EXCEPTION_CONTROLLER_REQUEST_VALIDATION_OBJECT_NAME_TOO_LONG'), V3s3ControllerRequestValidationException::OBJECT_NAME_TOO_LONG);
			}
		} catch(V3s3ControllerRequestValidationException $e) {
			$this->response = $this->response->withType('json');
			$this->response = $this->response->withStringBody(
				json_encode(
					[
						'status'=>0,
						'code'=>$e->getCode(),
						'message'=>$e->getMessage()
					]
				)
			);
			return $this->response;
		}

		$input = $this->request->getQuery();
		unset($input['download']);
		$v3s3 = $this->loadModel('V3s3.V3s3');
		$entity = $v3s3->api_get(
			array_replace(
				$input,
				[
					'name'=>$name,
				]
			)
		);

		if(is_object($entity)) {
			$this->response = $this->response->withType('json');
			$this->response = $this->response->withStringBody($entity->getProperty('data'));

			if(empty($row['mime_type'])) {
				$row['mime_type'] = (new finfo(FILEINFO_MIME))->buffer($entity->getProperty('data'));
			}
			$this->response = $this->response
				->withHeader('v3s3-object-id', $entity->getProperty('id'))
				->withHeader('Content-Type', $entity->getProperty('mime_type'))
				->withHeader('Content-Length', strlen($entity->getProperty('data')));
			if(!empty($this->request->getQuery('download'))) {
				$filename = basename($name);
				$this->response = $this->response->withDownload($filename);
			}
		} else {
			$this->response = $this->response->withStatus(404);
			$this->response = $this->response->withType('json');
			$this->response = $this->response->withStringBody(
				json_encode(
					[
						'status'=>1,
						'results'=>0,
						'message'=>__d('V3s3', 'V3S3_MESSAGE_404')
					]
				)
			);
		}

		return $this->response;
	}

	public function delete() {
		$name = $this->request->getUri()->getPath();

		try {
			if (empty($name) || ($name == '/')) {
				throw new V3s3ControllerRequestValidationException(__d('V3s3', 'V3S3_EXCEPTION_CONTROLLER_REQUEST_VALIDATION_DELETE_EMPTY_OBJECT_NAME'), V3s3ControllerRequestValidationException::DELETE_EMPTY_OBJECT_NAME);
			} else if (strlen($name) > 1024) {
				throw new V3s3ControllerRequestValidationException(__d('V3s3', 'V3S3_EXCEPTION_CONTROLLER_REQUEST_VALIDATION_OBJECT_NAME_TOO_LONG'), V3s3ControllerRequestValidationException::OBJECT_NAME_TOO_LONG);
			}
		} catch(V3s3ControllerRequestValidationException $e) {
			$this->response = $this->response->withType('json');
			$this->response = $this->response->withStringBody(
				json_encode(
					[
						'status'=>0,
						'code'=>$e->getCode(),
						'message'=>$e->getMessage()
					]
				)
			);
			return $this->response;
		}

		$input = $this->request->getQuery();
		$v3s3 = $this->loadModel('V3s3.V3s3');
		$entity = $v3s3->api_delete(
			array_replace(
				$input,
				[
					'name'=>$name,
					'ip_deleted_from'=>$this->request->clientIp(),
				]
			)
		);

		if(is_object($entity)) {
			$this->response = $this->response->withType('json');
			$this->response = $this->response->withHeader('v3s3-object-id', $entity->getProperty('id'));
			$this->response = $this->response->withStringBody(
				json_encode(
					[
						'status'=>1,
						'results'=>1,
						'message'=>__d('V3s3', 'V3S3_MESSAGE_DELETE_OBJECT_DELETED_SUCCESSFULLY')
					]
				)
			);
		} else {
			$this->response = $this->response->withStatus(404);
			$this->response = $this->response->withType('json');
			$this->response = $this->response->withStringBody(
				json_encode(
					[
						'status'=>1,
						'results'=>0,
						'message'=>__d('V3s3', 'V3S3_MESSAGE_NO_MATCHING_RESOURCES')
					]
				)
			);
		}

		return $this->response;
	}

	public function post() {
		$name = $this->request->getUri()->getPath();

		$input = $this->request->getBody()->getContents();
		$parsed_input = $this->request->input('json_decode', true);
		if(!empty($input) && empty($parsed_input)) {
			try {
				throw new V3s3ControllerRequestValidationException(__d('V3s3', 'V3S3_EXCEPTION_CONTROLLER_REQUEST_VALIDATION_POST_INVALID_REQUEST'), V3s3ControllerRequestValidationException::POST_INVALID_REQUEST);
			} catch(V3s3ControllerRequestValidationException $e) {
				$this->response = $this->response->withType('json');
				$this->response = $this->response->withStringBody(
					json_encode(
						[
							'status'=>0,
							'code'=>$e->getCode(),
							'message'=>$e->getMessage()
						]
					)
				);
				return $this->response;
			}
		}

		$attr = (!empty($parsed_input['filter'])?$parsed_input['filter']:[]);
		if(!empty($name) && ($name != '/')) {
			$attr['name'] = $name;
		}

		$v3s3 = $this->loadModel('V3s3.V3s3');
		$rows = $v3s3->post(
			$attr
		);

		if(!empty($rows)) {
			foreach ($rows as &$_row) {
				unset($_row['id']);
				unset($_row['timestamp']);
				unset($_row['hash_name']);
				unset($_row['timestamp_deleted']);
				if(empty($_row['mime_type'])) {
					$_row['mime_type'] = (new finfo(FILEINFO_MIME))->buffer($_row['data']).' (determined using PHP finfo)';
				}
				unset($_row['data']);
			}

			$format = ((!empty($parsed_input['format'])&&in_array($parsed_input['format'], ['json', 'xml', 'html']))?strtolower($parsed_input['format']):'json');
			switch($format) {
				case 'xml':
					$rows = V3s3Xml::simple_xml($rows);
					$this->response = $this->response->withType('xml');
					$this->response = $this->response->withStringBody($rows);
					return $this->response;
					break;
				case 'html':
					$rows = V3s3Html::simple_table($rows);
					$this->response = $this->response->withType('html');
					$this->response = $this->response->withStringBody($rows);
					return $this->response;
					break;
				case 'json':
				default:
					$this->response = $this->response->withType('json');
					$this->response = $this->response->withStringBody(
						json_encode($rows, JSON_PRETTY_PRINT)
					);
					return $this->response;
					break;
			}
		} else {
			$this->response = $this->response->withType('json');
			$this->response = $this->response->withStringBody(
				json_encode(
					[
						'status'=>1,
						'results'=>0,
						'message'=>__d('V3s3', 'V3S3_MESSAGE_NO_MATCHING_RESOURCES')
					]
				)
			);
			return $this->response;
		}
	}
}
