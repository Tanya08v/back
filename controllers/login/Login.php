<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 11:02
 */

namespace controllers\login;


use controllers\MainController;
use model\access\enum\AccessTypeEnum;
use model\user\enum\UserTypeEnum;
use model\user\User;

class Login extends MainController
{
    public function handleAction(array $params)
    {
        $user = new User();
        $userData = $user->getUserDataForLogin($params['request']['login'], $params['request']['password']);
        if ($userData) {
            switch ($userData['type']) {
                case UserTypeEnum::ACCESS_POINT :
                    $_SESSION['user_id'] = $userData['id'];
                    $_SESSION['access_point_id'] = $params['request']['access_point'];
                    $_SESSION['user_type'] = $userData['type'];
                    $_SESSION['access_type'] = AccessTypeEnum::IN;
                    $this->redirect('access/access_point_log');
                    break;
                case UserTypeEnum::PERSONAL :
                case UserTypeEnum::STUDENT :
                case UserTypeEnum::GUEST :
                    $_SESSION['user_id'] = $userData['id'];
                    $_SESSION['user_type'] = $userData['type'];
                    $this->redirect('home/home');
                    break;
            }
        } else {
//            throw new \Exception();
            $this->redirect('/login/logout');//TODO create default login view
        }
    }
}