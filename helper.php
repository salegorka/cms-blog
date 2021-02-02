<?php

function array_get($array, $key, $default = null)
{
    $keys = explode('.', $key);
    $item = $array;
    foreach ($keys as $key) {
        $item = $item[$key];
    }
    return $item ?? $default;
}

function readJsonFile($path)
{
    if (file_exists($path)) {
        $string = file_get_contents($path);
        return json_decode($string, true);
    }

    return null;
}

function clean($value = '')
{
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value);

    return $value;
}

function sendEmailToLog($address, $header, $body) {

    $message = "
    На адресс ${address} отправлено письмо;
Заголовок: ${header}
Сообщение: ${body}";


    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/Readme/Log.txt', $message, FILE_APPEND);

}