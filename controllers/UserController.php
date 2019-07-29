<?php

namespace App\controllers;

use App\models\entities\User;
use App\models\repositories\UserRepository;

class UserController extends Controller
{
    protected $defaultAction = 'users';

    public function userAction()
    {
        $id = $this->getId();
        $params = [
            'user' => (new UserRepository())->getDataOne($id)
        ];
        echo $this->render('user', $params);
    }

    public function usersAction()
    {
        $params = [
            'users' => (new UserRepository())->getAll()
        ];

        echo $this->render('users', $params);
    }

    public function deleteAction()
    {
        $id = (int)$_GET['id'];
        $userRepository = new UserRepository();
        $user = $userRepository->getOne($id);
        $userRepository->delete($user);
        header('Location: /user/users');
    }

    public function insertAction()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User();
            $userRepository = new UserRepository();
            $properties = $user->getProperties();
            foreach ($properties as $prop) {
                $user->$prop = $_POST[$prop];
            }
            $userRepository->insert($user);
            header('Location: /user/users');
            exit;
        }
        echo $this->render('userInsert', ['properties' => (new User())->getProperties()]);
    }

    public function updateAction()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = (int)$_GET['id'];
            $user = new User();
            foreach ($user as $prop => $value) {
                $user->$prop = $_POST[$prop];
            }
            $userRepository = new UserRepository();
            $user->id = $id;
            $userRepository->update($user);
            header('Location: /user/users');
            exit;
        }
    }
}