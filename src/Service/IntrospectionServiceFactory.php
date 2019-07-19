<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Service;

use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use TMV\OpenIdClient\Service\IntrospectionService;

class IntrospectionServiceFactory
{
    public function __invoke(ContainerInterface $container): IntrospectionService
    {
        /** @var ClientInterface $httpClient */
        $httpClient = $container->get('openid.service.http_client');

        /** @var RequestFactoryInterface $requestFactory */
        $requestFactory = $container->get('openid.factory.request_factory');

        return new IntrospectionService(
            $httpClient,
            $requestFactory
        );
    }
}
