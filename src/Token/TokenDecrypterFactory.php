<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Token;

use Jose\Component\Encryption\JWELoader;
use Psr\Container\ContainerInterface;
use TMV\OpenIdClient\Token\TokenDecrypter;

class TokenDecrypterFactory
{
    public function __invoke(ContainerInterface $container): TokenDecrypter
    {
        /** @var JWELoader $JWELoader */
        $JWELoader = $container->get('openid.service.jwe_loader');

        return new TokenDecrypter(
            $JWELoader
        );
    }
}
