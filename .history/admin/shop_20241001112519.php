<?php
// Database connection parameters
$servername = "localhost"; // Change if your server is different
$username = "your_username"; // Replace with your MySQL username
$password = "your_password"; // Replace with your MySQL password
$dbname = "your_database"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle file upload
    $target_dir = "uploads/"; // Directory where files will be uploaded
    $target_file = $target_dir . basename($_FILES["itemImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["itemImage"]["tmp_name"]);
    if ($check === false) {
        echo "<div class='alert alert-danger'>File is not an image.</div>";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "<div class='alert alert-danger'>Sorry, file already exists.</div>";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES["itemImage"]["size"] > 5000000) {
        echo "<div class='alert alert-danger'>Sorry, your file is too large.</div>";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "<div class='alert alert-danger'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<div class='alert alert-danger'>Your file was not uploaded.</div>";
    } else {
        // If everything is ok, try to upload file
        if (move_uploaded_file($_FILES["itemImage"]["tmp_name"], $target_file)) {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO shopping_items (image_url, title, description, price, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssis", $target_file, $_POST['itemTitle'], $_POST['itemDescription'], $_POST['itemPrice'], $_POST['itemStatus']);
            
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Item added successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
            }
            $stmt->close();
        } else {
            echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
        }
    }
}

// Fetch items from the database
$sql = "SELECT * FROM shopping_items";
$result = $conn->query($sql);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Shopping Page</title>
    <style>
        .card { margin: 15px; }
    </style>
</head>
<body>
    <?php include 'navbar.php' ?>
    <div class="container">
        <h1 class="mt-5">Shopping Page</h1>
        
        <!-- Form to add new items -->
        <form id="itemForm" class="mb-4" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="itemImage" class="form-label">Upload Image</label>
                <input type="file" class="form-control" id="itemImage" name="itemImage" required>
            </div>
            <div class="mb-3">
                <label for="itemTitle" class="form-label">Item Title</label>
                <input type="text" class="form-control" id="itemTitle" name="itemTitle" required>
            </div>
            <div class="mb-3">
                <label for="itemDescription" class="form-label">Description</label>
                <textarea class="form-control" id="itemDescription" name="itemDescription" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="itemPrice" class="form-label">Price</label>
                <input type="number" class="form-control" id="itemPrice" name="itemPrice" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="itemStatus" class="form-label">Status</label>
                <select class="form-select" id="itemStatus" name="itemStatus" required>
                    <option value="" disabled selected>Select status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Item</button>
        </form>

        <div id="itemList" class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                                <p class="card-text"><strong>Price:</strong> $<?php echo number_format($row['price'], 2); ?></p>
                                <p class="card-text"><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No items found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
