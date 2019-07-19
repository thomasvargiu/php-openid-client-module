<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\JWTFramework;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Signature\JWSBuilder;
use Psr\Container\ContainerInterface;

class JWSBuilderFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var AlgorithmManager $algorithmManager */
        $algorithmManager = $container->get('openid.service.algorithm_manager');

        return new JWSBuilder($algorithmManager);
    }
}
