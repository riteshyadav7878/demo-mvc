<?php
include 'conn.php';
include 'model/model.php';

class UserController {
    private $model;

    public function __construct($conn) {
        $this->model = new UserModel($conn);
    }

    public function register() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'] ?? null;
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if ($username && $email && $password) {
                if ($this->model->registerUser($username, $email, $password)) {
                    echo "Registration successful!";
                    header("Location: login.php");
                } else {
                    echo "Error: Registration failed.";
                }
            } else {
                echo "All fields are required.";
            }
        }
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if ($email && $password) {
                $user = $this->model->loginUser($email, $password);
                if ($user) {
                    session_start();
                    $_SESSION['username'] = $user['username'];
                    header("Location: welcome.php");
                } else {
                    echo "Invalid email or password.";
                }
            } else {
                echo "All fields are required.";
            }
        }
    }

    public function contact() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'] ?? null;
            $email = $_POST['email'] ?? null;
            $message = $_POST['message'] ?? null;

            if ($name && $email && $message) {
                if ($this->model->contactUser($name, $email, $message)) {
                    echo "Your contact message was sent successfully!";
                } else {
                    echo "Failed to send your contact message.";
                }
            } else {
                echo "All fields are required.";
            }
        }
    }

  
}
?>
