<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DataParams;
use App\Models\CategoryModel;
use App\Models\ProductModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use DateTime;

class Product extends BaseController
{
    protected $modelProduct;
    protected $modelCategory;
    private $db;

    public function __construct()
    {
        $this->db = db_connect();
        $this->db->initialize();

        $this->modelProduct = new ProductModel();
        $this->modelCategory = new CategoryModel();
    }

    public function index()
    {
        $parser = \Config\Services::parser();
        $params = new DataParams([
            'search' => $this->request->getGet('search'),
            'price_range' => $this->request->getGet('price_range'),
            'categories' => $this->request->getGet('categories'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page_users'),
            'perPage' => $this->request->getGet('perPage')
        ]);

        $result = $this->modelProduct->getFilteredProducts($params);

        foreach ($result['products'] as $row) {
            $row->created_at_format = $row->created_at->humanize();
        }

        $pageData = [
            'products' => $result['products']
        ];

        $data = [
            'pager' => $result['pager'],
            'total' => $result['total'],
            'params' => $params,
            'price_range' => $this->modelProduct->getPriceRange(),
            'categories' => $this->modelProduct->getAllCategories(),
            'baseUrl' => base_url('product'),
            'content' => $parser->setData($pageData)->render('parser/product/product_list')
        ];

        //, ['cache' => HOUR, 'cache_name' => 'cache_product_catalog']
        return view('section_public/product_list', $data);
    }
}
