<?php
// Include your database connection file
include 'conn.php';
include '../admin/model/model.php';

// Create an instance of the UserModel
$userModel = new UserModel($conn);

// Check if itemId is set in the URL
if (isset($_GET['itemId'])) {
    $itemId = $_GET['itemId'];
    // Fetch the item details based on the itemId
    $itemDetails = $userModel->getItemById($itemId); // Implement this method in UserModel
} else {
    // Redirect or handle error
    header("Location: index.php"); // Redirect to the main page if no itemId is provided
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cart Page</title>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h1 class="mt-3 bt-2 text-center">Item Details</h1>

        <?php if ($itemDetails): ?>
            <div class="card mb-3">
                <img src="<?php echo htmlspecialchars($itemDetails['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($itemDetails['title']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($itemDetails['title']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($itemDetails['description']); ?></p>
                    <p class="card-text"><strong>Price:</strong> ₹<?php echo number_format($itemDetails['price'], 2); ?></p>
                    <form method="POST" action="checkout.php">
                        <input type="hidden" name="itemId" value="<?php echo $itemDetails['id']; ?>">
                        <button type="submit" class="btn btn-primary">Buy Now</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center">
                <p>No item details found.</p>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
