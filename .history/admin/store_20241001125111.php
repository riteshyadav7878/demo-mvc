<?php
include 'conn.php';

// Handle form submission for adding, editing, deleting, and toggling items
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['itemImage'])) {
        handleImageUpload($conn);
    } elseif (isset($_POST['editId'])) {
        handleEdit($conn);
    } elseif (isset($_POST['deleteId'])) {
        handleDelete($conn);
    } elseif (isset($_POST['toggleStatusId'])) {
        toggleStatus($conn);
    }
}

// Fetch items from the database
$result = $conn->query("SELECT * FROM shopping_items ORDER BY id DESC");

// Function to show messages in a modal
function showMessage($message) {
    echo "<script>
        document.getElementById('notificationMessage').innerText = '$message';
        var notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));
        notificationModal.show();
    </script>";
}

// Function to handle image upload and item insertion
function handleImageUpload($conn) {
    $uploadOk = 1;
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["itemImage"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (!getimagesize($_FILES["itemImage"]["tmp_name"])) {
        showMessage("File is not an image.");
        $uploadOk = 0;
    }
    if ($_FILES["itemImage"]["size"] > 5000000) {
        showMessage("File too large.");
        $uploadOk = 0;
    }
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        showMessage("Only JPG, JPEG, PNG & GIF allowed.");
        $uploadOk = 0;
    }
    if ($uploadOk === 0) {
        showMessage("Upload failed.");
    } else {
        if (move_uploaded_file($_FILES["itemImage"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("INSERT INTO shopping_items (image_url, title, description, price, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssis", $target_file, $_POST['itemTitle'], $_POST['itemDescription'], $_POST['itemPrice'], $_POST['itemStatus']);
            $stmt->execute();
            showMessage("Item added successfully!");
            $stmt->close();
        } else {
            showMessage("Upload error.");
        }
    }
}

// Function to handle editing items
function handleEdit($conn) {
    $stmt = $conn->prepare("UPDATE shopping_items SET title=?, description=?, price=? WHERE id=?");
    $stmt->bind_param("ssdi", $_POST['itemTitle'], $_POST['itemDescription'], $_POST['itemPrice'], $_POST['editId']);
    if ($stmt->execute()) {
        showMessage("Item updated successfully!");
    } else {
        showMessage("Error: " . $stmt->error);
    }
    $stmt->close();
}

// Function to handle deleting items
function handleDelete($conn) {
    $stmt = $conn->prepare("DELETE FROM shopping_items WHERE id=?");
    $stmt->bind_param("i", $_POST['deleteId']);
    if ($stmt->execute()) {
        showMessage("Item deleted successfully!");
    } else {
        showMessage("Error: " . $stmt->error);
    }
    $stmt->close();
}

// Function to toggle item status
function toggleStatus($conn) {
    $stmt = $conn->prepare("UPDATE shopping_items SET status = CASE WHEN status='Active' THEN 'Inactive' ELSE 'Active' END WHERE id=?");
    $stmt->bind_param("i", $_POST['toggleStatusId']);
    $stmt->execute();
    $stmt->close();
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Shopping Page</title>
    <style>
        body { background-color: #f8f9fa; }
        .slider-image { width: 100px; height: 75px; object-fit: cover; }
        .table { margin-top: 20px; }
        .modal-content {
            border-radius: 50%;
            width: 200px; /* Adjust the width as needed */
            height: 200px; /* Adjust the height as needed */
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h1 class="mt-5">Shopping Page</h1>
        
        <!-- Form to add new items -->
        <form class="mb-4" method="POST" enctype="multipart/form-data">
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

        <!-- Items List -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php $sr_no = 1; ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $sr_no++; ?></td>
                        <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="slider-image"></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td>₹<?php echo number_format($row['price'], 2); ?></td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" onclick="populateEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="deleteId" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this item?');">Delete</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="toggleStatusId" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-<?php echo ($row['status'] == 'Active') ? 'secondary' : 'success'; ?> btn-sm">
                                    <?php echo ($row['status'] == 'Active') ? 'Deactivate' : 'Activate'; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No items found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" id="editId" name="editId">
                        <div class="mb-3">
                            <label for="editItemTitle" class="form-label">Item Title</label>
                            <input type="text" class="form-control" id="editItemTitle" name="itemTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="editItemDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editItemDescription" name="itemDescription" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editItemPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="editItemPrice" name="itemPrice" step="0.01" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Item</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <h5 id="notificationMessage" class="m-0"></h5>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function populateEditModal(item) {
            document.getElementById('editId').value = item.id;
            document.getElementById('editItemTitle').value = item.title;
            document.getElementById('editItemDescription').value = item.description;
            document.getElementById('editItemPrice').value = item.price;
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
