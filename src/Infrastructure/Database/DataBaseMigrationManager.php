<?php

require __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;
use Strolker\CleanArchitecture\Infrastructure\Persistence\DAO\MainDAO;
use Strolker\CleanArchitecture\Infrastructure\Database\DatabaseConnection;

class DataBaseMigrationManager
{
    private $dao;

    public function __construct($host, $dbname, $user, $password)
    {
        $dsn = "pgsql:host={$host};dbname={$dbname};user={$user};password={$password}";

        $this->dao = new MainDAO($dsn);
    }

    public function executeSqlFile($filePath)
    {
        $sql = file_get_contents($filePath);
        $this->dao->executeQuery($sql);
    }

    public function up()
    {
        pre("Creating tables...");
        $this->executeSqlFile(__DIR__ . '/Migrations/schemas/public/create_tables.sql');
        pre("Tables created successfully.");
    }

    public function down()
    {
        pre("Dropping tables...");
        $this->executeSqlFile(__DIR__ . '/Migrations/schemas/public/drop_tables.sql');
        pre("Tables dropped successfully.");
    }

    public function seed()
    {
        pre("Seeding database...");
        $this->executeSqlFile(__DIR__ . '/Migrations/schemas/public/seed_data.sql');
        pre("Database seeded successfully.");
    }
}

class DatabaseCreator
{
    private $connection;

    public function __construct($host, $user, $password)
    {
        // Conecta sem selecionar uma base de dados
        $this->connection = DatabaseConnection::getInstance(null, true)->getConnection();
    }

    public function createDatabase(string $databaseName): void
    {
        $query = "CREATE DATABASE \"$databaseName\"";

        try {
            $this->connection->exec($query);
            pre("Banco de dados '$databaseName' criado com sucesso!");
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar o banco de dados: " . $e->getMessage());
        }
    }
}


$dotenv = Dotenv::createImmutable(__DIR__, '.env.dev');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

pre("host: " . $host);
pre("dbname: " . $dbname);
pre("user: " . $user);
pre("password: " . $password);
$command = $argv[1] ?? '';

pre("command: " . $command);

switch ($command) {
    case 'create-db':
        // Usamos o DatabaseCreator para criar a base de dados
        $creator = new DatabaseCreator($host, $user, $password);
        $creator->createDatabase($dbname);
        break;

    case 'up':
        // Usamos o DatabaseManager para operações no banco de dados já existente
        $manager = new DataBaseMigrationManager($host, $dbname, $user, $password);
        $manager->up();
        break;

    case 'down':
        $manager = new DataBaseMigrationManager($host, $dbname, $user, $password);
        $manager->down();
        break;

    case 'seed':
        $manager = new DataBaseMigrationManager($host, $dbname, $user, $password);
        $manager->seed();
        break;

    default:
        echo "Command not recognized. Use 'create-db', 'up', 'down', or 'seed'.";
        break;
}