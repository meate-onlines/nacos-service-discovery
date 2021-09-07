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

use Hyperf\Nacos\Config;
use Hyperf\Nacos\Exception\RequestException;
use Hyperf\Nacos\Exception\RuntimeException;
use Hyperf\ServiceGovernanceNacos\Client;
use Hyperf\Utils\Codec\Json;
use function Swow\Util\var_dump_return;

class ServerDiscovery
{
    public $nodes;

    protected $client;

    /**
     * config path basepath/config/autoload/nacos.php.
     */
    public function __construct(array $config)
    {
        if (! empty($config['uri'])) {
            $baseUri = $config['uri'];
        } else {
            $baseUri = sprintf('http://%s:%d', $config['host'] ?? '127.0.0.1', $config['port'] ?? 8848);
        }
        $nacosConfig = new Config([
            'base_uri' => $baseUri,
            'username' => $config['username'] ?? null,
            'password' => $config['password'] ?? null,
            'guzzle_config' => $config['guzzle']['config'] ?? null,
        ]);
        $this->client = new Client($nacosConfig);
    }

    /**
     * @param string $tactics random|ipHash|urlHash|weightTactics
     * @param null|string $clientIp null|用户端真正的ip|接口路由|null
     */
    public function getNode(string $tactics, ?string $clientIp = null): array
    {
        if (empty($this->nodes)) {
            throw new RuntimeException('does not have healthy node', 15);
        }
        if (count($this->nodes) == 1) {
            return $this->nodes;
        }
        $tacticsObj = new TacticsClient();
        return $tacticsObj->{$tactics}->getOne($this->nodes, $clientIp);
    }

    /**
     * @param $optional = [
     *     'groupName' => '',
     *     'namespaceId' => '',
     *     'clusters' => '', // 集群名称(字符串，多个集群用逗号分隔)
     *     'healthyOnly' => false,
     * ]
     */
    public function nodesList(string $serverName, array $optional): self
    {
        $response = $this->client->instance->list($serverName, $optional);
        if ($response->getStatusCode() !== 200) {
            throw new RequestException((string) $response->getBody(), $response->getStatusCode());
        }
        $data = Json::decode((string) $response->getBody());
        $hosts = $data['hosts'] ?? [];
        $nodes = [];
        foreach ($hosts as $node) {
            //TODO Nacos 2.0.*的bug,获取服务器下失败实例时healthy的转态会返回true
            print_r($node);
            if (isset($node['ip'], $node['port']) && (! $node['healthy'] ?? false)) {
                $nodes[] = [
                    'host' => $node['ip'],
                    'port' => $node['port'],
                    'weight' => $node['weight'] ?? 1,
                ];
            }
        }

        $this->nodes = $nodes;
        return $this;
    }
}
