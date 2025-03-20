<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\DataParams;
use App\Models\M_Product;
use App\Models\ProductModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;

class Admin extends BaseController
{
    protected $helpers = ['date'];
    protected $modelUser;
    protected $modelProduct;
    private $db;

    public function __construct()
    {
        // Load helper secara manual
        helper($this->helpers);
        $this->db = db_connect();
        $this->db->initialize();
        $this->modelUser = new UserModel();
        $this->modelProduct = new ProductModel();
    }

    public function index()
    {
        $parser = \Config\Services::parser();
        $pageData = [
            'userStatistics' => [
                [
                    'total_users' => $this->modelUser->getTotalUsers(),
                    'active_users' => $this->modelUser->findActiveUsers(),
                    'new_users_this_month' => $this->modelUser->getNewUsersThisMonth(),
                    'growth_percentage' => 0
                ]
            ],
            'page_title' => 'Online Food System',
            'product_statistics_cell' => view_cell('ProductStatisticsCell', [], HOUR, 'cache_product_statistics'),
        ];

        $data['content'] = $parser->setData($pageData)->render('parser/admin/dashboard_statistics');
        return view('section_admin/dashboard', $data);
    }

    public function getUsers()
    {
        $params = new DataParams([
            'search' => $this->request->getGet('search'),
            'role' => $this->request->getGet('role'),
            'status'   => $this->request->getGet('status'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page_users'),
            'perPage' => $this->request->getGet('perPage')
        ]);
        $result = $this->modelUser->getFilteredUsers($params);

        $data = [
            //'title' => 'Manajemen Users',
            'accounts' => $result['accounts'],
            'pager' => $result['pager'],
            'total' => $result['total'],
            'params' => $params,
            'role' => $this->modelUser->getAllRoles(),
            'status' => $this->modelUser->getAllStatus(),
            'baseUrl' => base_url('admin/user'),
        ];

        return view('section_admin/user_list', $data);
        //, 'cache' => MINUTE * 15, 'cache_name' => 'cache_user_list'
    }
}
