<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Issuer;

use Jose\Component\KeyManagement\JKUFactory;
use Psr\Container\ContainerInterface;
use TMV\OpenIdClient\Issuer\IssuerFactory;
use TMV\OpenIdClient\Issuer\IssuerFactoryInterface;
use TMV\OpenIdClient\Issuer\Metadata\MetadataFactoryInterface;

class IssuerFactoryFactory
{
    public function __invoke(ContainerInterface $container): IssuerFactoryInterface
    {
        /** @var MetadataFactoryInterface $metadataFactory */
        $metadataFactory = $container->get(MetadataFactoryInterface::class);

        /** @var JKUFactory $JKUFactory */
        $JKUFactory = $container->get('openid.service.jku_factory');

        return new IssuerFactory(
            $metadataFactory,
            $JKUFactory
        );
    }
}
