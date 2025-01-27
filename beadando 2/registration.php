<?php
session_start();
require_once("Storage.php");

$userStorage = new Storage(new JsonIO('users.json'), false);

function validate($input, &$errors) {
    if(!isset($input['fullname']) || $input['fullname'] == "")
    {
        $errors['fullname'] = 'Kötelező nevet megadni!';
    }
    else if (!isset($input['fullname']) || count(array_filter(explode(' ', trim($input['fullname'])))) < 2) {
        $errors['fullname'] = 'Teljes név minimum 2 szó!';
    }

    if (!isset($input['email']) ||
        !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Helyes email címet adj meg!';
    }
    if (!isset($input['password1']))
    {
        $errors['password1'] = 'Add meg a jelszavad!';
    }
    if (!isset($input['password2']))
    {
        $errors['password2'] = 'Ismételd meg a jelszavad!';
    }
    else if($input['password1'] != $input['password2'])
    {
        $errors['password2'] = 'A két jelszó nem egyezik meg!';
    }
}

$errors = [];
$regmessage = [];
if (!empty($_POST)) {
    validate($_POST, $errors);
}
if(sizeof($errors) == 0 && sizeof($_POST) != 0)
{
    $matchingAchievement = $userStorage->findOne([
        "email"=>$_POST["email"]
    ]);
    if (!$matchingAchievement) {
        $userStorage->add([
            "email" => $_POST["email"],
            "fullname" => $_POST["fullname"],
            "password" => password_hash($_POST["password1"], PASSWORD_DEFAULT),
            "roles" => "user"
        ]);
        $regmessage['success'] = "Sikeres regisztráció!";
    } else {
        $regmessage['fail'] = "A megadott email címmel már regisztráltak!";
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
            <p class="registration-text">Regisztráció</p>
            <form method="post">
                <div class="mb-3">
                    <label for="fullname" class="form-label">Teljes név:</label>
                    <input id="fullname" name="fullname" class="form-control"
                        value="<?= isset($_POST['fullname']) ? $_POST['fullname'] : '' ?>">
                </div>
                <?php if(isset($errors['fullname'])): ?>
                    <div style="color: red"><?= $errors['fullname'] ?></div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail cím:</label>
                    <input id="email" name="email" class="form-control"
                        value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                </div>
                <?php if(isset($errors['email'])): ?>
                    <div style="color: red"><?= $errors['email'] ?></div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="password1" class="form-label">Jelszó:</label>
                    <input type="password" id="password1" name="password1" class="form-control"
                        value="<?= isset($_POST['password1']) ? $_POST['password1'] : '' ?>">
                </div>
                <?php if(isset($errors['password1'])): ?>
                    <div style="color: red"><?= $errors['password1'] ?></div>
                <?php endif; ?>

                <div class="mb-3">
                    <label for="password2" class="form-label">Jelszó mégegyszer:</label>
                    <input type="password" id="password2" name="password2" class="form-control"
                        value="<?= isset($_POST['password2']) ? $_POST['password2'] : '' ?>">
                </div>
                <?php if(isset($errors['password2'])): ?>
                    <div style="color: red"><?= $errors['password2'] ?></div>
                <?php endif; ?>
                <div>
                    <button type="submit" class="reg-button">Regisztráció</button>
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