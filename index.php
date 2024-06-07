<?php
require_once __DIR__ . "/src/utils/Security.php";
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Security::secureSession();
session_start();

$request = $_SERVER['REQUEST_URI'];


if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    switch($request){
        case '/tag_admin/':
            require __DIR__ . '/src/auth/controllers/LoginController.php';
            $controller = new LoginController();
            $controller->post();
            break;
        case '/tag_admin/create':
            require __DIR__ . '/src/controllers/domain/DomainController.php';
            $controller = new DomainController();
            $controller->add();
            break;
        case '/tag_admin/edit':
            require __DIR__ . '/src/controllers/domain/DomainController.php';
            $controller = new DomainController();
            $controller->edit();
            break;
        case '/tag_admin/deactivate':
            require __DIR__ . '/src/controllers/domain/DomainController.php';
            $controller = new DomainController();
            $controller->deactivate();
            break;
        case '/tag_admin/deleteTagIndex':
            require __DIR__ . '/src/controllers/tag/TagController.php';
            $controller = new TagController();
            $controller->deleteIndex();
            break;
        case '/tag_admin/deleteTagShow':
            require __DIR__ . '/src/controllers/tag/TagController.php';
            $controller = new TagController();
            $controller->deleteShow();
            break;
        case '/tag_admin/deleteDomain':
            require __DIR__ . '/src/controllers/domain/DomainController.php';
            $controller = new DomainController();
            $controller->delete();
            break;
        case '/tag_admin/addTag':
            require __DIR__ . '/src/controllers/tag/TagController.php';
            $controller = new TagController();
            $controller->create();
            break;
        case '/tag_admin/editTag':
            require __DIR__ . '/src/controllers/tag/TagController.php';
            $controller = new TagController();
            $controller->edit();
            break;
        case '/tag_admin/addCategory':
            require __DIR__ . '/src/controllers/CategoryController.php';
            $controller = new CategoryController();
            $controller->create();
            break;
       
        case '/tag_admin/editCategory':
            require __DIR__ . '/src/controllers/CategoryController.php';
            $controller = new CategoryController();
            $controller->edit();
            break;

        // 親管理者のみ
        case '/tag_admin/admin/edit':
            require __DIR__ . '/src/controllers/admin/AdminController.php';
            $controller = new AdminController();
            $controller->edit();
            break;
        case '/tag_admin/admin/delete':
            require __DIR__ . '/src/controllers/admin/AdminController.php';
            $controller = new AdminController();
            $controller->delete();
            break;
        case '/tag_admin/admin/create':
            require __DIR__ . '/src/controllers/admin/AdminController.php';
            $controller = new AdminController();
            $controller->create();
            break;
       
    }
} else if($_SERVER['REQUEST_METHOD'] === 'GET'){
    switch (true) {
        
        case $request === '/tag_admin/':
            require __DIR__ . '/src/auth/controllers/LoginController.php';
            $controller = new LoginController();
            $controller->get();
            break;
        case $request === '/tag_admin/admin' && isset($_SESSION["role"]) == "admin":
            require __DIR__ . '/src/auth/controllers/SuperAdminController.php';
            $controller = new SuperAdminController();
            $controller->get();
                    break;
        case $request === '/tag_admin/error':
            require __DIR__ . '/src/controllers/ErrorController.php';
            $controller = new ErrorController();
            $controller->get();
            break;
        case $request === '/tag_admin/index':
            require __DIR__ . '/src/controllers/domain/DomainIndexController.php';
            $controller = new DomainIndexController();
            $controller->get();
            break;
        case preg_match('/\/tag_admin\/showTag\/\?id=\d+/', $_SERVER['REQUEST_URI']):
            require __DIR__ . '/src/controllers/domain/DomainShowController.php';
            $controller = new DomainShowController();
            $controller->get();
            break;
        case preg_match('/\/tag_admin\/tag\/\?id=\d+/', $_SERVER['REQUEST_URI']):
            require __DIR__ . '/src/controllers/tag/TagShowController.php';
            $controller = new TagShowController();
            $controller->get();
            break;
        case preg_match('/\/tag_admin\/tagRange\/\?id=\d+/', $_SERVER['REQUEST_URI']):
            require __DIR__ . '/src/controllers/tag/TagShowController.php';
            $controller = new TagShowController();
            $controller->getRange();
            break;
        // default:
        //     require __DIR__ . '/src/controllers/domain/DomainShowController.php';
        //     $controller = new DomainShowController();
        //     $controller->error();
        //     break;
       
    }
}



?>

<?php



// require_once __DIR__ . "/src/utils/Security.php";
// require_once __DIR__ . '/vendor/autoload.php';
// require_once(dirname(__FILE__). "/src/config/conf.php");

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
// $dotenv->load();

// // Security::secureSession();
// session_start();

// $request = $_SERVER['REQUEST_URI'];



// // echo PATH;
// // exit;

// if ($_SERVER['REQUEST_METHOD'] === 'POST'){
//     switch(true){
//         case $request === PATH :
//             require __DIR__ . '/src/auth/controllers/LoginController.php';
//             $controller = new LoginController();
//             $controller->post();
//             break;
//         case $request === PATH .'create':
//             require __DIR__ . '/src/controllers/domain/DomainController.php';
//             $controller = new DomainController();
//             $controller->add();
//             break;
//         case $request === PATH .'edit':
//             require __DIR__ . '/src/controllers/domain/DomainController.php';
//             $controller = new DomainController();
//             $controller->edit();
//             break;
//         case $request === PATH .'deactivate':
//             require __DIR__ . '/src/controllers/domain/DomainController.php';
//             $controller = new DomainController();
//             $controller->deactivate();
//             break;
//         case $request === PATH .'deleteTagIndex':
//             require __DIR__ . '/src/controllers/tag/TagController.php';
//             $controller = new TagController();
//             $controller->deleteIndex();
//             break;
//         case $request === PATH .'deleteTagShow':
//             require __DIR__ . '/src/controllers/tag/TagController.php';
//             $controller = new TagController();
//             $controller->deleteShow();
//             break;
//         case $request === PATH .'deleteDomain':
//             require __DIR__ . '/src/controllers/domain/DomainController.php';
//             $controller = new DomainController();
//             $controller->delete();
//             break;
//         case $request === PATH .'addTag':
//             require __DIR__ . '/src/controllers/tag/TagController.php';
//             $controller = new TagController();
//             $controller->create();
//             break;
//         case $request === PATH .'editTag':
//             require __DIR__ . '/src/controllers/tag/TagController.php';
//             $controller = new TagController();
//             $controller->edit();
//             break;
//         case $request === PATH .'addCategory':
//             require __DIR__ . '/src/controllers/CategoryController.php';
//             $controller = new CategoryController();
//             $controller->create();
//             break;
       
//         case $request === PATH .'editCategory':
//             require __DIR__ . '/src/controllers/CategoryController.php';
//             $controller = new CategoryController();
//             $controller->edit();
//             break;
//         // 親管理者のみ
//         case $request === PATH .'admin/edit':
//             require __DIR__ . '/src/controllers/admin/AdminController.php';
//             $controller = new AdminController();
//             $controller->edit();
//             break;
//         case $request === PATH .'admin/delete':
//             require __DIR__ . '/src/controllers/admin/AdminController.php';
//             $controller = new AdminController();
//             $controller->delete();
//             break;
//         case $request === PATH .'admin/create':
//             require __DIR__ . '/src/controllers/admin/AdminController.php';
//             $controller = new AdminController();
//             $controller->create();
//             break;
    
//     }
// } else if($_SERVER['REQUEST_METHOD'] === 'GET'){
//     switch (true) {
//         case $request === PATH:
//             require __DIR__ . '/src/auth/controllers/LoginController.php';
//             $controller = new LoginController();
//             $controller->get();
//             break;
//         case $request === PATH . 'admin' && isset($_SESSION["role"]) == "admin":
//             require __DIR__ . '/src/auth/controllers/SuperAdminController.php';
//             $controller = new SuperAdminController();
//             $controller->get();
//                     break;
//         case $request === PATH .'error':
//             require __DIR__ . '/src/controllers/ErrorController.php';
//             $controller = new ErrorController();
//             $controller->get();
//             break;
//         case $request === PATH .'index':
//             require __DIR__ . '/src/controllers/domain/DomainIndexController.php';
//             $controller = new DomainIndexController();
//             $controller->get();
//             break;
//         case  preg_match('/^\/showTag\/\?id=\d+/', $_SERVER['REQUEST_URI']) === 1:
//             require __DIR__ . '/src/controllers/domain/DomainShowController.php';
//             $controller = new DomainShowController();
//             $controller->get();
//             break;
//         case preg_match('/^\/tag\/\?id=\d+/', $_SERVER['REQUEST_URI']) === 1:
//             require __DIR__ . '/src/controllers/tag/TagShowController.php';
//             $controller = new TagShowController();
//             $controller->get();
//             break;
//         case preg_match('/^\/tagRange\/\?id=\d+/', $_SERVER['REQUEST_URI']) === 1:
//             require __DIR__ . '/src/controllers/tag/TagShowController.php';
//             $controller = new TagShowController();
//             $controller->getRange();
//             break;
//         default:
//             require __DIR__ . '/src/controllers/domain/DomainShowController.php';
//             $controller = new DomainShowController();
//             $controller->error();
//             break;
       
//     }
// }


?>