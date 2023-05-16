<?php
namespace App\Traits;

trait ResponceTrait {

	/**
	 * @param $data
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function createdResponse($data, $message = null, $code = 0) {
		$response = [
			'code' => $code,
			'error' => false,
			'message' => (!empty($message) ? $message : trans('site.success')),
			'data' => $data,
		];
		return response()->json($response, 201);
	}

	/**
	 * @param $data
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function showResponse($data, $message = null, $code = 0, $extraKeyValue = []) {
		$response = [
			'error' => false,
			'code' => $code,
			'data' => $data,
			'message' => (!empty($message) ? $message : trans('site.success')),
		];
		foreach ($extraKeyValue as $key => $value) {
			$response[$key] = $value;
		}

		return response()->json($response, 200);
	}

	/***
		     * @param $data
		     * @return \Illuminate\Http\JsonResponse
	*/
	protected function listResponse($data, $message = null, $code = 0) {
		$response = [
			'code' => $code,
			'error' => false,
			'message' => (!empty($message) ? $message : trans('site.success')),
			'data' => $data,
		];
		return response()->json($response, 200);
	}
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function deletedResponse($message = null, $code = 0) {
		$response = [
			'code' => $code,
			'error' => false,
			'message' => (!empty($message) ? $message : trans('site.delete')),
		];
		return response()->json($response, 200);
	}

	protected function userNotExistResponse($message = null, $code = 1) {
		$response = [
			'code' => $code,
			'error' => true,
			'message' => (!empty($message) ? $message : trans('user.not_exists')),
		];
		return response()->json($response, 200);
	}
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function notFoundResponse($e = null, $code = 0) {
		$response = [
			'code' => $code,
			'error' => true,
			'message' => $e->getMessage(),
		];
		return response()->json($response, 404);
	}

	/**
	 * @param $e
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function clientErrorResponse($e = null, $code = 0) {
		$response = [
			'code' => $code,
			'error' => true,
			'message' => $e->getMessage(),
		];
		return response()->json($response, 422);
	}

	/**
	 * @param $errors
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function validationErrorResponse($errors, $code = 0) {
		$response = [
			'code' => $code,
			'error' => true,
			'message' => $errors->errors()->first(),
		];
		return response()->json($response, 200);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function unauthenticatedResponse($message = null, $code = 0) {
		$response = [
			'code' => $code,
			'error' => true,
			'message' => 'Unauthenticated',
		];
		return response()->json($response, 401);
	}

	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function notFoundResult($message = null, $code = 0) {
		$response = [
			'code' => $code,
			'error' => true,
			'message' => (!empty($message) ? $message : 'Resource Not Found'),
		];
		return response()->json($response, 200);
	}

	protected function outOfStockResponse($data, $message = null, $code = 0) {
		$response = [
			'code' => $code,
			'status' => 0,
			'error' => true,
			'message' => (!empty($message) ? $message : $data['message']),
			'data' => $data,
		];
		return response()->json($response, 200);
	}

	/**
	 * @param $errors
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function validationErrorResponce($errors, $message = null, $code = 0) {
		$response = [
			'code' => $code,
			'status' => 0,
			'message' => $errors->errors()->first(),
		];
		return response()->json($response, 200);
	}
	
	
	
public function generateOTP(){
	
	$i=0;
	$otp = " ";
	while($i < 4){
		$otp .= rand(0,9);
		
		$i++;
	}
	
	return $otp;
}
	
	
	
	
}
