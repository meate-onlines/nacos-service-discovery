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

use Kuke\NacosServerDiscovery\TacticsAlgorithm;

class Random implements TacticsAlgorithm
{
    public function getOne(array $nodeList, ?string $clientIp): array
    {
        $randKey = array_rand($nodeList);
        return $nodeList[$randKey];
    }
}
