<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Entities\Product as EntitiesProduct;
use App\Entities\ProductImage;
use App\Libraries\DataParams;
use App\Models\CategoryModel;
use App\Models\ProductImageModel;
use App\Models\ProductModel;
use CodeIgniter\HTTP\ResponseInterface;
use Myth\Auth\Models\UserModel;

class Product extends BaseController
{
    protected $modelProduct;
    protected $modelCategory;
    protected $modelProductImage;
    protected $modelUser;
    protected $modelGroup;
    private $db;

    public function __construct()
    {
        $this->db = db_connect();
        $this->db->initialize();

        $this->modelProduct = new ProductModel();
        $this->modelCategory = new CategoryModel();
        $this->modelProductImage = new ProductImageModel();
        $this->modelUser = new UserModel();
    }

    public function index()
    {
        //print_r($this->request->getGet('price_range'));
        $params = new DataParams([
            'search' => $this->request->getGet('search'),
            'price_range' => $this->request->getGet('price_range'),
            'categories' => $this->request->getGet('categories'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page_products'),
            'perPage' => $this->request->getGet('perPage')
        ]);

        $result = $this->modelProduct->getFilteredProducts($params);


        $data = [
            //'title' => 'Manajemen Users',
            'products' => $result['products'],
            'pager' => $result['pager'],
            'total' => $result['total'],
            'params' => $params,
            'price_range' => $this->modelProduct->getPriceRange(),
            'categories' => $this->modelProduct->getAllCategories(),
            'baseUrl' => base_url('admin/product'),
        ];

        //$data['content'] = $parser->setData($pageData)->render('parser/product/product_list', ['cache' => HOUR, 'cache_name' => 'cache_product_catalog']);
        return view('section_admin/product_list', $data);
    }

    public function addPhoto($id)
    {
        return view('section_admin/product_add_photo', ['errors' => [], 'product_id' => $id]);
    }

    public function onCreate(): string
    {
        $new = new EntitiesProduct();
        $categories = $this->modelCategory->findAll();

        return view(
            'section_admin/product_form',
            [
                'type' => 'Create',
                'product' => $new,
                'categories' => $categories,
                'action' => 'create',
                'errors' => []
            ]
        );
    }

    public function onUpdate($id): string
    {
        $data = $this->modelProduct->find($id);
        $categories = $this->modelCategory->findAll();
        return view(
            'section_admin/product_form',
            [
                'type' => 'Update',
                'product' => $data,
                'categories' => $categories,
                'action' => 'update'
            ]
        );
    }


    public function create()
    {

        $userNotCustomer = $this->db->table('users')
            ->select('users.email')
            ->join('auth_groups_users', 'users.id = auth_groups_users.user_id', 'left')
            ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id', 'left')
            ->whereIn('name', ['administrator', 'product_manager'])
            ->get()
            ->getResult();

        $userToEmail = [];
        foreach ($userNotCustomer as $row) {
            array_push($userToEmail, $row->email);
        }

        //data product
        $data = new EntitiesProduct();
        $data->fill($this->request->getPost());
        $data->product_id = null;
        $data->status = 'active';
        $data->is_new = true;
        $data->is_sale = false;
        $saveProduct = $this->modelProduct->save($data);

        if ($saveProduct == false) {
            return redirect()->back()
                ->with('errors', $this->modelProduct->errors())
                ->withInput();
            return redirect()->to('/admin/product/on_create');
        }

        $userfile = $this->request->getFile('userfile');
        $product_id = $this->modelProduct->getInsertID();
        $newName = $userfile->getName();

        $upload = $this->uploadProductPhoto($product_id, $userfile, $newName, 'create');


        if ($upload) {
            $new = new EntitiesProduct();
            $categories = $this->modelCategory->findAll();
            $this->modelProduct->delete($this->modelProduct->getInsertID());
            return view('section_admin/product_form', [
                'errors' => $upload,
                'type' => 'Create',
                'product' => $new,
                'categories' => $categories,
                'action' => 'create',
            ]);
        }

        //email
        $email = service('email');
        $email->setTo(user()->email);
        $email->setCc($userToEmail);
        $email->setSubject('New Product Added');

        $file_path = WRITEPATH . 'uploads/original/' . $product_id . '/' . $newName;
        $email->attach($file_path, 'inline', 'product_image.jpg', 'image/jpeg');

        $dataEmail = [
            'title' => 'New Product Information',
            'productId' => $this->modelProduct->getInsertID(),
            'productName' => $data->name,
            'productDesc' => $data->description,
            'productPrice' => $data->getFormattedPrice(),
            'link' => base_url('/admin/product/detail/' . $this->modelProduct->getInsertID()),
        ];
        $message = view('emails/email_product', $dataEmail); // Isi konten email
        $email->setMessage($message);

        if ($email->send()) {
            session()->setFlashdata('success', 'Product berhasil disimpan');
            return redirect()->to('/admin/product');
        } else {

            $data = ['error' => $email->printDebugger()];
            //$this->modelEnrollment->delete($this->modelEnrollment->getInsertID());
            return view('product_list');
        }
    }

    public function uploadProductPhoto($product_id, $userfile, $newName, $flag)
    {
        if (!$userfile->isValid()) {
            return view('section_admin/product_form', [

                'error' => $userfile->getErrorString()

            ]);
        }

        $validationRulesImage = [
            'userfile' => [
                'label' => 'Gambar',
                'rules' => [
                    'uploaded[userfile]',
                    'is_image[userfile]',
                    'mime_in[userfile,image/jpg,image/jpeg,image/png,image/webp]',
                    'max_size[userfile,5120]', // 5MB dalam KB (5 * 1024)
                    'min_dims[userfile,600,600]'
                ],
                'errors' => [
                    'uploaded' => 'Silakan pilih file gambar untuk diunggah',
                    'is_image' => 'File harus berupa gambar',
                    'mime_in' => 'File harus berformat JPG, JPEG, PNG, atau WEBP',
                    'max_size' => 'Ukuran file tidak boleh melebihi 5MB',
                    'min_dims' => 'Dimensi image/file minimum 600x600'
                ]
            ]
        ];

        if ($this->isAllowedFileTypeImage($userfile)) {
            if (!$this->validate($validationRulesImage)) {
                return $this->validator->getErrors();
            }
        }

        // $nowTime = new Time();
        // $dateString = $nowTime->toDateString();
        // $timeString = $nowTime->toTimeString();
        // $dateStringWithoutSpecialChar = str_replace("-", "", $dateString);
        // $timeStringWithoutSpecialChar = str_replace(":", "", $timeString);

        $uploadPathOriginal_NW = 'uploads/original/';
        $uploadPathMedium_NW = 'uploads/medium/';
        $uploadPathThumbnail_NW = 'uploads/thumbnail/';

        $uploadPathOriginal = WRITEPATH .  $uploadPathOriginal_NW;
        $uploadPathMedium = WRITEPATH . $uploadPathMedium_NW;
        $uploadPathThumbnail = WRITEPATH . $uploadPathThumbnail_NW;

        if (!is_dir($uploadPathOriginal)) {
            mkdir($uploadPathOriginal, 0777, true);
        }
        if (!is_dir($uploadPathMedium)) {
            mkdir($uploadPathMedium, 0777, true);
        }
        if (!is_dir($uploadPathThumbnail)) {
            mkdir($uploadPathThumbnail, 0777, true);
        }

        $uploadPathOriginal_ID = $uploadPathOriginal  . $product_id . '/';
        $uploadPathMedium_ID = $uploadPathMedium . $product_id . '/';
        $uploadPathThumbnail_ID = $uploadPathThumbnail . $product_id . '/';

        if (!is_dir($uploadPathOriginal_ID)) {
            mkdir($uploadPathOriginal_ID, 0777, true);
        }
        if (!is_dir($uploadPathMedium_ID)) {
            mkdir($uploadPathMedium_ID, 0777, true);
        }
        if (!is_dir($uploadPathThumbnail_ID)) {
            mkdir($uploadPathThumbnail_ID, 0777, true);
        }

        $image = service('image');


        $imageFile = $image;
        $newProductImage = new ProductImage();
        $newProductImage->product_id = $product_id;

        if ($flag == 'create') {
            $newProductImage->is_primary = true;
        } else {
            $newProductImage->is_primary = false;
        }

        //save to thumbnail
        $imageFile
            ->withFile($userfile)
            ->resize(150, 150, true)
            ->save($uploadPathThumbnail_ID  . $newName);
        $newProductImage->image_path = $uploadPathThumbnail_NW . $product_id . '/' .  $newName;
        $this->modelProductImage->save($newProductImage);

        //save to original
        $imageFile->withFile($userfile);
        $imageFile->text(
            'Copyright 2025 Online Food Ordering System',
            [
                'color'     => '#fff',
                'opacity'   => 0.5,
                'withShadow'   => true,
                'hAlign'    => 'center',
                'vAlign'    => 'botton',
                'fontSize'  => 12,
            ]
        );
        $imageFile->quality(80);
        $imageFile->save($uploadPathOriginal_ID . $newName);
        $newProductImage->image_path = $uploadPathOriginal_NW . $product_id . '/' .  $newName;
        $this->modelProductImage->save($newProductImage);

        //save to medium
        $imageFile
            ->withFile($userfile)
            ->text(
                'Copyright 2025 Online Food Ordering System',
                [
                    'color'     => '#fff',
                    'opacity'   => 0.5,
                    'withShadow'   => true,
                    'hAlign'    => 'center',
                    'vAlign'    => 'botton',
                    'fontSize'  => 12,
                ]
            )
            ->resize(500, 500)
            ->save($uploadPathMedium_ID . $newName);

        $newProductImage->image_path = $uploadPathMedium_NW . $product_id . '/' . $newName;
        $this->modelProductImage->save($newProductImage);
    }

    public function additionalProductPhoto()
    {
        $userfile = $this->request->getFile('userfile');
        $product_id = $this->request->getPost('product_id');
        $newName = $userfile->getName();

        $this->uploadProductPhoto($product_id, $userfile, $newName, 'add');

        session()->setFlashdata('success', 'Product berhasil disimpan');
        return redirect()->to('/admin/product');
    }

    public function update()
    {
        $data = new EntitiesProduct;
        $data->fill($this->request->getPost());
        if ($this->modelProduct->save($data)) {

            session()->setFlashdata('success', 'Product berhasil diubah');

            return redirect()->to('/admin/product');
        }

        return redirect()->back()
            ->with('errors', $this->modelProduct->errors())
            ->withInput();
        return redirect()->to('/admin/product');
    }

    public function delete($id)
    {
        $this->modelProduct->delete($id);
        return redirect()->to('/admin/product');
    }

    public function detail($id)
    {
        $product = $this->modelProduct->find($id);
        return view(
            'section_admin/product_detail',
            [
                'product' => $product,
            ]
        );
    }

    private function isAllowedFileTypeImage($file)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $fileExtension = $file->getClientExtension(); // Get file extension

        // If the file's extension is not in the allowed list
        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            return false; // Not allowed
        }

        return true; // Allowed
    }
}
