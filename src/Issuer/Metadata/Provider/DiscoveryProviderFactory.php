<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Issuer\Metadata\Provider;

use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use TMV\OpenIdClient\Issuer\Metadata\Provider\DiscoveryProvider;
use TMV\OpenIdClient\Issuer\Metadata\Provider\DiscoveryProviderInterface;

class DiscoveryProviderFactory
{
    public function __invoke(ContainerInterface $container): DiscoveryProviderInterface
    {
        /** @var ClientInterface $client */
        $client = $container->get('openid.service.http_client');

        /** @var RequestFactoryInterface $requestFactory */
        $requestFactory = $container->get('openid.factory.request_factory');

        /** @var UriFactoryInterface $uriFactory */
        $uriFactory = $container->get('openid.factory.uri_factory');

        return new DiscoveryProvider(
            $client,
            $requestFactory,
            $uriFactory
        );
    }
}
