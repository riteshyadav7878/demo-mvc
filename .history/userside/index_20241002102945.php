<?php
// Include your database connection file
include 'conn.php';
include '../admin/model/model.php';

// Create an instance of the UserModel
$model = new UserModel($conn);
$sliders = $model->getsliders();
$hasSliders = !empty($sliders);

$userModel = new UserModel($conn);
$items = $userModel->(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slider Carousel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .carousel-item {
            height: 80vh; /* Full viewport height */
            position: relative; /* Necessary for positioning captions */
        }
        .carousel-item img {
            width: 100%; /* Full width */
            height: 100%; /* Full height */
           /* Maintain aspect ratio */
        }
        .carousel-caption {
            top: 50%; /* Center the caption vertically */
            transform: translateY(-50%); /* Adjust for accurate centering */
            font-weight: bold;
        }

     
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

<div class="container-fluid p-0">
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <?php if ($hasSliders): ?>
                <?php foreach ($sliders as $index => $slider): ?>
                    <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                        <img src="<?php echo htmlspecialchars($slider['image']); ?>" class="d-block" alt="Slide">
                        <div class="carousel-caption d-none d-md-block">
                            <h5><?php echo htmlspecialchars($slider['title']); ?></h5>
                            <p><?php echo htmlspecialchars($slider['description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="carousel-item active">
                    <img src="placeholder.jpg" class="d-block" alt="No Images Available">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>No Images Available</h5>
                        <p>Please add images to the slider.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>

<div class="container">
        <h1 class="mt-3 bt-2 text-center">Shopping Now</h1>

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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
