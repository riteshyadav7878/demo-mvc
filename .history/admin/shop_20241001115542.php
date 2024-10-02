<?php
include 'conn.php';

// Handle form submission for adding items
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['itemImage'])) {
    // Handle file upload
    // ... (existing file upload code) ...
}

// Handle edit action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM shopping_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
}

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM shopping_items WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Item deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting item: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle activate/deactivate action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['activate']) || isset($_POST['deactivate']))) {
    $id = $_POST['id'];
    $status = isset($_POST['activate']) ? 'Active' : 'Inactive';
    $stmt = $conn->prepare("UPDATE shopping_items SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Item status updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating status: " . $stmt->error . "</div>";
    }
    $stmt->close();
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
        body {
            background-color: #f8f9fa;
        }
        .slider-image {
            width: 100px;
            height: 75px;
            object-fit: cover;
        }
        .table { margin-top: 20px; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
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

        <!-- Items List -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="slider-image"></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td>₹<?php echo number_format($row['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="edit" class="btn btn-warning btn-sm">Edit</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="<?php echo ($row['status'] == 'Active') ? 'deactivate' : 'activate'; ?>" class="btn btn-<?php echo ($row['status'] == 'Active') ? 'secondary' : 'success'; ?> btn-sm">
                                    <?php echo ($row['status'] == 'Active') ? 'Deactivate' : 'Activate'; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No items found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
