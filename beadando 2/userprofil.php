<?php
session_start();
require_once("Storage.php");

$userStorage = new Storage(new JsonIO('users.json'), false);
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
    <link rel="stylesheet" href="styles2.css">
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
    <div class="container d-flex justify-content-center min-vh-100">
        <div class="row">
            <div class="profil-div">
                <?php if (!isset($_SESSION["user"]) || $_SESSION["user"] == ""): ?>
                    <span class="login-name">Jelentkezz be!</span>
                <?php else: ?>
                    <span class="login-name">Szia <?= htmlspecialchars($_SESSION["user"]->fullname) ?>!</span>
                    <img class="profile-picture" src="https://static-00.iconduck.com/assets.00/profile-circle-icon-2048x2048-cqe5466q.png">
                    <?php if ($_SESSION["user"]->roles == "admin"): ?>
                            <a href="addcar.php"><button class="reg-button">Autók hozzáadása</button></a>
                    <?php endif; ?>
                <?php endif; ?> 
            </div>
        </div>
    </div>
</body>
</html>