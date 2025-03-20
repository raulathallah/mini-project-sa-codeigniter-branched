<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Product as EntitiesProduct;
use App\Entities\User as EntitiesUser;
use App\Libraries\DataParams;
use App\Models\UserModel as UserAccountModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use Myth\Auth\Entities\Group;
use Myth\Auth\Models\GroupModel;
use Myth\Auth\Models\UserModel;

class Role extends BaseController
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

    public function index()
    {
        $data = [
            'roles' => $this->groupModel->findAll(),
        ];
        return view('section_admin/role_list', $data);
    }

    public function onCreate(): string
    {

        $new = new GroupModel();
        return view(
            'section_admin/role_form',
            [
                'type' => 'Create',
                'role' => $new,
                'action' => 'create'
            ]
        );
    }

    public function onUpdate($id): string
    {
        $new = $this->groupModel->find($id);
        return view(
            'section_admin/role_form',
            [
                'type' => 'Update',
                'role' => $new,
                'action' => 'update'
            ]
        );
    }

    public function create()
    {
        $data = new Group();
        $data->fill($this->request->getPost());
        $data->null;

        $this->groupModel->save($data);
        session()->setFlashdata('success', 'Role berhasil disimpan');
        return redirect()->to('/admin/role');
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $data = new Group();
        $data->fill($this->request->getPost());
        $this->groupModel->update($id, $data);
        session()->setFlashdata('success', 'User berhasil diubah');
        return redirect()->to('/admin/role');
    }

    public function delete($id)
    {
        $this->groupModel->delete($id);
        session()->setFlashdata('success', 'Role berhasil dihapus');
        return redirect()->to('/admin/role');
    }
}
