<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Middleware;

use Psr\Container\ContainerInterface;
use TMV\OpenIdClient\Middleware\CallbackMiddleware;
use TMV\OpenIdClient\Service\AuthorizationService;

class CallbackMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): CallbackMiddleware
    {
        /** @var AuthorizationService $authorizationService */
        $authorizationService = $container->get(AuthorizationService::class);

        return new CallbackMiddleware(
            $authorizationService
        );
    }
}
