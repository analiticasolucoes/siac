<?php
require "../vendor/autoload.php";

use SIAC\Sessao;
use SIAC\Arquivo;

const DIRETORIO = "../files/";
$oSessao = new Sessao();
$oArquivo = new Arquivo();
$iIdUsuario = $oSessao->getVariavelSessao("iIdUsuario");
$sDescricao = $_POST['descricaoArquivo'];

$arquivo = $_FILES['arquivo'];
if ($arquivo['error'] != 0) {
    echo '<h1>Erro no Upload do arquivo!</h1>';
    switch ($arquivo['error']) {
        case UPLOAD_ERR_INI_SIZE:
            echo '<h2>O Arquivo excede o tamanho maximo permitido.</h2>';
            break;
        case UPLOAD_ERR_FORM_SIZE:
            echo '<h2>O Arquivo enviado e muito grande.</h2>';
            break;
        case UPLOAD_ERR_PARTIAL:
            echo '<h2>O upload nao foi completo.</h2>';
            break;
        case UPLOAD_ERR_NO_FILE:
            echo '<h2>Nenhum arquivo foi informado para upload.</h2>';
            break;
    }
    exit;
} else {
    //Testa se o arquivo foi enviado atraves do formulario, caso não solicita ao usuario que use a pagina de envio.
    if (is_uploaded_file($arquivo['tmp_name'])) {
        
        //Verifica se o arquivo foi movido para o diretorio correto.
        if (move_uploaded_file($arquivo['tmp_name'], DIRETORIO . $arquivo['name'])) {
            // Tudo Ok, arquivo enviado  
            $oArquivo->setId_usuario($iIdUsuario);
            $oArquivo->setNomeArquivo($arquivo['name']);
            $oArquivo->setEndereco(DIRETORIO . $arquivo['name']);
            $oArquivo->setDescricao($sDescricao);
            
            //Verifica se as informações sobre o arquivo foram incluidas no banco de dados.
            if($oArquivo->inserir()){
                echo $oArquivo->toString();
                echo "
                <script>
                    alert('O Arquivo foi recebido com sucesso!');
                </script>
                <a href='./postar_arquivo.php'>Voltar para a pagina anterior</a>";
            } else {
                echo "
                <script>
                    alert('Ocorreu um erro durante o upload! O arquivo nao foi incluido no banco de dados.');
                </script>
                <a href='./postar_arquivo.php'>Voltar para a pagina anterior</a>";
            }
        } else {
            echo "
            <script>
                alert('Ocorreu um erro durante o upload! O arquivo nao pode ser movido para a pasta de destino.');
            </script>
            <a href='./postar_arquivo.php'>Voltar para a pagina anterior</a>";
        }
    } else {
        echo "
        <script>
            alert('Ocorreu um erro durante o upload!\nPor favor utilize o formulario para envio de arquivos.');
            document.location = './postar_arquivo.php'; 
        </script>";
    }
}
?>