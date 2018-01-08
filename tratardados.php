<?php 
mb_internal_encoding("iso-8859-1");
//$conecta = mysql_connect("mapa_alliar.mysql.dbaas.com.br", "mapa_alliar", "sqlyt4da51241") or print (mysql_error()); 
//mysql_select_db("mapa_alliar", $conecta) or print(mysql_error()); 
$conecta = mysql_connect("localhost", "root", "") or print (mysql_error()); 
mysql_select_db("bd_mapa_cdb", $conecta) or print(mysql_error()); 



//RETIRAR CARGOS, AREAS e SETORES 
$sql = 'SELECT * FROM tb_funcionario WHERE lider = "S"';
$funcionarios = mysql_query($sql, $conecta);

while ($gestor = mysql_fetch_array($funcionarios)) {
    $sql = 'SELECT fg.* 
            FROM tb_funcionario_gestor AS fg
            INNER JOIN tb_funcionario AS f ON f.id = fg.funcionario 
            WHERE fg.gestor = '.$gestor['id'].' AND f.unidade != '.$gestor['unidade'];
    $unidades = mysql_query($sql, $conecta);
    while ($unidade = mysql_fetch_array($unidades)) {
        $sql = 'DELETE FROM tb_funcionario_gestor WHERE id = '.$unidade['id'];
        if(mysql_query($sql)){
            echo "Gestor ".$gestor['id']."\n";
        }
    }

}


/*SELECT fg.* 
            FROM tb_funcionario_gestor AS fg
            INNER JOIN tb_funcionario AS f ON f.id = fg.funcionario 
            WHERE fg.gestor = 959 AND f.unidade != 24
            GROUP BY f.unidade

//CORRIGIR ERRO DE CARACTER
/*    var_dump($_SERVER);
    
    die($_SERVER['SERVER_ADDR']);

    mb_internal_encoding("UTF-8");
    $conecta = mysql_connect("mysql01.timesistemas1.hospedagemdesites.ws", "timesistemas1", "sqlyt4da51241") or print (mysql_error()); 
    mysql_select_db("timesistemas1", $conecta) or print(mysql_error()); 
    $sql = 'SELECT * FROM tb_empresa ORDER BY id'; 
    $empresas = mysql_query($sql, $conecta);  

    //Pesquisar arquivos
    $sql = 'SELECT * FROM tb_empresa_arquivos WHERE empresa = 1';
    $arquivos2 = mysql_query($sql, $conecta);
    $arquivos = array();
    while($arquivo = mysql_fetch_array($arquivos2)) { 
       $arquivos[] = $arquivo; 
    }

    while ($empresa = mysql_fetch_array($empresas)) {
        if($empresa['id'] == 1){
            continue;
        }
        
        //copiar arquivos
        $cont = 1;
        
        foreach ($arquivos as $arquivo) {
            $cont++;
            $novoEndereco = str_replace('/1/', '/'.$empresa['id'].'/', $arquivo['arquivo']);
            $nome = utf8_decode($arquivo['nome']);
            $sqlInsert = 'INSERT INTO tb_empresa_arquivos (nome, arquivo, empresa) VALUES ("'.$arquivo['nome'].'", "'.$novoEndereco.'", '.$empresa['id'].');';
            if(mysql_query($sqlInsert)){
                echo 'Inseri na base de dados!'."\n";
            }else{
                echo 'ERRO: '.mysql_error()."\n";
                die('ERRO AO INSERIR REGISTRO NO BANCO! empresa: '.$empresa['id']);
            }
        }
        
        echo $empresa['id'].' - empresa copiada,'.$cont.' arquivos!'."\n";;
    }

    die('fimMigracao');*/

// +++!!!! SCRIPT DE REPLICAÇÃO DE ARQUIVOS
/*    //DELETE FROM tb_empresa_arquivos WHERE empresa != 1;

    //migrar dados
    $conecta = mysql_connect("mysql01.timesistemas1.hospedagemdesites.ws", "timesistemas1", "sqlyt4da51241") or print (mysql_error()); 
    mysql_select_db("timesistemas1", $conecta) or print(mysql_error()); 
    $sql = 'SELECT * FROM tb_empresa ORDER BY id'; 
    $empresas = mysql_query($sql, $conecta);  

    //Pesquisar arquivos
    $sql = 'SELECT * FROM tb_empresa_arquivos WHERE empresa = 1';
    $arquivos2 = mysql_query($sql, $conecta);
    $arquivos = array();
    while($arquivo = mysql_fetch_array($arquivos2)) { 
       $arquivos[] = $arquivo; 
    }

    while ($empresa = mysql_fetch_array($empresas)) {
        if($empresa['id'] == 1){
            continue;
        }
        $dir = 'public/arquivos/'.$empresa['id'].'/arquivos';
        if(!file_exists($dir)){
            mkdir($dir);
        }

        //copiar arquivos
        $cont = 1;
        
        foreach ($arquivos as $arquivo) {
            $cont++;
            $novoEndereco = str_replace('/1/', '/'.$empresa['id'].'/', $arquivo['arquivo']);
            echo $arquivo['arquivo']."\n";
            echo '   NOVO   '.$novoEndereco."\n";
            $nome = utf8_encode($arquivo['nome']);
            $sqlInsert = 'INSERT INTO tb_empresa_arquivos (nome, arquivo, empresa) VALUES ("'.$nome.'", "'.$novoEndereco.'", '.$empresa['id'].');';
            if(mysql_query($sqlInsert)){
                echo 'Inseri na base de dados!'."\n";
            }else{
                echo 'ERRO: '.mysql_error()."\n";
                die('ERRO AO INSERIR REGISTRO NO BANCO! empresa: '.$empresa['id']);
            }
            //CASO EXISTA O ARQUIVO DELETAR
            if(file_exists($novoEndereco)){
                unlink($novoEndereco);
            }

            //COPIAR ARQUIVO
            if(copy($arquivo['arquivo'], $novoEndereco)){
                echo 'copiei arquivo!'."\n";
            }else{
                die('ERO AO COPIAR ARQUIVO! empresa: '.$empresa['id'].' arquivo: '.$arquivo['id']);
            }

        }
        echo $empresa['id'].' - empresa copiada,'.$cont.' arquivos!'."\n";;
    }

    die('fimMigracao');   */
?>