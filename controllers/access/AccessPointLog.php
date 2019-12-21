<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 11:24
 */

namespace controllers\access;


use controllers\MainController;
use model\access\AccessLog;

class AccessPointLog extends MainController
{
    public function handleAction(array $params)
    {
        $this->checkLogin();
        $accessLog = new AccessLog();
        $page = $params['request']['p'] ?? 1;
        $paginationStep = 100;
        $this->getRender()->rend('access/log.twig',
            [
                'items' => $accessLog->getAccessPointLogWithUserData(
                    $_SESSION['access_point_id'],
                    $paginationStep,
                    ($page - 1) * $paginationStep
                ),
                'since' => ($page - 1) * $paginationStep + 1,
                'page' => $page,
            ]
        );
    }

}