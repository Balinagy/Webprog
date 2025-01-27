<?php
session_start();
require_once("Storage.php");

$carStorage = new Storage(new JsonIO('cars.json'), false);

function validate($input, &$errors) {
    if(!isset($input['carbrand']) || $input['carbrand'] == "")
    {
        $errors['carbrand'] = 'Kötelező megadni márkát!';
    }
    if(!isset($input['model']) || $input['model'] == "")
    {
        $errors['model'] = 'Kötelező megadni modelt!';
    }
    if(!isset($input['year']) || $input['year'] > 2025 || 1886 > $input['year'])
    {
        $errors['year'] = '1886 és 2025 közötti évet adj!';
    }
    if(!isset($input['transmission']))
    {
        $errors['transmission'] = 'Add meg a váltó típusát!';
    }
    if(!isset($input['fuel']))
    {
        $errors['fuel'] = 'Add meg az üzemanyagot!';
    }
    if(!isset($input['passengers']) || $input['passengers'] > 50 || 1 > $input['passengers'])
    {
        $errors['passengers'] = 'Az utasok száma 1 és 50 között lehet!';
    }
    if(!isset($input['price']) || 1 > $input['price'])
    {
        $errors['price'] = 'A napi ár nagyobb, mint 0!';
    }
    //azt nem tudtam, hogy kell validálni, hogy a beküldött link tényleg kép-e
    if (!isset($input['image']) || !filter_var($input['image'], FILTER_VALIDATE_URL)) {
        $errors['image'] = 'Érvényes URL-t adj meg!';
    }
}

$errors = [];
if (!empty($_POST)) {
    validate($_POST, $errors);
}
//mivel lehet több ugyanolyan autó, itt matching vizsgálat nem szükséges
$success = [];
if(sizeof($errors) == 0 && sizeof($_POST) != 0)
{
    $transmissions = [
        "manual" => "Manuális",
        "automat" => "Automata",
    ];
    $fuels = [
        "petrol" => "Benzin",
        "diesel" => "Dízel",
        "hybrid" => "Hybrid",
        "electric" => "Elektromos",
    ];

    $transmission = $transmissions[$_POST["transmission"]] ?? "Ismeretlen";
    $fueltype = $fuels[$_POST["fuel"]] ?? "Ismeretlen";

    $carStorage->add([
        "brand" => htmlspecialchars($_POST["carbrand"]),
        "model" => htmlspecialchars($_POST["model"]),
        "year" => (int)$_POST["year"],
        "transmission" => $transmission,
        "fuel_type" => $fueltype,
        "passengers" => (int)$_POST["passengers"],
        "daily_price_huf" => (int)$_POST["price"],
        "image" => htmlspecialchars($_POST["image"]),
    ]);
    $success['success'] = "Sikeres feltöltés";
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
    <title>iKarRental - Új autó hozzáadása</title>
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
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="new-car-div p-4 border rounded shadow">
            <p class="new-car-text">Új autó hozzáadása</p>
            <form method="post">
            <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <label for="carbrand" class="form-label">Autó márkája:</label>
                    <input id="carbrand" name="carbrand" class="form-control"
                        value="<?= isset($_POST['carbrand']) ? $_POST['carbrand'] : '' ?>">
                </div>
                <?php if(isset($errors['carbrand'])): ?>
                    <div style="color: red"><?= $errors['carbrand'] ?></div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="model" class="form-label">Autó modelje:</label>
                    <input id="model" name="model" class="form-control"
                        value="<?= isset($_POST['model']) ? $_POST['model'] : '' ?>">
                </div>
                <?php if(isset($errors['model'])): ?>
                    <div style="color: red"><?= $errors['model'] ?></div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="year" class="form-label">Gyártási év:</label>
                    <input type="number" id="year" name="year" class="form-control"
                        value="<?= isset($_POST['year']) ? $_POST['year'] : '' ?>">
                </div>
                <?php if(isset($errors['year'])): ?>
                    <div style="color: red"><?= $errors['year'] ?></div>
                <?php endif; ?>
                <div>
                    <p>Váltó fajtája:</p>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="manual">Manuális</label>
                            <input id="manual"
                                type="radio"
                                name="transmission"
                                value="manual"
                                <?= isset($_POST['transmission']) &&
                                $_POST['transmission'] == 'manual' ? 'checked' : '' ?>
                            >
                        </div>
                        <div class="col-6">
                            <label for="automat">Auto</label>
                            <input id="automat"
                                type="radio"
                                name="transmission"
                                value="automat"
                                <?= isset($_POST['transmission']) &&
                                $_POST['transmission'] == 'automat' ? 'checked' : '' ?>
                            >
                        </div>
                    </div>
                </div>
                <?php if(isset($errors['transmission'])): ?>
                    <div style="color: red"><?= $errors['transmission'] ?></div>
                <?php endif; ?>
                </div>
                <div class="col-6">
                <div>
                    <p>Üzemanyag:</p>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="petrol">Benzin</label>
                            <input id="petrol"
                                type="radio"
                                name="fuel"
                                value="petrol"
                                <?= isset($_POST['fuel']) &&
                                $_POST['fuel'] == 'petrol' ? 'checked' : '' ?>
                            >
                        </div>
                        <div class="col-6">
                            <label for="diesel">Dízel</label>
                            <input id="diesel"
                                type="radio"
                                name="fuel"
                                value="diesel"
                                <?= isset($_POST['fuel']) &&
                                $_POST['fuel'] == 'diesel' ? 'checked' : '' ?>
                            >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="hybrid">Hybrid</label>
                            <input id="hybrid"
                                type="radio"
                                name="fuel"
                                value="hybrid"
                                <?= isset($_POST['fuel']) &&
                                $_POST['fuel'] == 'hybrid' ? 'checked' : '' ?>
                            >
                        </div>
                        <div class="col-6">
                            <label for="electric">Áram</label>
                            <input id="electric"
                                type="radio"
                                name="fuel"
                                value="electric"
                                <?= isset($_POST['fuel']) &&
                                $_POST['fuel'] == 'electric' ? 'checked' : '' ?>
                            >
                        </div>
                    </div>
                    <?php if(isset($errors['fuel'])): ?>
                        <div style="color: red"><?= $errors['fuel'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="passengers" class="form-label">Utasok száma:</label>
                    <input type="number" id="passengers" name="passengers" class="form-control"
                        value="<?= isset($_POST['passengers']) ? $_POST['passengers'] : '' ?>">
                </div>
                <?php if(isset($errors['passengers'])): ?>
                    <div style="color: red"><?= $errors['passengers'] ?></div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="price" class="form-label">Napi ár:</label>
                    <input type="number" id="price" name="price" class="form-control"
                        value="<?= isset($_POST['price']) ? $_POST['price'] : '' ?>">
                </div>
                <?php if(isset($errors['price'])): ?>
                    <div style="color: red"><?= $errors['price'] ?></div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="image" class="form-label">Kép linkje:</label>
                    <input id="image" name="image" class="form-control"
                        value="<?= isset($_POST['image']) ? $_POST['image'] : '' ?>">
                </div>
                <?php if(isset($errors['image'])): ?>
                    <div style="color: red"><?= $errors['image'] ?></div>
                <?php endif; ?>
                </div>
                </div>
                <div>
                    <button type="submit" class="new-car-submit">Feltöltés</button>
                    <?php if(isset($success['success'])): ?>
                        <div style="color: green"><?= $success['success'] ?></div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</body>
</html>