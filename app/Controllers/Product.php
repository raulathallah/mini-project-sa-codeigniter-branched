<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DataParams;
use App\Models\CategoryModel;
use App\Models\ProductImageModel;
use App\Models\ProductModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use DateTime;

class Product extends BaseController
{
    protected $modelProduct;
    protected $modelCategory;
    protected $modelProductImage;
    private $db;

    public function __construct()
    {
        $this->db = db_connect();
        $this->db->initialize();

        $this->modelProduct = new ProductModel();
        $this->modelCategory = new CategoryModel();
        $this->modelProductImage = new ProductImageModel();
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

        // $pageData = [
        //     'products' => $result['products']
        // ];

        $data = [
            'pager' => $result['pager'],
            'total' => $result['total'],
            'params' => $params,
            'price_range' => $this->modelProduct->getPriceRange(),
            'categories' => $this->modelProduct->getAllCategories(),
            'baseUrl' => base_url('product'),
            'products' => $result['products'],
            //'content' => $parser->setData($pageData)->render('parser/product/product_list')
        ];

        //, ['cache' => HOUR, 'cache_name' => 'cache_product_catalog']
        return view('section_public/product_list', $data);
    }

    public function productImage($id, $filename)
    {
        $filePath = WRITEPATH . $filename;
        if (file_exists($filePath)) {
            return $this->response->setContentType(mime_content_type($filePath))->setBody(file_get_contents($filePath));
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
}
