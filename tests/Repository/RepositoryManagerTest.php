<?php


namespace App\Test\Repository;


use App\Repository\RepositoryManager;
use App\Services\Database\Database;
use Mockery;
use PHPUnit\Framework\TestCase;

class RepositoryManagerTest extends TestCase
{
    protected $repositoryManager;

    protected function setUp(): void
    {
        parent::setUp();

        $database = Mockery::mock(Database::class);
        $database->shouldReceive('get')
            ->andReturn([]);

        $database->shouldReceive('getOne')
            ->andReturn([]);

        $config = [
            'products' => [
                'repository' => \App\Repository\Products::class,
                'entity' => \App\Entity\Product::class,
                'file' => 'products.json'
            ]
        ];

        $this->repositoryManager = new RepositoryManager($config, $database);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testGetRepository(): void
    {
        $repo = $this->repositoryManager->getRepository('products');

        $this->assertInstanceOf(\App\Repository\Products::class, $repo);
    }
}
