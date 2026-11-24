<?php

declare(strict_types=1);

namespace Yireo\QueryDebugger\Test\Unit\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;
use PHPUnit\Framework\TestCase;
use Yireo\QueryDebugger\Config\Config;

class ConfigTest extends TestCase
{
    public function testIsDisabledOutsideDeveloperMode(): void
    {
        $scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $scopeConfig->expects($this->never())->method('isSetFlag');

        foreach ([State::MODE_PRODUCTION, State::MODE_DEFAULT] as $mode) {
            $config = new Config($scopeConfig, $this->getState($mode));
            $this->assertFalse($config->isEnabled());
        }
    }

    public function testIsEnabledInDeveloperModeWithFlag(): void
    {
        $scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $scopeConfig->method('isSetFlag')->with('dev/query_debugger/enabled')->willReturn(true);

        $config = new Config($scopeConfig, $this->getState(State::MODE_DEVELOPER));
        $this->assertTrue($config->isEnabled());
    }

    public function testIsDisabledInDeveloperModeWithoutFlag(): void
    {
        $scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $scopeConfig->method('isSetFlag')->willReturn(false);

        $config = new Config($scopeConfig, $this->getState(State::MODE_DEVELOPER));
        $this->assertFalse($config->isEnabled());
    }

    public function testMaxQueriesIsNeverNegative(): void
    {
        $scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $scopeConfig->method('getValue')->willReturnOnConsecutiveCalls('25', '-3', null);

        $config = new Config($scopeConfig, $this->getState(State::MODE_DEVELOPER));
        $this->assertSame(25, $config->getMaxQueries());
        $this->assertSame(0, $config->getMaxQueries());
        $this->assertSame(0, $config->getMaxQueries());
    }

    private function getState(string $mode): State
    {
        $state = $this->createMock(State::class);
        $state->method('getMode')->willReturn($mode);

        return $state;
    }
}
