<?php
/**
 * Created by PhpStorm.
 * User: Borys Plotka ( @3plo )
 * Date: 30.11.2019
 * Time: 23:38
 */

namespace controllers\strategies;


class HTTPPathScannerStrategey implements PathScannerStrategeyInterface
{
    /**
     * @var string
     */
    private $controllerDirectory;

    /**
     * @var string
     */
    private $controllerName;

    /**
     * @param string $path
     * @author Borys Plotka ( @3plo )
     */
    public function parsePath(string $path)
    {
        $path = $this->escapePath($path);
        $path = $this->removeGetParams($path);
        $controllerPathParts = $this->separatePath($path);
        $alternativeControllerPath = $this->getAlternativeController($path);
        if (empty($alternativeControllerPath)) {
            $this->controllerDirectory = $this->parseConrollerDirectory($controllerPathParts);
            $this->controllerName = $this->parseControllerName($controllerPathParts);
        } else {
            $this->controllerDirectory = $alternativeControllerPath['dir'];
            $this->controllerName = $alternativeControllerPath['name'];
        }
    }

    /**
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    /**
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    public function getControllerDirectory(): string
    {
        return $this->controllerDirectory;
    }

    /**
     * @param array $path
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    private function parseConrollerDirectory(array $path) : string
    {
        $result = '';
        foreach ($path as $pathPart) {
            if (!next($path)) {
                break;
            }
            $result .= str_replace('-', '_', $pathPart) . '\\';
        }
        return substr($result, 1, strlen($result));
    }

    /**
     * @param string $path
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    private function separatePath(string $path) : array
    {
        return preg_split('/[^a-z0-9-_.]+/', $path);
    }

    /**
     * @param $path
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    private function removeGetParams($path) : string
    {
        $result = strstr($path, '?', true);
        return $path = $result ? $result : $path;
    }

    /**
     * @param array $controllerName
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    private function parseControllerName(array $controllerName) : string
    {
        $result = '';
        $controllerNameParts = explode('_', end($controllerName));
        foreach ($controllerNameParts as $part) {
            $result .= ucwords($part);
        }
        return $result;
    }

    /**
     * @param string $path
     * @return string
     * @author Borys Plotka ( @3plo )
     */
    private function escapePath(string $path) : string
    {
        return str_replace('..', '.', $path);
    }

    /**
     * @param string $path
     * @return array
     * @author Borys Plotka ( @3plo )
     */
    private function getAlternativeController(string $path) : array
    {
        $result = [];
        try {
            $configPath = __DIR__ . '/../rout_config.json';
            $configList = $this->getRoutConfig($configPath);
            $path = substr($path, 1, strlen($path));
            $result = $this->getAlternativeControllerByClearPath($configList, $path);
        } catch (\Exception $e) {
            // TODO handle exception? throw custom exception, create custom exception
        }
        return $result;
    }

    /**
     * Возвращает директорию и название контроллера на основе преобразованого пути
     * @param array $configList массив альтернативных котроллеров
     * @param string $clearPath
     * @return array пустой массив если не обнаружено совпадений в противном случае массив содержит две записи dir и name
     * @author Borys Plotka ( @3plo )
     */
    private function getAlternativeControllerByClearPath(array $configList, string $clearPath) : array
    {
        $result = [];
        foreach ($configList as $configTitle => $configItem) {
            if ($result) {
                break;
            }
            foreach ($configItem as $key => $value) {
                if (preg_match($key, $clearPath)) {
                    $result['dir'] = $configItem[$key]['dir'];
                    $result['name'] = $configItem[$key]['name'];
                    $result['key'] = $key;
                }
            }
        }
        return $result;
    }

    /**
     * @param string $configPath
     * @return array
     * @throws \Exception
     * @author Borys Plotka ( @3plo )
     */
    private function getRoutConfig(string $configPath) : array
    {
        if (!file_exists($configPath)) {
            throw new \Exception();
        }
        $configFile = fopen($configPath, 'r');
        if (!$configFile) {
            throw new \Exception();
        }
        $configList = json_decode(stream_get_contents($configFile), true)['alternative_controller'];
        if (!$configList) {
            throw new \Exception();
        }
        if (is_array($configList)) {
            $result = $configList;
        } else {
            throw new \Exception();
        }
        return $result;
    }
}