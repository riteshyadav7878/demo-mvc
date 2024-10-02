<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <!-- Registration Form -->
    <h2>Register</h2>
    <form action="controller.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>

    <!-- Login Form -->
    <h2 class="mt-5">Login</h2>
    <form action="controller.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <!-- Slider Management -->
    <h2 class="mt-5">Manage Sliders</h2>
    <form action="controller.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" name="title" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="form-control" name="image" required>
        </div>
        <button type="submit" name="add" class="btn btn-primary">Add Slider</button>
    </form>

    <!-- Item Management -->
    <h2 class="mt-5">Manage Items</h2>
    <form action="controller.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="itemTitle">Item Title</label>
            <input type="text" class="form-control" name="itemTitle" required>
        </div>
        <div class="form-group">
            <label for="itemDescription">Item Description</label>
            <textarea class="form-control" name="itemDescription" required></textarea>
        </div>
        <div class="form-group">
            <label for="itemPrice">Item Price</label>
            <input type="number" class="form-control" name="itemPrice" required>
        </div>
        <div class="form-group">
            <label for="itemStatus">Item Status</label>
            <select class="form-control" name="itemStatus">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="form-group">
            <label for="itemImage">Item Image</label>
            <input type="file" class="form-control" name="itemImage" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Item</button>
    </form>

    <!-- Existing Items Table (Optional) -->
    <h2 class="mt-5">Existing Items</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- PHP code to fetch and display items -->
            <?php
            // Assuming $items is an array fetched from the model
            // foreach ($items as $item) {
            //     echo "<tr>
            //         <td>{$item['id']}</td>
            //         <td>{$item['title']}</td>
            //         <td>{$item['description']}</td>
            //         <td>{$item['price']}</td>
            //         <td>{$item['status']}</td>
            //         <td>
            //             <form action='controller.php' method='POST'>
            //                 <input type='hidden' name='editId' value='{$item['id']}'>
            //                 <button type='submit' class='btn btn-warning'>Edit</button>
            //                 <button type='submit' name='deleteId' value='{$item['id']}' class='btn btn-danger'>Delete</button>
            //                 <button type='submit' name='toggleStatusId' value='{$item['id']}' class='btn btn-info'>Toggle Status</button>
            //             </form>
            //         </td>
            //     </tr>";
            // }
            ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
