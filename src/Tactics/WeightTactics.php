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

class WeightTactics implements TacticsAlgorithm
{
    public function getOne(array $nodeList, ?string $clientIp): array
    {
        $weightsList = array_column($nodeList, 'weight');
        $total = array_sum($weightsList);
        $random = mt_rand(1, (int) $total);
        $total = 0;
        arsort($weightsList);
        $final = [];
        foreach ($weightsList as $key => $item) {
            $total += (int) $item;
            if ($total >= $random) {
                $final = $nodeList[$key];
            }
        }
        return $final;
    }
}
