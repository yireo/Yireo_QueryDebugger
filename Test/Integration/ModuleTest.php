<?php

declare(strict_types=1);

namespace Yireo\QueryDebugger\Test\Integration;

use PHPUnit\Framework\TestCase;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertModuleIsEnabled;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertModuleIsRegistered;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertModuleIsRegisteredForReal;

class ModuleTest extends TestCase
{
    use AssertModuleIsEnabled;
    use AssertModuleIsRegistered;
    use AssertModuleIsRegisteredForReal;

    public function testIfModuleWorks(): void
    {
        $this->assertModuleIsEnabled('Yireo_QueryDebugger');
        $this->assertModuleIsRegistered('Yireo_QueryDebugger');
        $this->assertModuleIsRegisteredForReal('Yireo_QueryDebugger');
    }
}
