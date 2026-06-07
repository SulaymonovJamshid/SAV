<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config/app.php';
require_once APP . '/Controllers/Controllers.php';

use App\Core\Router;

$router = new Router();

// Auth
$router->get('/',                      'AuthController@home');
$router->get('/login',                 'AuthController@showLogin');
$router->post('/login',                'AuthController@login');
$router->get('/register',              'AuthController@showRegister');
$router->post('/register',             'AuthController@register');
$router->get('/verify-phone',          'AuthController@showVerify');
$router->post('/verify-phone',         'AuthController@verify');
$router->get('/resend-otp',            'AuthController@resendOtp');
$router->get('/logout',                'AuthController@logout');

// Services (public-ish, needs login)
$router->get('/services',              'ServiceController@index');
$router->get('/services/{id}',         'ServiceController@detail');

// Reviews & Favorites
$router->post('/reviews/store',        'ReviewController@store');
$router->post('/reviews/delete',       'ReviewController@delete');
$router->post('/favorites/toggle',     'FavoriteController@toggle');

// User profile
$router->get('/profile',               'UserController@profile');
$router->post('/profile/update',       'UserController@updateProfile');
$router->get('/set-theme',             'UserController@setTheme');

// Service dashboard
$router->get('/dashboard',             'DashboardController@index');
$router->get('/dashboard/edit',        'DashboardController@edit');
$router->post('/dashboard/save',       'DashboardController@save');
$router->post('/dashboard/img/upload', 'DashboardController@uploadImage');
$router->post('/dashboard/img/delete', 'DashboardController@deleteImage');
$router->post('/dashboard/delete',     'DashboardController@deleteSvc');

// API
$router->get('/api/nearby',            'ApiController@nearby');
$router->get('/api/tumans',            'ApiController@tumans');

// Admin
$router->get('/admin',                 'AdminController@index');
$router->get('/admin/users',           'AdminController@users');
$router->post('/admin/users/toggle',   'AdminController@toggleUser');
$router->get('/admin/services',        'AdminController@services');
$router->post('/admin/services/approve','AdminController@approveService');
$router->post('/admin/services/delete','AdminController@deleteService');
$router->get('/admin/reviews',         'AdminController@reviews');
$router->post('/admin/reviews/delete', 'AdminController@deleteReview');

// Dispatch
$base = parse_url(APP_URL, PHP_URL_PATH) ?? '';
$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri  = substr($uri, strlen($base)) ?: '/';

$router->dispatch($uri, $_SERVER['REQUEST_METHOD']);
