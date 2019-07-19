<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\JWTFramework;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Encryption\Compression\CompressionMethodManager;
use Jose\Component\Encryption\Compression\Deflate;
use Jose\Component\Encryption\JWEDecrypter;
use Jose\Component\Encryption\JWELoader;
use Jose\Component\Encryption\Serializer\CompactSerializer;
use Jose\Component\Encryption\Serializer\JWESerializerManager;
use Psr\Container\ContainerInterface;

class JWELoaderFactory
{
    public function __invoke(ContainerInterface $container): JWELoader
    {
        /** @var AlgorithmManager $algorithmManager */
        $algorithmManager = $container->get('openid.service.algorithm_manager');

        $JWESerializerManager = new JWESerializerManager([
            new CompactSerializer(),
        ]);

        $compressionManager = new CompressionMethodManager([new Deflate()]);

        $JWEDecrypter = new JWEDecrypter(
            $algorithmManager,
            $algorithmManager,
            $compressionManager
        );

        return new JWELoader(
            $JWESerializerManager,
            $JWEDecrypter,
            null
        );
    }
}
