<?php

namespace App\controllers;

use App\main\App;
use App\models\entities\Booking;
use App\models\repositories\BookingRepository;
use App\models\repositories\GoodRepository;
use App\models\repositories\UserRepository;
use App\services\ArrayService;
use phpDocumentor\Reflection\Types\Object_;

class BasketController extends Controller
{
    const GOODS = 'goods';

    protected $defaultAction = 'index';

    public function addBasketAction()
    {
        $id = $this->getId();
        if (empty($id)) {
            return $this->redirect();
        }
        $good = (new GoodRepository())->getOne($id);
        if (empty($good)) {
            return $this->redirect();
        }

        $goods = $this->request->getSession(self::GOODS);
        if (array_key_exists($id, $goods)) {
            $goods[$id]['count']++;
        } else {
            $goods[$id] = [
                'name' => $good->name,
                'price' => $good->price,
                'count' => 1,
            ];
        }

        $this->request->setSession(self::GOODS, $goods);
        return $this->redirect();
    }

    public function removeBasketAction()
    {
        $id = $this->getId();
        $good = (new GoodRepository())->getOne($id);
        if (empty($good)) {
            return $this->redirect();
        }

        $goods = $this->request->getSession(self::GOODS);
        if ($goods[$id]['count'] > 1) {
            $goods[$id]['count']--;
        } else {
            unset($goods[$id]);
        }

        $this->request->setSession(self::GOODS, $goods);
        return $this->redirect();
    }

    public function indexAction()
    {
        $params = [
            'user_id' => $this->request->getSession('id'),
            'goods' => $_SESSION['goods']
        ];
        echo $this->render('basket', $params);
    }

    public function saveAction()
    {
        if (empty($this->request->getSession('login'))) {
            echo $this->render('basket', [
                'goods' => $this->request->getSession('goods'),
                'message_header' => 'необходимо авторизоваться'
            ]);
        } else if (empty($this->request->getSession('goods'))) {
            echo $this->render('basket', [
                'goods' => $this->request->getSession('goods'),
                'message_content' => 'Ваша корзина пуста'
            ]);
        } else {
            $booking = new Booking();
            $booking->items = json_encode($this->request->getSession('goods'));
            $booking->user_id = $this->getId();
            $booking->name = $this->request->getSession('login');
            $booking->fio = $this->request->getSession('fio');
            $bookingRepository = new BookingRepository();
            $bookingRepository->save($booking);
            $this->request->removeSession('goods');
            $this->redirect('/../good');
        }
    }

    public function reservesAction()
    {

        $user_id = $this->request->getSession('id');
        $login = $this->request->getSession('login');
        $reserves = (new BookingRepository())->getSomeAll('user_id', $user_id);
        $result = [];
        foreach ($reserves as $reserve) {
            $goods = json_decode($reserve->items, true);
            $result[] = [
                'id' => $reserve->id,
                'goods' => $goods
            ];
        }
        $params = [
            'login' => $login,
            'reserves' => $result
        ];

//todo в разработке: адиминистратор должен иметь возможность
// смотреть и редактировать все заказы

//        if ($login == 'admin') {
//            $users = (new UserRepository())->getColumn('login');
//            foreach ($users as $user) {
//                if($user['id']==$this->request->getParams('post')['select']){
//                    $user['selected'] = true;
//                }
//            }
//            $params['users'] = $users;
//
//        }


        echo $this->render('reserves', $params);
    }
}