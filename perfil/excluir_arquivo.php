<?php
require "../vendor/autoload.php";

use SIAC\Arquivo;

$oArquivo = new Arquivo();

foreach ($_POST as $idArquivo) {
    $oArquivo->setIdArquivo($idArquivo);
    $oArquivo->listar();
    if($oArquivo->excluir()){
        echo "
        <script>
            alert('Arquivo(s) excluido(s) com sucesso!'); 
            document.location = './arquivos.php';
        </script>";
    }else{
        echo "
        <script>
            alert('Houve um erro ao executar a exclusao, o(s) arquivo(s) nao foi(ram) excluido(s)!'); 
            
        </script>";
    }
}
?>