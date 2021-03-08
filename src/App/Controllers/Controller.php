<?php

namespace App\Controllers;

use App\Exception\BadAuthorizedException;

abstract class Controller {

    protected static function checkUserAuth()
    {
        if (!$_SESSION['isUserAuthorized']) {
            throw new BadAuthorizedException();
        }
    }

    protected static function checkContentManagerRights()
    {
        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
        }
    }

    protected static function checkAdminRights()
    {
        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 3)) {
            throw new BadAuthorizedException();
        }

    }

}