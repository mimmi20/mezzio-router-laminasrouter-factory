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

use AssertionError;
use Laminas\I18n\Translator\Translator;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Router\Http\TranslatorAwareTreeRouteStack;
use Mezzio\Router\LaminasRouter;
use Mimmi20\Mezzio\Router\RouterFactory;
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use ReflectionException;
use ReflectionProperty;

use function assert;

final class RouterFactoryTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
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

        $routerProp     = new ReflectionProperty($result, 'laminasRouter');
        $internalRouter = $routerProp->getValue($result);

        self::assertInstanceOf(TranslatorAwareTreeRouteStack::class, $internalRouter);
        self::assertSame($translator, $internalRouter->getTranslator());
        self::assertSame('routing', $internalRouter->getTranslatorTextDomain());
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function testInvokeWithNullRouter(): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::never())
            ->method('has');
        $container->expects(self::once())
            ->method('get')
            ->with(TranslatorInterface::class)
            ->willReturn(null);

        $factory = new RouterFactory();

        assert($container instanceof ContainerInterface);
        $result = $factory($container);

        self::assertInstanceOf(LaminasRouter::class, $result);

        $routerProp     = new ReflectionProperty($result, 'laminasRouter');
        $internalRouter = $routerProp->getValue($result);

        self::assertInstanceOf(TranslatorAwareTreeRouteStack::class, $internalRouter);
        self::assertNull($internalRouter->getTranslator());
        self::assertSame('routing', $internalRouter->getTranslatorTextDomain());
    }

    /**
     * @throws Exception
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    public function testInvokeWithoutRouter(): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(self::never())
            ->method('has');
        $container->expects(self::once())
            ->method('get')
            ->with(TranslatorInterface::class)
            ->willReturn(42);

        $factory = new RouterFactory();

        assert($container instanceof ContainerInterface);

        self::expectException(AssertionError::class);
        self::expectExceptionMessage(
            'assert($translator instanceof TranslatorInterface || $translator === null)',
        );
        self::expectExceptionCode(1);

        $factory($container);
    }
}
