<?php

require_once "./config/database.php";
require_once "./modules/Get.php";
require_once "./modules/Post.php";
require_once "./modules/Patch.php";
require_once "./modules/Delete.php";
require_once "./modules/Auth.php";
require_once "./modules/Crypt.php";

$db = new Connection();
$pdo = $db->connect();

$post = new Post($pdo);
$patch = new Patch($pdo);
$get = new Get($pdo);
$delete = new Delete($pdo);
$auth = new Authentication($pdo);
$crypt = new Crypt();

if(isset($_REQUEST['request'])){
    $request = explode("/", $_REQUEST['request']);
}
else{
    echo "URL does not exist.";
}



switch($_SERVER['REQUEST_METHOD']){

    case "GET":
        $headers = getallheaders();
    
        switch ($request[0]) {
            case "data":
                if ($auth->isAuthorized()) {
                    $dataString = json_encode($get->getShows($request[1] ?? null));
                    echo $crypt->encryptData($dataString);
                } else {
                    http_response_code(403);
                    echo json_encode(["error" => "Access denied."]);
                }
                break;
    
            case "allusers":
                $requiredRole = 2; // Admin role required
                if ($auth->isAuthorized($requiredRole)) {
                    echo json_encode($get->getAllUsers());
                } else {
                    http_response_code(403);
                    echo json_encode(["error" => "Access denied. Admins only."]);
                }
                break;
    
            case "log":
                echo json_encode($get->getLogs($request[1] ?? date("Y-m-d")));
                break;
    
            case "allposts":
                echo json_encode($get->getAllPosts());
                break;
    
            case "allcategories":
                echo json_encode($get->getAllCategories());
                break;
    
            case "allpostinacategory":
                echo json_encode($get->getPostsByCategory($request[1]));
                break;
    
            case "allcommentsinapost":
                echo json_encode($get->getCommentsByPost($request[1]));
                break;
    
            case "allpostinauser":
                echo json_encode($get->getPostsByUser($request[1]));
                break;
    
            case "findposts":
                echo json_encode($get->getPostById($_GET['id']));
                break;
    
            case "findcategory":
                echo json_encode($get->getCategoryById($_GET['id']));
                break;
    
            case "finduser":
                $username = $request[1] ?? null;
                if ($username) {
                    echo json_encode($get->findUser($username));
                } else {
                    http_response_code(400);
                    echo json_encode(["error" => "Username is required."]);
                }
                break;
    
            default:
                http_response_code(401);
                echo "This is an invalid endpoint.";
                break;
        }
        break;
    


    case "POST":
        $body = json_decode(file_get_contents("php://input"));
        switch($request[0]){
            case "login":
                echo json_encode($auth->login($body));
            break;
            
            case "user":
                echo json_encode($auth->addAccount($body));
            break;

            case "allposts":
                echo $crypt->decryptData($body);
            break;

            case "channel":
                echo json_encode($post->postChannel($body));
            break;

            default:
                http_response_code(401);
                echo "This is invalid endpoint";
            break;

            case "createcategory":
                echo json_encode($auth->addCategory($body));
            break;

            case "createcomment":
                echo json_encode($post->postComment($body));
            break;

            case "createposts":
                echo json_encode($post->postInCategory($body));
            break;

            case "nestedcomment":
                echo json_encode($post->postCommentReply($body));
            break;
        }
    break;


    case "PATCH":
        
        $body = json_decode(file_get_contents("php://input"));
        switch($request[0]){
            case "updateusers":
                echo json_encode($patch->patchUsers($body, $request[1]));
                break;

            case "updatepost":
                echo json_encode($patch->patchPost($body, $request[1]));
                break;

            case "updatecomment":
                echo json_encode($patch->patchCommment($body, $request[1]));
                break;

            case "updatecategory":
                echo json_encode($patch->patchCategory($body, $request[1]));
                break;
            
            case "updaterole":
                echo json_encode($patch->updateRole($body));
                break;


        
            
        }
    break;

    case "DELETE":

        $body = json_decode(file_get_contents("php://input"));
        switch ($request[0]) {

            default:
                http_response_code(401);
                echo "Invalid DELETE endpoint";
                break;

            case "archivecategories":
                echo json_encode($delete->archiveCategory($request[1]));
                break;

            case "archiveuser":
                echo json_encode($delete->archiveUsers($request[1]));
                break;

            case "archivepost":
                echo json_encode($delete->archivePost($request[1]));
                break;

            case "archivecomment":
                echo json_encode($delete->archiveComments($request[1]));
                break;
            
            case "deletecomment":
                echo json_encode($delete->deleteComment($request[1]));
                break;
            case "deletecategory":
                echo json_encode($delete->deleteCategory($request[1]));
                break;

            case "deletepost":
                echo json_encode($delete->deletePost($request[1]));
                break;
                
            case "deleteuser":
                echo json_encode($delete->deleteUser($request[1]));
                break;
        

        }

    default:
        http_response_code(400);
        echo "Invalid Request Method.";
    break;
}



?>