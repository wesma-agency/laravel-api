<?php

namespace App\Http\Controllers\API\Actions;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\UsersController;

class Get_users__Action extends ApiController {

	public function getResponse() {
		$data = ['aaa', 'bbb'];

		$Users = new UsersController();
		$data = $Users->getAllUsers();

		return $this->sendResponse($data);
		
	}
} 