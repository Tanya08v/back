<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 07.12.2019
 * Time: 11:42
 */

namespace model\mapper;


abstract class Mapper
{
    /**
     * @var array
     */
    protected $generalMap = [];

    /**
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    final public function getMap(): array
    {
        return $this->generalMap;
    }

    /**
     * @param int $type
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    final public function getMapValue(int $type): string
    {
        return $this->generalMap['$type'] ?? '';
    }

    /**
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    final public function getMapKeys(): array
    {
        return array_keys($this->generalMap);
    }
}