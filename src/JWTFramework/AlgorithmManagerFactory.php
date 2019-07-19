<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\JWTFramework;

use Jose\Component\Core\Algorithm;
use Jose\Component\Core\AlgorithmManager;
use Psr\Container\ContainerInterface;

class AlgorithmManagerFactory
{
    public function __invoke(ContainerInterface $container): AlgorithmManager
    {
        $algorithms = $container->get('config')['openid']['algorithms'] ?? [];

        return new AlgorithmManager(\array_map(\Closure::fromCallable([$this, 'fetchAlgorithm']), $algorithms));
    }

    private function fetchAlgorithm(ContainerInterface $container, string $className): Algorithm
    {
        return $container->has($className) ? $container->get($className) : new $className();
    }
}
