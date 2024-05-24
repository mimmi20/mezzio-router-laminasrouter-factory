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

use Laminas\I18n\Translator\Translator;
use Laminas\I18n\Translator\TranslatorInterface;
use Mezzio\Router\LaminasRouter;
use Mimmi20\Mezzio\Router\RouterFactory;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function assert;

final class RouterFactoryTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     */
    public function testInvoke(): void
    {
        $translator = $this->createMock(Translator::class);

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::never())
            ->method('has');
        $container->expects(self::once())
            ->method('get')
            ->with(TranslatorInterface::class)
            ->willReturn($translator);

        $factory = new RouterFactory();

        assert($container instanceof ContainerInterface);
        $result = $factory($container);

        self::assertInstanceOf(LaminasRouter::class, $result);
    }
}
