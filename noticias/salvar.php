<?php
require "../vendor/autoload.php";

use SIAC\Sessao;
use SIAC\Noticia;

$oSessao = new Sessao();

if ($oSessao->estaLogado() == null) {
    $oSessao->expulsar();
}

$opcao = $_GET['opcao'];
$idnoticia = @$_POST['ocultoID'];
$titulo = @$_POST['campoTitulo'];

switch($opcao){
    
    //Cria uma nova noticia
    case 1:
        $status = $_POST['ocultoStatus'];
        $visibilidade = $_POST['radioVisibilidade'];
        $resumo = $_POST['resumo'];
        $noticia_completa = $_POST['noticia_completa'];
        if (!$destaque = @$_POST['checkDestaque']) {
            $destaque = 0;
        } else {
            $destaque = 1;
        }

        $oNoticia = new Noticia();

        $oNoticia->setFuncionario_idusuario($oSessao->getVariavelSessao("iIdUsuario"));
        $oNoticia->setTitulo($titulo);
        $oNoticia->setResumo($resumo);
        $oNoticia->setNoticia_completa($noticia_completa);
        $oNoticia->setDestaque($destaque);
        $oNoticia->setStatus($status);
        $oNoticia->setVisibilidade($visibilidade);

        if ($oNoticia->inserir()) {
            echo "
            <script>
                alert('Noticia cadastrada com sucesso!');
                window.location = './index.php';
            </script>";
        } else {
            $erro = "<h1>ERRO AO INSERIR NOT&Iacute;CIA!</h1>";
        }
    break;
    
    //Altera uma noticia
    case 2:
        $status = @$_POST['ocultoStatus'];
        $visibilidade = @$_POST['radioVisibilidade'];
        $resumo = @$_POST['resumo'];
        $noticia_completa = @$_POST['noticia_completa'];
        if (!$destaque = @$_POST['checkDestaque']) {
            $destaque = 0;
        } else {
            $destaque = 1;
        }

        $oNoticia = new Noticia();

        $oNoticia->setIdnoticia($idnoticia);
        $oNoticia->setTitulo($titulo);
        $oNoticia->setResumo($resumo);
        $oNoticia->setNoticia_completa($noticia_completa);
        $oNoticia->setDestaque($destaque);
        $oNoticia->setStatus($status);
        $oNoticia->setVisibilidade($visibilidade);

        if ($oNoticia->alterar()) {
            echo "
            <script>
                alert('Noticia atualizada com sucesso!');
                window.location = './noticia.php?opcao=2&idnoticia=".$idnoticia."';
            </script>";
        } else {
            echo "<h1>ERRO AO ALTERAR NOT&Iacute;CIA!</h1>";
        }
    break;
}
?>