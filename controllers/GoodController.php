<?php

namespace App\controllers;

use App\models\entities\good;
use App\models\repositories\goodRepository;

class GoodController extends Controller
{
    protected $defaultAction = 'goods';

    public function goodAction()
    {
        $id = $this->getId();
        $params = [
            'good' => (new goodRepository())->getDataOne($id)
        ];
        echo $this->render('good', $params);
    }

    public function goodsAction()
    {
        $params = [
            'goods' => (new goodRepository())->getAll()
        ];

        echo $this->render('goods', $params);
    }

    public function deleteAction()
    {
        $id = (int)$_GET['id'];
        $goodRepository = new goodRepository();
        $good = $goodRepository->getOne($id);
        $goodRepository->delete($good);
        header('Location: /good/goods');
    }

    public function insertAction()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $good = new good();
            $goodRepository = new goodRepository();
            $properties = $good->getProperties();
            foreach ($properties as $prop) {
                $good->$prop = $_POST[$prop];
            }
            $goodRepository->insert($good);
            header('Location: /good/goods');
            exit;
        }
        echo $this->render('goodInsert', ['properties' => (new good())->getProperties()]);
    }

    public function updateAction()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = (int)$_GET['id'];
            $good = new good();
            foreach ($good as $prop => $value) {
                $good->$prop = $_POST[$prop];
            }
            $goodRepository = new goodRepository();
            $good->id = $id;
            $goodRepository->update($good);
            header('Location: /good/goods');
            exit;
        }
    }
}