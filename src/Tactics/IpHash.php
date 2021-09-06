<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Kuke\NacosServerDiscovery\Tactics;

use Kuke\NacosServerDiscovery\HashLogic;
use Kuke\NacosServerDiscovery\TacticsAlgorithm;

class IpHash extends HashLogic implements TacticsAlgorithm
{
    public function getOne(array $nodeList, ?string $clientIp): array
    {
        $nodeList = array_values($nodeList);
        return $nodeList[$this->hash($clientIp, count($nodeList))];
    }
}
