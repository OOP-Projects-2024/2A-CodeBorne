<?php 
class Delete {

    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function archiveCategory($id) {
        $code = 0;
        $payload = null;
        $remarks = "";
        $message = "";

        try {
            $headers = getallheaders();
            $username = $headers['X-Auth-User'];

            $sqlCheckUser = "SELECT role FROM users_tbl WHERE username=? AND (role = 2)";
            $stmtUser = $this->pdo->prepare($sqlCheckUser);
            $stmtUser->execute([$username]);

            if ($stmtUser->rowCount() == 0) {
                $code = 403;
                $remarks = "failed";
                $message = "Unauthorized. Admin or moderator access required.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }

            $sqlCheckCategory = "SELECT id FROM categories_tbl WHERE id=? AND isdeleted=0";
            $stmtCategory = $this->pdo->prepare($sqlCheckCategory);
            $stmtCategory->execute([$id]);

            if ($stmtCategory->rowCount() == 0) {
                $code = 404;
                $remarks = "failed";
                $message = "Category not found or already deleted.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }

            $sqlDelete = "UPDATE categories_tbl SET isdeleted = 1 WHERE id=?";
            $stmtDelete = $this->pdo->prepare($sqlDelete);
            $stmtDelete->execute([$id]);

            $code = 200;
            $remarks = "success";
            $message = "Category archived successfully.";
            $payload = array("archived_category_id" => $id);

        } catch (\PDOException $e) {
            $code = 400;
            $remarks = "failed";
            $message = $e->getMessage();
        }

        return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
    }


    
    
    public function archiveUsers($id) {
        $code = 0;
        $payload = null;
        $remarks = "";
        $message = "";

        try {
            $headers = getallheaders();
            $username = $headers['X-Auth-User'];

            $sqlCheckUser = "SELECT role FROM users_tbl WHERE username=? AND (role = 2)";
            $stmtUser = $this->pdo->prepare($sqlCheckUser);
            $stmtUser->execute([$username]);

            if ($stmtUser->rowCount() == 0) {
                $code = 403;
                $remarks = "failed";
                $message = "Unauthorized. Admin or moderator access required.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }

            $sqlCheckCategory = "SELECT id FROM users_tbl WHERE id=? AND isdeleted=0";
            $stmtCategory = $this->pdo->prepare($sqlCheckCategory);
            $stmtCategory->execute([$id]);

            if ($stmtCategory->rowCount() == 0) {
                $code = 404;
                $remarks = "failed";
                $message = "User not found or already deleted.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }

            $sqlDelete = "UPDATE users_tbl SET isdeleted = 1 WHERE id=?";
            $stmtDelete = $this->pdo->prepare($sqlDelete);
            $stmtDelete->execute([$id]);

            $code = 200;
            $remarks = "success";
            $message = "User archived successfully.";
            $payload = array("archived_user_id" => $id);

        } catch (\PDOException $e) {
            $code = 400;
            $remarks = "failed";
            $message = $e->getMessage();
        }

        return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
    }

    public function archivePost($id) {
        $code = 0;
        $payload = null;
        $remarks = "";
        $message = "";

        try {
            $headers = getallheaders();
            $username = $headers['X-Auth-User'];

            $sqlCheckUser = "SELECT role FROM users_tbl WHERE username=? AND (role = 2 OR role = 1)";
            $stmtUser = $this->pdo->prepare($sqlCheckUser);
            $stmtUser->execute([$username]);

            if ($stmtUser->rowCount() == 0) {
                $code = 403;
                $remarks = "failed";
                $message = "Unauthorized. Admin or moderator access required.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }

            $sqlCheckCategory = "SELECT id FROM posts_tbl WHERE id=? AND isdeleted=0";
            $stmtCategory = $this->pdo->prepare($sqlCheckCategory);
            $stmtCategory->execute([$id]);

            if ($stmtCategory->rowCount() == 0) {
                $code = 404;
                $remarks = "failed";
                $message = "Post not found or already deleted.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }

            $sqlDelete = "UPDATE posts_tbl SET isdeleted = 1 WHERE id=?";
            $stmtDelete = $this->pdo->prepare($sqlDelete);
            $stmtDelete->execute([$id]);

            $code = 200;
            $remarks = "success";
            $message = "Category archived successfully.";
            $payload = array("archived_user_id" => $id);

        } catch (\PDOException $e) {
            $code = 400;
            $remarks = "failed";
            $message = $e->getMessage();
        }

        return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
    }

    public function archiveComments($id) {
        $code = 0;
        $payload = null;
        $remarks = "";
        $message = "";

        try {
            $headers = getallheaders();
            $username = $headers['X-Auth-User'];

            $sqlCheckUser = "SELECT role FROM users_tbl WHERE username=? AND (role = 2 OR role = 1)";
            $stmtUser = $this->pdo->prepare($sqlCheckUser);
            $stmtUser->execute([$username]);

            if ($stmtUser->rowCount() == 0) {
                $code = 403;
                $remarks = "failed";
                $message = "Unauthorized. Admin or moderator access required.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }

            $sqlCheckCategory = "SELECT id FROM comments_tbl WHERE id=? AND isdeleted=0";
            $stmtCategory = $this->pdo->prepare($sqlCheckCategory);
            $stmtCategory->execute([$id]);

            if ($stmtCategory->rowCount() == 0) {
                $code = 404;
                $remarks = "failed";
                $message = "User not found or already deleted.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }

            $sqlDelete = "UPDATE comments_tbl SET isdeleted = 1 WHERE id=?";
            $stmtDelete = $this->pdo->prepare($sqlDelete);
            $stmtDelete->execute([$id]);

            $code = 200;
            $remarks = "success";
            $message = "Comment archived successfully.";
            $payload = array("archived_user_id" => $id);

        } catch (\PDOException $e) {
            $code = 400;
            $remarks = "failed";
            $message = $e->getMessage();
        }

        return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
    }

  
    public function deleteComment($id) {
        $code = 0;
        $payload = null;
        $remarks = "";
        $message = "";

        try {
            $headers = getallheaders();
            $username = $headers['X-Auth-User'];

            $sqlCheckUser = "SELECT role FROM users_tbl WHERE username=? AND (role = 2)";
            $stmtUser = $this->pdo->prepare($sqlCheckUser);
            $stmtUser->execute([$username]);

            if ($stmtUser->rowCount() == 0) {
                $code = 403;
                $remarks = "failed";
                $message = "Unauthorized. Admin or moderator access required.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }

            $sqlCheckComment = "SELECT id FROM comments_tbl WHERE id=?";
            $stmtComment = $this->pdo->prepare($sqlCheckComment);
            $stmtComment->execute([$id]);
    
            if ($stmtComment->rowCount() == 0) {
                $code = 404;
                $remarks = "failed";
                $message = "Comment not found.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }

            $sqlDelete = "DELETE FROM comments_tbl WHERE id=?";
            $stmtDelete = $this->pdo->prepare($sqlDelete);
            $stmtDelete->execute([$id]);

            $code = 200;
            $remarks = "success";
            $message = "Comment delete successfully.";
            $payload = array("delete_Comment_id" => $id);

        } catch (\PDOException $e) {
            $code = 400;
            $remarks = "failed";
            $message = $e->getMessage();
        }

        return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
    }


  public function deletePost($id) {
        $code = 0;
        $payload = null;
        $remarks = "";
        $message = "";

        try {
            $headers = getallheaders();
            $username = $headers['X-Auth-User'];

            $sqlCheckUser = "SELECT role FROM users_tbl WHERE username=? AND (role = 2)";
            $stmtUser = $this->pdo->prepare($sqlCheckUser);
            $stmtUser->execute([$username]);

            if ($stmtUser->rowCount() == 0) {
                $code = 403;
                $remarks = "failed";
                $message = "Unauthorized. Admin or moderator access required.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }

            $sqlCheckPost = "SELECT id FROM posts_tbl WHERE id=?";
            $stmtPost = $this->pdo->prepare($sqlCheckPost);
            $stmtPost->execute([$id]);
    
            if ($stmtPost->rowCount() == 0) {
                $code = 404;
                $remarks = "failed";
                $message = "Post not found.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }

            $sqlDelete = "DELETE FROM posts_tbl WHERE id=?";
            $stmtDelete = $this->pdo->prepare($sqlDelete);
            $stmtDelete->execute([$id]);

            $code = 200;
            $remarks = "success";
            $message = "Post delete successfully.";
            $payload = array("delete_Post_id" => $id);

        } catch (\PDOException $e) {
            $code = 400;
            $remarks = "failed";
            $message = $e->getMessage();
        }

        return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
    }


 public function deleteUser($Id) {
        $code = 0;
        $payload = null;
        $remarks = "";
        $message = "";
    
        try {
            $adminHeaders = getallheaders();
            $adminUsername = $adminHeaders['X-Auth-User'];
    
            $sqlCheckAdmin = "SELECT role FROM users_tbl WHERE username=?";
            $stmtAdmin = $this->pdo->prepare($sqlCheckAdmin);
            $stmtAdmin->execute([$adminUsername]);
    
            if ($stmtAdmin->rowCount() > 0) {
                $adminResult = $stmtAdmin->fetch();
                if ($adminResult['role'] < 2) { 
                    $code = 403;
                    $remarks = "failed";
                    $message = "Unauthorized. Admin access required.";
                    return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
                }
            } else {
                $code = 403;
                $remarks = "failed";
                $message = "Admin username not found.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }
    
            $sqlCheckCategory = "SELECT id FROM users_tbl WHERE id=?";
            $stmtCategory = $this->pdo->prepare($sqlCheckCategory);
            $stmtCategory->execute([$Id]);
    
            if ($stmtCategory->rowCount() == 0) { 
                $code = 401;
                $remarks = "failed";
                $message = "Users does not exist.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }
    
            $sqlDelete = "DELETE FROM users_tbl WHERE id=?";
            $stmtDelete = $this->pdo->prepare($sqlDelete);
            $stmtDelete->execute([$Id]);
    
            $code = 200;
            $remarks = "success";
            $message = "User has ben deleted successfully.";
            $payload = array("deleted_category_id" => $Id);
    
        } catch (\PDOException $e) {
            $code = 400;
            $remarks = "failed";
            $message = $e->getMessage();
        }
    
        return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
    }

 public function deleteCategory($categoryId) {
        $code = 0;
        $payload = null;
        $remarks = "";
        $message = "";
    
        try {
            $adminHeaders = getallheaders();
            $adminUsername = $adminHeaders['X-Auth-User'];
    
            $sqlCheckAdmin = "SELECT role FROM users_tbl WHERE username=?";
            $stmtAdmin = $this->pdo->prepare($sqlCheckAdmin);
            $stmtAdmin->execute([$adminUsername]);
    
            if ($stmtAdmin->rowCount() > 0) {
                $adminResult = $stmtAdmin->fetch();
                if ($adminResult['role'] < 2) { 
                    $code = 403;
                    $remarks = "failed";
                    $message = "Unauthorized. Admin access required.";
                    return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
                }
            } else {
                $code = 403;
                $remarks = "failed";
                $message = "Admin username not found.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }
    
            $sqlCheckCategory = "SELECT id FROM categories_tbl WHERE id=?";
            $stmtCategory = $this->pdo->prepare($sqlCheckCategory);
            $stmtCategory->execute([$categoryId]);
    
            if ($stmtCategory->rowCount() == 0) { 
                $code = 401;
                $remarks = "failed";
                $message = "Category does not exist.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }
    
            $sqlDelete = "DELETE FROM categories_tbl WHERE id=?";
            $stmtDelete = $this->pdo->prepare($sqlDelete);
            $stmtDelete->execute([$categoryId]);
    
            $code = 200;
            $remarks = "success";
            $message = "Category deleted successfully.";
            $payload = array("deleted_category_id" => $categoryId);
    
        } catch (\PDOException $e) {
            $code = 400;
            $remarks = "failed";
            $message = $e->getMessage();
        }
    
        return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
    }
   
}
?>