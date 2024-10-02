<?php
include 'model/model.php'; // Ensure this file includes the UserModel class

class UserController {
    private $userModel;

    public function __construct($conn) {
        $this->userModel = new UserModel($conn);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if ($this->userModel->registerUser($username, $password)) {
                header("Location: login.php?success=1"); // Redirect with a success flag
                exit();
            } else {
                echo "<div class='alert alert-danger'>Registration failed! Please try again.</div>";
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->userModel->loginUser($username, $password);

            if ($user) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: home.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Login failed! Invalid username or password.</div>";
            }
        }
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add'])) {
                $this->addSlider();
            } elseif (isset($_POST['edit'])) {
                $this->editSlider();
            } elseif (isset($_POST['delete'])) {
                $this->deleteSlider();
            }
        }
    }

    private function addSlider() {
        // Retrieve values from the form
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? ''; // Make sure to get description
        $status = $_POST['status'] ?? 'active'; // Default to 'active' if not set
        $image = $_FILES['image'] ?? null;

        // Check if title and image are provided
        if ($title && $image) {
            // Define the directory to store images
            $target_dir = "../uploads/";
            // Create a unique file name for the uploaded image
            $target_file = $target_dir . uniqid() . "_" . basename($image['name']);

            // Create the uploads directory if it doesn't exist
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($image['tmp_name'], $target_file)) {
                // Call the model to add the slider to the database
                if ($this->userModel->add($title, $description, $target_file, $status)) {
                    echo "<div class='alert alert-success'>Slider added successfully.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error adding slider to the database.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Error uploading image.</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Please provide both title and image.</div>";
        }
    }

    private function editSlider() {
        $id = $_POST['id'] ?? 0;
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $status = $_POST['status'] ?? 'active'; // Default to 'active' if not set
        $imagePath = isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK 
                     ? $this->uploadImage() 
                     : $_POST['existing_image'];

        if ($this->userModel->edit($id, $title, $description, $imagePath, $status)) {
            echo "<div class='alert alert-success'>Slider edited successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error editing slider.</div>";
        }
    }

    private function deleteSlider() {
        $id = $_POST['id'] ?? 0;
        if ($this->userModel->delete($id)) {
            echo "<div class='alert alert-success'>Slider deleted successfully.</div>";
        } else {
            echo "<div class='alert alert-danger'>Error deleting slider.</div>";
        }
    }

    private function uploadImage() {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../uploads/"; // Directory to store images
            $target_file = $target_dir . uniqid() . "_" . basename($_FILES['image']['name']); // Create a unique file name

            // Create the uploads directory if it doesn't exist
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                return $target_file; // Return the file path
            }
        }
        return null; // Return null if no valid image was uploaded
    }

    public function getSliders() {
        return $this->userModel->fetchAll();
    }
 

    // check contact .

    public function handleContactDeletion() {
        if (isset($_GET['delete_id'])) {
            $this->userModel->deleteContact($_GET['delete_id']);
        }
    }

    public function getContacts() {
        return $this->userModel->getContacts();
    }


    // store

    public function () {
        $messages = [];
        $items = $this->model->fetchAllItems();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_FILES['itemImage'])) {
                $messages = array_merge($messages, $this->model->addItem($_POST, $_FILES['itemImage']));
            } elseif (isset($_POST['editId'])) {
                $messages = array_merge($messages, $this->model->editItem($_POST['editId'], $_POST));
            } elseif (isset($_POST['deleteId'])) {
                $messages = array_merge($messages, $this->model->deleteItem($_POST['deleteId']));
            } elseif (isset($_POST['toggleStatusId'])) {
                $messages = array_merge($messages, $this->model->toggleItemStatus($_POST['toggleStatusId']));
            }
        }

        return [$items, $messages];
    }
}
?>
