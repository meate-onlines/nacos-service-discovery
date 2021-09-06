<?php

namespace Kuke\NacosServerDiscovery;


class HashLogic
{
    protected function hash(string $str, int $nodsCount): int
    {
        return crc32($str) % $nodsCount;
    }
}