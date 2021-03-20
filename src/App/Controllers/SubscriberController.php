<?php

namespace App\Controllers;

use App\Exception\NotFoundException;
use App\Services\SubscribeManager;
use App\Services\ArticleManager;
use App\View\View;
use App\View\JsonResponse;
use App\Exception\JsonException;

class SubscriberController extends Controller
{
    public function createSubscriber()
    {
        $email = $_POST['email'];

        $subscriberManager = new SubscribeManager();

        if ($subscriberManager->subscriberCheck($email)) {
            $response = ['message' => "Подписчик с таким email уже подписан на обновления", 'result' => 'fail'];
        } else {
            $subscriberManager->subscriberAdd($email);
            $response = ['result' => 'success'];
        }

        return new JsonResponse($response);
    }

    public function deleteSubscriber()
    {
        if (!(isset($_GET['id']) && $_GET['token'])) {
            throw new NotFoundException();
        }

        $subscriberManager = new SubscribeManager();
        $subscriberManager->subscriberDelById($_GET['id'], $_GET['token']);

        return new View("notificationCancel");
    }

    public function sendNotification()
    {
        parent::checkContentManagerRights();

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        }

        $articleManager = new ArticleManager();
        $data['article'] = $articleManager->loadSingleArticle($_GET['id']);

        $subscribeManager = new SubscribeManager();
        $subscribeManager->sendNotification($data['article']['id'], $data['article']['name'], $data['article']['description']);

        return new JsonResponse(['result' => 'success', 'message' => 'Уведомление успешно отправлено']);
    }
}
