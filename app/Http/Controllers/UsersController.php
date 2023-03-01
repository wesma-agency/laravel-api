<?php
namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\User;
// use App\Models\Roles;
// use Illuminate\Support\Facades\Validator;


class UsersController extends Controller {

  public function getAllUsers() {

    $mUsers = new User( );
    return $mUsers->getAllUsers();

  }

}