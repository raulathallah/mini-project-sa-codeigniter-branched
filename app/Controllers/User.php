<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_User;
use App\Models\UserModel as UserAccountModel;
use CodeIgniter\I18n\Time;

class User extends BaseController
{
    protected $modelAccount;

    public function __construct()
    {
        $this->modelAccount = new UserAccountModel();
    }

    public function index() {}

    public function userProfile()
    {
        $parser = \Config\Services::parser();

        $userData = $this->modelAccount->where('user_id', user_id())->first()->toArray();


        $data['content'] = $parser->setData($userData)->render('parser/user/user_profile');
        return view('section_public/user_profile', $data);
    }
}
