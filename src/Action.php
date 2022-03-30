<?php


namespace Dengpju\PhpScanner;


class Action
{
    /**
     * 功能sn
     * @var string
     */
    public $privilege_action_sn;
    /**
     * 项目名称
     * @var string
     */
    public $project_name;
    /**
     * 方法名
     * @var string
     */
    public $name;
    /**
     * 类名
     * @var string
     */
    public $class;
    /**
     * 路由:class@name
     * @var string
     */
    public $route;
    /**
     * 唯一md5:projec+class@name
     * @var string
     */
    public $uni_md5;
    /**
     * 标题
     * @var string
     */
    public $title;
    /**
     * 描述
     * @var string
     */
    public $desc;
}