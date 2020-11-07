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
            display: grid;
            grid-template-rows: repeat(3, max-content);
            padding-bottom: 100px;
        }
        #nav {
            padding: 10px 0px 0px 10px;
        }
        h4 {
            margin: 20px 0 15px 0;
            text-align: center;
            font-size: 19px;
        }
        #auth-form, #reg-form {
            width: max-content;
            margin: auto;
        }
        .warning {
            color: red;
        }
    </style>
</head>
<body>
<div id="nav">
    <button class="btn btn-secondary btn-sm" onclick="changeForm()">JUMP TO REGISTRATION FORM</button>
</div>
<div id="registration-container" style="display: none;">
    <h4>REGISTRATION FORM</h4>
    <form method="post" action="<?= dirname($_SERVER['SCRIPT_NAME']) ?>/inc/php/user-reg.inc.php" id="reg-form">
        <div class="form-group">
            <label for="gender">Gender</label>
            <select type="text" id="gender" name="gender" class="form-control">
                <option value="M">male</option>
                <option value="F">female</option>
            </select>
        </div>
        <div class="form-group">
            <label for="age">Age</label>
            <input required type="text" id="age" name="age" placeholder="age" class="form-control"
                   pattern="^[0-9]{1,2}$" title="Numbers only (one or two symbols)">
        </div>
        <div class="form-group">
            <label for="login">Login</label>
            <input required type="text" id="login" name="login" placeholder="login" class="form-control">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input required type="password" id="password" name="password"
                   placeholder="password" class="form-control" autocomplete="off">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input required type="text" id="email" name="email" placeholder="email" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Submit registration</button>
    </form>
</div>
<div id="login-container" style="display: block;">
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
</div>
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