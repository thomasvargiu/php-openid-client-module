<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use TMV\OpenIdClient\Middleware\AuthRedirectHandler;
use TMV\OpenIdClient\Service\AuthorizationService;

class AuthRedirectHandlerFactory
{
    public function __invoke(ContainerInterface $container): AuthRedirectHandler
    {
        /** @var AuthorizationService $authorizationService */
        $authorizationService = $container->get(AuthorizationService::class);

        /** @var null|ResponseFactoryInterface $responseFactory */
        $responseFactory = $container->has('openid.factory.response_factory')
            ? $container->get('openid.factory.response_factory')
            : null;

        return new AuthRedirectHandler(
            $authorizationService,
            $responseFactory
        );
    }
}
