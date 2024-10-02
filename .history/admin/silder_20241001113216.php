<?php
// Assuming you have a database connection file
include 'conn.php'; // Include your database connection here
include 'controller/controller.php'; // Include the UserController

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Instantiate UserController with the database connection
$userController = new UserController($conn);

// Call the appropriate method based on your application logic
$userController->handleRequest();

// Fetch sliders from the database after handling requests
$getSliders = $userController->getSliders();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slider Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* CSS for fixed image size */
        .slider-image {
            width: 100px; /* Set the width */
            height: 75px; /* Set the height */
            object-fit: cover; /* Ensure the aspect ratio is maintained */
        }
    </style>
</head>
<body>
    <?php include 'navbar.php' ?>

<div class="container mt-5">
    <h1 class="mb-4">Slider Management</h1>

    <!-- Add New Slider -->
    <h2>Add New Slider</h2>
    <form method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="form-group">
            <input type="text" name="title" class="form-control" placeholder="Title" required>
        </div>
        <div class="form-group">
            <textarea name="description" class="form-control" placeholder="Description" required></textarea>
        </div>
        <div class="form-group">
            <input type="file" name="image" class="form-control" required accept="image/*">
        </div>
        <div class="form-group">
            <select name="status" class="form-control">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" name="add" class="btn btn-primary">Add Slider</button>
    </form>

    <!-- Slider List -->
    <h2>Slider List</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Sr. No</th>
                <th>Title</th>
                <th>Description</th>
                <th>Image</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
<?php   
$sr_no = 1;
if (!empty($getSliders)) {
    foreach ($getSliders as $slider):
        ?>
        <tr>
            <td><?php echo $sr_no++; ?></td>
            <td><?php echo htmlspecialchars($slider['title']); ?></td>
            <td><?php echo htmlspecialchars($slider['description']); ?></td>
            <td><img src="<?php echo htmlspecialchars($slider['image']); ?>" alt="Image" class="slider-image"></td>
            <td><?php echo htmlspecialchars($slider['status']); ?></td>
            <td>
                <form method="POST" style="display:inline;" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $slider['id']; ?>">
                    <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($slider['image']); ?>">
                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal<?php echo $slider['id']; ?>">Edit</button>

                    <!-- Modal -->
                    <div class="modal fade" id="editModal<?php echo $slider['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel<?php echo $slider['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Slider</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" name="title" class="form-control mb-2" value="<?php echo htmlspecialchars($slider['title']); ?>" required>
                                    <textarea name="description" class="form-control mb-2" required><?php echo htmlspecialchars($slider['description']); ?></textarea>
                                    <input type="file" name="image" class="form-control mb-2" accept="image/*">
                                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="Image" class="slider-image mb-2">
                                    <select name="status" class="form-control mb-2">
                                        <option value="active" <?php echo ($slider['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo ($slider['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="edit" class="btn btn-primary">Save changes</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $slider['id']; ?>">
                    <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; 
} else {
    echo '<tr><td colspan="6" class="text-center">No sliders found.</td></tr>';
} ?>
</tbody>
</table>

</div>

<?php include 'footer.php' ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
