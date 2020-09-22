<?php
require './pdos/DatabasePdo.php';
require './pdos/IndexPdo.php';
require './pdos/StorePdo.php';
require './pdos/ValidationPdo.php';
require './pdos/mailer.lib.php';
require './pdos/EmailUserPdo.php';

require './vendor/autoload.php';

use \Monolog\Logger as Logger;
use Monolog\Handler\StreamHandler;

date_default_timezone_set('Asia/Seoul');
ini_set('default_charset', 'utf8mb4');

//에러출력하게 하는 코드
//error_reporting(E_ALL); ini_set("display_errors", 1);


//Main Server API
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    /* ******************   Test   ****************** */
    $r->addRoute('GET', '/', ['IndexController', 'index']);
    $r->addRoute('GET', '/test', ['IndexController', 'test']);
    $r->addRoute('GET', '/users', ['IndexController', 'getUsers']);
    $r->addRoute('GET', '/test/{testNo}', ['IndexController', 'testDetail']);
    $r->addRoute('POST', '/test', ['IndexController', 'testPost']);

//-------------------------유저 관련 api ----------------------------------------
    $r->addRoute('POST', '/sign-up', ['EmailUser', 'createEmailUser',]);
    $r->addRoute('POST', '/sign-in', ['EmailUser', 'loginEmailUser',]);
    $r->addRoute('POST', '/valid-nickname', ['EmailUser', 'loginEmailUserNickName',]);
    $r->addRoute('GET', '/basket', ['EmailUser', 'getProductBasket',]);
    $r->addRoute('PATCH', '/profile', ['EmailUser', 'updateUserProfile',]);
    $r->addRoute('POST', '/pw-reset', ['EmailUser', 'getUserPw',]);//패스워드 찾기
    $r->addRoute('POST', '/scrap', ['EmailUser', 'createUserScrap',]);
    $r->addRoute('GET', '/scrap', ['EmailUser', 'getUserScrap',]);
    $r->addRoute('PATCH', '/basket', ['EmailUser', 'updateUserBasket',]);
    $r->addRoute('DELETE', '/basket', ['EmailUser', 'deleteUserBasket',]);

//--------------------------스토어 카테고리-----------------------------------------

    $r->addRoute('GET', '/store/category', ['StoreController', 'getStoreCategory']);
    $r->addRoute('GET', '/store/category/detail', ['StoreController', 'getStoreCategoryDetail']);
    $r->addRoute('GET', '/store/category/detail2', ['StoreController', 'getStoreCategoryDetail2']);
    $r->addRoute('GET', '/store/category/detail3', ['StoreController', 'getStoreCategoryDetail3']);
    $r->addRoute('GET', '/product', ['StoreController', 'getStoreCategoryProduct']);
    $r->addRoute('GET', '/pop-product', ['StoreController', 'getStorePopProduct']);
    $r->addRoute('GET', '/sale-product', ['StoreController', 'getStoreSaleProduct']);
    $r->addRoute('GET', '/today-deal', ['StoreController', 'getStoreTodayDeal']);
    $r->addRoute('GET', '/product/{productIdx}', ['StoreController', 'getStoreProductDetail',]);
    $r->addRoute('GET', '/product/{productIdx}/review', ['StoreController', 'getProductReview',]);
    $r->addRoute('POST', '/product/{productIdx}/review', ['StoreController', 'createProductReview',]);
    $r->addRoute('PATCH', '/product/{productIdx}/review', ['StoreController', 'updateProductReview',]);
    $r->addRoute('POST', '/product-view', ['StoreController', 'createProductView',]);
    $r->addRoute('GET', '/product-view', ['StoreController', 'getProductView',]);
    $r->addRoute('GET', '/today-deal/{productIdx}', ['StoreController', 'getStoreTodayDealProductDetail',]);
    $r->addRoute('GET', '/product-info/{productIdx}', ['StoreController', 'getStoreProductInfo',]);
    $r->addRoute('POST', '/product/question', ['StoreController', 'createProductQuestion',]);
    $r->addRoute('GET', '/product/{productIdx}/question', ['StoreController', 'getProductQuestion',]);
    $r->addRoute('POST', '/basket', ['StoreController', 'createProductBasket',]);
    $r->addRoute('POST', '/purchase', ['StoreController', 'createUserPurchase',]);


    $r->addRoute('GET', '/jwt', ['MainController', 'validateJwt']);
    $r->addRoute('POST', '/jwt', ['MainController', 'createJwt']);



//    $r->addRoute('GET', '/users', 'get_all_users_handler');
//    // {id} must be a number (\d+)
//    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
//    // The /{title} suffix is optional
//    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// 로거 채널 생성
$accessLogs = new Logger('ACCESS_LOGS');
$errorLogs = new Logger('ERROR_LOGS');
// log/your.log 파일에 로그 생성. 로그 레벨은 Info
$accessLogs->pushHandler(new StreamHandler('logs/access.log', Logger::INFO));
$errorLogs->pushHandler(new StreamHandler('logs/errors.log', Logger::ERROR));
// add records to the log
//$log->addInfo('Info log');
// Debug 는 Info 레벨보다 낮으므로 아래 로그는 출력되지 않음
//$log->addDebug('Debug log');
//$log->addError('Error log');

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        switch ($routeInfo[1][0]) {
            case 'IndexController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/IndexController.php';
                break;
            case 'MainController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/MainController.php';
                break;
            case 'EmailUser':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/EmailUser.php';
                break;
            case 'StoreController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/StoreController.php';
                break;
            /*case 'EventController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/EventController.php';
                break;
            case 'ProductController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ProductController.php';
                break;
            case 'SearchController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/SearchController.php';
                break;
            case 'ReviewController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ReviewController.php';
                break;
            case 'ElementController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/ElementController.php';
                break;
            case 'AskFAQController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/AskFAQController.php';
                break;*/
        }

        break;
}
