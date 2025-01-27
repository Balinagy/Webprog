<?php
session_start();
require_once("Storage.php");

$carStorage = new Storage(new JsonIO('cars.json'), false);
$cars = $carStorage -> findAll();

//filterezés
$numofpassengers = isset($_GET['numofpassengers']) ? (int)$_GET['numofpassengers'] : 1;
if (isset($_GET['numofpassengers']))
{
    $cars = array_filter($cars, function($car) use ($numofpassengers){
        return $car->passengers  >= $numofpassengers;
    });
}
$start_date = isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : '';
$end_date = isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : '';
//todo a dátumok
$transmission = isset($_GET['transmission']) ? htmlspecialchars($_GET['transmission']) : '';
if (isset($_GET['transmission']))
{
    $cars = array_filter($cars, function($car) use ($transmission){
        if($transmission == "Manuális")
        {
            return $car->transmission == "Manuális";
        }
        else if($transmission == "Automata")
        {
            return $car->transmission == "Automata";
        }
        return true;
    });
}
$price_min = isset($_GET['price_min']) ? (int)$_GET['price_min'] : -1;
if (isset($_GET['price_min']))
{
    $cars = array_filter($cars, function($car) use ($price_min){
        return $car->daily_price_huf >= $price_min;
    });
}
$price_max = isset($_GET['price_max']) ? (int)$_GET['price_max'] : -1;
if (isset($_GET['price_max']))
{
    $cars = array_filter($cars, function($car) use ($price_max){
        if($price_max != 0)
        {
            return $car->daily_price_huf <= $price_max;
        }
        return true;
    });
}
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
    <title>iKarRental - Kezdőlap</title>
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
    <div class="ad-container">
        <div class="ad">
            <span>Kölcsönözz autókat</span>
            <br>
            <span>könnyedén!</span>
        </div>
        <a href="registration.php"><button class="reg-button">Regisztráció</button></a>
    </div>
    <form method="get">
        <div class="filter-area">
            <div class="filter-options">
                <div class="filter-top-row">
                    <div class="size-filter">
                        <button class="size-button" type="button" onclick="updatePassengers(-1)">-</button>
                        <span class="actual-size" id="num-of-passengers">1</span>
                        <button class="size-button" type="button" onclick="updatePassengers(1)">+</button>
                        <input type="hidden" name="numofpassengers" id="numofpassengers" value="1">
                        <span class="size-span">férőhely</span>
                    </div>
                    <div class="time-input">
                        <input type="date" name="start_date" placeholder="mettől">
                        <span>-tól</span>
                        <input type="date" name="end_date" placeholder="meddig">
                        <span>-ig</span>
                    </div>
                </div>
                <div class="filter-bottom-row">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="transmissionName">
                            Váltó típusa
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" type="button" onclick="setTransmission('Automata')">Automata</button></li>
                            <li><button class="dropdown-item" type="button" onclick="setTransmission('Manuális')">Manuális</button></li>
                        </ul>
                        <input type="hidden" name="transmission" id="transmission" value="">
                    </div>
                    <div class="price-input">
                        <input type="number" name="price_min" placeholder="Minimum">
                        <span>-</span>
                        <input type="number" name="price_max" placeholder="Maximum">
                        <span>Ft</span>
                    </div>
                </div>
            </div>
            <div class="filter-button-container">
                <button class="filter-button" type="submit">Szűrés</button>
            </div>
        </div>
    </form>
    <script>
    // utasok mező frissítése
    function updatePassengers(change) {
        const numDisplay = document.getElementById('num-of-passengers');
        const numInput = document.getElementById('numofpassengers');
        let current = parseInt(numInput.value, 10) || 1;
        current = Math.max(1, current + change);
        numDisplay.textContent = current;
        numInput.value = current;
    }

    // váltó típus frissítése
    function setTransmission(value) {
        document.getElementById('transmission').value = value;
        document.getElementById('transmissionName').innerHTML = value;
    }
</script>
    <!-- innentől autók betöltése -->
    <div class="container-fluid">
        <div class="row g-3">
            <?php foreach ($cars as $car): ?>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <a href="car.php?id=<?= $car->id ?>" class="link-disabled-style">
                        <div class="car-card">
                            <div class="upper-card">
                                <img src="<?= $car->image ?>">
                                <span><?= $car->daily_price_huf ?> Ft</span>
                            </div>
                            <div class="lower-card">
                                <div class="car-card-info">
                                    <span><?= $car->brand ?> <?= $car->model ?></span>
                                    <br>
                                    <span><?= $car->passengers ?> férőhely - <?= $car->transmission ?></span>
                                </div>
                                <div class="reserve">
                                    <button class="reserve-button">Foglalás</button>
                                </div>
                            </div>
                        </div>
                        </a>
                    </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>