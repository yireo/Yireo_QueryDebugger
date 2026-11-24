<?php

declare(strict_types=1);

namespace Yireo\QueryDebugger\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\State;

class Config
{
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly State $appState
    ) {
    }

    public function isEnabled(): bool
    {
        if ($this->appState->getMode() !== State::MODE_DEVELOPER) {
            return false;
        }

        return $this->scopeConfig->isSetFlag('dev/query_debugger/enabled');
    }

    public function getMaxQueries(): int
    {
        return max(0, (int)$this->scopeConfig->getValue('dev/query_debugger/max_queries'));
    }
}
