<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 06.12.2019
 * Time: 23:11
 */

namespace controllers\user;


use controllers\MainController;
use model\user\Guest AS GuestModel;

class GuestList extends MainController
{
    public function handleAction(array $params)
    {
        $this->checkLogin();
        $page = $params['request']['p'] ?? 1;
        $paginationStep = 100;
        $guest = new GuestModel();
        $this->getRender()->rend('user/guest_list.twig', [
            'guests' => $guest->getUserGuestList(
                $_SESSION['user_id'],
                $paginationStep,
                ($page - 1) * $paginationStep
            ),
            'since' => ($page - 1) * $paginationStep + 1,
            'page' => $page,
        ]);
    }
}