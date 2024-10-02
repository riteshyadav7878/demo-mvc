<?php
// Include your database connection file
include 'conn.php';
include '../admin/model/model.php';

// Create an instance of the UserModel
$model = new UserModel($conn);
$items = $usermode->getItems(); // Fetch items here
 
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
        .item-card { margin-bottom: 30px; }
        .slider-image { width: 100%; height: auto; object-fit: cover; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <h1 class="mt-5">Shopping Page</h1>

        <!-- Items Grid -->
        <div class="row">
        <?php if (isset($items) && count($items) > 0): ?>
            <?php foreach($items as $row): ?>
                <div class="col-md-4">
                    <div class="card item-card">
                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="card-img-top slider-image" alt="<?php echo htmlspecialchars($row['title']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                            <p class="card-text"><strong>Price:</strong> ₹<?php echo number_format($row['price'], 2); ?></p>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="itemId" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-primary">Buy Now</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p>No items found.</p>
            </div>
        <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
