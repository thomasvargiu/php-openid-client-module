<?php

declare(strict_types=1);

namespace TMV\OpenIdClientModule\Middleware;

use Psr\Container\ContainerInterface;
use TMV\OpenIdClient\Middleware\UserInfoMiddleware;
use TMV\OpenIdClient\Service\UserinfoService;

class UserInfoMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): UserInfoMiddleware
    {
        /** @var UserinfoService $userInfoService */
        $userInfoService = $container->get(UserinfoService::class);

        return new UserInfoMiddleware(
            $userInfoService
        );
    }
}
