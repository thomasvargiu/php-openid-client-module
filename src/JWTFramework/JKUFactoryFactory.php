<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\JWTFramework;

use Jose\Component\KeyManagement\JKUFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

class JKUFactoryFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var ClientInterface $httpClient */
        $httpClient = $container->get('openid.service.http_client');
        /** @var RequestFactoryInterface $requestFactory */
        $requestFactory = $container->get('openid.factory.request_factory');

        return new JKUFactory(
            $httpClient,
            $requestFactory
        );
    }
}
