<?php
    session_start();
    require('mysqli_connect.php');

    // 1. Fetch all products
    $sql = "SELECT nome, prezzo, disponibilità FROM prodotto";
    if (!$result = mysqli_query($dbc, $sql)) {
        die("DB query error: " . mysqli_error($dbc));
    }
?>

// 2. Loop and render cards
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Avaible Products</title>
        <link  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <style>
            .card.unavailable { opacity: 0.6; }
        </style>
    </head>
    <body>
        <div class="container py-4">
            <div class="row g-4">
                <?php 
                    while ($row = mysqli_fetch_assoc($result)):?>
                <?php
                    $name  = htmlspecialchars($row['nome'], ENT_QUOTES);
                    $price = number_format($row['prezzo'], 2, ',', ' ');
                    $avail = (bool)$row['disponibilità'];
                    $cardClass = $avail ? 'available' : 'unavailable';
                ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card <?= $cardClass ?> h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= $name ?></h5>
                        <p class="card-text mb-4">€ <?= $price ?></p>
                        <?php if ($avail): ?>
                        <a href="order.php?product=<?= urlencode($name) ?>"
                            class="btn btn-primary mt-auto">
                            Order
                        </a>
                        <?php else: ?>
                        <button class="btn btn-secondary mt-auto" disabled>
                            Not available
                        </button>
                        <?php endif ?>
                    </div>
                    </div>
                </div>
                <?php endwhile ?>
            </div>
        </div>
    </body>
</html>

<!-- Se non chiudo la conessione al db, le card rimangono reattive per eventuali cambiamenti? -->
<?php
    mysqli_free_result($result);
    mysqli_close($dbc);
?> 
