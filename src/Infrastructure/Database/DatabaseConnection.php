<?php

namespace Strolker\CleanArchitecture\Infrastructure\Database;

use Dotenv\Dotenv;
use PDO;
use PDOException;
use Exception;

class DatabaseConnection
{
    private static array $instances = [];
    private PDO $connection;

    private function __construct(string $dsn = null, bool $withoutDatabase = false)
    {
        if ($dsn) {
            $this->initializeConnection($dsn);
        } else if($withoutDatabase) {
            $this->connectWithoutDatabase();
        } else {
            $this->createDefaultConnection();
        }

        if (!$this->connection) {
            throw new DatabaseConnectionException("Falha ao conectar ao banco de dados.");
        }

        echo "Conectado com sucesso";
    }

    public function createDefaultConnection(): void
    {
        $dsn = $this->loadEnvironmentVariables();
        $this->initializeConnection($dsn);
    }

    private function loadEnvironmentVariables(bool $withoutDatabase = false): string
    {
        $appEnv = $_ENV['APP_ENV'] ?? 'dev';
        $dotenv = Dotenv::createImmutable(__DIR__, ".env." . $appEnv);
        $dotenv->load();

        $host = $_ENV['DB_HOST'];
        $user = $_ENV['DB_USER'];
        $password = $_ENV['DB_PASSWORD'];

        // Se a conexão for sem banco de dados, retorna a string DSN sem o nome do banco
        if ($withoutDatabase) {
            return "pgsql:host=$host";
        }

        $dbname = $_ENV['DB_NAME'];
        return "pgsql:host=$host;dbname=$dbname";
    }

    private function initializeConnection(string $dsn): void
    {
        try {
            $user = $_ENV['DB_USER'];
            $password = $_ENV['DB_PASSWORD'];
            $this->connection = new PDO($dsn, $user, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new DatabaseConnectionException("Erro ao conectar com o banco de dados: " . $e->getMessage());
        }
    }

    // Novo método para conectar sem banco de dados
    public function connectWithoutDatabase(): void
    {
        $dsn = $this->loadEnvironmentVariables(true);
        $this->initializeConnection($dsn);
    }

    public static function getInstance(string $dsn = null, bool $withoutDatabase = false): DatabaseConnection
    {
        if ($dsn) {
            return self::getOrCreateCustomInstance($dsn, $withoutDatabase);
        }

        return self::getOrCreateDefaultInstance($withoutDatabase);
    }

    private static function getOrCreateCustomInstance(string $dsn, bool $withoutDatabase = false): DatabaseConnection
    {
        if (!isset(self::$instances[$dsn])) {
            self::$instances[$dsn] = new DatabaseConnection($dsn, $withoutDatabase);
        }
        return self::$instances[$dsn];
    }

    private static function getOrCreateDefaultInstance(bool $withoutDatabase = false): DatabaseConnection
    {
        if (!isset(self::$instances['default'])) {
            self::$instances['default'] = new DatabaseConnection(null, $withoutDatabase);
        }
        return self::$instances['default'];
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}

class DatabaseConnectionException extends Exception {}