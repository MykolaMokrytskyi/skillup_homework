<?php

session_start();

$isGuest = $_SESSION['username'] ?? null;
$loginError = isset($_GET['error']) ? '<span class="warning">Wrong login or password, try again</span><br><br>' : '';

if ($isGuest === null):

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login form</title>
    <link rel="stylesheet" type="text/css"
          href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"/>
    <style type="text/css">
        body {
            padding-top: 50px;
            padding-bottom: 100px;
        }
        h4 {
            margin: 20px 0 15px 0;
            text-align: center;
            font-size: 19px;
        }
        #auth-form {
            width: max-content;
            margin: auto;
        }
        .warning {
            color: red;
        }
    </style>
</head>
<body>
<h4><?= $loginError ?>LOGIN FORM</h4>
<form method="post" action="<?= dirname($_SERVER['SCRIPT_NAME']) ?>/inc/php/user-auth.inc.php" id="auth-form">
    <div class="form-group">
        <label for="login">Login</label>
        <input required type="text" id="login" name="login" placeholder="login" class="form-control">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input required type="password" id="password" name="password"
               placeholder="password" class="form-control" autocomplete="off">
    </div>
    <button type="submit" class="btn btn-primary">Log In</button>
</form>
<script type="text/javascript">
    function changeForm() {
        let formsList = ['registration-container', 'login-container'];
        formsList.forEach(function(item) {
            let element = document.getElementById(item),
                displayMode = element.style.display,
                btn = document.getElementById('nav').querySelector('button');
            element.style.display = (displayMode === 'block') ? 'none' : 'block';
            if (item === 'login-container') {
                btn.innerHTML = (displayMode === 'block') ? 'JUMP TO LOGIN FORM' : 'JUMP TO REGISTRATION FORM';
            }
        });
    }
</script>
</body>
</html>
<?php exit(); ?>
<?php endif; ?>