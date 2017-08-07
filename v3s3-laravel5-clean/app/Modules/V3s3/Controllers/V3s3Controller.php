<?php

namespace App\Modules\V3s3\Controllers;

use finfo;

use App\Http\Controllers\Controller as LV5_Controller;
use Illuminate\Http\Request as LV5_Request;

use App\Modules\V3s3\Models\V3s3;
use App\Modules\V3s3\Helpers\V3s3Html;
use App\Modules\V3s3\Helpers\V3s3Xml;
use App\Modules\V3s3\Controllers\Exceptions\V3s3ControllerRequestValidationException;

class V3s3Controller extends LV5_Controller {
	public function put(LV5_Request $request) {
		$name = $request->path();

		try {
			if (empty($name) || ($name == '/')) {
				throw new V3s3ControllerRequestValidationException(__('V3s3Translation.V3S3_EXCEPTION_CONTROLLER_REQUEST_VALIDATION_PUT_EMPTY_OBJECT_NAME'), V3s3ControllerRequestValidationException::PUT_EMPTY_OBJECT_NAME);
			} else if (strlen($name) > 1024) {
				throw new V3s3ControllerRequestValidationException(__('V3s3Translation.V3S3_EXCEPTION_CONTROLLER_REQUEST_VALIDATION_OBJECT_NAME_TOO_LONG'), V3s3ControllerRequestValidationException::OBJECT_NAME_TOO_LONG);
			}
		} catch(V3s3ControllerRequestValidationException $e) {
			return response(
				[
					'status'=>0,
					'code'=>$e->getCode(),
					'message'=>$e->getMessage(),
				]
			);
		}

		$data = $request->getContent();
		$content_type = $request->header('Content-Type');
		$mime_type = (is_null($content_type)?(new finfo(FILEINFO_MIME))->buffer($data):$content_type);
		$v3s3 = new V3s3;
		$entity = $v3s3->put(
			[
				'ip'=>$request->ip(),
				'name'=>$name,
				'data'=>$data,
				'mime_type'=>$mime_type,
			]
		);

		return response(
			[
				'status'=>1,
				'message'=>__('V3s3Translation.V3S3_MESSAGE_PUT_OBJECT_ADDED_SUCCESSFULLY'),
			]
		)->header('v3s3-object-id', $entity->getProperty('id'));
	}

	public function get(LV5_Request $request) {
		$name = $request->path();

		try {
			if (strlen($name) > 1024) {
				throw new V3s3ControllerRequestValidationException(__('V3s3Translation.V3S3_EXCEPTION_CONTROLLER_REQUEST_VALIDATION_OBJECT_NAME_TOO_LONG'), V3s3ControllerRequestValidationException::OBJECT_NAME_TOO_LONG);
			}
		} catch(V3s3ControllerRequestValidationException $e) {
			return response(
				[
					'status'=>0,
					'code'=>$e->getCode(),
					'message'=>$e->getMessage(),
				]
			);
		}

		$input = $request->all();
		unset($input['download']);
		$v3s3 = new V3s3;
		$entity = $v3s3->get(
			array_replace(
				$input,
				[
					'name'=>$name,
				]
			)
		);

		$response = null;

		if(is_object($entity)) {
			$response = response($entity->getProperty('data'));

			if(empty($entity->getProperty('mime_type'))) {
				$entity->setProperty('mime_type', (new finfo(FILEINFO_MIME))->buffer($entity->getProperty('data')));
			}
			$response->withHeaders(
				[
					'v3s3-object-id'=>$entity->getProperty('id'),
					'Content-Type'=>$entity->getProperty('mime_type'),
					'Content-Length'=>strlen($entity->getProperty('data')),
				]
			);
			if(!empty($request->input('download'))) {
				$filename = basename($name);
				$response->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
			}
		} else {
			$response = response(
				[
					'status'=>1,
					'results'=>0,
					'message'=>__('V3s3Translation.V3S3_MESSAGE_404'),
				],
				404
			);
		}

		return $response;
	}

	public function delete(LV5_Request $request) {
		$name = $request->path();

		try {
			if (empty($name) || ($name == '/')) {
				throw new V3s3ControllerRequestValidationException(__('V3s3Translation.V3S3_EXCEPTION_CONTROLLER_REQUEST_VALIDATION_DELETE_EMPTY_OBJECT_NAME'), V3s3ControllerRequestValidationException::DELETE_EMPTY_OBJECT_NAME);
			} else if (strlen($name) > 1024) {
				throw new V3s3ControllerRequestValidationException(__('V3s3Translation.V3S3_EXCEPTION_CONTROLLER_REQUEST_VALIDATION_OBJECT_NAME_TOO_LONG'), V3s3ControllerRequestValidationException::OBJECT_NAME_TOO_LONG);
			}
		} catch(V3s3ControllerRequestValidationException $e) {
			return response(
				[
					'status'=>0,
					'code'=>$e->getCode(),
					'message'=>$e->getMessage(),
				]
			);
		}

		$input = $request->all();
		$v3s3 = new V3s3;
		$entity = $v3s3->api_delete(
			array_replace(
				$input,
				[
					'name'=>$name,
					'ip_deleted_from'=>$request->ip()
				]
			)
		);

		if(is_object($entity)) {
			return response(
				[
					'status'=>1,
					'results'=>1,
					'message'=>__('V3s3Translation.V3S3_MESSAGE_DELETE_OBJECT_DELETED_SUCCESSFULLY'),
				]
			)->header('v3s3-object-id', $entity->getProperty('id'));
		} else {
			return response(
				[
					'status'=>1,
					'results'=>0,
					'message'=>__('V3s3Translation.V3S3_MESSAGE_NO_MATCHING_RESOURCES'),
				],
				404
			);
		}
	}

	public function post(LV5_Request $request) {
		$name = $request->path();

		$input = $request->getContent();
		$parsed_input = (!empty($input)?json_decode($input, true):[]);
		if(!empty($input) && empty($parsed_input)) {
			try {
				throw new V3s3ControllerRequestValidationException(__('V3s3Translation.V3S3_EXCEPTION_CONTROLLER_REQUEST_VALIDATION_POST_INVALID_REQUEST'), V3s3ControllerRequestValidationException::POST_INVALID_REQUEST);
			} catch(V3s3ControllerRequestValidationException $e) {
				return response(
					[
						'status'=>0,
						'code'=>$e->getCode(),
						'message'=>$e->getMessage(),
					]
				);
			}
		}

		$attr = (!empty($parsed_input['filter'])?$parsed_input['filter']:[]);
		if(!empty($name) && ($name != '/')) {
			$attr['name'] = $name;
		}

		$v3s3 = new V3s3;
		$entityResultSet = $v3s3->post(
			$attr
		);

		if(!empty($entityResultSet)) {
			$rows = $entityResultSet->toArray();
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
					return response($rows)->header('Content-Type', 'text/xml; charset=utf-8');
					break;
				case 'html':
					$rows = V3s3Html::simple_table($rows);
					return response($rows)->header('Content-Type', 'text/html; charset=utf-8');
					break;
				case 'json':
				default:
					return response()->json($rows, 200, array(), JSON_PRETTY_PRINT);
					break;
			}
		} else {
			return response(
				[
					'status'=>1,
					'results'=>0,
					'message'=>__('V3s3Translation.V3S3_MESSAGE_NO_MATCHING_RESOURCES'),
				]
			);
		}
	}
}
