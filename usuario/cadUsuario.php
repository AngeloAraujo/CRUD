<?php
include_once "../config/conf.inc.php";
// pega variáveis enviadas via GET - são enviadas para edição de um registro
$acao = isset($_GET['acao']) ? $_GET['acao'] : "";
$id = isset($_GET['id']) ? $_GET['id'] : "";
// verifica se está editando um registro
if ($acao == 'editar') {
    // buscar dados do usuário que estamos editando
    try {
        // cria a conexão com o banco de dados 
        $conexao = new PDO(MYSQL_DSN, DB_USER, DB_PASSWORD);
        // montar consulta
        $query = 'SELECT * FROM usuario WHERE id = :id';
        // preparar consulta
        $stmt = $conexao->prepare($query);
        // vincular variaveis com a consult
        $stmt->bindValue(':id', $id);
        // executa a consulta
        $stmt->execute();
        // pega o resultado da consulta - nesse caso a consulta retorna somente um registro pq estamos buscando pelo ID que é único 
        // por isso basta um fetch
        $usuario = $stmt->fetch();
    } catch (PDOException $e) { // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
        print("Erro ao conectar com o banco de dados...<br>" . $e->getMessage());
        die();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

    <title>Cadastro de Usuário</title>
    <script>
        // floreio -- para o usuário confirmar a exclusão
        function excluir(url) {
            if (confirm("Confirma a exclusão?"))
                window.location.href = url; //redireciona para o arquivo que irá efetuar a exclusão
        }
        window.onload = (function() {

            document.getElementById('fpesquisa').addEventListener('submit', function(ev) {
                ev.preventDefault()
                carregaDados();
            });
        });

        function carregaDados() {
            busca = document.getElementById('busca').value;
            const xhttp = new XMLHttpRequest(); // cria o objeto que fará a conexão assíncrona
            xhttp.onload = function() { // executa essa função quando receber resposta do servidor
                dados = JSON.parse(this.responseText); // os dados são convertidos para objeto javascript
                montaTabela(dados); // chama função que montará a tabela na interface
            }
            // configuração dos parâmetros da conexão assíncrona
            xhttp.open("GET", "pesquisa.php?busca=", true); // arquivo que será acessado no servidor remoto  
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); // cabeçalhos - necessário para requisição POST
            xhttp.send("busca=" + busca); // parâmetros para a requisição
        }
    </script>
</head>

<body class='container'>
    <h1>Cadastrar novo Usuário</h1>
    <section id='cadusuario' class='row'>
        <!-- Formulário para cadastro e edição de dados do usuário, 
        caso seja aberto a página com a ação de editar o formulário trará os campos preenchidos com os dados do registro selecionado  -->
        <div class='col'>
            <form action="acao.php" method="post">
                <!-- esse formulário envia os dados para o arquivo acao.php -->
                <div id="telaproduto">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="modal-body">
                                    <div class="form-group">Id:
                                        <input type="text" class='form-control' style='width:50px' readonly name="id" id="id" value=<?php if (isset($usuario)) echo $usuario['id'];
                                                                                                                                    else echo 0; ?>>
                                    </div>
                                    <div class="form-group">Nome
                                        <input class="form-control" type="text" id="nome" name="nome" placeholder="Digite seu nome" value=<?php if (isset($usuario)) echo $usuario['nome'] ?>>
                                    </div><br>
                                    <div class="form-group">Sobrenome:
                                        <input class="form-control" type="text" id="sobrenome" name="sobrenome" placeholder="Digite seu sobrenome" value=<?php if (isset($usuario)) echo $usuario['sobrenome'] ?>>
                                    </div><br>
                                    <div class="form-group">E-mail:
                                        <input class="form-control" type="email" id="email" name="email" placeholder="Digite seu e-mail" value=<?php if (isset($usuario)) echo $usuario['email'] ?>>
                                    </div><br>
                                    <div class="form-group">Senha:
                                        <input class="form-control" type="password" id="senha" name="senha" placeholder="Digite uma senha" value=<?php if (isset($usuario)) echo $usuario['senha'] ?>>
                                    </div><br>
                                    <div class="form-group">Data de Nascimento:
                                        <input class="form-control" type="date" id="dtnasc" name="dtnasc" value=<?php if (isset($usuario)) echo $usuario['dtnasc'] ?>>
                                    </div><br>

                                    <div class="form-group">Parente:
                                        <input class= "form-control" type="text" id="parente" name="parente" value=<?php if (isset($usuario)) echo $usuario['parente'] ?>>
                                    </div><br>
                                    <div class="form-group">Origem:
                                        <input class= "form-control" type="text" id="origem" name="origem" value=<?php if (isset($usuario)) echo $usuario['origem'] ?>>
                                    </div><br>
                                    <div class="form-group">Endereço:
                                        <input class= "form-control" type="text" id="endereco" name="endereco" value=<?php if (isset($usuario)) echo $usuario['endereco'] ?>>
                                    </div><br>
                                    <div class="form-group">Cidade:
                                        <input class= "form-control" type="text" id="cidade" name="cidade" value=<?php if (isset($usuario)) echo $usuario['cidade'] ?>>
                                    </div><br>
                                    <div class="form-group">Telefone para Contato:
                                        <input class="form-control" type="text" id="telefone" name="telefone" value=<?php if (isset($usuario)) echo $usuario['telefone'] ?>>
                                    </div><br>
                                </div>
                            </div>

                        </div>
                    </div>
                        <div class='col'>
                            <br>
                            <button type='submit' name='acao' value='salvar' class='btn btn-primary'>Enviar</button>
                        </div>
                    </div>
            </form>
        </div>
    </section>
    <hr>
    <!-- Nesta seção serão listados os usuários já cadastrados no banco de dados -->
    <section class='row'>
        <!-- esse formulário é para permitir a pesquisa de um usuário cadastrado -->
        <div class='col'>
            <form action="" method="get" id='pesquisa'>
                <!-- esse formulário submte para essa mesma página para recarregar com o resultado da busca -->
                <div class='row'>
                    <div class='col-8'>
                        <h2> Lista de Usuários cadastrados</h2>
                    </div>
                    <div class='col'><input class='form-control' type="search" name='busca' id='busca'></div>
                    <div class='col'><button type="submit" class='btn btn-success' name='pesquisa'>Buscar</button></div>
                </div>
            </form>
            <div class='row'>
                <!-- aqui montamos a tabela com os dados vindo do banco -->
                <table class='table table-striped table-hover'>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nome</th>
                            <th>Sobrenome</th>
                            <th>Data de Nascimento</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Parente</th>
                            <th>Origem</th>
                            <th>Endereço</th>
                            <th>Cidade</th>
                            <th>Senha</th>
                            <th>Editar</th>
                            <th>Excluir</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        <?php
                        try {
                            // cria  a conexão com o banco de dados 
                            $conexao = new PDO(MYSQL_DSN, DB_USER, DB_PASSWORD);
                            // pega o valor informado pelo usuário no formulário de pesquisa
                            $busca = isset($_GET['busca']) ? $_GET['busca'] : "";
                            // monta consulta
                            $query = 'SELECT * FROM usuario';
                            if ($busca != "") { // se o usuário informou uma pesquisa
                                $busca = '%' . $busca . '%'; // concatena o curiga * na pesquisa
                                $query .= ' WHERE nome like :busca'; // acrescenta a clausula where
                            }
                            // prepara consulta
                            $stmt = $conexao->prepare($query);
                            // vincular variaveis com a consulta
                            if ($busca != "") // somente se o usuário informou uma busca
                                $stmt->bindValue(':busca', $busca);
                            // executa a consuta 
                            $stmt->execute();
                            // pega todos os registros retornados pelo banco
                            $usuarios = $stmt->fetchAll();
                            foreach ($usuarios as $usuario) { // percorre o array com todos os usuários imprimindo as linhas da tabela
                                $editar = '<a href=cadUsuario.php?acao=editar&id=' . $usuario['id'] . '>Alt</a>';
                                $excluir = "<a href='#' onclick=excluir('acao.php?acao=excluir&id={$usuario['id']}')>Excluir</a>";
                                echo '<tr><td>' . $usuario['id'] . '</td><td>' . $usuario['nome'] . '</td><td>' . $usuario['sobrenome'] . '</td><td>' . $usuario['dtnasc'] . '</td>
                                <td>' . $usuario['email'] . '</td><td>' . $usuario['telefone'] . '</td>
                                <td>' . $usuario['parente'] . '</td><td>' . $usuario['origem'] . '</td><td>' . $usuario['endereco'] . '</td>
                                <td>' . $usuario['cidade'] . '</td><td>' . $usuario['senha'] . '</td><td>' . $editar . '</td><td>' . $excluir . '</td></tr>';
                            }
                        } catch (PDOException $e) { // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
                            print("Erro ao conectar com o banco de dados...<br>" . $e->getMessage());
                            die();
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>

</html>