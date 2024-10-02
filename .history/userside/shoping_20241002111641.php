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
        .item-card { margin-bottom: 30px; transition: transform 0.2s; }
        .slider-image { width: 100%; height: 150px; object-fit: cover; transition: transform 0.2s; }
        .item-card:hover .slider-image { transform: scale(1.1); }
        .item-card:hover { transform: translateY(-2px); }
        .item-card {
                    margin-bottom: 30px;
                    transition: transform 0.2s;
                    background-color: #ffffff; /* Change this to your desired background color */
                    border: 1px solid #ddd; /* Optional: Add a border */
                    border-radius: 5px; /* Optional: Add border radius */
                    padding: 15px; /* Optional: Add padding */
                }

    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container bg-primary">
        <h1 class="mt-3 bt-2 text-center">Shopping </h1>

        <!-- Items List -->
        <div class="row">
    <?php if (isset($items) && count($items) > 0): ?>
        <?php foreach($items as $row): ?>
            <div class="col-3">
                <div class="item-card card">
                    <a href="cart.php?itemId=<?php echo $row['id']; ?>">
                        <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="card-img-top slider-image" alt="<?php echo htmlspecialchars($row['title']); ?>">
                    </a>
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
