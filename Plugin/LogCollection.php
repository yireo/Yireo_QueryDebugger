<?php

declare(strict_types=1);

namespace Yireo\QueryDebugger\Plugin;

use Magento\Framework\Data\Collection;

class LogCollection
{
    public function beforeLoad(Collection $collection, $printQuery = false, $logQuery = false)
    {
        $logQuery = true;
        return [$printQuery, $logQuery];
    }
}
