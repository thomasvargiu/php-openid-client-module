<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\JWTFramework;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Signature\JWSVerifier;
use Psr\Container\ContainerInterface;

class JWSVerifierFactory
{
    public function __invoke(ContainerInterface $container): JWSVerifier
    {
        /** @var AlgorithmManager $algorithmManager */
        $algorithmManager = $container->get('openid.service.algorithm_manager');

        return new JWSVerifier($algorithmManager);
    }
}
