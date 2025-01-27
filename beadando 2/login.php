<?php
session_start();
require_once("Storage.php");

$userStorage = new Storage(new JsonIO('users.json'), false);

function validate($input, &$errors) {
    if (!isset($input['email']) ||
        !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Helyes email címet adj meg!';
    }
    if (!isset($input['password']))
    {
        $errors['password'] = 'Add meg a jelszavad!';
    }
}

$errors = [];
$regmessage = [];
if (!empty($_POST)) {
    validate($_POST, $errors);
}
if(sizeof($errors) == 0 && sizeof($_POST) != 0)
{
    $matchingUser = $userStorage->findOne([
        "email"=>$_POST["email"],
    ]);
    if ($matchingUser && password_verify($_POST["password"],$matchingUser->password)) {
        $_SESSION["user"] = $matchingUser;
        $regmessage['success'] = "Sikeres bejelentkezés!";
    } else {
        $regmessage['fail'] = "Helytelen adatok!";
    }
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
    <link rel="stylesheet" href="styles2.css">
    <title>iKarRental - Regisztráció</title>
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
        <div class="registration-div p-4 border rounded shadow">
            <p class="registration-text">Bejelentkezés</p>
            <form method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail cím:</label>
                    <input id="email" name="email" class="form-control"
                        value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                </div>
                <?php if(isset($errors['email'])): ?>
                    <div style="color: red"><?= $errors['email'] ?></div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="password" class="form-label">Jelszó:</label>
                    <input type="password" id="password" name="password" class="form-control"
                        value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>">
                </div>
                <?php if(isset($errors['password'])): ?>
                    <div style="color: red"><?= $errors['password'] ?></div>
                <?php endif; ?>

                    <button type="submit" class="reg-button">Bejelentkezés</button>
                    <?php if(isset($success['success'])): ?>
                        <div style="color: green"><?= $success['success'] ?></div>
                    <?php endif; ?>
                    <?php if(isset($regmessage['success'])): ?>
                        <div style="color: green"><?= $regmessage['success'] ?></div>
                    <?php endif; ?>
                    <?php if(isset($regmessage['fail'])): ?>
                        <div style="color: red"><?= $regmessage['fail'] ?></div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</body>
</html>