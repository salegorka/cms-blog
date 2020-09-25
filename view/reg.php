<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Зарегистрироваться</title>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>

<form action="/reg" method="post" class="reg-form d-flex justify-content-center align-items-center">
    <div class="reg-form__content d-flex flex-column align-items-center">
        <a href="/" class="logo d-flex justify-content-sm-start justify-content-center align-items-center">møviebløg</a>
        <div class="reg-form__info">
            <p class="reg-form__info-text">Если у вас уже есть аккаунт на нашем сайте <a class="text-link" href="/login">войдите</a></p>
        </div>
        <div class="form-group">
            <label for="regEmailInput" class="reg-form__label">Email</label>
            <input type="email" class="form-control" id="regEmailInput" name="regEmailInput"
                   value="<?= !empty($_POST) ? $_POST['regEmailInput'] : '' ?>">
        </div>
        <div class="form-group">
            <label for="regUsernameInput" class="reg-form__label">Имя на сайте</label>
            <input type="text" class="form-control" id="regUsernameInput" name="regUsernameInput"
                   value="<?= !empty($_POST) ? $_POST['regUsernameInput'] : '' ?>">
        </div>
        <div class="form-group">
            <label for="regPassInput" class="reg-form__label">Пароль</label>
            <input type="password" class="form-control" id="regPassInput" name="regPassInput"
                   value="<?= !empty($_POST) ? $_POST['regPassInput'] : '' ?>">
        </div>
        <div class="form-group">
            <label for="regPassControlInput" class="reg-form__label">Пароль еще раз</label>
            <input type="password" class="form-control" id="regPassControlInput" name="regPassControlInput"
                   value="<?= !empty($_POST) ? $_POST['regPassControlInput'] : '' ?>">
        </div>
        <?php if (!empty($data['error'])) : ?>
        <div class="reg-form__error">
            <p class="reg-form__error-text"><?= $data['error'] ?></p>
        </div>
        <?php endif; ?>
        <button class="reg-form__button button" type="submit">Зарегистрироваться</button>
    </div>
</form>


<script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="js/main.js"></script>
</body>
</html>
