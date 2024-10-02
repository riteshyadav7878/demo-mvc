<?php
include 'conn.php'

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image_url = $_POST['itemImage'];
    $title = $_POST['itemTitle'];
    $description = $_POST['itemDescription'];
    $price = $_POST['itemPrice'];
    $status = $_POST['itemStatus'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO shopping_items (image_url, title, description, price, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $image_url, $title, $description, $price, $status);
    
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Item added successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
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
        .card { margin: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Shopping Page</h1>
        
        <!-- Form to add new items -->
        <form id="itemForm" class="mb-4" method="POST">
            <div class="mb-3">
                <label for="itemImage" class="form-label">Image URL</label>
                <input type="text" class="form-control" id="itemImage" name="itemImage" required>
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
            <!-- Items will be displayed here -->
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
