
<?php
use App\Controllers\PostController;
use App\Controllers\SecurityController;
use App\Controllers\UserController;
use App\Factories\MySQLFactory;
use App\Managers\TokenManager;
use App\Managers\UserManagers;

require_once 'vendor/autoload.php';

//get the URL from the server variable without the query string
$url = strtok($_SERVER["REQUEST_URI"],'?');
// url = /posts
$rank = 1;
$log=false;
if(isset($_COOKIE['token'])){
$tokenManager = new TokenManager();
$user = $tokenManager->getUserFromToken($_COOKIE['token']);
if($user !== null){
    $rank = $user->getRank();
    $log=true;
}
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($url) {
        case '/login':
            $securityController = new SecurityController();
            $securityController->login();
            break;
        case '/register':
            $securityController = new SecurityController();
            $securityController->register();
            break;
        case '/delUser':
            if($rank === 1){
                exit(header("Location: /"));
                return;
            }
            header("Location: /users");
            $userManager = new UserManagers(new MySQLFactory());
            $userManager->deleteUser($_POST['test']);
            break;
            
        case '/createPost': 
            $postController = new PostController();
            $postController->createPost();
            break;
        default:
            http_response_code(405);
            echo "405: Method Not Allowed";
            break;
    }

}
else 
{
    switch ($url) {
        case '/':
            $postController = new PostController();
            $postController->index();
            break;
        case '/post': 
           
            $postController = new PostController();
            $postController->post(intval($_GET['id']));
            

            break;
        case '/createPost': 
            if($log){
            $postController = new PostController();
            $postController->CreatePostForm();
            }
            exit(header("Location: /login"));
            break;
        case '/users':
            $UserController = new UserController();
            $UserController->ShowUsers();
            break;
        case '/login':   
            $securityController = new SecurityController();
            $securityController->loginRender();
            break;
        case '/register':
            $securityController = new SecurityController();
            $securityController->registerRender();
            break;
        default:
            break;
        break;
    
    }
}


