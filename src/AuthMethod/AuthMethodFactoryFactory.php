<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\AuthMethod;

use Psr\Container\ContainerInterface;
use TMV\OpenIdClient\AuthMethod\AuthMethodFactory;
use TMV\OpenIdClient\AuthMethod\ClientSecretBasic;
use TMV\OpenIdClient\AuthMethod\ClientSecretJwt;
use TMV\OpenIdClient\AuthMethod\ClientSecretPost;
use TMV\OpenIdClient\AuthMethod\None;
use TMV\OpenIdClient\AuthMethod\PrivateKeyJwt;
use TMV\OpenIdClient\AuthMethod\SelfSignedTLSClientAuth;
use TMV\OpenIdClient\AuthMethod\TLSClientAuth;

class AuthMethodFactoryFactory
{
    public function __invoke(ContainerInterface $container): AuthMethodFactory
    {
        $authMethods = [
            ClientSecretBasic::class,
            ClientSecretJwt::class,
            ClientSecretPost::class,
            None::class,
            PrivateKeyJwt::class,
            TLSClientAuth::class,
            SelfSignedTLSClientAuth::class,
        ];

        return new AuthMethodFactory(\array_map([$container, 'get'], $authMethods));
    }
}
