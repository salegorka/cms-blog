<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" type="text/css" href="/css/main.css">

    <title>Киноблог</title>

</head>
<body>
<div class="container-fluid p-0">
    <?php if (isset($_SESSION['isUserAuthorized']) && $_SESSION['isUserAuthorized']): ?>
        <div class="row user-panel-container">
            <div class="col user-panel d-flex justify-content-between">
                <span class="user-panel-username">Hello, <a href="#" class="user-panel-username-link"><?= $_SESSION['username'] ?></a></span>
                <div class="user-panel-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
        <div class="row user-menu">
            <ul class="user-menu-list">
                <li class="user-menu-el"><a href="#" class="user-menu-link">Профиль</a></li>
                <?php if($_SESSION['rights'] >= 2) : ?>
                    <li class="user-menu-el"><a href="/admin" class="user-menu-link">Управление сайтом</a></li>
                <?php endif; ?>
                <li class="user-menu-el"><a href="/logout" class="user-menu-link">Выйти</a></li>
            </ul>
        </div>
    <?php endif; ?>
    <header class="row header">
        <div class="col-sm py-1 py-sm-0"><a href="/" class="logo d-flex justify-content-sm-start justify-content-center align-items-center">møviebløg</a></div>
        <?php if(!(isset($_SESSION['isUserAuthorized']) && $_SESSION['isUserAuthorized'])) : ?>
            <div class="col-sm py-1 py-sm-0 login-container d-flex justify-content-center justify-content-sm-end align-items-center">
                <a href="/login" class="login-link">Войти</a>
            </div>
        <?php endif; ?>
        <nav class="menu-container col-sm">
            <ul class="menu-container-list d-sm-flex justify-content-sm-between ">
                <li class="menu-container-el d-flex align-items-center justify-content-center"><a class="menu-container-link" href="#">Link1</a></li>
                <li class="menu-container-el d-flex align-items-center justify-content-center"><a class="menu-container-link" href="#">Link2</a></li>
                <li class="menu-container-el d-flex align-items-center justify-content-center"><a class="menu-container-link" href="#">Link3</a></li>
            </ul>
        </nav>
    </header>