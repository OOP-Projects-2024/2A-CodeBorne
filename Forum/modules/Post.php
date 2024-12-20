<?php

include_once "Common.php";
class Post extends Common{

    protected $pdo;

    public function __construct(\PDO $pdo){
        $this -> pdo = $pdo;
    }
    public function postChannel($body){
        $result = $this->postData("cards_tbl", $body, $this->pdo);
        if($result['code'] == 200){
            $this->logger("Flag", "POST", "Created a new channel record.");
            return $this->generateResponse($result['data'], "success", "Successfully created new records.", $result['code']);
        }
        $this->logger("Flag", "POST", $result['errmsg']);
        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);

    }

    public function postShows($body){
        $result = $this->postData("adult_swim_bumps", $body, $this->pdo);
        if($result['code'] == 200){
            $this->logger("Flag", "POST", "Created a new show record.");
            return $this->generateResponse($result['data'], "success", "Successfully created new records.", $result['code']);
        }
        $this->logger("Flag", "POST", $result['errmsg']);
        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);    
    }


    public function postComment($body) {
        if (is_object($body)) {
            $body = (array) $body;
        }
    
        $result = $this->postData("comments_tbl", $body, $this->pdo);
        
        if($result['code'] == 200){
            $this->logger("Flag", "POST", "Created a new comment record.");
            return $this->generateResponse($result['data'], "success", "Successfully created new records.", $result['code']);
        }
    
        $this->logger("Flag", "POST", $result['errmsg']);
        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);
    }

    public function postInCategory($body) {
        if (is_object($body)) {
            $body = (array) $body;
        }
    
        $result = $this->postData("posts_tbl", $body, $this->pdo);
        
        if($result['code'] == 200){
            $this->logger("Flag", "POST", "Created a new posts record.");
            return $this->generateResponse($result['data'], "success", "Successfully created new records.", $result['code']);
        }
    
        $this->logger("Flag", "POST", $result['errmsg']);
        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);
    }


    public function postCommentReply($body) {
        if (is_object($body)) {
            $body = (array) $body;
        }
    
        if (!isset($body['parentcomment_id']) || !isset($body['user_id']) || !isset($body['content'])) {
            return $this->generateResponse(null, "failed", "Missing required fields: parentcomment_id, user_id, content", 400);
        }
    
        $parentcomment_id = (int) $body['parentcomment_id'];  
    
        $parentCheckQuery = "SELECT * FROM comments_tbl WHERE id = :parentcomment_id LIMIT 1";
        $stmt = $this->pdo->prepare($parentCheckQuery);
        $stmt->bindParam(':parentcomment_id', $parentcomment_id, PDO::PARAM_INT);
        $stmt->execute();
        $parentComment = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$parentComment) {
            return $this->generateResponse(null, "failed", "Parent comment does not exist.", 400);
        }
    
        $posts_id = $parentComment['posts_id'];  
    
        $data = [
            'user_id' => $body['user_id'],
            'content' => $body['content'],
            'parentcomment_id' => $body['parentcomment_id'],
            'posts_id' => $posts_id 
        ];
    
        $result = $this->postData("comments_tbl", $data, $this->pdo);
    
        if ($result['code'] == 200) {
            $this->logger("Flag", "POST", "Created a new comment reply record.");
            return $this->generateResponse($result['data'], "success", "Successfully created new reply.", $result['code']);
        }
    
        $this->logger("Flag", "POST", $result['errmsg']);
        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);
    }
}

?>