<?php
/**
 * This file is part of the mimmi20/mezzio-router-laminasrouter-factory package.
 *
 * Copyright (c) 2024, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Mezzio\Router;

use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Router\Http\TranslatorAwareTreeRouteStack;
use Mezzio\Router\LaminasRouter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use function assert;

final class RouterFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): LaminasRouter
    {
        $translator = $container->get(TranslatorInterface::class);

        assert($translator instanceof TranslatorInterface || $translator === null);

        $router = new TranslatorAwareTreeRouteStack();
        $router->setTranslator($translator, 'routing');

        return new LaminasRouter($router);
    }
}
