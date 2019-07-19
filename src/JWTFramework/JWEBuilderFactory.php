<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\JWTFramework;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Encryption\Compression\CompressionMethodManager;
use Jose\Component\Encryption\Compression\Deflate;
use Jose\Component\Encryption\JWEBuilder;
use Psr\Container\ContainerInterface;

class JWEBuilderFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var AlgorithmManager $algorithmManager */
        $algorithmManager = $container->get('openid.service.algorithm_manager');

        return new JWEBuilder(
            $algorithmManager,
            $algorithmManager,
            new CompressionMethodManager([new Deflate()])
        );
    }
}
