<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 19:16
 */

namespace controllers\access;


use controllers\MainController;

class Type extends MainController
{
    public function handleAction(array $params)
    {
        $this->checkLogin();
        if ($_SESSION['access_point_id']) {
            $_SESSION['access_type'] = $params['request']['type'];
        }
        $this->redirect('home/home');
    }

}