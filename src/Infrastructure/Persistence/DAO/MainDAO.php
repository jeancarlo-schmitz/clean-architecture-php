<?php 
namespace Strolker\CleanArchitecture\Infrastructure\Persistence\DAO;

use Strolker\CleanArchitecture\Infrastructure\Database\DatabaseConnection;
use PDO;
use Exception;

class MainDAO
{
    private $fetchMode = PDO::FETCH_ASSOC; // Modo padrão de fetch
    protected PDO $connection;
    protected array $parameters = [];
    protected array $preparedStatements = [];

    public function __construct($dsn = null)
    {
        $this->connection = DatabaseConnection::getInstance($dsn)->getConnection();
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function clearParameters(): void
    {
        $this->parameters = [];
    }

    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    public function commitTransaction(): void
    {
        $this->connection->commit();
    }

    public function rollbackTransaction(): void
    {
        $this->connection->rollBack();
    }

    public function getOneRow($sql): array
    {
        $stmt = $this->executeQuery($sql);
        return $stmt->fetch($this->fetchMode);
    }

    public function getArray($sql): array
    {
        $stmt = $this->executeQuery($sql);
        return $stmt->fetchAll($this->fetchMode);
    }

    public function getOne($sql)
    {
        $stmt = $this->executeQuery($sql);
        $result = $stmt->fetch(PDO::FETCH_NUM);
        return $result ? $result[0] : '';
    }

    public function executeQuery(string $sql): mixed
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($this->parameters);
            $this->clearParameters();
            return $stmt;
        } catch (Exception $e) {
            throw new Exception('Erro ao executar a query: ' . $e->getMessage());
        }
    }

    public function setFetchMode($fetchMode): void
    {
        switch ($fetchMode) {
            case 'assoc':
                $this->fetchMode = PDO::FETCH_ASSOC;
                break;
            case 'num':
                $this->fetchMode = PDO::FETCH_NUM;
                break;
            case 'both':
                $this->fetchMode = PDO::FETCH_BOTH;
                break;
            default:
                $this->fetchMode = PDO::FETCH_ASSOC;
        }
    }
}