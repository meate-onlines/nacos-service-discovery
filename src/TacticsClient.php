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
namespace Kuke\NacosServerDiscovery;

use Hyperf\Nacos\Exception\InvalidArgumentException;
use Kuke\NacosServerDiscovery\Tactics\IpHash;
use Kuke\NacosServerDiscovery\Tactics\Random;
use Kuke\NacosServerDiscovery\Tactics\UrlHash;
use Kuke\NacosServerDiscovery\Tactics\WeightTactics;

class TacticsClient
{
    protected $alias = [
        'random' => Random::class,
        'ipHash' => IpHash::class,
        'urlHash' => UrlHash::class,
        'weightTactics' => WeightTactics::class,
    ];

    protected $providers = [];

    public function __get($name): TacticsAlgorithm
    {
        if (! isset($name) || ! isset($this->alias[$name])) {
            throw new InvalidArgumentException("{$name} is invalid.");
        }

        if (isset($this->providers[$name])) {
            return $this->providers[$name];
        }

        $class = $this->alias[$name];
        return $this->providers[$name] = new $class();
    }
}
