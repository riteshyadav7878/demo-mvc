<?php
class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function registerUser($username, $email, $password) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$passwordHash')";
        return $this->conn->query($sql);
    }

    public function loginUser($email, $password) {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    public function contactUser($name, $email, $message) {
        $sql = "INSERT INTO contacts (name, email, message) VALUES ('$name', '$email', '$message')";
        return $this->conn->query($sql);
    }

 
    // slider 
  
   
   
 
}
?>
