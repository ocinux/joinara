<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        // PHP 8.6 emits a one-shot warning when ArraySessionHandler is first declared
        // (missing create_sid() required in PHP 9.0). Laravel's error handler promotes
        // it to ErrorException mid-request, failing the first session-touching test of
        // each process. Preload the class outside request handling to absorb it.
        set_error_handler(static function (): bool { return true; }, E_ALL);
        class_exists(\Illuminate\Session\ArraySessionHandler::class);
        restore_error_handler();

        parent::setUp();
    }
}
