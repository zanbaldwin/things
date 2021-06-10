<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection as DbalConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DoctrinePurgeCommand extends Command
{
    protected static $defaultName = 'doctrine:purge';
    protected static $defaultDescription = 'Purge the database of all data.';

    /** @var string[] */
    private const IMMUTABLE_TABLES = [];

    private DbalConnection $conn;

    public function __construct(EntityManagerInterface $em)
    {
        $this->conn = $em->getConnection();
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sql = 'SELECT TABLE_NAME AS `table` FROM `information_schema`.`TABLES` WHERE `TABLE_SCHEMA` = :dbName';
        $statement = $this->conn->prepare($sql);
        $statement->execute(['dbName' => $this->conn->getDatabase()]);
        $tables = array_filter(array_map(function (array $row): ?string {
            return $row['table'] ?? null;
        }, $statement->fetchAllAssociative()), function (?string $tableName): bool {
            return is_string($tableName) && $tableName !== '' && !in_array($tableName, static::IMMUTABLE_TABLES, true);
        });

        $this->conn->exec('SET FOREIGN_KEY_CHECKS=0');
        foreach ($tables as $tableName) {
            $this->conn->exec(sprintf(
                'DELETE FROM %s.%s',
                $this->conn->quoteIdentifier($this->conn->getDatabase()),
                $this->conn->quoteIdentifier($tableName)
            ));
        }
        $this->conn->exec('SET FOREIGN_KEY_CHECKS=1');

        return Command::SUCCESS;
    }
}
