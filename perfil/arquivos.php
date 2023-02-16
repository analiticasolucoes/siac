<?php
    require "../vendor/autoload.php";

    use SIAC\Sessao;
    use SIAC\Paginacao;
    use SIAC\Arquivo;

    $oSessao = new Sessao();
    $oArquivo = new Arquivo();

    if ($oSessao->estaLogado() == null) {
        $oSessao->expulsar();
    }

    $iIdUsuario = $oSessao->getVariavelSessao("iIdUsuario");
    $sNivelAcesso = $oSessao->getVariavelSessao("nivelAcesso");
    $sNomeUsuario = $oSessao->getVariavelSessao("sNomeUsuario");

    $listaArquivos = $oArquivo->listarTodos();

    $oPaginacao = new Paginacao(10, @sizeof($listaArquivos), "arquivos.php?");

    if (!@$pagina = $_GET['pagina'])
        $oPaginacao->setINumeroPagina();
    else
        $oPaginacao->setINumeroPagina($pagina);

    $iFirstReg = $oPaginacao->getIPrimeiroRegistro();

    $listaArquivos = $oArquivo->listarTodos($iFirstReg, 10);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>..: Seja bem-vindo(a) ao seu Perfil em CEET - Vasco Coutinho :..</title>
        <link rel="stylesheet" href="../css/estilo_perfil.css" type="text/css" media="screen"/>
        <link rel="stylesheet" href="../css/menu_style.css" type="text/css" media='screen'/>
        <script src="../js/perfil.js" type="text/javascript"></script>
        <style type='text/css'>
            .link1{
                background-color: white;
            }

            .link2{
                background-color: silver;
            }
        </style>
    </head>	

    <body>		
        <div id='geral'>

            <div id='menu_superior'>
                <div id='text_menu_esquerda'>Seja bem-vindo(a) <?php echo $sNomeUsuario ?></div>
                <div id='text_menu_direita'><a href='../portal/index.php' class='link_sair'>SAIR</a></div>
            </div>

            <?php
            require_once("painelPerfil.php");
            $sPainelPerfil = gerarPainelPerfil($sNivelAcesso, $sNomeUsuario);
            echo $sPainelPerfil;
            ?>

            <div id='painel_exibicao'>
                <form name='formArquivo' action='excluir_arquivo.php' method='post' onSubmit='return excluirArquivo();'>

                    <div id='menu_aba'>
                        <ul>
                            <li><a href='./index.php' class='link1'>Mensagens</a></li>
                            <li><a href='./arquivos.php' class='link2'>Arquivos</a></li>
                        </ul>

                        <div id='botoes'>
                            <?php if ($sNivelAcesso == "Administrador" || $sNivelAcesso == "Coordenador" || $sNivelAcesso == "Secretaria") { ?>
                                <input type='button' value='Postar Arquivo' onClick='javascript:document.location = "./postar_arquivo.php";'/>
                                <input type='submit' value='Excluir'/>
                            <?php } ?>
                        </div>
                    </div>

                    <div id='painel_exibicao_conteudo'>
                        <table class='tabela'>
                            <tr class='titulo'>
                                <td class='celTituloNomeArquivo'>Nome</td>
                                <td class='celTituloDataEnvio'>Data de Envio</td>
                                <td class='celTituloTipo'>Tipo</td>
                                <td class='celTituloTamanho'>Tamanho</td>
                            </tr>
                            <?php
                            
                            if ($listaArquivos) {
                                foreach ($listaArquivos as $arquivo) {
                                    echo "
                                    <tr class='linhaDados'>
                                        <td class='celNomeArquivo'><input type='checkbox' name='checkBoxFile". $arquivo->getIdArquivo() . "' value='" . $arquivo->getIdArquivo() . "'/>&nbsp;<a href='" . $arquivo->getEndereco(). "'>" . $arquivo->getNomeArquivo() . "</a></td>
                                        <td class='celDataEnvio'>" . date("d/m/Y H:i:s", strtotime($arquivo->getDataEnvio())) . "</td>
                                        <td class='celTipo'></td>
                                        <td class='celTamanho'></td>
                                    </tr>";
                                }
                            } else {
                                echo "
                                <tr class='linhaDados'>
                                    <td colspan=4><center><b>NENHUM ARQUIVO DISPON&Iacute;VEL</b></center></td>
                                </tr>";
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