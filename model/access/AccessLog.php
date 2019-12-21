<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 0:42
 */

namespace model\access;


use model\Model;

class AccessLog extends Model
{
    protected $mainTable = 'access_log';

    /**
     * @param int $userId
     * @param int $accessPointId
     * @param int $typeId
     * @return int
     * @author Borys Plotka ( @3plo )
     */
    public function checkIn(int $userId, int $accessPointId, int $typeId): int
    {
        $this->id = $this->addRow(
            $this->mainTable,
            [
                'user_id' => $userId,
                'access_point_id' => $accessPointId,
                'type_id' => $typeId,
            ]
        );
        return $this->id;
    }

    /**
     * @param int $userId
     * @param int $accessPointId
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getLastAccessLogItem(int $userId, int $accessPointId): array
    {
        return $this->getSingleRowByQuery("
            SELECT
              *
            FROM
              $this->mainTable
            WHERE
              user_id = $userId AND
              access_point_id = $accessPointId
            ORDER BY
              created_at DESC
            LIMIT 1
        ");
    }

    /**
     * @param int $accessPointId
     * @param int $limit
     * @param int $offset
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getAccessPointLog(int $accessPointId, int $limit = 0, int $offset = 0): array
    {
        return $this->getListByParamsList(
            [
                'access_point_id' => $accessPointId,
            ],
            'id',
            false,
            $limit,
            $offset
        );
    }

    /**
     * @param int $accessPointId
     * @param int $limit
     * @param int $offset
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getAccessPointLogWithUserData(int $accessPointId, int $limit = 0, int $offset = 0): array
    {
        $limit = $limit ? " LIMIT $limit " : '';
        $offset = $offset ? " OFFSET $offset " : '';
        return $this->getListByQuery("
            SELECT
              l.id,
              u.first_name,
              u.father_name,
              u.surname,
              u.type,
              l.type_id AS access_type,
              l.created_at
            FROM
              $this->mainTable AS l
                INNER JOIN
              user AS u
                ON (l.user_id = u.id)
            WHERE
              l.access_point_id = $accessPointId
            ORDER BY 
              l.id DESC
            $limit
            $offset
        ");
    }
}