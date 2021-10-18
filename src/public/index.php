<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require 'api/helpers.php';
require_once('connection.php');

$dotenv = new Dotenv\Dotenv(__DIR__ . str_repeat(DIRECTORY_SEPARATOR . '..', 2));
$dotenv->load();

$config = [
  'apiKey' => $_ENV['API_KEY'],
  'secret' => $_ENV['SECRET'],
  'host' => $_ENV['HOST'],
  'scopes' => $_ENV['SCOPES'],
  'user' => $_ENV['PR_API_KEY'],
  'password' => $_ENV['PR_PASSWORD'],
  'shop' => $_ENV['SHOP'],
  'dbName' => $_ENV['DB_DATABASE'],
  'dbUsername' => $_ENV['DB_USERNAME'],
  'dbPassword' => $_ENV['DB_PASSWORD'],
  'dbHost' => $_ENV['DB_HOST'],
  'settings' => ['displayErrorDetails' => true],
];
$app = new \Slim\App($config);

$app->add(function ($req, $res, $next) {
  $response = $next($req, $res);
  return $response
          ->withHeader('Access-Control-Allow-Origin', '*')
          ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
          ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH');
});

// install route
$app->get('/', function (Request $request, Response $response) {
  header('Access-Control-Allow-Origin: *');
  $apiKey = $this->get('apiKey');
  $host = $this->get('host');
  $shop = $request->getQueryParam('shop');
  if (!validateShopDomain($shop)) {
    return $response->getBody()->write("Invalid shop domain!");
  }
  $scope = $this->get('scopes');

  $redirectUri = $host . $this->router->pathFor('oAuthCallback');
  $installUrl = "https://{$shop}/admin/oauth/authorize?client_id={$apiKey}&scope={$scope}&redirect_uri={$redirectUri}";

  return $response->withRedirect($installUrl)
          ->withHeader('Access-Control-Allow-Origin', '*')
          ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
          ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// callback route
$app->get('/auth/shopify/callback', function (Request $request, Response $response) {;
  $controller = "pages";
  $action = 'home';
  actionRouter($controller, $action);
})->setName('oAuthCallback');

$app->get('/changetheme', function (Request $request, Response $response) {;
  set_time_limit(300);
  $controller = "pages";
  $action = 'changeTheme';
  actionRouter($controller, $action);
})->setName('oAuthCallback');



function actionRouter($controller, $action, $data = null)
{
  include_once('controllers/' . $controller . '_controller.php');
  // Tạo ra tên controller class từ các giá trị lấy được từ URL sau đó gọi ra để hiển thị trả về cho người dùng.
  $klass = str_replace('_', '', ucwords($controller, '_')) . 'Controller';
  $controller = new $klass;
  if ($data) {
    $controller->$action($data);
  } else {
    $controller->$action();
  }
} {
  # code...
}


$app->run();
