<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    private static $rolesCreated = false;

    /**
     * Creates the application.
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create basic roles for testing if they don't exist (only once per test suite)
        if (!self::$rolesCreated) {
            $roles = ['admin', 'technicien', 'client', 'manager'];
            
            foreach ($roles as $roleName) {
                Role::firstOrCreate([
                    'name' => $roleName,
                    'guard_name' => 'web'
                ]);
            }
            
            self::$rolesCreated = true;
        }
    }
}
