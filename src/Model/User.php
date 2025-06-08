<?php

// Agrupa a classe dentro de Model
namespace SRC\Model;
// Importa a classe de conexão com o banco de dados 
require_once 'Connection.php';
use SRC\Model\Connection;
// Usa PDO para trabalhar com o banco
use PDO;

class User
{
    // Guarda a conexão com o banco
    private $conn;

    public function __construct()
    {
        // Cria uma nova conexão quando a classe for usada
        $this->conn = (new Connection())->getConnection();
    }

    public function salvar($dados)
    {
        // Insere um novo usuário no banco de dados
        $sql = "INSERT INTO users (name, cpf, email, birth_date, phone, password)
                VALUES (:name, :cpf, :email, :birth_date, :phone, :password)";

        // Prepara e executa a consulta com os dados informados
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name'       => $dados['nome_completo'],
            ':cpf'        => $dados['cpf'],
            ':email'      => $dados['email'],
            ':birth_date' => $dados['data_nascimento'],
            ':phone'      => $dados['telefone'],
            ':password'   => $dados['senha']
        ]);
    }

    public function buscarTodos()
    {
        // Busca os usuários cadastrados
        $sql = "SELECT * FROM users";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluir($id)
    {
        // Exclui o usuário de acordo com o id
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function buscarPorId($id)
{
    // Busca um usuário de acordo com o id
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function atualizar($id, $dados)
{
    // Atualiza os usuários 
    $sql = "UPDATE users SET name = :name, email = :email, birth_date = :birth_date, phone = :phone";

    // Se a senha foi preenchida, ela é adicionada na consulta
    if (!empty($dados['senha'])) {
        $sql .= ", password = :password";
    }
    // Acrescenta na consulta o id do usuário para a atualização
    $sql .= " WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    // Prepara os dados obrigatórios
    $params = [
        ':name'       => $dados['nome_completo'],
        ':email'      => $dados['email'],
        ':birth_date' => $dados['data_nascimento'],
        ':phone'      => $dados['telefone'],
        ':id'         => $id
    ];

    // Adiciona a senha se ela tiver sido preenchido 
    if (!empty($dados['senha'])) {
        $params[':password'] = $dados['senha'];
    }

    // Realiza o update
    return $stmt->execute($params);
}


public function buscarPorCpf(string $cpf) {
    // Procura o usuário pelo cpf
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE cpf = :cpf");
    $stmt->execute([':cpf' => $cpf]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function buscarPorEmail(string $email) {
    // Procura o usuário pelo email
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}

