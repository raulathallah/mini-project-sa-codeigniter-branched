<?php

use App\Controllers\Admin\Admin;
use App\Controllers\Admin\Product as AdminProduct;
use App\Controllers\Admin\Role;
use App\Controllers\Admin\User as AdminUser;
use App\Controllers\Auth;
use App\Controllers\Home;
use App\Controllers\Product;
use App\Controllers\User;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->get('/', [Home::class, 'login']);
$routes->get('/', [Home::class, 'index']);


$routes->get('product', [Product::class, 'index']);




$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
  $routes->get('register', 'Auth::register', ['as' => 'register']);
  $routes->post('register', 'Auth::attemptRegister');

  $routes->get('login', 'Auth::login', ['as' => 'login']);
  $routes->post('login', 'Auth::attemptLogin');

  $routes->get('logout', 'Auth::logout');
});

$routes->get('productImage/(:num)/(:segment)', [Product::class, 'productImage']);
$routes->get('profile', [User::class, 'userProfile']);


$routes->group('admin/product', [
  'filter' => 'role:product_manager,administrator',
], function ($routes) {
  $routes->get('', [AdminProduct::class, 'index']);
  $routes->get('on_create', [AdminProduct::class, 'onCreate']);
  $routes->post('create', [AdminProduct::class, 'create']);
  $routes->get('on_update/(:num)', [AdminProduct::class, 'onUpdate']);
  $routes->post('update', [AdminProduct::class, 'update']);
  $routes->get('delete/(:num)', [AdminProduct::class, 'delete']);
  $routes->get('detail/(:num)', [AdminProduct::class, 'detail']);
  $routes->get('add_photo/(:num)', [AdminProduct::class, 'addPhoto']);
  $routes->post('additional_photo', [AdminProduct::class, 'additionalProductPhoto']);
});


$routes->group('admin/user', [
  'filter' => 'role:administrator',
], function ($routes) {
  $routes->get('', [Admin::class, 'getUsers']);
  $routes->get('on_create', [AdminUser::class, 'onCreate']);
  $routes->post('create', [AdminUser::class, 'create']);
  $routes->get('on_update/(:num)', [AdminUser::class, 'onUpdate']);
  $routes->post('update', [AdminUser::class, 'update']);
  $routes->get('delete/(:num)', [AdminUser::class, 'delete']);
  $routes->get('detail/(:num)', [AdminUser::class, 'detail']);
  $routes->get('on_update_role/(:num)', [AdminUser::class, 'onUpdateRole']);
  $routes->post('update_role', [AdminUser::class, 'updateRole']);
});

$routes->group('admin/role', [
  'filter' => 'role:administrator',
], function ($routes) {
  $routes->get('', [Role::class, 'index']);
  $routes->get('on_create', [Role::class, 'onCreate']);
  $routes->post('create', [Role::class, 'create']);
  $routes->get('on_update/(:num)', [Role::class, 'onUpdate']);
  $routes->post('update', [Role::class, 'update']);
  $routes->get('delete/(:num)', [Role::class, 'delete']);
});


$routes->group('admin', ['filter' => 'role:product_manager,administrator', 'namespace' => 'App\Controllers\Admin'], function ($routes) {
  //$routes->get('dashboard', [Dashboard::class, 'index'], ['filter'=>\App\Filters\AuthFilter::class]);  
  $routes->get('dashboard', [Admin::class, 'index']);
});

$routes->get('unauthorized', [Home::class, 'unauthorized']);
