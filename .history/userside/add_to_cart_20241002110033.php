<?php
session_start();

// Check if itemId is set in the POST request
if (isset($_POST['itemId'])) {
    $itemId = (int)$_POST['itemId'];

    // Here you would typically add the item to a session cart or database
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the item is already in the cart
    if (!in_array($itemId, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $itemId; // Add item to cart
    }

    // Redirect back to the cart or a success page
    header('Location: cart.php?itemId=' . $itemId);
    exit;
}

// Redirect to homepage or an error page if itemId is not set
header('Location: index.php');
exit;
