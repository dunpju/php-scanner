<?php


namespace Dengpju\PhpScanner;


use Exception;
use ReflectionException;

class Scan
{
    /**
     * @var Configure
     */
    protected $config;

    /**
     * 节点
     * @var array
     */
    protected $nodes = [];

    public function __construct(Configure $config)
    {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    /**
     * @param string $path
     * @return array
     */
    private function files(string $path): array
    {
        $files = [];
        $globFiles = glob($path . "*");
        foreach ($globFiles as $file) {
            if (is_dir($file)) {
                $files = array_merge($files, $this->files($file . '/'));
            } else {
                $files[] = $file;
            }
        }
        return $files;
    }

    /**
     * 扫描
     * @throws Exception
     * @throws ReflectionException
     */
    public function scaner()
    {
        if (!$this->config->projectName) {
            throw new Exception("projectName property Can't be empty");
        }
        if (!$this->config->scanNamespace) {
            throw new Exception("scanNamespace property Can't be empty");
        }
        if (is_dir($this->config->scanRoot)) {
            throw new Exception("directory does not exist");
        }
        $phpfiles = $this->files($this->config->scanRoot);
        foreach ($phpfiles as $php) {
            if (preg_match("/\.php$/", $php)) {
                require_once($php);
            }
        }
        $classes = get_declared_classes();
        foreach ($classes as $class) {
            if (in_array($class, $this->config->excludeNamespace)) {
                continue;
            }
            $isSkip = false;
            foreach ($this->config->excludeNamespace as $excludeNamespace) {
                $excludeNamespace = rtrim($excludeNamespace, "*");
                if (0 === strpos($class, $excludeNamespace)) {
                    $isSkip = true;
                    break;
                }
            }
            if ($isSkip) {
                continue;
            }
            if (strstr($class, $this->config->scanNamespace)) {
                $refClass = new \ReflectionClass($class);
                $methods = $refClass->getMethods();
                foreach ($methods as $method) {
                    if ($method->class == $class) {
                        $m = [];
                        $action = new Action();
                        $action->privilege_action_sn = "pas" . Util::randomNumbser(29);
                        $action->project_name = $this->config->projectName;
                        $action->name = $method->name;
                        $action->class = $method->class;
                        $action->route = $method->class . '@' . $method->name;
                        $action->uni_md5 = Util::md5($action->project_name, $action->class, $action->name);
                        $action->title = Util::parse($method->getDocComment());
                        $action->desc = "";
                        $this->nodes[$action->uni_md5] = $action;
                    }
                }
            }
        }
    }


}