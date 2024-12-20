<?php
include_once "Common.php";
class Get extends Common{

    protected $pdo;

    public function __construct(\PDO $pdo){
        $this -> pdo = $pdo;
    }

    public function getLogs($date = "2024-12-07") {
        $filename = "./logs/$date" . ".log";
        $logs = array();
        
        try {
            $file = new SplFileObject($filename);
            while (!$file->eof()) {
                array_push($logs, $file->fgets());
            }
            $remarks = "success";
            $message = "Successfully retrieved logs";
        } 
        catch (Exception $e) {
            $remarks = "failed";
            $message = $e->getMessage();
        }

        return $this->generateResponse(array("logs"=>$logs), $remarks, $message, 200);        
    }
  
    public function getShows($id = null){
        $condition = "isdeleted = 0";
        if($id != null){
            $condition .= " AND id=" . $id;
        }

        $result = $this->getDataByTable('posts_tbl', $condition, $this->pdo);
        if($result['code'] == 200){
            return $this->generateResponse($result['data'], "success", "Successfully retrieved records", $result['code']);
        }
        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);

    }
    
    public function getAllUsers() {
        $condition = "isdeleted = 0";

        $result = $this->getDataByTable('users_tbl', $condition, $this->pdo);
        if($result['code'] == 200){
            return $this->generateResponse($result['data'], "success", "Successfully retrieved all users", $result['code']);
        }
        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);
    }

    public function getAllPosts() {
        $condition = "isdeleted = 0";

        $result = $this->getDataByTable('posts_tbl', $condition, $this->pdo);
        if($result['code'] == 200){
            return $this->generateResponse($result['data'], "success", "Successfully retrieved all users", $result['code']);
        }
        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);
    }

    public function getAllCategories() {
        $condition = "isdeleted = 0";

        $result = $this->getDataByTable('categories_tbl', $condition, $this->pdo);
        if($result['code'] == 200){
            return $this->generateResponse($result['data'], "success", "Successfully retrieved all users", $result['code']);
        }
        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);
    }

    public function findUser($username) {
        $condition = "username = ? AND isdeleted = 0";

        $result = $this->getDataByTable('users_tbl', $condition, $this->pdo, [$username]);
        if($result['code'] == 200){
            if (isset($result['data'][0]['password'])) {
                unset($result['data'][0]['password']);
            }

            return $this->generateResponse($result['data'][0], "success", "Successfully retrieved user", $result['code']);
        }
        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);
    }
    
    protected function getDataByTable($tableName, $condition, $pdo, $params = []) {
        $errmsg = "";
        $code = 0;
    
        try {
            $sqlString = "SELECT * FROM $tableName WHERE $condition";
            $sql = $pdo->prepare($sqlString);
            $sql->execute($params);
    
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);
            $code = 200;
    
            return array("data" => $data, "code" => $code);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }
    
        return array("errmsg" => $errmsg, "code" => $code);
    }


    public function getPostById($id) {

        if (!is_numeric($id)) {
            return $this->generateResponse(null, "failed", "Invalid post ID", 400);
        }
    
        $condition = "id = :id AND isdeleted = 0";
        $query = "SELECT * FROM posts_tbl WHERE $condition";
    
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($post) {
                return $this->generateResponse($post, "success", "Successfully retrieved the post", 200);
            } else {
                return $this->generateResponse(null, "failed", "Post not found or deleted", 404);
            }
        } catch (PDOException $e) {
            return $this->generateResponse(null, "failed", $e->getMessage(), 500);
        }
    }

    public function getPostsByCategory($id) {
        $errmsg = "";
        $code = 0;
    
        try {

            $sqlString = "
                SELECT 
                    posts_tbl.id, 
                    posts_tbl.title, 
                    posts_tbl.content, 
                    categories_tbl.name AS category_name
                FROM posts_tbl
                INNER JOIN categories_tbl ON posts_tbl.category_id = categories_tbl.id
                WHERE categories_tbl.id = ? AND posts_tbl.isdeleted = 0
            ";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute([$id]);
    
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);
            $code = 200;
    
            return array("data" => $data, "code" => $code); 
        } catch (\PDOException $e) {
    
            $errmsg = $e->getMessage();
            $code = 400;
        }
    
        return array("errmsg" => $errmsg, "code" => $code); 
    }
    
    public function getCommentsByPost($id) {
        $errmsg = "";
        $code = 0;
    
        try {
            $sqlString = "
                SELECT 
                    comments_tbl.id AS comment_id,
                    comments_tbl.content AS comment_content,
                    comments_tbl.created_at AS comment_created_at,
                    comments_tbl.parentcomment_id AS parentcomment_id,
                    posts_tbl.id AS post_id,
                    posts_tbl.title AS post_title,
                    posts_tbl.content AS post_content,
                    posts_tbl.created_at AS post_created_at
                FROM comments_tbl
                INNER JOIN posts_tbl ON comments_tbl.posts_id = posts_tbl.id
                WHERE posts_tbl.id = ? AND posts_tbl.isdeleted = 0
                ORDER BY comments_tbl.created_at ASC
            ";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute([$id]);
    
            $comments = $sql->fetchAll(\PDO::FETCH_ASSOC);
    
            $commentsById = [];
            $tree = [];
    
            foreach ($comments as $comment) {
                $comment['children'] = [];
                $commentsById[$comment['comment_id']] = $comment;
            }
    
            foreach ($commentsById as &$comment) {
                if ($comment['parentcomment_id']) {
                    $commentsById[$comment['parentcomment_id']]['children'][] = &$comment;
                } else {
                    $tree[] = &$comment;
                }
            }
    
            $code = 200;
            return array("data" => $tree, "code" => $code);
    
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }
    
        return array("errmsg" => $errmsg, "code" => $code);
    }

    public function getCategoryById($id) {
        if (!is_numeric($id)) {
            return $this->generateResponse(null, "failed", "Invalid post ID", 400);
        }
    
        $condition = "id = :id AND isdeleted = 0";
        $query = "SELECT * FROM categories_tbl WHERE $condition";
    
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($post) {
                return $this->generateResponse($post, "success", "Successfully retrieved the caegory", 200);
            } else {
                return $this->generateResponse(null, "failed", "Category not found or deleted", 404);
            }
        } catch (PDOException $e) {
            return $this->generateResponse(null, "failed", $e->getMessage(), 500);
        }
    }

    public function getPostsByUser($id) {
        $errmsg = "";
        $code = 0;
    
        try {
            $sqlString = "
                SELECT 
                    posts_tbl.id AS post_id,
                    posts_tbl.title AS post_title,
                    posts_tbl.content AS post_content,
                    posts_tbl.created_at AS post_created_at,
                    users_tbl.username AS post_username
                FROM posts_tbl
                INNER JOIN users_tbl ON posts_tbl.user_id = users_tbl.id
                WHERE posts_tbl.user_id = ? AND posts_tbl.isdeleted = 0
            ";
    
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute([$id]);
    
            $data = $sql->fetchAll(\PDO::FETCH_ASSOC);
            $code = 200;
    
            return array("data" => $data, "code" => $code); 
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }
    
        return array("errmsg" => $errmsg, "code" => $code); 
    }

}

?>