<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 01.12.2019
 * Time: 15:21
 */

namespace application\helpers;


class OperationHelper
{
    /**
     * @param array $data
     * @param string $rowGlue
     * @param string $keyValueGlue
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    public static function implodeWithKeys(array $data, string $rowGlue = '', string $keyValueGlue = ''): string
    {
        $result = '';
        foreach ($data as $title => $value) {
            $result .= $title . $keyValueGlue . $value . $rowGlue;
        }
        $result = substr($result, 0, -strlen($rowGlue));
        return $result;
    }

    /**
     * @param array $data
     * @param string $columnTitle
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public static function extractColumnValuesFromArray(array $data, string $columnTitle): array
    {
        $result = [];
        foreach ($data as $row) {
            $result[] = $row[$columnTitle];
        }
        return $result;
    }

    /**
     * @param array $data
     * @param string $columnTitle
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    public static function extractColumnDistinctValuesFromArray(array $data, string $columnTitle): array
    {
        $result = [];
        foreach ($data as $row) {
            $result[$row[$columnTitle]] = $row[$columnTitle];
        }
        return $result;
    }
}