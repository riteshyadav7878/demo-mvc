<?php
// Include your database connection file
include 'conn.php';
include '../admin/model/model.php';

// Create an instance of the UserModel
$userModel = new UserModel($conn);

// Fetch item details based on the item ID from the URL
$itemId = isset($_GET['itemId']) ? intval($_GET['itemId']) : 0;
$title = isset($_GET['title']) ? htmlspecialchars($_GET['title']) : '';
$price = isset($_GET['price']) ? $_GET['price'] : '';

// Validate the price
$price = is_numeric($price) ? floatval($price) : 0; // Convert to float or set to 0 if invalid
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Cart - <?php echo $title; ?></title>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h1 class="mt-3 text-center">Item Details</h1>
        
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card">
                    <img src="path_to_image_based_on_item_id_or_details" class="card-img-top" alt="<?php echo $title; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $title; ?></h5>
                        <p class="card-text"><strong>Price:</strong> ₹<?php echo number_format($price, 2); ?></p>
                        <form method="POST" action="add_to_cart.php">
                            <input type="hidden" name="itemId" value="<?php echo $itemId; ?>">
                            <input type="hidden" name="itemTitle" value="<?php echo $title; ?>">
                            <input type="hidden" name="itemPrice" value="<?php echo $price; ?>">
                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mt-5 text-center">Other Items</h3>
        <div class="row">
            <?php
            // Fetch all items to display below
            $items = $userModel->getAllshop();
            foreach($items as $row): ?>
                <div class="col-3">
                    <div class="item-card card">
                        <a href="cart.php?itemId=<?php echo $row['id']; ?>&title=<?php echo urlencode($row['title']); ?>&price=<?php echo $row['price']; ?>">
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        </a>
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
