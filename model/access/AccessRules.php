<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 0:57
 */

namespace model\access;


use model\Model;
use model\user\User;

class AccessRules extends Model
{
    const TOKEN_TTL = 10 * 60;

    protected $mainTable = 'access_rules';

    /**
     * @param User $user
     * @param string $dateFrom
     * @param string $dateTill
     * @param string $timeFrom
     * @param string $timeTill
     * @param AccessPoint $accessPoint
     * @return int
     * @author Borys Plotka ( @3plo )
     */
    public function addRule(
        User $user,
        string $dateFrom,
        string $dateTill,
        string $timeFrom,
        string $timeTill,
        AccessPoint $accessPoint
    ): int
    {
        $this->id = $this->addRow(
            $this->mainTable,
            [
                'user_id' => $user->getId(),
                'date_valid_from' => $dateFrom,
                'date_valid_till' => $dateTill,
                'time_valid_from' => $timeFrom,
                'time_valid_till' => $timeTill,
                'access_point_id' => $accessPoint->getId(),
            ]
        );
        return $this->id;
    }

    /**
     * @param int $userId
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getUserAccessRules(int $userId): array
    {
        return $this->getListByParamsList(
            [
                'user_id' => $userId,
                'status' => Model::ACCOUNT_ALIVE,
            ]
        );
    }

    /**
     * @param int $userId
     * @param int $accessPointId
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getUserAccessRulesByAccessPoint(int $userId, int $accessPointId): array
    {
        return $this->getSingleRowByParamsList(
            [
                'user_id' => $userId,
                'access_point_id' => $accessPointId,
                'status' => Model::ACCOUNT_ALIVE,
            ]
        );
    }

    /**
     * @param int $userId
     * @param int $accessPointId
     * @param string $token
     * @param int $ts
     * @param int $r
     * @param int $accessTypeId
     * @return bool
     * @author Borys Plotka ( @3plo )
     */
    public function hasUserAccess(int $userId, int $accessPointId, string $token, int $ts, int $r, int $accessTypeId): bool
    {
        $result = false;
        if ($accessRule = $this->getUserAccessRulesByAccessPoint($userId, $accessPointId)) {//has access rule
            if ($accessRule['date_valid_from'] <= DATE && $accessRule['date_valid_till'] >= DATE) {//has access for current date
                if ($accessRule['time_valid_from'] <= TIME && $accessRule['time_valid_till'] >= TIME) {//has access for current time
                    $user = new User();
                    $user->setId($userId);
                    $tokenData = $user->getUserToken($ts, $r);
                    if ($tokenData['token'] === $token) {//is token valid
                        $result = TIME - $tokenData['ts'] <= self::TOKEN_TTL;//is token not expired
                        $accessLog = new AccessLog();
                        $accessLogRow = $accessLog->getLastAccessLogItem($userId, $accessPointId);
                        if ($result && !empty($accessLogRow) && $accessLogRow['type_id'] == $accessTypeId) {
                            $result = false;//deny for double entrance
                        }
                    }
                }
            }
        }
        if ($result) {
            $accessLog = new AccessLog();
            $accessLog->checkIn($userId, $accessPointId, $accessTypeId);
        }
        return $result;
    }

    /**
     * @param int $userId
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getUserRulesWithAllData(int $userId): array
    {
        return $this->getListByQuery("
            SELECT
              r.id,
              r.date_valid_from,
              r.date_valid_till,
              r.time_valid_from,
              r.time_valid_till,
              u.first_name,
              u.surname,
              u.father_name,
              p.title AS access_point
            FROM
              $this->mainTable AS r
                INNER JOIN
              user AS u
                ON (r.user_id = u.id)
                INNER JOIN
              access_point AS p 
                ON (r.access_point_id = p.id)
            WHERE
              r.status = " . Model::ACCOUNT_ALIVE . " AND
              r.user_id = $userId
        ");
    }
}