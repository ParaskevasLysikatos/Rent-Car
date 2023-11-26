<?php

namespace Tests;

use App\Booking;
use App\Type;
use App\User;
use App\UserRole;
use App\Vehicle;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    private function truncate()
    {
        $truncate = isset($this->truncate) && is_array($this->truncate) ? $this->truncate : [];

        foreach ($truncate as $table) {
            DB::table($table)->delete();
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->truncate();
    }

    protected function tearDown(): void
    {
        $this->truncate();

        parent::tearDown();
    }
}
