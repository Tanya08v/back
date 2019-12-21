<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 07.12.2019
 * Time: 11:45
 */

namespace model\mapper\user;


use model\mapper\Mapper;
use model\user\enum\UserTypeEnum;

class UserTypeMapper extends Mapper
{
    protected $generalMap = [
        UserTypeEnum::STUDENT => 'Student',
        UserTypeEnum::ACCESS_POINT => 'Access point',
        UserTypeEnum::PERSONAL => 'Personal',
        UserTypeEnum::GUEST => 'Guest',
    ];
}