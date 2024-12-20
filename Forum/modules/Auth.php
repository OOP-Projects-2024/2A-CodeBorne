<?php
class Authentication{

    protected $pdo;

    public function __construct(\PDO $pdo){
        $this -> pdo = $pdo;
    }

    public function isAuthorized($requiredRole = null) {
        $headers = getallheaders();
        if ($headers['Authorization'] != $this->getToken()) {
            return false; // Token is invalid
        }
    
        if ($requiredRole !== null) {
            $sqlString = "SELECT role FROM users_tbl WHERE username=?";
            $stmt = $this->pdo->prepare($sqlString);
            $stmt->execute([$headers['X-Auth-User']]);
    
            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetch();
                return $result['role'] >= $requiredRole;
            }
    
            return false; 
        }
    
        return true; 
    }
    
    private function getToken(){
        $headers = getallheaders();
        
        $sqlString = "SELECT token FROM users_tbl WHERE username=?";
            $stmt = $this->pdo->prepare($sqlString);
            $stmt->execute([$headers['X-Auth-User']]);
            $result = $stmt->fetchAll()[0];

            return $result['token'];
        }
            
    private function generateHeader() {
            $header = [
                "typ" => "JWT",
                "alg" => "HS256",
                "app" => "Bloggers",
                "dev" => "James Lawrence A. Dela Cruz"
            ];
            return base64_encode(json_encode($header));
    }

    private function generatePayload($id, $username) {
        $payload = [
            "uid" => $id,
            "uc" => $username,
            "email" => "holycow0612@gmail.com",
            "date" => date_create(),
            "exp" => date("Y-m-d H:i:s")
        ];
        return base64_encode(json_encode($payload));
    }

    private function generateToken($id, $username) {
        $header = $this->generateHeader();
        $payload = $this->generatePayload($id, $username);
        $signature = hash_hmac("sha256", "$header.$payload", TOKEN_KEY);
        return "$header.$payload." . base64_encode($signature);
    }
    

    private function isSamePassword($inputPassword, $existingHash){
        $hash = crypt($inputPassword, $existingHash);
        return $hash == $existingHash;
    }

    private function encryptPassword($password){
        $hashFormat = "$2y$10$";
        $saltLength = 22;
        $salt = $this->generateSalt($saltLength);
        return crypt ($password, $hashFormat . $salt);
    }

    private function generateSalt($length){
        $urs = md5(mt_rand(), true);
        $b64String = base64_encode($urs);
        $mb64String = str_replace("+", ".", $b64String);
        return substr($mb64String, 0, $length);
    }

    public function saveToken ($token, $username){
        $errmsg = "";
        $code = 0;

        try{
            $sqlString = "UPDATE users_tbl SET token=? WHERE username = ?";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute( [$token, $username] );

            $code = 200;
            $data = null;

            return array("data"=>$data, "code"=>$code); 
        }
        catch(\PDOException $e){
            $errmsg = $e->getMessage();
            $code = 400;
        }

        
        return array("errmsg"=>$errmsg, "code"=>$code); 
    }
    
    public function login($body) {
        $errmsg = "";
        $code = 0;
    
        try {
            $sqlString = "SELECT id, username, password, role FROM users_tbl WHERE username = ?";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute([$body->username]);
    
            if ($sql->rowCount() > 0) {
                $user = $sql->fetch();
    
                if ($user['role'] == 3) {
                    return array("errmsg" => "Your account is banned.", "code" => 403);
                }
    
                if (password_verify($body->password, $user['password'])) {
                    $token = $this->generateToken($user['id'], $user['username']);
                    $token_parts = explode('.', $token);
                    $signature = $token_parts[2]; 
    
                    $this->saveToken($signature, $user['username']);
    
                    return array(
                        "payload" => [
                            "id" => $user['id'],
                            "username" => $user['username'],
                            "token" => $signature 
                        ],
                        "remarks" => "Success",
                        "message" => "Logged in successfully",
                        "code" => 200
                    );
                } else {
                    $errmsg = "Wrong password.";
                }
            } else {
                $errmsg = "User not found.";
            }
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
        }
    
        $code = 400;
        return array("errmsg" => $errmsg, "code" => $code);
    }
    

    public function addAccount($body) {
        $errmsg = "";
        $code = 0;
    
        $body->password = $this->encryptPassword($body->password);
    
        if (!isset($body->role)) {
            $body->role = 0;
        }
    
        try {
            $sqlString = "INSERT INTO users_tbl (id, username, password, bio, role) VALUES (?, ?, ?, ?, ?)";
            $sql = $this->pdo->prepare($sqlString);
    
            $values = [
                $body->id,
                $body->username,
                $body->password,
                $body->bio ?? null, 
                $body->role
            ];
            $sql->execute($values);
    
            $code = 200;
            $data = ["message" => "Account created successfully"];
            return array("data" => $data, "code" => $code);
    
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }
    
        return array("errmsg" => $errmsg, "code" => $code);
    }
    
    

    public function addCategory($body) {
        $headers = getallheaders();
        $username = $headers['X-Auth-User'];
    
        $sqlString = "SELECT role FROM users_tbl WHERE username=?";
        $stmt = $this->pdo->prepare($sqlString);
        $stmt->execute([$username]);
    
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch();
            if ($result['role'] == 3) {
                return array("errmsg" => "User is banned.", "code" => 403);
            }
        } else {
            return array("errmsg" => "User not found.", "code" => 404);
        }
    
        $values = [];
        $errmsg = "";
        $code = 0;
    
        foreach ($body as $value) {
            array_push($values, $value);
        }
    
        try {
            $sqlString = "INSERT INTO categories_tbl(id, user_id, name, description) VALUES (?, ?, ?, ?)";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute($values);
    
            if ($result['role'] == 0) {
                $updateRoleSql = "UPDATE users_tbl SET role = 1 WHERE username = ?";
                $updateStmt = $this->pdo->prepare($updateRoleSql);
                $updateStmt->execute([$username]);
            }
    
            $code = 200;
            $data = "Category created and role updated if needed.";
    
            return array("data" => $data, "code" => $code);
        } catch (\PDOException $e) {
            $errmsg = $e->getMessage();
            $code = 400;
        }
    
        return array("errmsg" => $errmsg, "code" => $code);
    }

    
    
}

?>