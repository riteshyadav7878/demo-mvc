<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Shopping Page</title>
    <style>
        .card {
            margin: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Shopping Page</h1>
        
        <!-- Form to add new items -->
        <form id="itemForm" class="mb-4">
            <div class="mb-3">
                <label for="itemImage" class="form-label">Image URL</label>
                <input type="text" class="form-control" id="itemImage" required>
            </div>
            <div class="mb-3">
                <label for="itemTitle" class="form-label">Item Title</label>
                <input type="text" class="form-control" id="itemTitle" required>
            </div>
            <div class="mb-3">
                <label for="itemDescription" class="form-label">Description</label>
                <textarea class="form-control" id="itemDescription" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="itemPrice" class="form-label">Price</label>
                <input type="number" class="form-control" id="itemPrice" required>
            </div>
            <div class="mb-3">
                <label for="itemStatus" class="form-label">Status</label>
                <select class="form-select" id="itemStatus" required>
                    <option value="" disabled selected>Select status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Item</button>
        </form>

        <div id="itemList" class="row">
            <!-- Items will be appended here -->
        </div>
    </div>

    <script>
        document.getElementById('itemForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const imageUrl = document.getElementById('itemImage').value;
            const title = document.getElementById('itemTitle').value;
            const description = document.getElementById('itemDescription').value;
            const price = document.getElementById('itemPrice').value;
            const status = document.getElementById('itemStatus').value;

            // Create a card element for the new item
            const card = document.createElement('div');
            card.className = 'col-md-4';

            card.innerHTML = `
                <div class="card">
                    <img src="${imageUrl}" class="card-img-top" alt="${title}">
                    <div class="card-body">
                        <h5 class="card-title">${title}</h5>
                        <p class="card-text">${description}</p>
                        <p class="card-text"><strong>Price:</strong> $${price}</p>
                        <p class="card-text"><strong>Status:</strong> ${status}</p>
                        <button class="btn btn-danger" onclick="removeItem(this)">Delete Item</button>
                    </div>
                </div>
            `;

            // Append the new card to the item list
            document.getElementById('itemList').appendChild(card);

            // Clear the form inputs
            this.reset();
        });

        function removeItem(button) {
            // Remove the card from the DOM
            const card = button.closest('.card');
            card.parentElement.remove();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
