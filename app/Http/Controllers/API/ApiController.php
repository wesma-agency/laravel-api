<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\Route;



class ApiController extends Controller {

	public function index() {

		$routeParams = array_values(Route::current()->parameters());

		$className = $routeParams[0] ?? NULL;
		$className = 'App\\Http\\Controllers\\API\\Actions\\' 
			. ucfirst($className) 
			. '__Action';


		if (  class_exists($className) ) {
			$Action = new $className();
			return $Action->getResponse();
		}

		else {
			return $this->sendError(
				["Такого метода не существует"], 
				404,
				[['class' => $className]]
			);
		} 
		

	}


	public function sendResponse($data = [], $message = []) {

		if( empty($message) ){
			$message = ['Успех'];
		}
	
		$response = [
			'success' => true,
			'message' => $message,
			'data'    => $data,
		];

		return response()->json(
				$response, 
				200,
				['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
				JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
			);

	}


	public function sendError($message, $code = 404, $error = []) {

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
}