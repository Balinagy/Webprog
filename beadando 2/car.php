<?php
session_start();
require_once("Storage.php");

$carStorage = new Storage(new JsonIO('cars.json'), false);
$car = $carStorage -> findById($_GET['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles.css">
    <title>iKarRental - <?= $car->brand ?> <?= $car->model ?></title>
</head>
<body>
    <nav class="navbar bg-dark border-bottom border-body" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">iKarRental</a>
            <div class="d-flex">
                    <?php if (isset($_SESSION["user"]) && $_SESSION["user"]!= ""): ?>
                        <a href="userprofil.php"><button class="reg-button">Profil</button></a>
                    <?php endif; ?>
                    <?php if (isset($_SESSION["user"]) && $_SESSION["user"]!= ""): ?>
                        <form action="logout.php">
                            <button type="submit" class="login-button">Kijelentkezés</button>
                        </form>
                    <?php endif; ?>
                    <?php if (!isset($_SESSION["user"]) || $_SESSION["user"] == ""): ?>
                        <a href="login.php"><button class="login-button">Bejelentkezés</button></a>
                        <a href="registration.php"><button class="reg-button">Regisztráció</button></a>
                    <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="individual-car-container">
        <div class="individual-car-div">
            <div class="individual-car-name">
                <span><?= $car->brand ?> <?= $car->model ?></span>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="individual-car-img">
                            <img src="<?= $car->image ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="individual-car-options">
                            <div class="individual-car-info">
                                <div class="individual-car-info2">
                                    <div class="individual-car-fueltrans">
                                        <span>Üzemanyag: <?= $car->fuel_type ?></span>
                                        <br>
                                        <span>Váltó: <?= $car->transmission ?></span>
                                    </div>
                                    <div class="individual-car-yearpass">
                                        <span>Gyártási év: <?= $car->year ?></span>
                                        <br>
                                        <span>Férőhelyek száma: <?= $car->passengers ?></span>
                                    </div>
                                </div>
                                <div class="individual-car-price">
                                    <span><?= $car->daily_price_huf ?> Ft/nap</span>
                                </div>
                            </div>
                            <div class="individual-car-buttons">
                                <button class="date-button">Dátum kiválasztása</button>
                                <button class="ind-reserve-button">Lefoglalom</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>