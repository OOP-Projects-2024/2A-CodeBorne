<?php
class Patch {

    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function PatchUsers($body, $user_id) {
        $values = [];
        $errmsg = "";
        $code = 0;
    
        try {
            if (isset($body->password)) {
                $body->password = $this->encryptPassword($body->password);
            }            
            $fields = [];
            foreach ($body as $key => $value) {
                $fields[] = "$key=?";
                $values[] = $value;
            }

            if (isset($body->bio)) {
                $fields[] = "bio=?";
                $values[] = $body->bio;
            }
    
            $values[] = $user_id; 

            $sqlString = "UPDATE users_tbl SET " . implode(", ", $fields) . " WHERE id = ?";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute($values);
    
            $code = 200;
            $data = null;
    
            return array("data" => $data, "code" => $code); 
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
    
            return array("error" => $errmsg, "code" => $code); 
        }
    }
    
    private function encryptPassword($password) {
        $hashFormat = "$2y$10$";
        $saltLength = 22;
        $salt = $this->generateSalt($saltLength);
        return crypt($password, $hashFormat . $salt);
    }
    
    private function generateSalt($length) {
        $urs = md5(mt_rand(), true);
        $b64String = base64_encode($urs);
        $mb64String = str_replace("+", ".", $b64String);
        return substr($mb64String, 0, $length);
    }

    public function patchCategory($body, $id) {
        $values = [];
        $errmsg = "";
        $code = 0;

        foreach ($body as $value) {
            array_push($values, $value);
        }

        array_push($values, $id);

        try {
            $sqlString = "UPDATE categories_tbl SET name=?, description=? WHERE id = ?";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute($values);

            $code = 200;
            $data = null;

            return array("data" => $data, "code" => $code);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }

        return array("errmsg" => $errmsg, "code" => $code);
    }

    public function patchPost($body, $id) {
        $values = [];
        $errmsg = "";
        $code = 0;

        foreach ($body as $value) {
            array_push($values, $value);
        }

        array_push($values, $id);

        try {
            $sqlString = "UPDATE posts_tbl SET title=?, content=? WHERE id = ?";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute($values);

            $code = 200;
            $data = null;

            return array("data" => $data, "code" => $code);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }

        return array("errmsg" => $errmsg, "code" => $code);
    }

    public function patchCommment($body, $id) {
        $values = [];
        $errmsg = "";
        $code = 0;

        foreach ($body as $value) {
            array_push($values, $value);
        }

        array_push($values, $id);

        try {
            $sqlString = "UPDATE comments_tbl SET content=? WHERE id = ?";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute($values);

            $code = 200;
            $data = null;

            return array("data" => $data, "code" => $code);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }

        return array("errmsg" => $errmsg, "code" => $code);
    }

    public function updateRole($body) {
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
    
            $sqlCheckUser = "SELECT username FROM users_tbl WHERE username=?";
            $stmtUser = $this->pdo->prepare($sqlCheckUser);
            $stmtUser->execute([$body->username]);
    
            if ($stmtUser->rowCount() == 0) { 
                $code = 401;
                $remarks = "failed";
                $message = "Username does not exist.";
                return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
            }
    
            $sqlString = "UPDATE users_tbl SET role=? WHERE username=?";
            $stmtUpdate = $this->pdo->prepare($sqlString);
            $stmtUpdate->execute([$body->role, $body->username]);
    
            $code = 200;
            $remarks = "success";
            $message = "User role updated successfully.";
            $payload = array("username" => $body->username, "new_role" => $body->role);
    
        } catch (\PDOException $e) {
            $code = 400;
            $remarks = "failed";
            $message = $e->getMessage();
        }
    
        return array("payload" => $payload, "remarks" => $remarks, "message" => $message, "code" => $code);
    }


    
}
?>
