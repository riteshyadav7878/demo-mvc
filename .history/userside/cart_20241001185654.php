<?php
// Include your database connection file
include 'conn.php';
include '../admin/model/model.php';

// Create an instance of the UserModel
$userModel = new UserModel($conn);
$items = $userModel->getAllshop(); // Fetch all items

// Get the selected item ID from the query string
$selectedItemId = isset($_GET['itemId']) ? (int)$_GET['itemId'] : null;

// Fetch the selected item details
$selectedItem = null;
if ($selectedItemId) {
    foreach ($items as $item) {
        if ($item['id'] === $selectedItemId) {
            $selectedItem = $item;
            break;
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cart Page</title>
    <style>
        body { background-color: #f8f9fa; }
        .slider-image { width: 100%; height: 300px; object-fit: cover; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h1 class="mt-5">Cart Page</h1>

        <?php if ($selectedItem): ?>
            <h3>You have selected:</h3>
            <div class="mb-4">
                <img src="<?php echo htmlspecialchars($selectedItem['image_url']); ?>" class="slider-image" alt="<?php echo htmlspecialchars($selectedItem['title']); ?>">
                <h5 class="mt-2"><?php echo htmlspecialchars($selectedItem['title']); ?></h5>
                <p><?php echo htmlspecialchars($selectedItem['description']); ?></p>
                <p><strong>Price:</strong> ₹<?php echo number_format($selectedItem['price'], 2); ?></p>
            </div>
        <?php else: ?>
            <p>No item selected.</p>
        <?php endif; ?>

        <h3>All Items</h3>
        <div class="row">
        <?php foreach($items as $row): ?>
            <div class="col-3">
                <div class="item-card card">
                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="card-img-top slider-image" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                        <p class="card-text"><strong>Price:</strong> ₹<?php echo number_format($row['price'], 2); ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
