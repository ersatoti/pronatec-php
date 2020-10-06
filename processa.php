<?php

class Pessoa
{

  var $conexao = "";
  var $nome = "";
  var $sobrenome = "";
  var $sexo = "";
  var $email = "";
  var $telefone = "";
  var $imagem = "";

  public function abrirConexaoBanco()
  {
    $this->conexao = mysqli_connect('localhost', 'user', 'pass', 'database');
    if (!$this->conexao) {
      die('Não foi possível conectar ao MySQL');
    }
    return $this->conexao;
  }

  public function setValuesForm()
  {
    $this->nome = $_POST['nome'];
    $this->sobrenome = $_POST['sobrenome'];
    $this->sexo = $_POST['sexo'];
    $this->email = $_POST['email'];
    $this->telefone = $_POST['telefone'];
  }

  public function salvarImagem()
  {
    if (isset($_FILES['arquivo']['name']) && $_FILES['arquivo']['error'] == 0) {
      $arquivo_tmp = $_FILES['arquivo']['tmp_name'];
      $nomeFile = $_FILES['arquivo']['name'];
      $extensao = pathinfo($nomeFile, PATHINFO_EXTENSION);
      $extensao = strtolower($extensao);
      if (strstr('.jpg;.jpeg;.gif;.png', $extensao)) {
        $novoNome = uniqid(time()) . '.' . $extensao;
        $destino = $_SERVER['DOCUMENT_ROOT'] . '/0210/upload/' . $novoNome;
        if (@move_uploaded_file($arquivo_tmp, $destino)) {
          $imgHost = str_replace("main.php", "upload/" . $novoNome, $_SERVER['HTTP_REFERER']);
          $this->imagem = $destino;
        }
      } else
        echo 'Você poderá enviar apenas arquivos "*.jpg;*.jpeg;*.gif;*.png"<br />';
    }
  }

  public function inserirRegistro()
  {
    // Criando a declaração SQL:
    $sql = "INSERT INTO Pessoa(nome, sobrenome, sexo, email, telefone, imagem)
    VALUES ('$this->nome','$this->sobrenome','$this->sexo', '$this->email', '$this->telefone', '$this->imagem')";

    // Executando a declaração no banco de dados:
    $resultado = mysqli_query($this->conexao, $sql) or die("Erro ao executar a inserção dos dados");
    echo "Registro inserido com sucesso";
  }

  public function fecharConexao()
  {
    mysqli_close($this->conexao);
  }

  public function voltarTeleInicial()
  {
    echo "<br><br><a href='index.html'>Voltar à página inicial</a>";
  }
}

$pessoa = new Pessoa();

$pessoa->abrirConexaoBanco();
$pessoa->setValuesForm();
$pessoa->salvarImagem();
$pessoa->inserirRegistro();
$pessoa->fecharConexao();
$pessoa->voltarTeleInicial();
