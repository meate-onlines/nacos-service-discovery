<?php

namespace Kuke\NacosServerDiscovery;

interface TacticsAlgorithm
{
    public function getOne(array $nodeList, ?string $clientIp): array;
}