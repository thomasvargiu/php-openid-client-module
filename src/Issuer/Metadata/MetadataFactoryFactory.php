<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Issuer\Metadata;

use Psr\Container\ContainerInterface;
use TMV\OpenIdClient\Issuer\Metadata\MetadataFactory;
use TMV\OpenIdClient\Issuer\Metadata\MetadataFactoryInterface;
use TMV\OpenIdClient\Issuer\Metadata\Provider\DiscoveryProviderInterface;
use TMV\OpenIdClient\Issuer\Metadata\Provider\WebFingerProviderInterface;

class MetadataFactoryFactory
{
    public function __invoke(ContainerInterface $container): MetadataFactoryInterface
    {
        /** @var DiscoveryProviderInterface $discoveryProvider */
        $discoveryProvider = $container->get(DiscoveryProviderInterface::class);
        /** @var WebFingerProviderInterface $webFingerProvider */
        $webFingerProvider = $container->get(WebFingerProviderInterface::class);

        return new MetadataFactory(
            $discoveryProvider,
            $webFingerProvider
        );
    }
}
