<?php


namespace Dengpju\PhpScanner;


class Config
{
    /**
     * 项目名称
     * @var string
     */
    public $projectName;

    /**
     * 需要扫描的主目录
     * @var string
     */
    public $scanRoot;

    /**
     * 扫描的命名空间
     * @var string
     */
    public $scanNamespace;

    /**
     * 排除的命名空间
     * @var string[]
     */
    public $excludeNamespace = [];

}