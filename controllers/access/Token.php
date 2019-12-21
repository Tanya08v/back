<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 14:10
 */

namespace controllers\access;


use application\helpers\OperationHelper;
use controllers\MainController;
use model\user\User;

class Token extends MainController
{
    public function handleAction(array $params)
    {
        $this->checkLogin();
        $user = new User();
        $user->setId($_SESSION['user_id']);
        $tokenData = $user->getUserToken();
        $userData = $user->getUserData();
        $this->getRender()->rend('access/token.twig', [
            'link' =>
                'http://' .
                $_SERVER['SERVER_NAME'] .
                '/access/check_in?token=' .
                implode('_amp;', [
                    'token' => $tokenData['token'],
                    'ts' => $tokenData['ts'],
                    'r' => $tokenData['r'],
                    'l' => $userData['login'],
                ]),
//                OperationHelper::implodeWithKeys(
//                    [
//                        'token' => $tokenData['token'],
//                        'ts' => $tokenData['ts'],
//                        'r' => $tokenData['r'],
//                        'l' => $userData['login'],
//                    ],
//                    '&&&;',
//                    '='
//                ),
        ]);
    }
}