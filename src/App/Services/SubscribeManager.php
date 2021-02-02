<?php

namespace App\Services;

use App\Model\User;
use App\Model\Subscriber;
use App\Model\Article;

class SubscribeManager {

    public function subscriberCheck($email) {
        try {
            $subscriber = Subscriber::where('email', '=', $email)->firstOrFail();
        } catch(\Exception $e) {
            return false;
        }
        return true;
    }

    public function subscriberAdd($email) {
        $subscriber = new Subscriber();
        $subscriber->email = $email;

        $subscriber->token = mt_rand(1, 999999999);

        $subscriber->save();
    }

    public function subscriberDel($email) {
        $subscriber = Subscriber::where('email', '=', $email)->first();
        Subscriber::destroy($subscriber->id);
    }

    public function subscriberDelById($id, $token) {
        $subscriber = Subscriber::find($id);
        if ($subscriber->token != $token) {
            throw new App\Exception\NotFoundException();
        }
        Subscriber::destroy($id);
    }

    public function subscribeUserOn($id) {
        $user = User::find($id);
        $user->subscribe = true;
        $user->save();
    }

    public function subscribeUserOff($id) {
        $user = User::find($id);
        $user->subscribe = false;
        $user->save();
    }

    public function sendNotification($id, $name, $descr) {

        $subscribers = Subscriber::where('id', '!=', 0)->get()->toArray();
        foreach($subscribers as $subscriber) {

            $header = "На сайте добавлена новая статья: ${name}";

            $body = "
Новая статья: ${name};
 ${descr};
Cсылка на статью: ${_SERVER['HTTP_HOST']}/article?id=${id};
Ссылка чтобы отписаться от рассылки: ${_SERVER['HTTP_HOST']}/subscriber/del?id=${subscriber['id']}&token=${subscriber['token']};
";

            sendEmailToLog($subscriber['email'], $header, $body);
        }

        $users = User::where('subscribe', '=', 1)->get()->toArray();
        foreach($users as $user) {
            $header = "На сайте добавлена новая статья: ${name}";

            $body = "
Новая статья: ${name};
${descr};
Cсылка на статью: ${_SERVER['HTTP_HOST']}/article?id=${id};
Отписаться от рассылки вы можете в своем личном кабинете;
";

            sendEmailToLog($user['email'], $header, $body);
        }

    }

}