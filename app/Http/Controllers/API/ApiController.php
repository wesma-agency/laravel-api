<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Validator;

class ApiController extends Controller {


	public function __construct() {
		// $this->middleware('App\Http\Middleware\ApiBackend');
	}

	public function index(Request $request) {

		$routeParams = array_values(Route::current()->parameters());

		$className = $routeParams[0] ?? NULL;
		$className = 'App\\Http\\Controllers\\API\\Actions\\' 
			. ucfirst($className) 
			. '__Action';


		if (  class_exists($className) ) {

			$Action = new $className();
			return $Action->getResponse($request);
		}

		else {
			return $this->sendError(
				["Такого метода не существует."], 
				404,
				[['class' => $className]]
			);
		} 
		

	}

	protected function checkAuth() {

		$accessData = false;

		try {
			
			$JWTAuth = \Tymon\JWTAuth\Facades\JWTAuth::parseToken()->authenticate();
			$accessData = $JWTAuth->getOriginal();
			
		} 
		//
		catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			throw new HttpResponseException(
				redirect('/')->with('status', 'Что-то пошло не так...')
			);

		} 
		//
		catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
			throw new HttpResponseException(
				redirect('/')->with('status', 'Что-то пошло не так...')
			);

		} 
		//
		catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
			throw new HttpResponseException(
				redirect('/')->with('status', 'Что-то пошло не так...')
			);

		}
		//
		catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
			throw new HttpResponseException(
				redirect('/')->with('status', 'Что-то пошло не так...')
			);

		}

		
		return $accessData;

	}


	public function sendResponse($data = [], $message = []) {

		if( empty($message) ){
			$message = ['Успех!'];
		}
	
		$response = [
			'success' => true,
			'message' => $message,
			'code' 		=> 200,
			'data'    => $data,
		];

		return response()->json(
				$response, 
				200,
				['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
				JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
			);

	}


	public function sendError($message = [], $code = 404, $error = []) {

		if( empty($message) ){
			$message = ['Что-то пошло не так!'];
		}

		$response = [
			'success' => false,
			'message' => $message,
			'code' 		=> $code,
			'error' 	=> $error
		];
		return response()->json(
				$response, 
				$code,
				['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
				JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
			);

	}


	static public function getErrorsValidation( $userFields=[], $arValid=[]) {

		$result = [];

		if( !empty($userFields) && !empty($arValid) ){
			$validator = Validator::make(
        $userFields,
        $arValid
      );


			if ($validator->fails()) {

				$result = [
					'messages' => [],
					'errors' => [],
					'validator' => []
				];

				$errors = $validator->messages()->toArray();

				foreach ($errors as $error) {

					if (!empty($error)) {
						foreach ($error as $message) {
							$result['messages'][] = $message;
						}
					}

				}

				$result['errors'] = $errors;

      }

			$result['validator'] = $validator;

		}

		return $result;

	}

}