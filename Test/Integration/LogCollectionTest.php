<?php

declare(strict_types=1);

namespace Yireo\QueryDebugger\Test\Integration;

use Magento\Framework\App\State;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Logger\Handler\Base;
use Magento\Framework\Logger\Monolog;
use Magento\Store\Model\ResourceModel\Store\Collection as StoreCollection;
use Magento\TestFramework\Helper\Bootstrap;
use Monolog\Handler\TestHandler;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertInterceptorPluginIsRegistered;
use Yireo\QueryDebugger\Config\Config;
use Yireo\QueryDebugger\Plugin\LogCollection;

class LogCollectionTest extends TestCase
{
    use AssertInterceptorPluginIsRegistered;

    public function testPluginIsRegistered(): void
    {
        $this->assertInterceptorPluginIsRegistered(
            AbstractDb::class,
            LogCollection::class,
            'Yireo_QueryDebugger::logCollection'
        );
    }

    public function testIsDisabledByDefault(): void
    {
        $config = Bootstrap::getObjectManager()->create(Config::class, [
            'appState' => $this->getState(State::MODE_DEVELOPER),
        ]);
        $this->assertFalse($config->isEnabled());
        $this->assertSame(500, $config->getMaxQueries());
    }

    public function testLoggerWritesToDedicatedFile(): void
    {
        $plugin = Bootstrap::getObjectManager()->create(LogCollection::class);
        $property = new ReflectionProperty(LogCollection::class, 'logger');
        $logger = $property->getValue($plugin);

        $this->assertInstanceOf(Monolog::class, $logger);
        $this->assertSame('queryDebugger', $logger->getName());
        $handlers = $logger->getHandlers();
        $this->assertCount(1, $handlers);
        $this->assertInstanceOf(Base::class, $handlers[0]);
        $this->assertStringEndsWith('/var/log/query_debugger.log', (string)$handlers[0]->getUrl());
    }

    /**
     * @magentoConfigFixture default/dev/query_debugger/enabled 1
     */
    public function testRealCollectionQueryIsLoggedInDeveloperMode(): void
    {
        $objectManager = Bootstrap::getObjectManager();
        $config = $objectManager->create(Config::class, [
            'appState' => $this->getState(State::MODE_DEVELOPER),
        ]);
        $handler = new TestHandler();
        $logger = new Monolog('test', [$handler]);
        $plugin = $objectManager->create(LogCollection::class, ['config' => $config, 'logger' => $logger]);

        $collection = $objectManager->create(StoreCollection::class);
        $plugin->beforeLoad($collection);

        $this->assertCount(1, $handler->getRecords());
        $this->assertStringContainsString('store', $handler->getRecords()[0]['message']);
    }

    /**
     * @magentoConfigFixture default/dev/query_debugger/enabled 1
     */
    public function testNothingIsLoggedInProductionMode(): void
    {
        $objectManager = Bootstrap::getObjectManager();
        $config = $objectManager->create(Config::class, [
            'appState' => $this->getState(State::MODE_PRODUCTION),
        ]);
        $handler = new TestHandler();
        $plugin = $objectManager->create(LogCollection::class, [
            'config' => $config,
            'logger' => new Monolog('test', [$handler]),
        ]);

        $plugin->beforeLoad($objectManager->create(StoreCollection::class));

        $this->assertCount(0, $handler->getRecords());
    }

    private function getState(string $mode): State
    {
        $state = $this->createMock(State::class);
        $state->method('getMode')->willReturn($mode);

        return $state;
    }
}
