<?php
require "../vendor/autoload.php";

use SIAC\Sessao;
use SIAC\Paginacao;
use SIAC\Mensagem;
use SIAC\Usuario;

$oSessao = new Sessao();
$oMensagem = new Mensagem();
$pagina = null;

if ($oSessao->estaLogado() == null) {
    $oSessao->expulsar();
}

$iIdUsuario = $oSessao->getVariavelSessao("iIdUsuario");
$sNivelAcesso = $oSessao->getVariavelSessao("nivelAcesso");
$sNomeUsuario = $oSessao->getVariavelSessao("sNomeUsuario");

$oMensagem->setDestinatario($iIdUsuario);
$aMensagens = $oMensagem->listarPorDestinatario();

$oPaginacao = new Paginacao(10, sizeof($aMensagens), "index.php?");

if (!$pagina = isset($_GET['pagina']))
    $oPaginacao->setINumeroPagina();
else
    $oPaginacao->setINumeroPagina($pagina);

$iFirstReg = $oPaginacao->getIPrimeiroRegistro();

$aMensagens = $oMensagem->listarPorDestinatario($iFirstReg, 10);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>..: Seja bem-vindo(a) ao seu Perfil em CEET - Vasco Coutinho :..</title>
        <link rel='stylesheet' href='../css/estilo_perfil.css' type='text/css' media='screen'/>
        <link rel="stylesheet" href="../css/menu_style.css" type="text/css" media='screen'/>
        <script src="../js/perfil.js" type="text/javascript"></script>
    </head>	

    <body>		
        <div id='geral'>

            <div id='menu_superior'>
                <div id='text_menu_esquerda'>Seja bem-vindo(a) <?php echo $sNomeUsuario; ?></div>
                <div id='text_menu_direita'><a href='../portal/index.php' class='link_sair'>SAIR</a></div>
            </div>

            <?php
            require_once("painelPerfil.php");
            $sPainelPerfil = gerarPainelPerfil($sNivelAcesso, $sNomeUsuario);
            echo $sPainelPerfil;
            ?>

            <div id='painel_exibicao'>
                <form name='formMensagem' action='excluirMensagem.php' method='post'>

                    <div id='menu_aba'>
                        <ul>
                            <li><a href='./index.php' onClick='atualiza_menu(1)' class='link1'>Mensagens</a></li>
                            <li><a href='./arquivos.php' onClick='atualiza_menu(2)' class='link2'>Arquivos</a></li>
                        </ul>
                        <div id='botoes'>
                            <input type='button' value='Excluir' onClick='excluirMensagem()' />
                            <input type='button' value='Marcar como N&atilde;o-lida' onClick='marcarMensagem()'/>
                            <input type='hidden' name='campoOculto' id='campoOculto' value=''/>
                        </div>
                    </div>

                    <div id='painel_exibicao_conteudo'>

                        <table class='tabela'>
                            <tr class='titulo'>
                                <td colspan=2 class='celTituloRemetente'>Remetente</td>
                                <td class='celTituloMensagem'>Titulo</td>
                                <td class='celTituloDataMensagem'>Data de Envio</td>
                                <td class='celTituloStatus'>Status</td>
                            </tr>
                            <?php
                            if ($aMensagens == null) {
                                echo "
                                <tr class='linhaDados'>
                                    <td colspan=5><b>NENHUMA MENSAGEM DISPON&Iacute;VEL</b></td>
                                </tr>";
                            } else {
                                $oUsuario = new Usuario();
                                foreach ($aMensagens as $mensagem) {
                                    list($idmensagem, $remetente,,, $status, $titulo, $data_envio) = $mensagem;
                                    $data_envio = date("H:i d/m/Y", strtotime($data_envio));
                                    $oUsuario->setIdusuario($remetente);
                                    $oUsuario->listar();
                                    $remetente = $oUsuario->getNome();
                                    echo "
                                    <tr class='linhaDados'>
                                        <td class'celCheck'><input type='checkbox' name='check{$idmensagem}' value='{$idmensagem}'/></td>
                                        <td class='celRemetente'>{$remetente}</td>
                                        <td class='celTitulo'>{$titulo}</td>
                                        <td class='celDataEnvio'>{$data_envio}</td>
                                        <td class='celStatus'>{$status}</td>
                                    </tr>";
                                }
                            }
                            ?>

                        </table>
                    </div>
                </form>

                <div id='paginacao'>
                    <?php
                    // exibir painel de navegacao
                    echo $oPaginacao->getSPainelNavegacao();
                    ?>
                </div>
            </div>

            <div id='menu_inferior'>
                <ul>
                    <li><a href="../forum/index.php" target="_self">F&Oacute;RUM</a></li>
                    <?php if ($sNivelAcesso == "Administrador" || $sNivelAcesso == "Coordenador" || $sNivelAcesso == "Secretaria") { ?>
                        <li><a href="../noticias/index.php" target="_self">NOT&Iacute;CIAS</a></li>
                        <li><a href="../secretaria/index.php" target="_self">SECRETARIA</a></li>
                    <?php } ?>					
                    <li><a href="../portal/index.php" target="_self">SAIR</a></li>						
                </ul>
            </div>

        </div>
    </body>
</html>