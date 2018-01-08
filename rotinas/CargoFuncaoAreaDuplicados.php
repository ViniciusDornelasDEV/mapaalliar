<?php 
mb_internal_encoding("iso-8859-1");
$conecta = mysql_connect("mapa_alliar.mysql.dbaas.com.br", "mapa_alliar", "sqlyt4da51241") or print (mysql_error()); 
mysql_select_db("mapa_alliar", $conecta) or print(mysql_error()); 

//$conecta = mysql_connect("localhost", "root", "") or print (mysql_error()); 
//mysql_select_db("bd_mapa_cdb", $conecta) or print(mysql_error()); 



//RETIRAR CARGOS, AREAS e SETORES 
$sql = 'SELECT * FROM tb_funcao';
$cargos = mysql_query($sql, $conecta);

while ($cargo = mysql_fetch_array($cargos)) {
    $sql = 'SELECT * FROM tb_funcao WHERE nome = "'.$cargo['nome'].'" AND setor = '.$cargo['setor'].' AND id != '.$cargo['id'];
    $duplicados = mysql_query($sql, $conecta);
    while ($duplicado = mysql_fetch_array($duplicados)) {
        $sql = 'UPDATE tb_funcionario SET funcao = '.$cargo['id'].' WHERE funcao = '.$duplicado['id'];
        mysql_query($sql);

        $sql = 'DELETE FROM tb_funcao WHERE id = '.$duplicado['id'];
        mysql_query($sql);
        
    }
    echo "Funcao ".$cargo['id']."\n";
}

$sql = 'SELECT * FROM tb_setor';
$setores = mysql_query($sql, $conecta);

while ($setor = mysql_fetch_array($setores)) {
    $sql = 'SELECT * FROM tb_setor WHERE nome = "'.$setor['nome'].'" AND area = '.$setor['area'].' AND id != '.$setor['id'];
    $duplicados = mysql_query($sql, $conecta);
    while ($duplicado = mysql_fetch_array($duplicados)) {
        $sql = 'UPDATE tb_funcao SET setor = '.$setor['id'].' WHERE setor = '.$duplicado['id'];
        mysql_query($sql);

        $sql = 'UPDATE tb_escala SET setor = '.$setor['id'].' WHERE setor = '.$duplicado['id'];
        mysql_query($sql);

        $sql = 'DELETE FROM tb_setor WHERE id = '.$duplicado['id'];
        mysql_query($sql);

    }
    echo "Setor ".$setor['id']."\n";
}


$sql = 'SELECT * FROM tb_area';
$areas = mysql_query($sql, $conecta);

while ($area = mysql_fetch_array($areas)) {
    $sql = 'SELECT * FROM tb_area WHERE nome = "'.$area['nome'].'" AND id != '.$area['id'];
    $duplicados = mysql_query($sql, $conecta);
    while ($duplicado = mysql_fetch_array($duplicados)) {
        $sql = 'UPDATE tb_setor SET area = '.$area['id'].' WHERE area = '.$duplicado['id'];
        mysql_query($sql);

        $sql = 'DELETE FROM tb_area WHERE id = '.$duplicado['id'];
        mysql_query($sql);

    }
    echo "Area ".$area['id']."\n";
}
die('FIM!');
?>