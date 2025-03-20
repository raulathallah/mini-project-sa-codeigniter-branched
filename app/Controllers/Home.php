<?php

namespace App\Controllers;

use App\Entities\ProductImage;
use App\Models\ProductImageModel;
use App\Models\ProductModel;
use CodeIgniter\Files\File;
use CodeIgniter\I18n\Time;

class Home extends BaseController
{

    protected $modelProduct;
    protected $modelProductImage;
    protected $db;

    public function __construct()
    {
        $this->db = db_connect();
        $this->db->initialize();

        $this->modelProduct = new ProductModel();
        $this->modelProductImage = new ProductImageModel();
    }

    public function index()
    {
        $userData = array();
        if (cache()->get("user")) {
            $userData = cache()->get("user");
        }
        return view('home', ['user' => $userData]);
    }

    public function login()
    {
        return view('login');
    }

    public function about()
    {
        return view('about');
    }

    public function unauthorized()
    {
        return view('unauthorized');
    }

    public function upload()
    {
        $userfile = $this->request->getFile('userfile');

        $product_id = $this->request->getPost('product_id');


        if (!$userfile->isValid()) {

            return view('home', [

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

                return view('section_admin/product_add_photo', [
                    'errors' => $this->validator->getErrors(),
                    'product_id' => $product_id
                ]);
            }
        }

        $newName = $userfile->getRandomName();


        $nowTime = new Time();
        $dateString = $nowTime->toDateString();
        $timeString = $nowTime->toTimeString();
        $dateStringWithoutSpecialChar = str_replace("-", "", $dateString);
        $timeStringWithoutSpecialChar = str_replace(":", "", $timeString);


        //$userfile->move(WRITEPATH . 'uploads', $newName);
        //$filepath = WRITEPATH . 'uploads/original' . $newName;

        $image = service('image');
        $uploadPath = WRITEPATH . 'uploads/' . $product_id . '/';
        $uploadPathOriginal = $uploadPath . 'original/';
        $uploadPathMedium = $uploadPath . 'medium/';
        $uploadPathThumbnail = $uploadPath . 'thumbnail/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
            mkdir($uploadPathOriginal, 0777, true);
            mkdir($uploadPathMedium, 0777, true);
            mkdir($uploadPathThumbnail, 0777, true);
        }

        // $image->withFile($userfile)
        //     ->fit(100, 100, 'center')
        //     ->save(WRITEPATH . 'uploads/thumbnail/' . 'thumbnail_' . $newName);

        $newProductImage = new ProductImage();
        $newProductImage->product_id = $product_id;

        $initialImageData = $this->modelProductImage->where('product_id', $product_id)->first();

        $imageFile = $image->withFile($userfile);
        //save to original
        $imageFile->quality(80); // Set quality to 80%
        $imageFile->text(
            'Copyright 2025 Online Food Ordering System',
            [
                'color'     => '#fff',
                'opacity'   => 0.5,
                'withShadow'   => true,
                'hAlign'    => 'center',
                'vAlign'    => 'botton',
                'fontSize'  => 60,
            ]
        );
        $imageFile->save($uploadPathOriginal . '/' . 'original_' . $newName);

        $updateProductImageInitial = [
            'product_image_id' => $initialImageData->product_image_id,
            'product_id' => $initialImageData->product_id,
            'image_path' => $uploadPathOriginal . 'original_' . $newName,
            'is_primary' => true,
        ];

        $this->modelProductImage->save($updateProductImageInitial);

        //save to thumbnail
        $imageFile->resize(150, 150);
        $imageFile->save($uploadPathThumbnail . '/' . 'thumbnail_' . $newName);
        $newProductImage->is_primary = false;
        $newProductImage->image_path = $uploadPathThumbnail . 'thumbnail_' . $newName;
        $this->modelProductImage->save($newProductImage);

        //save to medium
        $imageFile->resize(500, 500);
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
        $imageFile->save($uploadPathMedium . '/' . 'medium_' . $newName);
        $newProductImage->is_primary = false;
        $newProductImage->image_path = $uploadPathMedium . 'medium_' . $newName;
        $this->modelProductImage->save($newProductImage);

        // $image->withFile($userfile)
        //     ->resize(300, 300, true, 'height')
        //     ->text(
        //         'Copyright 2020 My Photo Co',
        //         [
        //             'color'     => '#fff',
        //             'opacity'   => 0.5,
        //             'withShadow'   => true,
        //             'hAlign'    => 'center',
        //             'vAlign'    => 'botton',
        //             'fontSize'  => 20,
        //         ]
        //     )
        //     ->save(WRITEPATH . 'uploads/watermark/' .  'wm_' . $newName);

        //$studentData = $this->modelStudent->where('user_id', user_id())->first();

        //$fileName = $studentData->student_id . '_' . $dateStringWithoutSpecialChar . '_' . $timeStringWithoutSpecialChar .  '.pdf';

        //$userfile->move(WRITEPATH . 'uploads/original', $fileName);
        //$filepath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'original' . DIRECTORY_SEPARATOR . $fileName;

        // if (user_id()) {
        //     $studentData->diploma_path = $fileName;
        //     $this->modelStudent->save($studentData);
        // }

        // $data = ['uploaded_fileinfo' => new File($filepath)];

        return view(
            'uploads/success_page',
            //$data
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
