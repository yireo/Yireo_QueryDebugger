<?php

declare(strict_types=1);

namespace Yireo\QueryDebugger\Plugin;

use Magento\Framework\Data\Collection\AbstractDb;
use Psr\Log\LoggerInterface;
use Yireo\QueryDebugger\Config\Config;

class LogCollection
{
    private bool $resolving = false;
    private int $loggedQueries = 0;

    public function __construct(
        private readonly Config $config,
        private readonly LoggerInterface $logger
    ) {
    }

    public function beforeLoad(AbstractDb $collection, mixed $printQuery = false, mixed $logQuery = false): ?array
    {
        if ($this->resolving || $collection->isLoaded() || !$this->isEnabled()) {
            return null;
        }

        $maxQueries = $this->config->getMaxQueries();
        if ($maxQueries > 0 && $this->loggedQueries >= $maxQueries) {
            return null;
        }

        $this->loggedQueries++;
        $sql = (string)$collection->getSelectSql(true);
        $this->logger->debug(str_replace(["\r\n", "\r", "\n"], ' ', $sql));

        return null;
    }

    private function isEnabled(): bool
    {
        $this->resolving = true;
        try {
            return $this->config->isEnabled();
        } finally {
            $this->resolving = false;
        }
    }
}
