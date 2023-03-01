<?php
namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Str;

// use App\Models\Roles;
// use Illuminate\Support\Facades\Validator;


class UsersController extends Controller {

  public function getAllUsers() {

    $mUsers = new User();
    return $mUsers->getAllUsers();

  }

  public function editUser($request) {

    $validator = Validator::make($request->all(), [
      'id' => 'required|integer',
      'name' => 'required|string|between:2,100',
      'email' => 'required|string|email|max:100|unique:users',
      'password' => 'required|string|min:6',
    ]);



    $messages = array();
    foreach( $validator->messages()->toArray() as $name => $error){
      
      if( !empty($error) ){
        foreach( $error as $message ){
          $messages[] = $message;
        }
      }
    }

    $errors = $validator->messages()->toArray();

    if( !empty($errors['id'] )) {
      $messages = $errors['id'][0];
      $errors = ['id' => $errors['id']];
    }


    if ($validator->fails()) {
      return array(
        'success' => false,
				'message' => $messages,
				'code' => 400,
				'error' => $errors
			);
    }

    else{
      $requestData = $request->all();
      $requestData['password'] =  bcrypt($request->password);
      if( !empty($requestData['role']) ){
        $requestData['role'] =  Str::upper($request->role);
      }

      $mUsers = new User();
      return $mUsers->editUser($requestData);
    }


  }

}