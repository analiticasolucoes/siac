<?php
require "../vendor/autoload.php";

use SIAC\Sessao;
use SIAC\Noticia;
use SIAC\Paginacao;

$oSessao = new Sessao();
$oNoticia = new Noticia();

if ($oSessao->estaLogado() == false) {
    $oSessao->expulsar();
}

$iIdUsuario = $oSessao->getVariavelSessao("iIdUsuario");
$sNivelAcesso = $oSessao->getVariavelSessao("nivelAcesso");
$sNomeUsuario = $oSessao->getVariavelSessao("sNomeUsuario");

$listaNoticias = $oNoticia->listarTodos();

$oPaginacao = new Paginacao(15, @sizeof($listaNoticias), "index.php?");

if (!$pagina = @$_GET['pagina'])
    $oPaginacao->setINumeroPagina();
else
    $oPaginacao->setINumeroPagina($pagina);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>..: GERENCIAMENTO DE NOT&Iacute;CIAS :..</title>
        <link rel='stylesheet' href='../css/estilo_noticias.css' type='text/css' media='screen'/>
        <link rel="stylesheet" href="../css/menu_style.css" type="text/css" media='screen'/>
        <script type="text/javascript" language='javascript' src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js"></script>
        <script src="../js/perfil.js" type="text/javascript"></script>
        <script src="../js/accordion.js" type="text/javascript" language='javascript'></script>
    </head>	

    <body>		
        <div id='geral'>

            <div id='menu_superior'>
                <div id='text_menu_esquerda'>Seja bem-vindo(a) <?php echo $oSessao->getVariavelSessao("sNomeUsuario"); ?></div>
                <div id='text_menu_direita'><a href='../portal/index.php' class='link_sair'>SAIR</a></div>
            </div>

            <div id='painel_perfil'>
                <ul>
                    <li><a href='#'>NOT&Iacute;CIA</a>
                        <ul>
                            <li><a href='./index.php' >LISTAR TODAS</a></li>
                            <li><a href='./noticia.php?opcao=1' >POSTAR</a></li>
                            <li><a href='./pesquisar.php' >PESQUISAR</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div id='painel_exibicao'>
                <form name='formMensagem' action='excluirMensagem.php' method='post'>

                    <div id='menu_aba'>
                        <b>NOT&Iacute;CIAS DISPON&Iacute;VEIS</b><br/>
                        Clique sobre a not&iacute;cia para exibir seu conte&uacute;do
                        <div id='botoes'>
                            <input type='button' value='Excluir' onClick='excluirMensagem()' disabled />
                            <input type='button' value='Marcar como Inativa' onClick='marcarMensagem()' disabled />
                            <input type='hidden' name='campoOculto' id='campoOculto' value=''/>
                        </div>
                    </div>

                    <div id='painel_exibicao_conteudo'>

                        <table class='tabela'>
                            <tr class='titulo'>
                                <td colspan=2 class='celTituloData'>Data</td>
                                <td class='celTituloNoticia'>Titulo</td>								
                                <td class='celTituloStatus'>Status</td>
                            </tr>
                            <?php
                            if ($listaNoticias) {
                                $listaNoticias = $oNoticia->listarTodos($oPaginacao->getIPrimeiroRegistro(), $oPaginacao->getIQtdRegistrosPorPagina());
                                foreach ($listaNoticias as $noticia) {
                                    echo "
                                    <tr class='linhaDados'>
                                        <td class='celCheck'><input type='checkbox' name='check".$noticia->getIdnoticia()."' value='".$noticia->getIdnoticia()."'/></td>
                                        <td class='celData'>".date("d/m/Y H:i", strtotime($noticia->getData_noticia()))."</td>
                                        <td class='celTitulo'><a href='./noticia.php?opcao=2&idnoticia=".$noticia->getIdnoticia()."'>".$noticia->getTitulo()."</a></td>
                                        <td class='celStatus'>".$noticia->getStatus()."</td>
                                    </tr>";
                                }
                            } else {
                                echo "
                                <tr class='linhaDados'>
                                    <td colspan=5><b>NENHUMA NOT&Iacute;CIA ENCONTRADA</b></td>
                                </tr>";
                            }
                            ?>
                        </table>
                    </div>
                    <div id='paginacao'>
                        <?php
                        // exibir painel de navegacao
                        echo $oPaginacao->getSPainelNavegacao();
                        ?>
                    </div>
                </form>
            </div>
            
            <div id='menu_inferior'>
                <ul>
                    <li><a href="../perfil/index.php" target="_self">PERFIL</a></li>					
                    <li><a href="../forum/index.php" target="_self">F&Oacute;RUM</a></li>
                    <?php if ($sNivelAcesso == "Administrador" || $sNivelAcesso == "Coordenador" || $sNivelAcesso == "Secretaria") { ?>
                    <li><a href="../secretaria/index.php" target="_self">SECRETARIA</a></li>
                    <?php } ?>					
                    <li><a href="../portal/index.php" target="_self">SAIR</a></li>						
                </ul>
            </div>

        </div>
    </body>
</html>