<?php


namespace App\Repository;

use App\Services\Database\DatabaseInterface;


class RepositoryManager
{
    /**
     * @var DatabaseInterface
     */
    private $database;

    /**
     * @var array
     */
    private $repositories = [];

    /**
     * RepositoryManager constructor.
     * @param array $repositoriesConfig
     * @param DatabaseInterface $database
     */
    public function __construct(array $repositoriesConfig, DatabaseInterface $database)
    {
        $this->database = $database;

        $this->loadRepositories($repositoriesConfig);
    }

    /**
     * @param array $repositoriesConfig
     */
    protected function loadRepositories(array $repositoriesConfig): void
    {
        foreach ($repositoriesConfig as $repository => $repositoryConfig) {
            $this->repositories[$repository] = new $repositoryConfig['repository'](
                $repositoryConfig['file'],
                $repositoryConfig['entity'],
                $this->database
            );
        }
    }

    /**
     * @param string $repositoryName
     * @return Repository
     */
    public function getRepository(string $repositoryName): Repository
    {
        return $this->repositories[$repositoryName];
    }
}
