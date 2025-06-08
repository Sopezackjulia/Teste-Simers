<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Importa o bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Importa o CSS -->
  <link rel="stylesheet" href="../../public/styles.css" />
  <title>Cadastro</title>
</head>

<body>
  <?php
  // Inicia a sessão
  session_start();
  // Pega os erros presentes na sessão (se existirem)
  $erros = $_SESSION['erros'] ?? [];
  // Pega os dados preenchidos pelo usuário anteriormente
  $dados = $_SESSION['dados'] ?? [];
  // Encerra a sessão
  session_destroy();
  ?>

<!-- Menu de navegação -->
  <ul class="nav nav-pills justify-content-center shadow-sm p-3 mb-5 bg-body-tertiary rounded">
    <li class="nav-item">
      <a class="nav-link active" aria-current="page" href="#">Cadastro</a>
    </li>
    <li class="nav-item">
      <!-- Link para a listagem de usuários -->
      <a class="nav-link" href="userList.php">Visualizar usuários</a>
    </li>
  </ul>

  <div class="form-container">
    <h4 class="mb-4 text-center">Cadastrar usuários</h4>
    <!-- Formulário de cadastro -->
    <form class="row g-3 needs-validation" action="../Controller/userController.php" method="POST" novalidate>
      <div class="col-12">
        <!-- Campo de nome -->
        <label for="nomeCompleto" class="form-label">Nome completo</label>
        <!-- Se tiver erro, adiciona a classe is-invalid para estilização -->
        <input type="text" class="form-control <?= isset($erros['nome_completo']) ? 'is-invalid' : '' ?>"
          id="nomeCompleto" name="nome_completo" required
          value="<?= htmlspecialchars($dados['nome_completo'] ?? '') ?>">
          <!-- Mostra a mensagem de erro -->
        <div class="invalid-feedback"><?= $erros['nome_completo'] ?? 'Informe seu nome completo.' ?></div>
      </div>


      <div class="col-12">
        <!-- Campo de cpf -->
        <label for="cpf" class="form-label">CPF</label>
        <input type="text" class="form-control <?= isset($erros['cpf']) ? 'is-invalid' : '' ?>" id="cpf" name="cpf"
          required pattern="\d{11}" maxlength="11" value="<?= htmlspecialchars($dados['cpf'] ?? '') ?>">
        <div class="invalid-feedback"><?= $erros['cpf'] ?? 'Informe um CPF válido (somente números).' ?></div>
      </div>

      <div class="col-12">
        <!-- Campo de e-mail -->
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control <?= isset($erros['email']) ? 'is-invalid' : '' ?>" id="email"
          name="email" required value="<?= htmlspecialchars($dados['email'] ?? '') ?>">
        <div class="invalid-feedback"><?= $erros['email'] ?? 'Informe um e-mail válido.' ?></div>
      </div>

      <div class="col-12">
        <!-- Campo de data de nascimento -->
        <label for="dataNascimento" class="form-label">Data de nascimento</label>
        <input type="date" class="form-control <?= isset($erros['data_nascimento']) ? 'is-invalid' : '' ?>"
          id="dataNascimento" name="data_nascimento" required
          value="<?= htmlspecialchars($dados['data_nascimento'] ?? '') ?>">
        <div class="invalid-feedback"><?= $erros['data_nascimento'] ?? 'Informe sua data de nascimento.' ?></div>
      </div>


      <div class="col-12">
        <!-- Campo de telefone -->
        <label for="telefone" class="form-label">Telefone</label>
        <input type="tel" class="form-control <?= isset($erros['telefone']) ? 'is-invalid' : '' ?>"
          id="telefone" name="telefone" required pattern="\d{10,11}"
          value="<?= htmlspecialchars($dados['telefone'] ?? '') ?>">
        <div class="invalid-feedback"><?= $erros['telefone'] ?? 'Informe um telefone válido (com DDD).' ?></div>
      </div>

      <div class="col-12">
        <!-- Campo de senha -->
        <label for="senha" class="form-label">Senha</label>
        <input type="password" class="form-control <?= isset($erros['senha']) ? 'is-invalid' : '' ?>"
          id="senha" name="senha" required minlength="6">
        <div class="invalid-feedback"><?= $erros['senha'] ?? 'A senha deve ter no mínimo 6 caracteres.' ?></div>
      </div>

      <div class="col-12">
        <!-- Campo para a confirmação da senha -->
        <label for="confirmarSenha" class="form-label">Confirme a senha</label>
        <input type="password"
          class="form-control <?= isset($erros['confirmar_senha']) ? 'is-invalid' : '' ?>"
          id="confirmarSenha" name="confirmar_senha" required minlength="6">
        <div class="invalid-feedback"><?= $erros['confirmar_senha'] ?? 'Confirme a senha corretamente.' ?></div>
      </div>

      <div class="col-12 text-center">
        <!-- Botão de cadastro -->
        <button class="btn btn-primary mt-3" type="submit">Cadastrar</button>
      </div>
    </form>
  </div>
</body>
</html>