<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 13:39
 */

namespace controllers\login;


use controllers\MainController;

class Student extends MainController
{
    public function handleAction(array $params)
    {
        $this->getRender()->rend('login/student.twig');
    }
}