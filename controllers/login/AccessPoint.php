<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 1:36
 */

namespace controllers\login;


use controllers\MainController;
use model\access\AccessPoint as ModelAccessPoint;

class AccessPoint extends MainController
{
    public function handleAction(array $params)
    {
        if ($_SESSION['user_id']) {
            $this->redirect('access/access_point_log');
        } else {
            $accessPoint = new ModelAccessPoint();
            $this->getRender()->rend(
                'login/access_point.twig',
                [
                    'points' => $accessPoint->getAccessPointList(),
                ]
            );
        }
    }
}