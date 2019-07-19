<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Service;

use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use TMV\OpenIdClient\Service\RegistrationService;

class RegistrationServiceFactory
{
    public function __invoke(ContainerInterface $container): RegistrationService
    {
        /** @var ClientInterface $httpClient */
        $httpClient = $container->get('openid.service.http_client');

        /** @var RequestFactoryInterface $requestFactory */
        $requestFactory = $container->get('openid.factory.request_factory');

        return new RegistrationService(
            $httpClient,
            $requestFactory
        );
    }
}
