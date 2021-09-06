<?php

namespace Kuke\NacosServerDiscovery\Tactics;

use Kuke\NacosServerDiscovery\HashLogic;
use Kuke\NacosServerDiscovery\TacticsAlgorithm;

class UrlHash extends HashLogic implements TacticsAlgorithm
{
    public function getOne(array $nodeList, ?string $clientIp): array
    {
        $nodeList = array_values($nodeList);
        return $nodeList[$this->hash($clientIp, count($nodeList))];
    }
}