<?php

namespace App\controllers;

use App\main\App;
use App\models\entities\User;
use App\models\repositories\UserRepository;

class UserController extends Controller
{
    protected $defaultAction = 'user';

    public function userAction()
    {
        $this->request->getSession('login') ?: $this->redirect();

        $id = $this->getId();
        if (!$id) {
            $id = $_SESSION['id'];
        }
        $date = '20019-12-12';
        $user = App::call()->userRepository->getOne($id);
        $params = [
            'date' => $date,
            'user' => $user,
            'auth_user' => $_SESSION['login']
        ];

        echo $this->render('user', $params);
    }

    public function usersAction()
    {
        $this->request->getSession('is_admin') ?: $this->redirect();

        $params = [
            'users' => App::call()->userRepository->getAll()
        ];

        echo $this->render('users', $params);
    }

    public
    function deleteAction()
    {
        $this->request->getSession('is_admin') ?: $this->redirect();

        $id = $this->getId();
        $user = App::call()->userRepository->getOne($id);
        App::call()->userRepository->delete($user);
        return $this->redirect();
    }

    public
    function insertAction()
    {
        $this->request->getSession('is_admin') ?: $this->redirect();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User();
            $user->fio = $_POST['fio'];
            $user->login = $_POST['login'];
            $user->password = $_POST['password'];
            App::call()->userRepository->save($user);
            return $this->redirect();
        }
        echo $this->render('userInsert', []);
    }

    /**
     * аутентификация
     * @param $user_login
     * @param $user_password
     * @return mixed|string
     */
    public
    function checkAuth($user_login, $user_password)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = App::call()->userRepository->getSome('login', $user_login);
            if ($user && $user->password == $user_password) {
                $this->request->setSession('id', $user->id);
                $this->request->setSession('login', $user->login);
                $this->request->setSession('fio', $user->fio);
                $this->request->setSession('is_admin', $user->is_admin);
                $params = [
                    'user' => $user
                ];
            } else {
                $params = [];
            }
            echo $this->render('user', $params);
        }
    }

    /**
     * авторизация
     */
    public function authAction()
    {
        if ($_POST['login']) {
            $this->checkAuth($_POST['login'], $_POST['password']);
        } else {
            echo $this->render('formAuth', []);
        }
    }

    /**
     * разлогинивание
     */
    public function exitAction()
    {
        $this->request->removeSession();
        echo $this->render('user', []);
    }

    public function bookingAction()
    {
        var_dump(App::call()->bookingRepository->getAllBooking(1));
    }
}