<?php

namespace App\Http\Controllers\API\Actions;

use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\UsersController;

class Get_users__Action extends ApiController {

	public function getResponse($request) {

		$accessData = $this->checkAuth();

		if (
			!empty($accessData['role'])
			&& $accessData['role'] === "DEVELOPER"
			&& $accessData['active'] === 1
		) {

			$Users = new UsersController();
			$data = $Users->getAllUsers();

			return $this->sendResponse($data);

		} else {
			return $this->sendError(
				$message = ["Уходи!"],
				$code = 403,
				$error = [['access' => 'denied']]
			);
		}

	}
}