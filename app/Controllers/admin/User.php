<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\User as EntitiesUser;
use App\Libraries\DataParams;
use App\Models\UserModel as UserAccountModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;

class User extends BaseController
{
    protected $modelAccount;
    protected $modelUser;
    protected $groupModel;
    private $db;

    public function __construct()
    {
        $this->db = db_connect();
        $this->db->initialize();

        $this->modelAccount = new UserAccountModel();
        $this->modelUser = new UserModel();
        $this->groupModel = new GroupModel();
    }

    public function index() {}

    public function onCreate(): string
    {
        $new = new EntitiesUser();

        return view(
            'section_admin/user_form',
            [
                'type' => 'Create',
                'user' => $new,
                'action' => 'create'
            ]
        );
    }

    public function onUpdate($id): string
    {
        $data = $this->modelAccount->find($id);
        return view(
            'section_admin/user_form',
            [
                'type' => 'Update',
                'user' => $data,
                'action' => 'update'
            ]
        );
    }

    public function onUpdateRole($user_id): string
    {
        $data = $this->modelUser->find($user_id);
        $role = $this->modelAccount->where('user_id', $user_id)->first()->role;

        return view(
            'section_admin/user_role_form',
            [
                'type' => 'Update',
                'user' => $data,
                'userRole' => $role,
                'groups' => $this->groupModel->findAll(),
                'userGroups' => $this->groupModel->getGroupsForUser($user_id),
                'action' => 'update_role'
            ]
        );
    }
    public function updateRole()
    {
        $data = $this->modelUser->find($this->request->getPost('user_id'));
        $accountData = $this->modelAccount->where('user_id', $this->request->getPost('user_id'))->first();

        $id = $data->id;

        // Update group user
        $groupId = $this->request->getVar('group');

        if (!empty($groupId)) {
            $accountData->role = $this->groupModel->where('id', $groupId)->first()->name;
            $currentGroups = $this->groupModel->getGroupsForUser($id);

            // Hapus dari group lama
            foreach ($currentGroups as $group) {

                $this->groupModel->removeUserFromGroup($id, $group['group_id']);
            }
            // Tambahkan ke group baru
            $this->groupModel->addUserToGroup($id, $groupId);

            $this->modelAccount->save($accountData);
        }


        return redirect()->to('admin/user')->with('message', 'User berhasil diupdate');
        return view(
            'section_admin/user_role_form',
            [
                'type' => 'Update',
                'user' => $data,
                'action' => 'update_role'
            ]
        );
    }

    public function create()
    {
        $data = new EntitiesUser;
        $data->fill($this->request->getPost());
        //$data->setPassword();
        $data->status = 'inactive';
        $data->last_login = null;


        $validationRules = $this->modelAccount->getValidationRules();
        $validationMessages = $this->modelAccount->getValidationMessages();
        $validationRules['email'] = 'required|is_unique[accounts.email,account_id]|valid_email';
        $validationRules['username'] = 'required|is_unique[accounts.username,account_id]|min_length[3]';

        // Validate input data
        if (!$this->validate($validationRules, $validationMessages)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }
        $this->modelAccount->save($data);
        session()->setFlashdata('success', 'User berhasil disimpan');
        return redirect()->to('/admin/user');
    }

    public function update()
    {
        $user_id = $this->request->getPost('user_id');
        $password = $this->request->getPost('password');

        $data = new EntitiesUser;
        $data->fill($this->request->getPost());
        $data->last_login = new Time();

        $validationRules = $this->modelAccount->getValidationRules();
        $validationMessages = $this->modelAccount->getValidationMessages();

        $validationRules['email'] = 'required|is_unique[users.email,user_id,' . $user_id . ']|valid_email';
        $validationRules['username'] = 'required|is_unique[users.username,user_id,' . $user_id . ']|min_length[3]';

        if (!empty($password)) {
            //$data->password = $data->setPassword();
        } else {
            //$data->password = $data->password;
        }

        // Validate input data
        if (!$this->validate($validationRules, $validationMessages)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors())
                ->withInput();
        }

        $this->modelAccount->update($user_id, $data);
        session()->setFlashdata('success', 'User berhasil diubah');
        return redirect()->to('/admin/user');
    }

    public function delete($id)
    {
        $data = $this->modelAccount->find($id);
        $user = $this->modelUser->find($data->user_id);

        if ($data && $user) {
            $this->modelUser->delete($user->id);
            $this->modelAccount->delete($id);
        }
        return redirect()->to('/admin/user');
    }

    public function detail($id)
    {
        $parser = \Config\Services::parser();

        $userData = $this->modelAccount->where('user_id', $id)->first()->toArray();


        $data['content'] = $parser->setData($userData)->render('parser/user/user_profile');
        return view('section_public/user_profile', $data);
    }
}
