<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 05.12.2019
 * Time: 21:41
 */

namespace model\user;


use model\Model;
use model\user\enum\UserTypeEnum;

class Guest extends Model
{
    protected $mainTable = 'user_guest';

    /**
     * @return int
     * @author Borys Plotka ( @3plo )
     */
    public static function getGuestUserType(): int
    {
        return UserTypeEnum::GUEST;
    }

    /**
     * @param User $user
     * @param int $guestOwnerId
     * @return int
     * @author Borys Plotka ( @3plo )
     */
    public function addGuest(
        User $user,
        int $guestOwnerId
    ): int
    {
        $this->id = $this->addRow(
            $this->mainTable,
            [
                'guest_user_id' => $user->getId(),
                'guest_owner_id' => $guestOwnerId,
            ]
        );
        return $this->id;
    }

    /**
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getGuestData(): array
    {
        return $this->getCurrentTableRow();
    }

    /**
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getGuestUserData(): array
    {
        return $this->getSingleRowByQuery("
            SELECT
              u.*
            FROM
              $this->mainTable AS g
                INNER JOIN
              user AS u
                ON (g.guest_user_id = u.id)
            WHERE
              g.id = $this->id
        ");
    }

    /**
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getGuestAccessList(): array
    {
        return $this->getListByQuery("
            SELECT
              r.*
            FROM
              $this->mainTable AS g
                INNER JOIN
              access_rules AS r
                ON (g.guest_user_id = r.user_id)
            WHERE
              g.id = $this->id
        ");
    }

    /**
     * @param int $userId
     * @param int $limit
     * @param int $offset
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getUserGuestList(int $userId, int $limit = 0, int $offset = 0): array
    {
        $limit = $limit ? " LIMIT $limit " : '';
        $offset = $offset ? " OFFSET $offset " : '';
        return $this->getListByQuery("
            SELECT
              u.*
            FROM
              $this->mainTable AS g
                INNER JOIN
              user AS u
                ON (g.guest_user_id = u.id)
            WHERE
              g.guest_owner_id = $userId
            $limit
            $offset
        ");
    }
}