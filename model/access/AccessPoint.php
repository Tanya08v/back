<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 13:24
 */

namespace model\access;


use model\Model;

class AccessPoint extends Model
{
    protected $mainTable = 'access_point';

    /**
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getAccessPointList(): array
    {
        return $this->getListByParamsList(
            [
                'status' => Model::ACCOUNT_ALIVE
            ]
        );
    }
}