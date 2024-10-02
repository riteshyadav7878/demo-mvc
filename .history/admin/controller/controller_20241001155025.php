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
                header("Location: login.php?success=1");
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
            } elseif (isset($_FILES['itemImage'])) {
                $this->handleImageUpload();
            } elseif (isset($_POST['editId'])) {
                $this->handleEditItem();
            } elseif (isset($_POST['deleteId'])) {
                $this->handleDeleteItem();
            } elseif (isset($_POST['toggleStatusId'])) {
                $this->toggleItemStatus();
            }
        }
    }

    private function addSlider() {
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $status = $_POST['status'] ?? 'active';
        $image = $_FILES['image'] ?? null;

        if ($title && $image) {
            $target_file = $this->uploadImage($image);

            if ($target_file && $this->userModel->add($title, $description, $target_file, $status)) {
                echo "<div class='alert alert-success'>Slider added successfully.</div>";
            } else {
                echo "<div class='alert alert-danger'>Error adding slider to the database or uploading image.</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Please provide both title and image.</div>";
        }
    }

    private function editSlider() {
        $id = $_POST['id'] ?? 0;
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $status = $_POST['status'] ?? 'active';
        $imagePath = isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK
            ? $this->uploadImage($_FILES['image'])
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

    private function handleImageUpload() {
        $uploadOk = 1;
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["itemImage"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate the image
        if (!getimagesize($_FILES["itemImage"]["tmp_name"])) {
            echo "File is not an image.";
            $uploadOk = 0;
        }
        if ($_FILES["itemImage"]["size"] > 5000000) {
            echo "File too large.";
            $uploadOk = 0;
        }
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            echo "Only JPG, JPEG, PNG & GIF allowed.";
            $uploadOk = 0;
        }

        // Attempt to upload the image
        if ($uploadOk === 0) {
            echo "Upload failed.";
        } else {
            if (move_uploaded_file($_FILES["itemImage"]["tmp_name"], $target_file)) {
                if ($this->userModel->addItem($target_file, $_POST['itemTitle'], $_POST['itemDescription'], $_POST['itemPrice'], $_POST['itemStatus'])) {
                    echo "New Item Added Successfully!";
                } else {
                    echo "Error adding item.";
                }
            } else {
                echo "Upload error.";
            }
        }
    }

    private function handleEditItem() {
        if ($this->userModel->editItem($_POST['editId'], $_POST['itemTitle'], $_POST['itemDescription'], $_POST['itemPrice'])) {
            echo "Record updated successfully!";
        } else {
            echo "Error updating record.";
        }
    }

    private function handleDeleteItem() {
        if ($this->userModel->deleteItem($_POST['deleteId'])) {
            echo "Item deleted successfully!";
        } else {
            echo "Error deleting item.";
        }
    }

    private function toggleItemStatus() {
        if ($this->userModel->toggleItemStatus($_POST['toggleStatusId'])) {
            echo "Status toggled successfully!";
        } else {
            echo "Error toggling status.";
        }
    }

    private function uploadImage($image) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . uniqid() . "_" . basename($image['name']);
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        if (move_uploaded_file($image['tmp_name'], $target_file)) {
            return $target_file;
        }
        return null;
    }

    public function getSliders() {
        return $this->userModel->fetchAll();
    }

    public function handleContactDeletion() {
        if (isset($_GET['delete_id'])) {
            $this->userModel->deleteContact($_GET['delete_id']);
        }
    }

    public function getContacts() {
        return $this->userModel->getContacts();
    }
}

?>
