<?php
class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function registerUser($username, $password) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Create the SQL query
        $query = "INSERT INTO admin (username, password) VALUES ('$username', '$hashedPassword')";
        
        // Execute the query and return the result
        return $this->conn->query($query);
    }

    public function loginUser($username, $password) {
        // Create a simple query to select the user
        $query = "SELECT * FROM admin WHERE username = '$username'";
        $result = $this->conn->query($query);

        // Check if the user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($password, $user['password'])) {
                return $user; // Return the user details if login is successful
            }
        }
        return false; // Return false if login fails
    }

    // silder
    
    public function add($title, $description, $image, $status) {
        $stmt = $this->conn->prepare("INSERT INTO slider (title, description, image, status) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . $this->conn->error);
        }
        $stmt->bind_param("ssss", $title, $description, $image, $status);
        
        if (!$stmt->execute()) {
            echo "Error executing query: " . $stmt->error;
            return false;
        }
        return true;
    }

    public function edit($id, $title, $description, $image, $status) {
        $stmt = $this->conn->prepare("UPDATE slider SET title=?, description=?, image=?, status=? WHERE id=?");
        if ($stmt === false) {
            die('Prepare failed: ' . $this->conn->error);
        }
        $stmt->bind_param("ssssi", $title, $description, $image, $status, $id);
        
        if (!$stmt->execute()) {
            echo "Error executing query: " . $stmt->error;
            return false;
        }
        return true;
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM slider WHERE id=?");
        if ($stmt === false) {
            die('Prepare failed: ' . $this->conn->error);
        }
        $stmt->bind_param("i", $id);
        
        if (!$stmt->execute()) {
            echo "Error executing query: " . $stmt->error;
            return false;
        }
        return true;
    }

    public function fetchAll() {
        $result = $this->conn->query("SELECT * FROM slider");
        if (!$result) {
            echo "Error executing query: " . $this->conn->error;
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getsliders() {
        $query = "SELECT * FROM slider WHERE status = 'active' ORDER BY id DESC";
        $result = mysqli_query($this->conn, $query);
    
        return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
    }

   // contact 


        public function deleteContact($id) {
            $stmt = $this->conn->prepare("DELETE FROM contacts WHERE id = ?");
            $stmt->bind_param("i", $id);
            return $stmt->execute();
        }

        public function getContacts() {
            return $this->conn->query("SELECT id, name, email, message FROM contacts ORDER BY id DESC");
        }

        // store 

        public function addItem($data, $file) {
            $messages = [];
            $uploadOk = 1;
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($file["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
            if (!getimagesize($file["tmp_name"])) {
                $messages[] = "File is not an image.";
                $uploadOk = 0;
            }
            if ($file["size"] > 5000000) {
                $messages[] = "File too large.";
                $uploadOk = 0;
            }
            if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                $messages[] = "Only JPG, JPEG, PNG & GIF allowed.";
                $uploadOk = 0;
            }
            if ($uploadOk === 0) {
                $messages[] = "Upload failed.";
            } else {
                if (move_uploaded_file($file["tmp_name"], $target_file)) {
                    $stmt = $this->conn->prepare("INSERT INTO shopping_items (image_url, title, description, price, status) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssis", $target_file, $data['itemTitle'], $data['itemDescription'], $data['itemPrice'], $data['itemStatus']);
                    if ($stmt->execute()) {
                        $messages[] = "New Item Added Successfully!";
                    } else {
                        $messages[] = "Error: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $messages[] = "Upload error.";
                }
            }
            return $messages;
        }
    
        public function editItem($id, $data) {
            $stmt = $this->conn->prepare("UPDATE shopping_items SET title=?, description=?, price=? WHERE id=?");
            $stmt->bind_param("ssdi", $data['itemTitle'], $data['itemDescription'], $data['itemPrice'], $id);
            if ($stmt->execute()) {
                return ["Record updated successfully!"];
            } else {
                return ["Error: " . $stmt->error];
            }
        }
    
        public function deleteItem($id) {
            $stmt = $this->conn->prepare("DELETE FROM shopping_items WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                return ["Item deleted successfully!"];
            } else {
                return ["Error: " . $stmt->error];
            }
        }
    
        public function toggleItemStatus($id) {
            $stmt = $this->conn->prepare("UPDATE shopping_items SET status = CASE WHEN status='Active' THEN 'Inactive' ELSE 'Active' END WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                return ["Status toggled successfully!"];
            } else {
                return ["Error: " . $stmt->error];
            }
        }
        
}
?>
