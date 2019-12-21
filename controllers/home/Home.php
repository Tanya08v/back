<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 11:54
 */

namespace controllers\home;


use controllers\MainController;
use model\access\AccessRules;
use model\user\User;

class Home extends MainController
{
    public function handleAction(array $params)
    {
        $this->checkLogin();
        $user = new User();
        $user->setId($_SESSION['user_id']);
        $accessRules = new AccessRules();
        $this->getRender()->rend(
            'home/home.twig',
            [
                'user' => $user->getUserData(),
                'rules' => $accessRules->getUserRulesWithAllData($_SESSION['user_id']),
            ]
        );
    }

}