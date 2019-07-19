<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Service;

use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use TMV\OpenIdClient\Service\UserinfoService;
use TMV\OpenIdClient\Token\IdTokenVerifierInterface;
use TMV\OpenIdClient\Token\TokenDecrypterInterface;

class UserinfoServiceFactory
{
    public function __invoke(ContainerInterface $container): UserinfoService
    {
        /** @var ClientInterface $httpClient */
        $httpClient = $container->get('openid.service.http_client');

        /** @var RequestFactoryInterface $requestFactory */
        $requestFactory = $container->get('openid.factory.request_factory');

        /** @var IdTokenVerifierInterface $idTokenVerifier */
        $idTokenVerifier = $container->get(IdTokenVerifierInterface::class);

        /** @var TokenDecrypterInterface $tokenDecrypter */
        $tokenDecrypter = $container->get(TokenDecrypterInterface::class);

        return new UserinfoService(
            $httpClient,
            $idTokenVerifier,
            $tokenDecrypter,
            $requestFactory
        );
    }
}
