<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 0:11
 */

namespace model;


use application\registers\DBConfigRegister;

abstract class Model
{
    const ACCOUNT_ALIVE = 0;
    const ACCOUNT_DELETED = 1;

    /**
     * @var \PDO
     */
    protected static $connection;

    /**
     * @author Borys Plotka ( @3plo )
     */
    public static function init()
    {
        if (!isset(self::$connection)) {
            $register = DBConfigRegister::getInstance();
            $config = $register->getConfig()->getConfig();
            self::$connection = new \PDO(
                $config['driver'] . ':dbname=' . $config['name'] . ';host=' . $config['host'],
                $config['user'],
                $config['password']
            );
        }
    }

    /**
     * @var string
     */
    protected $idColumnTitle = 'id';

    /**
     * @var int
     */
    protected $id = 0;

    /**
     * @var string
     */
    protected $mainTable = '';

    /**
     * @param $id
     * @return $this
     * @author Borys Plotka ( @3plo )
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     * @author Borys Plotka ( @3plo )
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $query
     * @return array
     */
    protected function getSingleRowByQuery(string $query) : array
    {
        $query = str_replace("'NOW()'", DATE, $query);
        $result = self::$connection->query($query);
        $result->setFetchMode(\PDO::FETCH_ASSOC);
        $date = $result->fetchAll();

        foreach ($date as $value) {
            $data = $value;
        }
        return $data ?? [];
    }

    /**
     * @param string $query
     * @param string $paramTitle
     * @return string
     */
    protected function getSingleValueByQuery(string $query, string $paramTitle = 'id') : string
    {
        return $this->getSingleRowByQuery($query)[$paramTitle] ?? '';
    }

    /**
     * @param string $query
     * @param string $idTitle
     * @return array
     */
    protected function getListByQuery(string $query, string $idTitle = 'id') : array
    {
        $result = self::$connection->query($query);
        $result->setFetchMode(\PDO::FETCH_ASSOC);
        $date = $result->fetchAll();
        foreach ($date as $value) {
            $data[$value[$idTitle]] = $value;
        }
        return $data ?? [];
    }

    /**
     * @param string $query
     * @param string $columnTitle
     * @return array
     */
    protected function getListSingleColumnByQuery(string $query, string $columnTitle = 'id') : array
    {
        $data = $this->getListByQuery($query);
        $result = [];
        foreach ($data as $id => $row) {
            array_push($result, $row[$columnTitle]);
        }
        return $result;
    }

    /**
     * @param string $tableName //Название таблицы в базе
     * @param array $valueList //Список значений
     * Длина $paramList и $valueList должны совпадать
     * @return int //ИД что был добавлен 0 в  случае неудачи
     */
    protected function addRow(string $tableName, array $valueList) : int
    {
        $valueList = $this->validateParams($valueList);
        $params = implode(', ', array_keys($valueList));
        $values = "'" . implode("', '", $valueList) . "'";
        $query = "INSERT INTO $tableName($params) VALUES ($values);";
        $result = self::$connection->query($query);
        $id = self::$connection->lastInsertId($tableName);
        return (int)($id ?? 0);
    }

    /**
     * @param array $data
     * @return array
     */
    protected function escapeDataList(array $data) : array
    {
        foreach ($data as &$item) {
            $item = str_replace("'", "`", $item);
        }
        return $data;
    }

    /**
     * @param string $tableName
     * @param $id
     * @param bool $force
     * @return bool
     */
    protected function deleteRow(string $tableName, $id, $force = false) : bool
    {
        $result = false;
        if ($id) {
            if ($force) {
                self::$connection->query("DELETE FROM $tableName WHERE id = $id");
            } else {
                $delete = self::ACCOUNT_DELETED;
                self::$connection->query("UPDATE $tableName SET deleted = $delete WHERE id = $id");
            }
            $result = true;
        }
        return $result;
    }

    /**
     * @param string $tableName //Название таблицы в базе
     * @param array $valueList //Двухмерный асоциативный массив
     * (первый уровень ид не важен, второй уровень ид - навание столбца в базе, значение - значение для вставки в базу)
     * @return bool
     */
    protected function addMultiRow(string $tableName, array $valueList) : bool
    {
        $valueListToQuery = [];
        $params = [];
        foreach ($valueList as $id => $row) {
            $row = $this->validateParams($row);
            if (!$params) {
                $params = "`" . implode("`, `", array_keys($row)) . "`";
            }
            $valueListToQuery[] = "('" . implode("', '", $row) . "')";
        }
        $result = false;
        if ($params) {
            $query = "INSERT INTO $tableName($params) VALUES " . implode(', ', $valueListToQuery) . ";";
            $result = self::$connection->query($query);
        }
        return $result !== false;
    }

    /**
     * @param string $tableName
     * @param $id
     * @param array $params
     * @param string $idTitle
     * @return bool
     */
    protected function updateRow(string $tableName, $id, array $params, string $idTitle = 'id') : bool
    {
        $result = false;
        $currentRow = $this->getSingleRowByQuery("SELECT * FROM $tableName WHERE $idTitle = '$id'");
        $paramsString = '';
        $params = $this->validateParams($params);
        foreach ($params as $title => $value) {
            if (array_key_exists($title, $currentRow)) {
                if ($paramsString) {
                    $paramsString .= ', ';
                }
                $paramsString .= "$title = '$value'";
            }
        }

        if ($id && $paramsString) {
            self::$connection->query("UPDATE $tableName SET $paramsString WHERE $idTitle = '$id'");
            $result = true;
        }
        return $result;
    }

    /**
     * @param string $idTitle
     * @return array
     */
    protected function getTableRowById(
        string $idTitle = 'id'
    ): array
    {
        return $this->getSingleRowByQuery("
            SELECT
              *
            FROM
              $this->mainTable
            WHERE
              $idTitle = $this->id
        ");
    }

    /**
     * @param string $idTitle
     * @return array
     */
    protected function getCurrentTableRow(string $idTitle = 'id'): array
    {
        return $this->getSingleRowByQuery("
            SELECT
              *
            FROM
              $this->mainTable
            WHERE
              $idTitle = '$this->id'
        ");
    }

    /**
     * @param array $paramsList
     * @param string $idTitle
     * @param bool $ascSort
     * @param int $limitAmount
     * @param int $offsetAmount
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    protected function getListByParamsList(
        array $paramsList = [],
        string $idTitle = 'id',
        bool $ascSort = true,
        int $limitAmount = 0,
        int $offsetAmount = 0
    ): array
    {
        $where = '';
        foreach ($paramsList as $id => $value) {
            $where .= $where ? ' AND ' : ' ';
            $value = strlen($value) == strlen((float)$value ) && (float)$value == $value ? $value : "'$value'";
            $where .= " $id = $value";
        }
        $where = $where ? ' WHERE ' . $where : '';
        $orderBy = " ORDER BY $idTitle " .
            ($ascSort ? ' ASC ' : ' DESC ');
        $limit = $limitAmount ? " LIMIT $limitAmount " : '';
        $offset = $offsetAmount ? " OFFSET $offsetAmount " : '';
        return $this->getListByQuery("
            SELECT
              *
            FROM
              $this->mainTable
            $where
            $orderBy
            $limit
            $offset
        ", $idTitle);
    }

    /**
     * @param string $idColumnTitle
     * @return array
     */
    protected function getTableList(
        string $idColumnTitle = 'id'
    ): array
    {
        return $this->getListByQuery("
            SELECT
              *
            FROM
              $this->mainTable
        ", $idColumnTitle);
    }

    /**
     * @param array $paramsList
     * @return array
     */
    protected function getSingleRowByParamsList(array $paramsList): array
    {
        $data = $this->getListByParamsList($paramsList, $this->idColumnTitle, true, 1);
        $result = [];
        foreach ($data as $row) {
            $result = $row;
            break;
        }
        return $result;
    }

    /**
     * @param array $paramsList
     * @param string $columnTitle
     * @return string
     */
    protected function getSingleValueByParamsList(array $paramsList, string $columnTitle): string
    {
        return $this->getSingleRowByParamsList($paramsList)[$columnTitle] ?? '';
    }

    /**
     * @param array $params
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    protected function validateParams(array $params): array
    {
        $result = [];
        foreach ($params as $key => $value) {
            if (is_string($value)) {
                $result[$key] = htmlspecialchars($value);
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}