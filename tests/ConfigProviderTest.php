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

namespace Mimmi20Test\Mezzio\Router;

use Mezzio\Router\LaminasRouter;
use Mezzio\Router\RouterInterface;
use Mimmi20\Mezzio\Router\ConfigProvider;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    private ConfigProvider $provider;

    /** @throws void */
    protected function setUp(): void
    {
        $this->provider = new ConfigProvider();
    }

    /** @throws Exception */
    public function testProviderDefinesExpectedFactoryServices(): void
    {
        $dependencies = $this->provider->getDependencyConfig();
        self::assertArrayHasKey('factories', $dependencies);

        $factories = $dependencies['factories'];
        self::assertIsArray($factories);
        self::assertArrayHasKey(LaminasRouter::class, $factories);

        self::assertArrayHasKey('aliases', $dependencies);

        $aliases = $dependencies['aliases'];
        self::assertIsArray($aliases);
        self::assertArrayHasKey(RouterInterface::class, $aliases);
    }

    /** @throws Exception */
    public function testInvocationReturnsArrayWithDependencies(): void
    {
        $config = ($this->provider)();

        self::assertArrayHasKey('dependencies', $config);

        $dependencies = $config['dependencies'];
        self::assertArrayHasKey('factories', $dependencies);

        $factories = $dependencies['factories'];
        self::assertArrayHasKey(LaminasRouter::class, $factories);

        self::assertArrayHasKey('aliases', $dependencies);

        $aliases = $dependencies['aliases'];
        self::assertArrayHasKey(RouterInterface::class, $aliases);
    }
}
