<?php

namespace App\controllers;

use App\models\repositories\GoodRepository;

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
        $goods = $this->request->getSession(self::GOODS);
        $id = $this->getId();

        if (empty($id)) {
            return $this->redirect();
        }
        $good = (new GoodRepository())->getOne($id);
        if (empty($good)) {
            return $this->redirect();
        }
        if ($goods[$id]['count'] > 1) {
            $goods[$id]['count']--;
        } else {
            unset($goods[$id]);
        }

        $this->request->setSession(self::GOODS, $goods);
        return $this->redirect();
    }

    public function saveBasketAction()
    {
        $goods = $this->request->getSession(self::GOODS);
        foreach ($goods as $id => $good) {
            var_dump($id);
            var_dump($good);
        }
//        App::call()->basketRepository->save($goods);
    }

    public function indexAction()
    {
        $params = [
            'userId' => $_SESSION['user'],
            'goods' => $_SESSION['goods']
        ];
        echo $this->render('basket', $params);
    }
}