<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Test</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { background-color: #f4f7f6; padding-top: 50px; }
        .card { border: none; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .table-container { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        th { background-color: #343a40 !important; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card p-4 mb-5">
            <h4 class="mb-4">Product Entry</h4>
            <form id="form">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="product_name" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Save</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Submitted At</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="productTable"></tbody>
                <tfoot>
                    <tr class="table-secondary">
                        <td colspan="4" class="text-end fw-bold">Grand Total:</td>
                        <td id="grandTotal" class="fw-bold text-primary">0.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            function loadProducts() {
                $.get('/api/products', function(data) {
                    let rows = '';
                    let grandTotal = 0;
                    data.forEach(p => {
                        let total = p.quantity * p.price;
                        grandTotal += total;
                        rows += `<tr>
                            <td>${p.product_name}</td>
                            <td>${p.quantity}</td>
                            <td>$${parseFloat(p.price).toFixed(2)}</td>
                            <td>${new Date(p.created_at).toLocaleString()}</td>
                            <td>$${total.toFixed(2)}</td>
                        </tr>`;
                    });
                    $('#productTable').html(rows);
                    $('#grandTotal').text('$' + grandTotal.toFixed(2));
                });
            }

            loadProducts();

            $('#form').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '/save',
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function() {
                        loadProducts();
                        $('#form')[0].reset();
                    }
                });
            });
        });
    </script>
</body>
</html>
