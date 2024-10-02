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
    public function getContactMessages() {
        $query = "SELECT * FROM contact_messages"; // Adjust the table name as necessary
        $result = $this->conn->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete a contact message by ID
    public function deleteContactMessage($id) {
        $query = "DELETE FROM contact_messages WHERE id = :id"; // Adjust table name and column as necessary
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
   
   
 
}
?>
