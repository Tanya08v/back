<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:41
 */

namespace controllers\source_file_controllers;


use controllers\comands\SourceFilePathParseCommand;
use controllers\enums\source_file_controller_enums\FileFormatEnum;
use controllers\enums\source_file_controller_enums\SourceFileContentTypeEnum;
use controllers\MainController;

class MainSourceFileController extends MainController
{
    /**
     * @var SourceFilePathParseCommand
     */
    protected $sourceFilePathParseCommand;

    /**
     * @param array $params
     * @author Borys Plotka ( @3plo )
     */
    public function handleAction(array $params)
    {
        $this->sourceFilePathParseCommand = new SourceFilePathParseCommand($_SERVER['DOCUMENT_ROOT'] . '/static');
        $filePath = $this->sourceFilePathParseCommand->parsePath($params['path']);
//        die(var_dump(file_exists($filePath), $filePath, 'D:\OpenServer\domains\service_platform\ServicePlatform\www\static\js\jquery-3.2.1.min.js'));
        if (file_exists($filePath)) {
            $this->getSourceFile($filePath);
            die();
        } else {
            $filePath = $this->sourceFilePathParseCommand->parsePath($this->mapSourceFilePath($params['path']));
            if (file_exists($filePath)) {
                $this->getSourceFile($filePath);
            } else {
                // TODO handle file Not Exsist exception
            }
        }
    }

    /**
     * @param $path
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    protected function mapSourceFilePath($path) : string
    {
        $result = '';
        // TODO create Mapper
        if ($path = '/favicon.ico') {
            $result = '/img/favicon.ico';
        }
        return $result;
    }

    /**
     * @param string $filePath
     * @param string $sourcerType
     * @author Borys Plotka ( @3plo )
     */
    protected function getSourceFile(string $filePath, string $sourcerType = SourceFileContentTypeEnum::DEFAULT)
    {
        if (!$this->getContentTypeTitle($sourcerType)) {
            // TODO handle default content type
        }
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $this->getContentTypeTitle($this->getSourceFileFormat($filePath)));
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
    }

    /**
     * @param string $contentType
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    protected function getContentTypeTitle(string $contentType) : string
    {
        // TODO create Mapper
        $result = '';
        switch ($contentType) {
            case SourceFileContentTypeEnum::CSS :
                $result = 'text/css';
                break;
            case SourceFileContentTypeEnum::JAVA_SCRIPT :
                $result = 'application/javascript';
                break;
            case SourceFileContentTypeEnum::IMAGE_JPEG :
                $result = 'image/jpeg';
                break;
            case SourceFileContentTypeEnum::IMAGE_PNG :
                $result = 'image/png';
                break;
            case SourceFileContentTypeEnum::IMAGE_ICO :
                $result = 'image/ico';
                break;
            default :
                // TODO create default content-type
                $result = '';
        }
        return $result;
    }

    /**
     * @param string $path
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    protected function getSourceFileFormat(string $path) : string
    {
        $fileFormat = substr($path, strripos($path, '.') + 1, strlen($path) - 1);
        $result = '';
        switch ($fileFormat) {
            case FileFormatEnum::CSS :
                $result = SourceFileContentTypeEnum::CSS;
                break;
            case FileFormatEnum::JAVA_SCRIPT :
                $result = SourceFileContentTypeEnum::JAVA_SCRIPT;
                break;
            case FileFormatEnum::IMAGE_JPEG :
                $result = SourceFileContentTypeEnum::IMAGE_JPEG;
                break;
            case FileFormatEnum::IMAGE_PNG :
                $result = SourceFileContentTypeEnum::IMAGE_PNG;
                break;
            case FileFormatEnum::IMAGE_ICO :
                $result = SourceFileContentTypeEnum::IMAGE_ICO;
                break;
            default :
                // TODO create default content-type
        }
        return $result;
    }
}