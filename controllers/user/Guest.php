<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 06.12.2019
 * Time: 22:44
 */

namespace controllers\user;


use application\helpers\OperationHelper;
use controllers\MainController;
use model\access\AccessPoint;
use model\access\AccessRules;
use model\department\Department;
use model\user\User;
use model\user\Guest AS GuestModel;

class Guest extends MainController
{
    public function handleAction(array $params)
    {
        $this->checkLogin();
        //TODO check access to create guest
        $user = new User();
        $guest = new GuestModel();
        if ($params['request']['add_guest']) {
            $accessRules = new AccessRules();
            if ($user->checkUserData(
                $params['request']['first_name'],
                $params['request']['surname'],
                $params['request']['father_name'],
                $params['request']['login'],
                $params['request']['password'],
                $department = new Department(),
                $guest::getGuestUserType()
            )){
                $user->addUser(
                    $params['request']['first_name'],
                    $params['request']['surname'],
                    $params['request']['father_name'],
                    $params['request']['login'],
                    $params['request']['password'],
                    $department,
                    $guest::getGuestUserType()
                );
                $guest->addGuest(
                    $user,
                    $_SESSION['user_id']
                );
                $accessRules->addRule(
                    $user,
                    $params['request']['date'],
                    $params['request']['date'],
                    '08:00:00',
                    '23:00:00',
                    (new AccessPoint())->setId($params['request']['access_point'])
                );
                $this->redirect('/user/guest_list');
            }
            $this->redirect('/user/guest');
        } else {
            $data = [];
            if ($id = $params['request']['id']) {
                $guest->setId($id);
                $data['guest'] = $guest->getGuestUserData();
                $data['access'] = array_shift($guest->getGuestAccessList());
            }
            $accessRules = new AccessRules();
            $data['access'] = $accessRules->getUserAccessRules($_SESSION['user_id']);
            $data['userPoints'] = OperationHelper::extractColumnDistinctValuesFromArray(
                $data['access'],
                'access_point_id'
            );
            $accessPoint = new AccessPoint();
            $data['points'] = $accessPoint->getAccessPointList();
            $this->getRender()->rend('/user/guest.twig', $data);
        }
    }

}