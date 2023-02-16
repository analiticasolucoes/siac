<?php
    require "../vendor/autoload.php";

    use SIAC\Sessao;

    $oSessao = new Sessao();
    $nivelAcesso = $oSessao->getVariavelSessao("nivelAcesso");

    if (!$oSessao->estaLogado() || $nivelAcesso != "Administrador" && $nivelAcesso != "Professor") {
        $oSessao->expulsar();
    }

    $iIdUsuario = $oSessao->getVariavelSessao("iIdUsuario");
    $sNivelAcesso = $oSessao->getVariavelSessao("nivelAcesso");
    $sNomeUsuario = $oSessao->getVariavelSessao("sNomeUsuario");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>..: Seja bem-vindo(a) ao seu Perfil em CEET - Vasco Coutinho :..</title>
        <link rel='stylesheet' href='../css/estilo_perfil.css' type='text/css' media='screen'/>
        <link rel="stylesheet" href="../css/menu_style.css" type="text/css" media='screen'/>
        <script src="aba.js" type="text/javascript"></script>
        <style type='text/css'>
            <!--
            #item_menu_aba_2{
                background: silver;
            }

            #item_menu_aba_1{
                background: none;
            }
            -->
        </style>
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
                <div id='painel_exibicao_conteudo'>
                    <form name='formArquivo' action='enviar_arquivo.php' method='post' enctype='multipart/form-data'>
                        <b>Arquivo:</b>&nbsp;<input name="arquivo" type="file"/> 
                        <br/><br/>
                        <b>Descricao do Arquivo:</b><br/><br/>
                        <input type='text' name='descricaoArquivo' size='95'/><br/><br/>
                        <b>Disponibilizar este arquivo para:</b><br/><br/>
                        <input type='checkbox' name='check1' value='' disabled/> Turma 1<br/>
                        <input type='checkbox' name='check2' value='' disabled/> Turma 2<br/>
                        <input type='checkbox' name='check3' value='' disabled/> Turma 3<br/>
                        <input type='checkbox' name='todos'  value='' checked disabled/> Todos os Usuarios<br/>
                        <br/>
                        <input name="bt_env" type="submit" value="Enviar para o Servidor" /> 
                    </form>
                    <br/><a href='./arquivos.php'><b>Voltar para Pagina Anterior</b></a>
                </div>
            </div>

            <div id='menu_inferior'>
                <ul>
                    <li><a href="../forum/index.php" target="_self">FORUM</a></li>
                    <?php if ($sNivelAcesso == "Administrador" || $sNivelAcesso == "Coordenador" || $sNivelAcesso == "Secretaria") { ?>
                    <li><a href="../noticias/index.php" target="_self">NOTICIAS</a></li>
                    <li><a href="../secretaria/index.php" target="_self">SECRETARIA</a></li>
                    <?php } ?>					
                    <li><a href="../portal/index.php" target="_self">SAIR</a></li>						
                </ul>
                <br class="clearit" />
            </div>

        </div>
    </body>
</html>