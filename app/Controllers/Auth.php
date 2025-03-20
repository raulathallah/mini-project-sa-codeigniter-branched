<?php

namespace App\Controllers;

use App\Entities\User as EntitiesUser;
use App\Models\M_User;
use App\Models\UserModel as ModelsUserModel;
use CodeIgniter\I18n\Time;
use Myth\Auth\Controllers\AuthController;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;

class Auth extends AuthController
{
  protected $auth;
  protected $config;
  protected $userModel;
  protected $groupModel;
  protected $accountModel;

  public function __construct()
  {
    //$this->user_model = new M_User();
    parent::__construct();
    $this->userModel = new UserModel();
    $this->groupModel = new GroupModel();
    $this->accountModel = new ModelsUserModel();

    $this->auth = service('authentication');
  }

  public function attemptLogin()
  {
    $result = parent::attemptLogin();

    if (!user_id()) {
      return redirect()->back()
        ->with('error', "Please check you credentials")
        ->withInput();
    }

    if (!in_groups('administrator')) {
      $account = $this->accountModel->where('user_id', user_id())->first();
      $account->status = 'active';
      $account->last_login = new Time();

      $this->accountModel->save($account);
    }
    return $this->redirectBasedOnRole();
  }

  public function logout()
  {

    if (!in_groups('administrator')) {
      $account = $this->accountModel->where('user_id', user_id())->first();
      $account->status = 'inactive';
      $this->accountModel->save($account);
    }
    parent::logout();
    return redirect()->to('/');
  }

  public function attemptRegister()
  {
    // Jalankan registrasi bawaan
    $store = parent::attemptRegister();

    if ($this->users->errors()) {
      return redirect()->back()
        ->with('error', $this->users->errors())
        ->withInput();
    }

    $email = $this->request->getPost('email');

    //$roleGroup = $this->request->getPost('role_group');
    $roleGroup = 'customer';

    $user = $this->userModel->where('email', $email)->first();

    $new = [
      'user_id'       => (int)$user->id,
      'username'      => $user->username,
      'email'         => $user->email,
      'full_name'     => $user->username,
      'role'          => $roleGroup,
      'status'        => 'active',
      'last_login'    => new Time(),
    ];

    $data = new EntitiesUser($new);

    $validationRules = $this->accountModel->getValidationRules();
    $validationMessages = $this->accountModel->getValidationMessages();
    $validationRules['email'] = 'required|is_unique[accounts.email,account_id]|valid_email';
    $validationRules['username'] = 'required|is_unique[accounts.username,account_id]|min_length[3]';

    // Validate input data
    if (!$this->validate($validationRules, $validationMessages)) {
      return redirect()->back()
        ->with('errors', $this->validator->getErrors())
        ->withInput();
    }
    $this->accountModel->save($data);

    if ($user) {
      // Tambahkan ke group student
      $studentGroup = $this->groupModel->where('name', $roleGroup)->first();
      if ($studentGroup) {
        $this->groupModel->addUserToGroup($user->id, $studentGroup->id);
      }
    }
    return redirect()->route('login')->with('message', lang('Auth.activationSuccess'));
  }

  private function redirectBasedOnRole()
  {
    $userId = user_id();

    if ($userId == null) {
      return redirect()->to('/login');
    }

    $userGroups = $this->groupModel->getGroupsForUser($userId);

    // foreach ($userGroups as $group) {
    //   if ($group['name'] === 'admin') {
    //     return redirect()->to('dashboard/admin');
    //   } else if ($group['name'] === 'lecturer') {
    //     return redirect()->to('dashboard/lecturer');
    //   } else if ($group['name'] === 'student') {
    //     return redirect()->to('dashboard/student');
    //   }
    // }

    return redirect()->to('/');
  }

  // public function login()
  // {
  //   helper('date');
  //   $now = date('Y-m-d H:i:s', now());

  //   //$user = $this->user_model->getUser();

  //   //$user['activity_history'] = array_merge($user['activity_history'], [['desc' => 'login', 'time' => $now]]);

  //   //cache()->save('logged_in', $now, MINUTE * 5);
  //   //cache()->save("user", $user, MINUTE * 5);

  //   return redirect()->to('/home');
  // }

  // public function logout()
  // {
  //   cache()->delete("user");
  //   cache()->delete("logged_in");
  //   return redirect()->to('/');
  // }
}
