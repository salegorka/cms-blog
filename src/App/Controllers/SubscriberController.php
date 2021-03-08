<?php

namespace App\Controllers;

use App\Exception\NotFoundException;
use App\Exception\BadAuthorizedException;
use App\Services\SubscribeManager;
use App\Services\ArticleManager;
use App\View\View;

class SubscriberController extends Controller {

    public static function createSubscriber()
    {

        $email = $_POST['email'];

        $subscriberManager = new SubscribeManager();

        if ($subscriberManager->subscriberCheck($email)) {
            return json_encode(['error' => "Подписчик с таким email уже подписан на обновления"]);
        } else {
            $subscriberManager->subscriberAdd($email);
            return json_encode(['ok' => true]);
        }

    }

    public static function deleteSubscriber()
    {

        if (!(isset($_GET['id']) && $_GET['token'])) {
            throw new NotFoundException();
        }

        $subscriberManager = new SubscribeManager();
        $subscriberManager->subscriberDelById($_GET['id'], $_GET['token']);

        return new View("notificationCancel");

    }

    public static function sendNotification()
    {

        parent::checkContentManagerRights();

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        }

        $articleManager = new ArticleManager();
        $data['article'] = $articleManager->loadSingleArticle($_GET['id']);

        $subscribeManager = new SubscribeManager();
        $subscribeManager->sendNotification($data['article']['id'], $data['article']['name'], $data['article']['description']);

        echo json_encode(['ok' => 'Уведомление успешно отправлено']);
    }
}