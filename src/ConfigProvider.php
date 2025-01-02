<?php

/**
 * This file is part of the mimmi20/mezzio-router-laminasrouter-factory package.
 *
 * Copyright (c) 2024-2025, Thomas Mueller <mimmi20@live.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Mimmi20\Mezzio\Router;

use Mezzio\Router\LaminasRouter;
use Mezzio\Router\RouterInterface;

final class ConfigProvider
{
    /**
     * Return general-purpose laminas-navigation configuration.
     *
     * @return array<string, array<string, array<int|string, string>>>
     *
     * @throws void
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }

    /**
     * Return application-level dependency configuration.
     *
     * @return array<string, array<int|string, string>>
     *
     * @throws void
     *
     * @api
     */
    public function getDependencyConfig(): array
    {
        return [
            'aliases' => [
                RouterInterface::class => LaminasRouter::class,
            ],
            'factories' => [
                LaminasRouter::class => RouterFactory::class,
            ],
        ];
    }
}
