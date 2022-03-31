<?php


namespace Dengpju\PhpScanner;


interface IStorage
{
    /**
     * 存储
     * to mysql and to redis
     * @return array
     */
    public function save(): array;

    /**
     * 获取
     * @param string $key
     * @return array
     */
    public function get(string $key): array;

    /**
     * 是否存在
     * @param string $key
     * @return bool
     */
    public function exist(string $key): bool;
}