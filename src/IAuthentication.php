<?php


namespace Dengpju\PhpScanner;


interface IAuthentication
{
    /**
     * @param array $myPrivilege
     * @param array $requestParams
     * @return bool
     */
    public function identify(array $myPrivilege, array $requestParams): bool;
}