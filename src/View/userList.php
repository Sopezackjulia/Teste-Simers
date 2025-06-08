<?php session_start();
// Importa o controlador que lida com as ações de usuários
require_once __DIR__ . '/../Controller/UserController.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../../public/styles.css" />
  <title>Visualizar Usuários</title>
</head>

<body>
  <ul class="nav nav-pills justify-content-center shadow-sm p-3 mb-5 bg-body-tertiary rounded">
    <li class="nav-item">
      <!-- Link para o formulário de cadastro -->
      <a class="nav-link" href="index.php">Cadastro</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="#">Visualizar usuários</a>
    </li>
  </ul>

  <div class="container">
    <!-- Mostra o formulário de edição se o usuário clicar em editar -->
    <?php if ($usuarioParaEditar): ?>
      <div class="mb-4 p-3 border rounded bg-light">
        <h5>Editar Usuário: <?= htmlspecialchars($usuarioParaEditar['name']) ?></h5>

        <?php
        // Pega erros e dados antigos da sessão
        $erros = $_SESSION['erros'] ?? [];
        $dados = $_SESSION['dados'] ?? [];
        // Limpa esses dados da sessão
        unset($_SESSION['erros'], $_SESSION['dados']);
        ?>

        <!-- Formulário de edição -->
        <form action="../Controller/UserController.php" method="post" novalidate>
          <!-- ID oculto do usuário -->
          <input type="hidden" name="edit_id" value="<?= $usuarioParaEditar['id'] ?>" />

          <div class="mb-3">
            <!-- Campo de nome -->
            <label>Nome Completo</label>
            <input type="text" name="nome_completo"
              class="form-control <?= isset($erros['nome_completo']) ? 'is-invalid' : '' ?>" required
              value="<?= htmlspecialchars($dados['nome_completo'] ?? $usuarioParaEditar['name']) ?>" />
            <div class="invalid-feedback"><?= $erros['nome_completo'] ?? 'Informe seu nome completo.' ?></div>
          </div>

          <div class="mb-3">
            <!-- Campo de e-mail -->
            <label>E-mail</label>
            <input type="email" name="email" class="form-control <?= isset($erros['email']) ? 'is-invalid' : '' ?>"
              required value="<?= htmlspecialchars($dados['email'] ?? $usuarioParaEditar['email']) ?>" />
            <div class="invalid-feedback"><?= $erros['email'] ?? 'Informe um e-mail válido.' ?></div>
          </div>

          <div class="mb-3">
            <!-- Campo de data de nascimento -->
            <label>Data de Nascimento</label>
            <input type="date" name="data_nascimento"
              class="form-control <?= isset($erros['data_nascimento']) ? 'is-invalid' : '' ?>" required
              value="<?= htmlspecialchars($dados['data_nascimento'] ?? $usuarioParaEditar['birth_date']) ?>" />
            <div class="invalid-feedback"><?= $erros['data_nascimento'] ?? 'Informe sua data de nascimento.' ?></div>
          </div>

          <div class="mb-3">
            <!-- Campo de telefone -->
            <label>Telefone</label>
            <input type="text" name="telefone" class="form-control <?= isset($erros['telefone']) ? 'is-invalid' : '' ?>"
              required value="<?= htmlspecialchars($dados['telefone'] ?? $usuarioParaEditar['phone']) ?>" />
            <div class="invalid-feedback"><?= $erros['telefone'] ?? 'Informe um telefone válido (com DDD).' ?></div>
          </div>
          
          <div class="mb-3">
            <!-- Campo de senha -->
            <!-- Se o campo ficar em branco, a senha continua a mesma -->
            <label>Senha (deixe em branco para manter a atual)</label>
            <input type="password" name="senha" class="form-control <?= isset($erros['senha']) ? 'is-invalid' : '' ?>"
              minlength="6" />
            <div class="invalid-feedback"><?= $erros['senha'] ?? 'A senha deve ter no mínimo 6 caracteres.' ?></div>
          </div>

          <!-- Botão de salvar -->
          <button type="submit" class="btn btn-success">Salvar Alterações</button>
          <!-- Botão de cancelar -->
          <a href="userList.php" class="btn btn-secondary">Cancelar</a>
        </form>
      </div>
    <?php endif; ?>

    <!-- Tabela com os usuários cadastrados -->
    <div class="table-container">
      <h4 class="mb-4 text-center">Usuários Cadastrados</h4>
      <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle text-center">
          <thead class="table-dark">
            <tr>
              <th>Nome Completo</th>
              <th>CPF</th>
              <th>E-mail</th>
              <th>Data de Nascimento</th>
              <th>Telefone</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <!-- Lista os dados dos usuários na tabela -->
            <?php foreach ($usuarios as $usuario): ?>
              <tr>
                <!-- Exibe as informações do usuário, o htmlspecialchars evita problemas na segurança -->
                <td><?= htmlspecialchars($usuario['name']) ?></td>
                <td><?= htmlspecialchars($usuario['cpf']) ?></td>
                <td><?= htmlspecialchars($usuario['email']) ?></td>
                <td><?= htmlspecialchars($usuario['birth_date']) ?></td>
                <td><?= htmlspecialchars($usuario['phone']) ?></td>
                <td>
                  <!-- Botão de edição com o usuário selecionado -->
                  <a href="userList.php?edit_id=<?= $usuario['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                  <!-- Botão para exclusão -->
                  <!-- Envia um pedido ao UserController para a exclusão desse usuário -->
                  <form method="POST" action="../Controller/UserController.php" style="display:inline;"
                    onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                      <input type="hidden" name="delete_id" value="<?= $usuario['id'] ?>">
                      <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>