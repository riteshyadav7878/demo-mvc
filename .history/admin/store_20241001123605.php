<?php
include 'conn.php';

// Handle form submission for adding items
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Item
    if (isset($_FILES['itemImage'])) {
        // Handle file upload...
        // (Include the file upload logic here as before)

        // Assuming the file upload logic has been executed successfully
        if ($uploadOk == 1) {
            $title = $_POST['itemTitle'];
            $description = $_POST['itemDescription'];
            $price = $_POST['itemPrice'];
            $status = $_POST['itemStatus'];

            $query = "INSERT INTO shopping_items (image_url, title, description, price, status) 
                      VALUES ('$target_file', '$title', '$description', $price, '$status')";
            if ($conn->query($query) === TRUE) {
                echo "<div class='alert alert-success'>Item added successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
            }
        }
    }

    // Edit Item
    if (isset($_POST['editId'])) {
        $editId = $_POST['editId'];
        $title = $_POST['itemTitle'];
        $description = $_POST['itemDescription'];
        $price = $_POST['itemPrice'];

        $query = "UPDATE shopping_items SET title='$title', description='$description', price=$price WHERE id=$editId";
        if ($conn->query($query) === TRUE) {
            echo "<div class='alert alert-success'>Item updated successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }

    // Delete Item
    if (isset($_POST['deleteId'])) {
        $deleteId = $_POST['deleteId'];
        $query = "DELETE FROM shopping_items WHERE id=$deleteId";
        if ($conn->query($query) === TRUE) {
            echo "<div class='alert alert-success'>Item deleted successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }

    // Toggle Status
    if (isset($_POST['toggleStatusId'])) {
        $toggleId = $_POST['toggleStatusId'];
        $currentStatusQuery = "SELECT status FROM shopping_items WHERE id=$toggleId";
        $result = $conn->query($currentStatusQuery);
        $row = $result->fetch_assoc();
        $newStatus = ($row['status'] === 'Active') ? 'Inactive' : 'Active';

        $query = "UPDATE shopping_items SET status='$newStatus' WHERE id=$toggleId";
        if ($conn->query($query) === TRUE) {
            echo "<div class='alert alert-success'>Status updated to $newStatus!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }
}

// Fetch items from the database in descending order
$result = $conn->query("SELECT * FROM shopping_items ORDER BY id DESC");
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
                        <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="slider-image"></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td>₹<?php echo number_format($row['price'], 2); ?></td>                        
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" 
                                    data-bs-toggle="modal" data-bs-target="#editModal"
                                    onclick="populateEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                Edit
                            </button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="deleteId" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
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
                <tr>
                    <td colspan="7" class="text-center">No items found.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Item</h5>
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
// Close the connection
$conn->close();
?>
