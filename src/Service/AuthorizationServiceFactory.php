<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Service;

use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use TMV\OpenIdClient\Service\AuthorizationService;
use TMV\OpenIdClient\Token\ResponseTokenVerifierInterface;
use TMV\OpenIdClient\Token\TokenDecrypterInterface;
use TMV\OpenIdClient\Token\TokenSetFactoryInterface;
use TMV\OpenIdClient\Token\TokenSetVerifierInterface;

class AuthorizationServiceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var TokenSetFactoryInterface $tokenSetFactory */
        $tokenSetFactory = $container->get(TokenSetFactoryInterface::class);
        /** @var TokenSetVerifierInterface $tokenSetVerifier */
        $tokenSetVerifier = $container->get(TokenSetVerifierInterface::class);
        /** @var ResponseTokenVerifierInterface $responseTokenVerifier */
        $responseTokenVerifier = $container->get(ResponseTokenVerifierInterface::class);
        /** @var TokenDecrypterInterface $tokenDecrypter */
        $tokenDecrypter = $container->get(TokenDecrypterInterface::class);
        /** @var ClientInterface $httpClient */
        $httpClient = $container->get('openid.service.http_client');
        /** @var RequestFactoryInterface $httpClient */
        $requestFactory = $container->get('openid.factory.request_factory');
        /** @var UriFactoryInterface $uriFactory */
        $uriFactory = $container->get('openid.factory.uri_factory');

        return new AuthorizationService(
            $tokenSetFactory,
            $tokenSetVerifier,
            $responseTokenVerifier,
            $tokenDecrypter,
            $httpClient,
            $requestFactory,
            $uriFactory
        );
    }
}
