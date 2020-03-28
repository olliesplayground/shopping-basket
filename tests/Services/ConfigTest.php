<?php


namespace App\Test\Services;


use App\Services\Config\Config;
use Mockery;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        define('APPPATH', __DIR__.'/../Data/');
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testGetConfig(): void
    {
        $config = Config::getConfig();

        $this->assertArrayHasKey('test', $config);
    }
}
