<?php


namespace Dengpju\PhpScanner;


use Exception;

class Configure
{
    /**
     * 项目名称
     * @var string
     */
    protected $projectName;

    /**
     * 需要扫描的主目录
     * @var string
     */
    protected $scanRoot;

    /**
     * 扫描的命名空间
     * @var string
     */
    protected $scanNamespace;

    /**
     * 排除的命名空间
     * @var string[]
     */
    protected $excludeNamespace = [];

    /**
     * Configure constructor.
     * @param string $projectName
     * @param string $scanRoot
     * @param string $scanNamespace
     * @param string[] $excludeNamespace
     * @throws Exception
     */
    public function __construct(string $projectName, string $scanRoot, string $scanNamespace, array $excludeNamespace = [])
    {
        $this->projectName = $projectName;
        $this->scanRoot = $scanRoot;
        $this->scanNamespace = $scanNamespace;
        $this->excludeNamespace = $excludeNamespace;
        if (!$this->getProjectName()) {
            throw new Exception("projectName property Can't be empty");
        }
        if (!$this->getScanNamespace()) {
            throw new Exception("scanNamespace property Can't be empty");
        }
        if (!is_dir($this->getScanRoot())) {
            throw new Exception("directory does not exist");
        }
    }

    /**
     * @return string
     */
    public function getProjectName(): string
    {
        return $this->projectName;
    }

    /**
     * @return string
     */
    public function getScanRoot(): string
    {
        return $this->scanRoot;
    }

    /**
     * @return string
     */
    public function getScanNamespace(): string
    {
        return $this->scanNamespace;
    }

    /**
     * @return string[]
     */
    public function getExcludeNamespace(): array
    {
        return $this->excludeNamespace;
    }

}