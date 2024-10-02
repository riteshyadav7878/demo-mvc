<?php
// Include your database connection file
include 'conn.php';
include '../admin/model/model.php';

// Create an instance of the UserModel
$userModel = new UserModel($conn);

// Check if an itemId is passed in the URL
$itemId = isset($_GET['itemId']) ? (int)$_GET['itemId'] : null;

$item = null;
if ($itemId) {
    $item = $userModel->getItemById($itemId); // Fetch item by ID
}

$items = $userModel->getAllshop(); // Fetch all items for display below
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
        .item-card { margin-bottom: 30px; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h1 class="mt-3 text-center">Cart Item</h1>

        <?php if ($item): ?>
            <div class="card mb-3">
                <img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['title']); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($item['description']); ?></p>
                    <p class="card-text"><strong>Price:</strong> ₹<?php echo number_format($item['price'], 2); ?></p>
                    <form method="POST" action="add_to_cart.php" class="d-inline">
                        <input type="hidden" name="itemId" value="<?php echo $item['id']; ?>">
                        <button type="submit" class="btn btn-primary">Add to Cart</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center">
                <p>No item found.</p>
            </div>
        <?php endif; ?>

        <h2 class="mt-5">Other Items</h2>
        <div class="row">
            <?php foreach ($items as $row): ?>
                <?php if ($row['id'] !== $itemId): // Exclude the clicked item ?>
                    <div class="col-3">
                        <div class="item-card card">
                            <a href="cart.php?itemId=<?php echo $row['id']; ?>">
                                <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title']); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                                <p class="card-text"><strong>Price:</strong> ₹<?php echo number_format($row['price'], 2); ?></p>
                                <form method="POST" action="add_to_cart.php" class="d-inline">
                                    <input type="hidden" name="itemId" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
