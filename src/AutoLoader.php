<?php
namespace GedasTheEvil\LaravelTranslationCheck;

/**
 * Class AutoLoader
 * A loader for when you are not using composer
 * @package GedasTheEvil\LaravelTranslationCheck
 */
class AutoLoader
{
    public function __construct()
    {
        \spl_autoload_register([$this, 'loadOurClass']);
    }

    public function loadOurClass($class_name = '')
    {
        if ($this->isOurClass($class_name)) {
            /** @noinspection PhpIncludeInspection */
            require($this->getFilename($class_name));
        }
    }

    private function isOurClass($class_name = '')
    {
        return (strpos($class_name, __NAMESPACE__) === 0);
    }

    /**
     * @param string $class_name
     * @return string
     */

    private function getFilename($class_name = '')
    {
        $class_name = str_ireplace(__NAMESPACE__, '', $class_name);
        return __DIR__ . str_ireplace('\\', '/', $class_name) . ".php";
    }
}

new AutoLoader();
