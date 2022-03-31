<?php


namespace Dengpju\PhpScanner;


interface IAuthentication
{
    /**
     * 鉴定权限
     * @param array $myPrivilege
     * @param array $requestParams
     * @return bool
     */
    public function identify(array $myPrivilege, array $requestParams): bool;
}