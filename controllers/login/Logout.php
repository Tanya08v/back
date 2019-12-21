<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 11:24
 */

namespace controllers\login;


use controllers\MainController;
use model\user\enum\UserTypeEnum;

class Logout extends MainController
{
    public function handleAction(array $params)
    {
        switch ($_SESSION['user_type']) {
            case UserTypeEnum::ACCESS_POINT :
                $path = '/login/access_point';
                break;
            case UserTypeEnum::STUDENT :
                $path = '/login/student';
                break;
            default :
                $path = '/login/student';
        }
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        $this->redirect($path);
    }
}