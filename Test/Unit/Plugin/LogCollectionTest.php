<?php

declare(strict_types=1);

namespace Yireo\QueryDebugger\Test\Unit\Plugin;

use Magento\Framework\Data\Collection\AbstractDb;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Yireo\QueryDebugger\Config\Config;
use Yireo\QueryDebugger\Plugin\LogCollection;

class LogCollectionTest extends TestCase
{
    public function testNothingIsLoggedWhenDisabled(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->never())->method('debug');

        $plugin = new LogCollection($this->getConfig(false, 0), $logger);
        $this->assertNull($plugin->beforeLoad($this->getCollection('SELECT 1')));
    }

    public function testQueryIsLoggedWhenEnabled(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('debug')->with('SELECT 1');

        $plugin = new LogCollection($this->getConfig(true, 0), $logger);
        $this->assertNull($plugin->beforeLoad($this->getCollection('SELECT 1'), false, false));
    }

    public function testLoadedCollectionIsNotLogged(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->never())->method('debug');

        $plugin = new LogCollection($this->getConfig(true, 0), $logger);
        $plugin->beforeLoad($this->getCollection('SELECT 1', true));
    }

    public function testMaximumNumberOfQueriesIsRespected(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->exactly(2))->method('debug');

        $plugin = new LogCollection($this->getConfig(true, 2), $logger);
        for ($i = 0; $i < 5; $i++) {
            $plugin->beforeLoad($this->getCollection('SELECT ' . $i));
        }
    }

    public function testLineBreaksAreStripped(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('debug')
            ->with("SELECT * FROM foo WHERE bar = 'a b c [fake] d'");

        $plugin = new LogCollection($this->getConfig(true, 0), $logger);
        $plugin->beforeLoad($this->getCollection("SELECT * FROM foo WHERE bar = 'a\nb\r\nc\r[fake] d'"));
    }

    private function getConfig(bool $enabled, int $maxQueries): Config&MockObject
    {
        $config = $this->createMock(Config::class);
        $config->method('isEnabled')->willReturn($enabled);
        $config->method('getMaxQueries')->willReturn($maxQueries);

        return $config;
    }

    private function getCollection(string $sql, bool $loaded = false): AbstractDb&MockObject
    {
        $collection = $this->createMock(AbstractDb::class);
        $collection->method('isLoaded')->willReturn($loaded);
        $collection->method('getSelectSql')->with(true)->willReturn($sql);

        return $collection;
    }
}
