<?php

namespace App\Http\Controllers\API\Actions;

use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\UsersController;

class Edit_user__Action extends ApiController {

	public function getResponse($request) {

		$accessData = $this->checkAuth();


		if (
			!empty($accessData['role'])
			&& $accessData['role'] === "DEVELOPER"
			&& $accessData['active'] === 1
		) {


			$Users = new UsersController();
			$data = $Users->editUser($request);
			

			if( 
				$data !== false 
				&& $data['success'] !== false
			){
				return $this->sendResponse(
					$data['data'],	 // $data
					$data['message'] // $message
				);
			}

			else {
				return $this->sendError(
					$data['message'], // $message
					$data['code'], 		// $code
					$data['error']		// $error
				);
			}

			

		} else {
			return $this->sendError(
				["Уходи!"], 							// $message
				403, 											// $code
				[['access' => 'denied']] 	// $error
			);
		}

	}
}