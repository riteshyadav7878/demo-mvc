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

       // stroe 
       public function getAllItems() {
        $result = $this->conn->query("SELECT * FROM shopping_items ORDER BY id DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addItem($image_url, $title, $description, $price, $status) {
        $stmt = $this->conn->prepare("INSERT INTO shopping_items (image_url, title, description, price, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $image_url, $title, $description, $price, $status);
        return $stmt->execute();
    }

    public function editItem($id, $title, $description, $price) {
        $stmt = $this->conn->prepare("UPDATE shopping_items SET title=?, description=?, price=? WHERE id=?");
        $stmt->bind_param("ssdi", $title, $description, $price, $id);
        return $stmt->execute();
    }

    public function deleteItem($id) {
        $stmt = $this->conn->prepare("DELETE FROM shopping_items WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function toggleStatus($id) {
        $stmt = $this->conn->prepare("UPDATE shopping_items SET status = CASE WHEN status='Active' THEN 'Inactive' ELSE 'Active' END WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
 

    public function getshop() {
        $query = "SELECT * FROM shopping_items WHERE status = 'active' ORDER BY id DESC";
        $result = mysqli_query($this->conn, $query);
    
        return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
    }
    
}
?>
