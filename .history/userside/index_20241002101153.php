<?php
// Include your database connection file
include 'conn.php';
include '../admin/model/model.php';

// Create an instance of the UserModel
$model = new UserModel($conn);
$sliders = $model->getsliders();
$hasSliders = !empty($sliders);
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

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
