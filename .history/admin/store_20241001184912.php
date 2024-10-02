<?php
// Assuming you have a database connection file
include 'conn.php'; // Include your database connection here
include 'controller/controller.php'; // Include the UserController

// Instantiate UserController with the database connection
$userController = new UserController($conn); 

// Call the appropriate method based on your application logic
$userController->handlestore();

// Fetch sliders and items from the database after handling requests
$getSliders = $userController->getSliders();
$items = $userController->getItems(); // Fetch items here
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
        .slider-image { width: 100px; 
            height: 75px; 
            object-fit: cover; }
        .table { margin-top: 20px; }
        .action-button {
                            margin-right: 5px; /* Space between action buttons */
                        }

                        .btn {
                            margin-right: 1px; /* Space between Active/Inactive buttons */
                        }
        /style>
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
            <?php if (isset($items) && count($items) > 0): ?>
                <?php $sr_no = 1; ?>
                <?php foreach($items as $row): ?>
                    <tr>
                        <td><?php echo $sr_no++; ?></td>
                        <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="slider-image"></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td>₹<?php echo number_format($row['price'], 2); ?></td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm action-button" data-bs-toggle="modal" data-bs-target="#editModal" onclick="populateEditModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">Edit</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="deleteId" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm action-button" onclick="return confirm('Delete this item?');">Delete</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="toggleStatusId" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-<?php echo ($row['status'] == 'Active') ? 'secondary' : 'success'; ?> btn-sm action-button">
                                    <?php echo ($row['status'] == 'Active') ? 'Deactivate' : 'Activate'; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
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

    <?php if (!empty($messages)): ?>
        <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Notifications</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo implode('<br>', $messages); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function populateEditModal(item) {
            document.getElementById('editId').value = item.id;
            document.getElementById('editItemTitle').value = item.title;
            document.getElementById('editItemDescription').value = item.description;
            document.getElementById('editItemPrice').value = item.price;
        }

        document.addEventListener("DOMContentLoaded", function() {
            if (document.getElementById('messageModal')) {
                const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
                messageModal.show();
            }
        });
    </script>
</body>
</html>
