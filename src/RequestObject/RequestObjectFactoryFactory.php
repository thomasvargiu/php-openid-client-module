<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\RequestObject;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Encryption\Compression\CompressionMethodManager;
use Jose\Component\Encryption\Compression\Deflate;
use Jose\Component\Encryption\JWEBuilder;
use Jose\Component\Encryption\Serializer\CompactSerializer as EncryptionSerializer;
use Jose\Component\Encryption\Serializer\JWESerializer;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializer;
use Psr\Container\ContainerInterface;
use TMV\OpenIdClient\RequestObject\RequestObjectFactory;

class RequestObjectFactoryFactory
{
    public function __invoke(ContainerInterface $container): RequestObjectFactory
    {
        /** @var AlgorithmManager $algorithmManager */
        $algorithmManager = $container->get('openid.service.algorithm_manager');
        /** @var JWSBuilder $JWSBuilder */
        $JWSBuilder = $container->has('openid.service.jws_builder')
            ? $container->get('openid.service.jws_builder')
            : new JWSBuilder($algorithmManager);
        /** @var JWEBuilder $JWEBuilder */
        $JWEBuilder = $container->has('openid.service.jwe_builder')
            ? $container->get('openid.service.jwe_builder')
            : new JWEBuilder($algorithmManager, $algorithmManager, new CompressionMethodManager([new Deflate()]));
        /** @var JWSSerializer $JWSSerializer */
        $JWSSerializer = $container->has('openid.service.jws_serializer')
            ? $container->get('openid.service.jws_serializer')
            : new CompactSerializer();
        /** @var null|JWESerializer $JWESerializer */
        $JWESerializer = $container->has('openid.service.jwe_serializer')
            ? $container->get('openid.service.jwe_serializer')
            : new EncryptionSerializer();

        return new RequestObjectFactory(
            $algorithmManager,
            $JWSBuilder,
            $JWEBuilder,
            $JWSSerializer,
            $JWESerializer
        );
    }
}
