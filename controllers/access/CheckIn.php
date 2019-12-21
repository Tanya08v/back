<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 1:17
 */

namespace controllers\access;


use controllers\MainController;
use model\access\AccessRules;
use model\user\User;

class CheckIn extends MainController
{
    public function handleAction(array $params)
    {
        $this->checkLogin();
        $tokenData = explode('_amp;', $params['request']['token']);
        $token = $tokenData[0];
        $ts = $tokenData[1];
        $r = $tokenData[2];
        $login = $tokenData[3];
        $user = new User();
        $userData = $user->getUserDataByLogin($login);
        $hasAccess = false;
        if ($userData) {
            $accessRules = new AccessRules();
            $hasAccess = $accessRules->hasUserAccess(
                $userData['id'],
                $_SESSION['access_point_id'],
                $token,
                $ts,
                $r,
                $_SESSION['access_type']
            );
        }
        $this->getRender()->rend('access/result.twig', [
            'has_access' => $hasAccess ? 1 : 0,
            'user' => $userData,
        ]);
    }
}