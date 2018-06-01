<?php require_once '../main.php'; ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require_once '../modules/header.php'; ?>
        <title><?php echo APP_NAME ?></title>
    </head>
    <body class="hold-transition login-page">

        <div class="login-box">
            <div class="login-logo">
                <p>Hotel Booking System</p>
            </div>
            <div class="login-box-body">
                <a class="btn btn-block btn-primary btn-lg" href="<?php echo WEB_ROOT_PATH ?>panel" role="button">Continue to Staff Panel</a>
            </div>
        </div>

        <?php require_once '../modules/footer.php'; ?>
    </body>
</html>