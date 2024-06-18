<?php

use Softadastra\Exception\NotFoundException;
use Softadastra\Router\Router;

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once(dirname(__DIR__) . '/vendor/autoload.php');
define('VIEWS', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR);
define('SCRIPTS', dirname($_SERVER['SCRIPT_NAME']) . DIRECTORY_SEPARATOR);
define("BASE_URL", "");
define("ADMIN_URL", BASE_URL . "admin" . "/");
define('CSS_PATH', 'assets/css/');
define('JS_PATH', 'assets/js/');
define('FAVICON_PATH', 'assets/favicon/');
define('IMAGE_PATH', 'softadastra_images/');
// LES CONSTANTES POUR LA BASE DES DONNEES
define('DB_NAME', 'web243_mulystore');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PWD', '');

if (isset($_GET['url'])) {
    $url = $_GET['url'];
} else {
    $url = '/';
}


$router = new Router($url);
/* Les routes pour les controllers du shop softadastra */
$router->get('/', 'Softadastra\Controllers\\ArticleController@home');
$router->get('/article/:id', 'Softadastra\Controllers\\ArticleController@show');
$router->post('/add-to-cart/:id', 'Softadastra\Controllers\\ArticleController@addToCart');
$router->get('/cart', 'Softadastra\Controllers\\ArticleController@cart');
$router->get('/empty-cart', 'Softadastra\Controllers\\ArticleController@emptycart');
$router->post('/update-cart', 'Softadastra\Controllers\\ArticleController@updateCart');
$router->get('/order', 'Softadastra\Controllers\\ArticleController@order');
$router->post('/process-order', 'Softadastra\Controllers\\ArticleController@processOrder');
$router->post('/search', 'Softadastra\Controllers\\ArticleController@search');
/* Les routes pour l'authentification */
$router->get('/auth/login', 'Softadastra\Controllers\\AuthController@login');
$router->post('/auth/postLogin', 'Softadastra\Controllers\\AuthController@postLogin');
$router->get('/auth/register', 'Softadastra\Controllers\\AuthController@register');
$router->post('/auth/postRegister', 'Softadastra\Controllers\\AuthController@postRegister');
$router->get('/auth/logout/:id', 'Softadastra\Controllers\\AuthController@logout');
/* Les routes pour l'adminisatration d'un utilisateur */
$router->get('/admin/users/dashboard/:id', 'Softadastra\Controllers\\UsersController@dashboard');
/* categorye et publications des articles */
$router->get('/admin/users/show-top-category', 'Softadastra\Controllers\\UsersController@showTopCategory');
$router->get('/admin/users/show-mid-category/:id', 'Softadastra\Controllers\\UsersController@showMidCategory');
$router->get('/admin/users/show-end-category/:id', 'Softadastra\Controllers\\UsersController@showEndCategory');
$router->get('/admin/users/show-formulaire/:id', 'Softadastra\Controllers\\UsersController@showFormulaire');
$router->post('/admin/users/publishedArticle', 'Softadastra\Controllers\\UsersController@publishedArticle');
/* user utiliteur */
$router->get('/admin/users/listing/:id', 'Softadastra\Controllers\\UsersController@listingArticles');
$router->get('/admin/users/show-article/:id', 'Softadastra\Controllers\\UsersController@showArticle');
$router->get('/admin/users/my-order/:id', 'Softadastra\Controllers\\UsersController@myOrder');
$router->get('/admin/users/client-order/:id', 'Softadastra\Controllers\\UsersController@clientOrder');
$router->post('/admin/users/deleteArticle/:id', 'Softadastra\Controllers\\UsersController@deleteArticle');
try {
    $router->run();
} catch (NotFoundException $e) {
    return $e->error404();
}
