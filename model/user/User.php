<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 0:31
 */

namespace model\user;


use model\department\Department;
use model\Model;

class User extends Model
{
    protected $mainTable = 'user';

    /**
     * @param string $firstName
     * @param string $surname
     * @param string $fatherName
     * @param string $login
     * @param string $password
     * @param Department $department
     * @param int $type
     * @return int
     * @author Borys Plotka ( @3plo )
     */
    public function addUser(
        string $firstName,
        string $surname,
        string $fatherName,
        string $login,
        string $password,
        Department $department,
        int $type
    ): int
    {
        $this->id = $this->addRow(
            $this->mainTable,
            [
                'first_name' => $firstName,
                'surname' => $surname,
                'father_name' => $fatherName,
                'login' => $login,
                'password' => md5($password),
                'department' => $department->getId(),
                'type' => $type,
            ]
        );
        return $this->id;
    }

    /**
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getUserData(): array
    {
        return $this->getCurrentTableRow();
    }

    /**
     * @param string $login
     * @param string $password
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getUserDataForLogin(string $login, string $password): array
    {
        $userData = $this->getUserDataByLogin($login);
        return md5($password) == $userData['password'] ? $userData : [];
    }

    /**
     * @param string $login
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getUserDataByLogin(string $login): array
    {
        return $this->getSingleRowByParamsList(
            [
                'login' => $login,
            ]
        );
    }

    /**
     * @param int $ts
     * @param int $r
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function getUserToken(int $ts = TIMESTAMP, int $r = 0): array
    {
        $userData = $this->getUserData();
        $salt = md5($r = $r ? $r : rand(1, $ts));
        return
            [
                'token' => sha1($userData['login'] . ' ' . $userData['password'] . $ts . $salt),
                'ts' => $ts,
                'r' => $r,
            ];
    }

    /**
     * @param int $ts
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public function tempHash(int $ts = TIMESTAMP): array
    {
        $userData = $this->getUserData();
        return [
            'token' => sha1($userData['login'] . ' ' . $userData['password'] . $ts),
            'ts' => $ts,
        ];
    }

    /**
     * @param string $firstName
     * @param string $surname
     * @param string $fatherName
     * @param string $login
     * @param string $password
     * @param Department $department
     * @param int $type
     * @param array $ignoreUserList
     * @return bool
     * @author Borys Plotka ( @3plo )
     */
    public function checkUserData(
        string $firstName,
        string $surname,
        string $fatherName,
        string $login,
        string $password,
        Department $department,
        int $type,
        array $ignoreUserList = []
    ): bool
    {
        return $this->isLoginUnique($login);
    }

    /**
     * @param string $login
     * @param array $ignoreUserList
     * @return bool
     * @author Borys Plotka ( @3plo )
     */
    protected function isLoginUnique(string $login, array $ignoreUserList = []): bool
    {
        $userData = $this->getUserDataByLogin($login);
        return empty($userData) || in_array($userData['id'], $ignoreUserList);
    }
}