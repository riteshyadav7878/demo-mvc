<?php
session_start();
include 'conn.php'; // Include your database connection file
include '../admin/model/model.php';

$userModel = new UserModel($conn);

// Initialize the cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['itemId'])) {
    $itemId = intval($_POST['itemId']);
    // Check if item is already in the cart
    if (!in_array($itemId, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $itemId;
    }
}

// Fetch all items in the cart
$cartItems = [];
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $itemId) {
        $cartItems[] = $userModel->getItemById($itemId); // Assume this method exists to fetch a single item by ID
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cart</title>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h1 class="mt-3">Your Cart</h1>
        <div class="row">
            <?php if (count($cartItems) > 0): ?>
                <?php foreach ($cartItems as $item): ?>
                    <div class="col-3">
                        <div class="card mb-3">
                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                                <p class="card-text">Price: ₹<?php echo number_format($item['price'], 2); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
