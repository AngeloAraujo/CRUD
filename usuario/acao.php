<?php
include_once "../config/conf.inc.php";    // arquivo de configuração
// acao.php é responsável por inserir, editar e excluir um registro no banco de dados
// aqui coletar os dados eviados pelo formulário de cadastro via POST
$nome =  isset($_POST['nome'])?$_POST['nome']:"";
$sobrenome =  isset($_POST['sobrenome'])?$_POST['sobrenome']:"";
$email =  isset($_POST['email'])?$_POST['email']:"";
$senha =  isset($_POST['senha'])?$_POST['senha']:"";
$dtnasc = isset($_POST['dtnasc'])?$_POST['dtnasc']:'';
$telefone = isset($_POST['telefone'])?$_POST['telefone']:'';
$parente = isset($_POST['parente'])?$_POST['parente']:'';
$origem = isset($_POST['origem'])?$_POST['origem']:'';
$endereco = isset($_POST['endereco'])?$_POST['endereco']:'';
$cidade = isset($_POST['cidade'])?$_POST['cidade']:'';
$id = isset($_POST['id'])?$_POST['id']:'';
var_dump($id);
var_dump($sobrenome);
var_dump($nome);
var_dump($email);
var_dump($senha);
var_dump($dtnasc);
var_dump($telefone);
var_dump($parente);
var_dump($origem);
var_dump($endereco);
var_dump($cidade);


// se a ação for excluir virá via GET
$acao =  isset($_GET['acao'])?$_GET['acao']:"";

if ($acao == 'excluir'){ // exclui um registro do banco de dados
    try{
        $id =  isset($_GET['id'])?$_GET['id']:0;  // se for exclusão o ID vem via GET
        
        // cria a conexão com o banco de dados 
        $conexao = new PDO(MYSQL_DSN,DB_USER,DB_PASSWORD);
        $query = 'DELETE FROM usuario WHERE id = :id';
        $stmt = $conexao->prepare($query);
        $stmt->bindValue(':id',$id);
        // executar a consulta
        if ($stmt->execute())
            header('location: cadUsuario.php');
        else
            echo 'Erro ao excluir dados';
    }catch(PDOException $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
        print("Erro ao conectar com o banco de dados...<br>".$e->getMessage());
        die();
    }
}else{ // então é para inserir ou atualizar
    if ($nome != "" && $senha != "" && $email != ""){
        // salvar no banco de dados    
        try{
            // cria a conexão com o banco de dados 
            $conexao = new PDO(MYSQL_DSN,DB_USER,DB_PASSWORD);
            // montar consulta
            
            if ($id > 0) // se o ID está informado é atualização
                $query = 'UPDATE usuario 
                             SET nome = :nome, sobrenome= :sobrenome, email = :email, senha = :senha, dtnasc = , telefone = :telefone,
                             parente = :parente,origem = :origem, endereco = :endereco,cidade = :cidade WHERE id = :id';
            else // senão será inserido um novo registro
                $query = 'INSERT INTO usuario (nome,sobrenome, email, senha,dtnasc, telefone, parente, origem,endereco, cidade) 
                               VALUES (:nome, :sobrenome, :email, :senha,:dtnasc, :telefone,  :parente, :origem,:endereco,:cidade)';
                        
            // preparar consulta
            $stmt = $conexao->prepare($query);
            
            // vincular variaveis com a consulta
           // $stmt->bindValue(':id',$id);
            $stmt->bindValue(':nome',$nome);  
            $stmt->bindValue(':sobrenome',$sobrenome);      
            $stmt->bindValue(':email',$email);        
            $stmt->bindValue(':senha',$senha);
            $stmt->bindValue(':dtnasc',$dtnasc);
            $stmt->bindValue(':telefone',$telefone);
            $stmt->bindValue(':parente',$parente);
            $stmt->bindValue(':origem',$origem);
            $stmt->bindValue(':endereco',$endereco);
            $stmt->bindValue(':cidade',$cidade);
            
        
            if ($id > 0) // atualização
                $stmt->bindValue(':id',$id);

            // executar a consulta
            if ($stmt->execute())
                header('location: cadUsuario.php');
            else
                echo 'Erro ao inserir/editar dados';
        }catch(PDOException $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
            print("Erro ao conectar com o banco de dados entrou no if...<br>".$e->getMessage());
            die();
        }catch(Exception $e){ // se ocorrer algum erro na execuçao da conexão com o banco executará o bloco abaixo
            print("Erro genérico...<br>".$e->getMessage());
            die();
        }
    }
}
?>