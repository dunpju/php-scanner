<?php


namespace Dengpju\PhpScanner;


use ReflectionException;

class Scanner
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * 节点
     * @var array
     */
    protected $nodes = [];

    public function __construct(Config $config)
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
     * @throws ReflectionException
     */
    public function scaner()
    {
        $phpfiles = $this->files($this->config->scanRoot);
        print_r($phpfiles);
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
                        $m['privilege_action_sn'] = $method->name;
                        $m['project_name'] = $method->name;
                        $m['method'] = $method->name;
                        $m['name'] = $method->name;
                        $m['class'] = $method->class;
                        $m['uni_md5'] = $this->action($m['project_name'], $m['method'], $m['name'], $m['class']);
                        $m['title'] = $this->parse($method->getDocComment());
                        $m['desc'] = "";
                        $this->nodes[$m['uni_md5']] = $m;
                    }
                }
            }
        }
        print_r($this->nodes);
    }

    /**
     * @param string $project
     * @param string $method
     * @param string $class
     * @param string $name
     * @return string
     */
    protected function action(string $project, string $method, string $class, string $name): string
    {
        return md5($project . '+' . $method . '+' . $class . '@' . $name);
    }

    /**
     * @param string $docComment
     * @return string
     */
    protected function parse(string $docComment): string
    {
        preg_match_all("/(?<=(\@Message\(\")).*?(?=(\"\)))/", $docComment, $doc);
        if ($doc) {
            if (isset($doc[0]) && isset($doc[0][0]) && !empty($doc[0][0])) {
                return trim($doc[0][0], '"');
            }
        }
        return "";
    }
}