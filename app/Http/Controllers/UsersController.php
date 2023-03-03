<?php
namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\API\ApiController;

// use App\Models\Roles;
// use Illuminate\Support\Facades\Validator;


class UsersController extends Controller {

  public function getAllUsers($request) {

    //-- Все поля пользовательского ввода
    $requestData = $request->all();

    $ids = null;
    if (!empty($requestData['id'])) {
      $ids = explode(',', $requestData['id']);
    }

    $mUsers = new User();

    //-- Получить значения из БД
    $result = $mUsers->getAllUsers($ids);

    if ($result === 0) {

      $errorResponse = array(
        'success' => false,
        'message' => ['Не удалось получить информацию о пользователях.'],
        'code' => 400,
        'error' => [0 => 'Что-то пошло не так в процессе выполнения запроса к БД.'],
      );

      return $errorResponse;

    } else {
      $successResponse = array(
        'success' => true,
        'message' => [],
        'data' => $result
      );

      if (empty($result)) {
        $successResponse['message'] = ['Нет таких пользователей.'];
      }

      return $successResponse;

    }

  }

  public function editUser($request) {

    $mUsers = new User();

    //-- Для метода editUser($id, $fields);
    $id = null;
    $fields = array();

    //-- Все поля пользовательского ввода
    $requestData = $request->all();
    //-- Массив для правил валидации полей пользовательского ввода
    $arValid = array();

    //-- Сообщения об ошибке для UI
    $messages = array();
    //-- Сами ошибки от методов проверки
    $errors = array();


    $errorResponse = array(
      'success' => false,
      'message' => $messages,
      'code' => 400,
      'error' => $errors
    );


    //-- Если id пустой, дальше можно ничего не проверять
    if (empty($requestData['id'])) {

      $validator = ApiController::getErrorsValidation(
        $requestData,
        ['id' => 'required|integer']
      );



      if (!empty($validator)) {
        $errorResponse['message'] = $validator['messages'];
        $errorResponse['error'] = $validator['errors'];

        return $errorResponse;
      }

    }

    //-- Если id не пустой, то можно проверять дальше
    // в т.ч. и сам id на валидность
    else {

      $id = (int) $requestData['id'];
      $arValid['id'] = 'required|integer|exists:users,id';



      if (!empty($requestData['email'])) {

        $fields['email'] = $requestData['email'];

        //-- Проверить почту на уникальность среди всех пользователей,
        // но игнорировать почту самого текущего пользователя, 
        // она может быть равна самой себе
        $arValid['email'] = 'required|string|email|max:100|unique:users,email,'
          . $id . ',id';

      }

      if (!empty($requestData['name'])) {
        $fields['name'] = $requestData['name'];
        $arValid['name'] = 'required|string|between:2,100';
      }

      if (!empty($requestData['role'])) {
        $fields['role'] = $requestData['role'];
        $arValid['role'] = 'required|in:' . env('DB_USER_ROLES');
      }

      if (!empty($requestData['active'])) {
        $fields['active'] = $requestData['active'];
        $arValid['active'] = 'required|integer|in:0,1';
      }

      if (!empty($requestData['password'])) {
        $fields['password'] = $requestData['password'];
        $arValid['password'] = 'required|string|min:6';
      }


      $validator = ApiController::getErrorsValidation(
        $requestData,
        $arValid
      );


      //-- Если пользовательский ввод инвалидный
      if (!empty($validator)) {
        $errorResponse['message'] = $validator['messages'];
        $errorResponse['error'] = $validator['errors'];

        return $errorResponse;
      }

      //-- Если пользовательский ввод валидный
      else {

        //-- Зашифровать пароль
        if (!empty($fields['password'])) {
          $fields['password'] = bcrypt($fields['password']);
        }

        //-- Перевести роль в верхний регистр
        $fields['role'] = Str::upper($fields['role']);



        //-- Записать изменения полей пользователя в БД
        $result = $mUsers->editUser($id, $fields);

        if ($result === 0) {

          $errorResponse = array(
            'success' => false,
            'message' => ['Не удалось изменить пользователя.'],
            'code' => 400,
            'error' => [0 => 'Что-то пошло не так в процессе выполнения запроса к БД.'],
          );

          return $errorResponse;

        } else {
          return array(
            'success' => true,
            'message' => [],
            'data' => $result
          );
        }

      }


    }


  }

  public function addUser($request) {

    $mUsers = new User();

    //-- Все поля пользовательского ввода
    $requestData = $request->all();

    //-- Массив для правил валидации полей пользовательского ввода
    $arValid = array(
      'name' => 'required|string|between:2,100',
      'email' => 'required|string|email|max:100|unique:users',
      'password' => 'required|string|confirmed|min:6',
      'active' => 'integer|in:0,1',
      'role' => 'in:' . env('DB_USER_ROLES'),
    );


    //-- Сообщения об ошибке для UI
    $messages = array();
    //-- Сами ошибки от методов проверки
    $errors = array();


    $errorResponse = array(
      'success' => false,
      'message' => $messages,
      'code' => 400,
      'error' => $errors
    );



    $validator = ApiController::getErrorsValidation(
      $requestData,
      $arValid
    );


    //-- Если пользовательский ввод инвалидный
    if (!empty($validator)) {
      $errorResponse['message'] = $validator['messages'];
      $errorResponse['error'] = $validator['errors'];

      return $errorResponse;
    }

    //-- Если пользовательский ввод валидный
    else {

      //-- Создать нового пользователя в БД
      $user = User::create(array_merge(
          $validator['validator']->validated(),
        [
          'password' => bcrypt($request->password),
          'role' => Str::upper($request->role),
        ]
      ));

      dd($user);

    }


  }

}