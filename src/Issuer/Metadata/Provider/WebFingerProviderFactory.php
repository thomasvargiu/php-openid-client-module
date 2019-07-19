<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Issuer\Metadata\Provider;

use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use TMV\OpenIdClient\Issuer\Metadata\Provider\DiscoveryProviderInterface;
use TMV\OpenIdClient\Issuer\Metadata\Provider\WebFingerProvider;
use TMV\OpenIdClient\Issuer\Metadata\Provider\WebFingerProviderInterface;

class WebFingerProviderFactory
{
    public function __invoke(ContainerInterface $container): WebFingerProviderInterface
    {
        /** @var ClientInterface $client */
        $client = $container->get('openid.service.http_client');

        /** @var RequestFactoryInterface $requestFactory */
        $requestFactory = $container->get('openid.factory.request_Factory');

        /** @var UriFactoryInterface $uriFactory */
        $uriFactory = $container->get('openid.factory.uri_factory');

        /** @var DiscoveryProviderInterface $discoveryProvider */
        $discoveryProvider = $container->get(DiscoveryProviderInterface::class);

        return new WebFingerProvider(
            $client,
            $requestFactory,
            $uriFactory,
            $discoveryProvider
        );
    }
}
