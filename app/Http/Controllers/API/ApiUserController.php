<?php

namespace App\Http\Controllers\API;


class ApiUserController extends ApiController {

	public function index() {
		$products = ['aaa', 'bbb'];
		return $this->sendResponse($products, 'Products retrieved successfully.');
	}
}