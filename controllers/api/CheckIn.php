<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 0:52
 */

namespace controllers\api;


use controllers\MainController;
use model\access\AccessRules;
use model\user\User;

class CheckIn extends MainController
{
    public function handleAction(array $params)
    {
        $token = $params['request']['token'];
        $login = $params['request']['l'];
        $ts = $params['request']['ts'];
        $r = $params['request']['r'];
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
        echo json_encode(
            [
                'status' => 200,
                'has_access' => $hasAccess,
            ]
        );
    }
}