<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\AuthMethod;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\Serializer\JWSSerializer;
use Psr\Container\ContainerInterface;
use TMV\OpenIdClient\AuthMethod\PrivateKeyJwt;

class PrivateKeyJwtFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var JWSBuilder $JWSBuilder */
        $JWSBuilder = $container->has('openid.service.jws_builder')
            ? $container->get('openid.service.jws_builder')
            : $this->getAlgorithmManager($container);

        /** @var JWSSerializer $JWSSerializer */
        $JWSSerializer = $container->has('openid.service.jws_serializer')
            ? $container->get('openid.service.jws_serializer')
            : new CompactSerializer();

        return new PrivateKeyJwt(
            $JWSBuilder,
            $JWSSerializer
        );
    }

    private function getAlgorithmManager(ContainerInterface $container): AlgorithmManager
    {
        return $container->get('openid.service.algorithm_manager');
    }
}
