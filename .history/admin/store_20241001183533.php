<?php
// Include your database connection file
include 'conn.php';
include '../admin/model/model.php';

// Create an instance of the UserModel
$userModel = new UserModel($conn);
$items = $userModel->getAllshop(); // Fetch items here
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
        .item-card { margin-bottom: 30px; } /* Adjust margin as needed */
        .slider-image { width: 100%; height: 150px; object-fit: cover; transition: transform 0.3s; } /* Transition for smooth effect */
        
        /* Hover effect */
        .slider-image:hover {
            transform: scale(1.1); /* Enlarge image slightly */
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h1 class="mt-5">Shopping Page</h1>

        <!-- Items List -->
        <div class="row">
        <?php if (isset($items) && count($items) > 0): ?>
            <?php foreach($items as $row): ?>
                <div class="col-3">
                    <div class="item-card card">
                        <!-- Make the entire card clickable -->
                        <a href="cart.php?itemId=<?php echo $row['id']; ?>">
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="card-img-top slider-image" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                            <p class="card-text"><strong>Price:</strong> ₹<?php echo number_format($row['price'], 2); ?></p>
                            <form method="POST" action="cart.php" class="d-inline">
                                <input type="hidden" name="itemId" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-primary">Buy Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center col-12">
                <p>No items found.</p>
            </div>
        <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
