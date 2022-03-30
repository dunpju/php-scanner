<?php


namespace Dengpju\PhpScanner;


interface IStorage
{
    /**
     * 存储到redis
     * @return array
     */
    public function toRedis(): array;

    /**
     * 获取
     * @param string $key
     * @return array
     */
    public function get(string $key): array;

    /**
     * @param string $key
     * @return bool
     */
    public function exist(string $key): bool;
}