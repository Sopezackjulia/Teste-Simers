<?php

namespace SRC\Controller;
// Carrega o arquivo da classe user
require_once __DIR__ . '/../Model/User.php';
// Usa a classe User
use SRC\Model\User;

// Cria um objeto da classe User para usar as funções
$usuario = new User();
// Função que verifica se o CPF é real 
function validarCPF(string $cpf): bool {
    // Remove caracteres não numéricos
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    // Verifica se tem 11 números e se todos são iguais
    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para ver se o cpf é válido
    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }

    // CPF é válido
    return true;
}

// Exclui o usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    // Chama a função de exclusão
    $usuario->excluir($_POST['delete_id']);
    // Redireciona para a lista de usuários
    header("Location: ../View/userList.php");
    exit();
}

// Atualiza o usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    session_start();

    // Id do usuário que vai ser atualizado
    $id = $_POST['edit_id'];

    // Cria um array vazio para erros
    $erros = [];
    // Pega os dados enviados no formulário
    $dados = [
        'nome_completo'   => trim($_POST['nome_completo']),
        'email'           => trim($_POST['email']),
        'data_nascimento' => trim($_POST['data_nascimento']),
        'telefone'        => trim($_POST['telefone']),
        'senha'           => $_POST['senha'] ?? ''
    ];

    // Verifica se o nome do usuário foi preenchido
    if (empty($dados['nome_completo'])) {
        $erros['nome_completo'] = 'Informe seu nome completo.';
    }

    // Valida o email 
    if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        $erros['email'] = 'Informe um e-mail válido.';
    }

    // Verifica se a data de nascimento foi preenchida
    if (empty($dados['data_nascimento'])) {
        $erros['data_nascimento'] = 'Informe sua data de nascimento.';
    }

    // Valida o telefone
    if (!preg_match('/^\d{10,11}$/', $dados['telefone'])) {
        $erros['telefone'] = 'Informe um telefone válido (com DDD).';
    }

    // Valida se a senha tem 6 digitos e se está preenchida
    if (!empty($dados['senha']) && strlen($dados['senha']) < 6) {
        $erros['senha'] = 'A senha deve ter no mínimo 6 caracteres.';
    }

    // Verifica se o email já está em uso
    $usuarioEmail = $usuario->buscarPorEmail($dados['email']);
    if ($usuarioEmail && $usuarioEmail['id'] != $id) {
        $erros['email'] = 'Este e-mail já está cadastrado para outro usuário.';
    }

    // Se existir algum erro, volta para o formulário
    if (!empty($erros)) {
        $_SESSION['erros'] = $erros;
        $_SESSION['dados'] = $dados;
        header("Location: ../View/userList.php?edit_id={$id}");
        exit();
    }

    // Prepara os dados para atualização
    $dadosAtualizar = [
        'nome_completo'   => $dados['nome_completo'],
        'email'           => $dados['email'],
        'data_nascimento' => $dados['data_nascimento'],
        'telefone'        => $dados['telefone'],
    ];

    // Se a senha for preenchida, ela também atualiza
    if (!empty($dados['senha'])) {
        $dadosAtualizar['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
    }

    // Atualiza no banco
    $usuario->atualizar($id, $dadosAtualizar);

    // Limpa o formulário e erros
    unset($_SESSION['erros'], $_SESSION['dados']);

    // Redireciona para a lista de usuários
    header("Location: ../View/userList.php");
    exit();
}

// Carrega os dados do usuário no formulário de edição
$usuarioParaEditar = null;
if (isset($_GET['edit_id'])) {
    // Pega os dados do usuário para o preenchimento
    $usuarioParaEditar = $usuario->buscarPorId($_GET['edit_id']);
}

// Cadastro de usuários
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['edit_id'])) {
    session_start();

    $erros = [];
    $dados = [
        'nome_completo'   => trim($_POST['nome_completo']),
        'cpf'             => trim($_POST['cpf']),
        'email'           => trim($_POST['email']),
        'data_nascimento' => trim($_POST['data_nascimento']),
        'telefone'        => trim($_POST['telefone']),
        'senha'           => $_POST['senha'],
        'confirmar_senha' => $_POST['confirmar_senha']
    ];

    // Validação dos campos 
    if (empty($dados['nome_completo'])) {
        $erros['nome_completo'] = 'Informe seu nome completo.';
    }

    if (!preg_match('/^\d{11}$/', $dados['cpf']) || !validarCPF($dados['cpf'])) {
        $erros['cpf'] = 'Informe um CPF real e válido (somente números).';
    }

    if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        $erros['email'] = 'Informe um e-mail válido.';
    }

    if (empty($dados['data_nascimento'])) {
        $erros['data_nascimento'] = 'Informe sua data de nascimento.';
    }

    if (!preg_match('/^\d{10,11}$/', $dados['telefone'])) {
        $erros['telefone'] = 'Informe um telefone válido (com DDD).';
    }

    if (strlen($dados['senha']) < 6) {
        $erros['senha'] = 'A senha deve ter no mínimo 6 caracteres.';
    }

    if ($dados['senha'] !== $dados['confirmar_senha']) {
        $erros['confirmar_senha'] = 'Confirme a senha corretamente.';
    }

    // Verifica se o cpf já está cadastrado
    $usuarioCpf = $usuario->buscarPorCpf($dados['cpf']);
    if ($usuarioCpf) {
        $erros['cpf'] = 'Este CPF já está cadastrado.';
    }

    // Verifica se o email já está cadastrado 
    $usuarioEmail = $usuario->buscarPorEmail($dados['email']);
    if ($usuarioEmail) {
        $erros['email'] = 'Este e-mail já está cadastrado.';
    }

    if (!empty($erros)) {
        $_SESSION['erros'] = $erros;
        $_SESSION['dados'] = $dados;
        header("Location: ../View/index.php");
        exit();
    }

    // Criptografa a senha antes de salvar
    $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
    // Remove o confirmar senha
    unset($dados['confirmar_senha']);
    // Salva no banco 
    $usuario->salvar($dados);

    // Redireciona para a lista de usuários
    header("Location: ../View/userList.php");
    exit();
}

// Pega os usuários do banco e mostra os usuários na lista
$usuarios = $usuario->buscarTodos();
// Redireciona para a lista de usuários
include_once __DIR__ . '/../View/userList.php';
