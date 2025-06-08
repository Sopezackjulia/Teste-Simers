<?php
namespace SRC\Model;
// Usa a biblioteca para carregar as variáveis do .env
use Dotenv\Dotenv;
// Usa a classe PDO para a conexão com o banco
use PDO;
// Usa exceções para erros do PDO
use PDOException;
// Carrega o arquivo autoload.php
require_once __DIR__ . '/../../vendor/autoload.php';

class Connection
{
    // Guarda a conexão com o banco
    private $connection;

    public function __construct()
    {
        // Carrega as variáveis do .env
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        // Pega as configurações do .env
        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];
        $charset = $_ENV['DB_CHARSET'];

        // Cria a string de conexão com o banco
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

        try {
            // Tenta criar a conexão usando PDO
            $this->connection = new PDO($dsn, $user, $pass);
            // Configura para mostrar os erros
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Se der erro, mostra uma mensagem com o problema
            throw new \Exception("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    // Função para pegar a conexão criada
    public function getConnection()
    {
        return $this->connection;
    }
}
