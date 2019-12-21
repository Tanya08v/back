<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:26
 */

namespace controllers\comands;


class SourceFilePathParseCommand
{
    /**
     * @var string
     */
    private $sourceFilesDir;

    /**
     * SourceFilePathParseCommand constructor.
     * @param string $sourceFilesDir
     * @author Borys Plotka ( @3plo )
     */
    public function __construct(string $sourceFilesDir)
    {
        $this->sourceFilesDir = $sourceFilesDir;
    }

    /**
     * @param string $path
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    public function parsePath(string $path) : string
    {
        return $this->sourceFilesDir . $path;
    }
}