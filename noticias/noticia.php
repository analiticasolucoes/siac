<?php
require "../vendor/autoload.php";

use SIAC\Sessao;
use SIAC\Noticia;
use SIAC\Funcionario;

require_once("./fckeditor/fckeditor.php");

$oSessao = new Sessao();

if ($oSessao->estaLogado() == null) {
    $oSessao->expulsar();
}

$iIdUsuario = $oSessao->getVariavelSessao("iIdUsuario");
$erro = null;
$opcao = @$_GET['opcao'];

switch ($opcao) {
    case 1:  //CRIAR UMA NOVA NOTICIA
        $idnoticia = null;
        $nome = $oSessao->getVariavelSessao("sNomeUsuario");
        $titulo = null;
        $resumo = null;
        $noticia_completa = null;
        $data_noticia = date("d/m/Y H:i:s");
        $destaque = true;
        $status = "ABERTA";
        $visibilidade = 3;
        break;

    case 2:  //ALTERAR UMA NOTICIA
        if (!$idnoticia = @$_GET['idnoticia']) {
            $erro = "ERRO AO EXIBIR NOT&Iacute;CIA!<BR/>ID NOTICIA N&Atilde;O INFORMADO!";
        } else {
            $oNoticia = new Noticia();
            $oNoticia->setIdnoticia($idnoticia);

            if ($oNoticia->listar()) {
                $titulo = $oNoticia->getTitulo();
                $resumo = $oNoticia->getResumo();
                $noticia_completa = $oNoticia->getNoticia_completa();
                $data_noticia = date("d/m/Y H:i:s", strtotime($oNoticia->getData_noticia()));
                $destaque = $oNoticia->getDestaque();
                $status = $oNoticia->getStatus();
                $visibilidade = $oNoticia->getVisibilidade();
                $idfuncionario = $oNoticia->getFuncionario_idusuario();

                $oFuncionario = new Funcionario();
                $oFuncionario->setFuncionario_idusuario($idfuncionario);

                if ($oFuncionario->listar()) {
                    $nome = $oFuncionario->getNome();
                } else {
                    $nome = "Nome n&atilde;o encontrado";
                }
            } else {
                $erro = "ERRO AO EXIBIR NOTICIA! A NOTICIA N&Atilde;O FOI ENCONTRADA!";
            }
        }
        break;

    case null:
        $erro = "ERRO AO CARREGAR A P&Aacute;GINA!<br/>A NOTICIA N&Atilde;O SER&Aacute; EXIBIDA!<br/>ESCOLHA UMA OP&Ccedil;&Atilde;O V&Aacute;LIDA E TENTE NOVAMENTE.";
        break;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>..: GERENCIAMENTO DE NOT&Iacute;CIAS :..</title>
        <link rel='stylesheet' href='../css/estilo_noticias.css' type='text/css' media='screen'/>
        <link rel="stylesheet" href="../css/menu_style.css" type="text/css" media='screen'/>
        <script src="../js/noticias.js" type="text/javascript"></script>
        <script src="./fckeditor/fckeditor.js" type="text/javascript"></script>
        <script src="./ckeditor/ckeditor.js" type="text/javascript"></script>
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
                            <li><a href='./noticia.php?opcao=1' >POSTAR</a></li>
                            <li><a href='./pesquisar.php' >PESQUISAR</a></li>
                            <li><a href='./index.php' >VOLTAR</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div id='painel_exibicao'>

                <div id='painel_exibicao_conteudo'>
                    <?php if($erro == null){?>
                    <form name='formNoticia' method='post' <?php echo "action='./salvar.php?opcao=".$opcao."'";?> onSubmit='return validarFormulario();'>
                        <?php 
                        if($opcao == 1) {
                            echo "
                            <b>POSTAR NOT&Iacute;CIA</b><br/>
                            Preencha os campos abaixo para criar uma nova not&iacute;cia";
                        } elseif($opcao == 2) {
                            echo "
                            <b>ALTERAR NOT&Iacute;CIA</b><br/>
                            Altere os campos abaixo e depois clique no bot&atilde;o \"Salvar\" para editar a not&iacute;cia";
                        }?>                      
                        
                        <table class="tabelaPostarNoticia">
                            <tr class="row1">
                                <td class='col1' id='labelID'><b>ID Not&iacute;cia:</b></td>
                                <td class='col2' id='celID'><input type='text' size=1 name='campoID' id='campoID' class='campoID' value='<?php echo $idnoticia;?>' disabled /><input type='hidden' name='ocultoID' value='<?php echo $idnoticia;?>'/></td>
                                <td class='col3' id='labelData'><b>Data:</b></td>
                                <td class='col4' id='celData'><input type='text' size=16 name='campoData' id='campoData' class='campoData' value='<?php echo $data_noticia;?>' disabled /></td>
                            </tr>
                            <tr class='row2'>
                                <td class='col1' id='labelAutor'><b>Autor:</b></td>
                                <td class='col2' id='celAutor'><input type='text' size=30 name='campoAutor' id='campoAutor' class='campoAutor' value='<?php echo $nome;?>' disabled /></td>
                                <td class='col3' id='labelDestaque'><b>Destaque:</b></td>
                                <td class='col4' id='celDestaque'><input type='checkbox' name='checkDestaque' id='checkDestaque' class='checkDestaque' <?php echo $destaque ? "checked" :"";?>/></td>
                            </tr>
                            <tr class='row3'>
                                <td class='col1' id='labelTitulo'><b>T&iacute;tulo:</b></td>
                                <td class='col2' id='celTitulo' colspan=3><input type='text' size=89 name='campoTitulo' id='campoTitulo' class='campoTitulo' value='<?php echo $titulo;?>'/></td>
                            </tr>
                            <tr class='row4'>
                                <td class='col1' id='labelStatus'><b>Status:</b></td>
                                <td class='col2' id='celStatus'>
                                    <?php
                                    if($opcao == 1){
                                        echo "
                                        <input type='text' name='campoStatus' id='campoStatus' class='campoStatus' value='$status' disabled />
                                        <input type='hidden' name='ocultoStatus' value='$status' />";
                                    }else{
                                        echo "
                                        <input type='text' name='campoStatus' id='campoStatus' class='campoStatus' value='ABERTA' disabled />
                                        <input type='hidden' name='ocultoStatus' value='ABERTA' />";
                                    }
                                    ?>
                                </td>
                                <td class='col3' id='labelVisibilidade'><b>Visibilidade da Not&iacute;cia:</b></td>
                                <td class='col4' id='celVisibilidade'>
                                    <input type="radio" name="radioVisibilidade" value="1" <?php echo $visibilidade == 1 ? "checked" : "";?>/><b>Site</b>
                                    <input type="radio" name="radioVisibilidade" value="2" <?php echo $visibilidade == 2 ? "checked" : "";?>/><b>Portal</b>
                                    <input type="radio" name="radioVisibilidade" value="3" <?php echo $visibilidade == 3 ? "checked" : "";?>/><b>Todos</b>
                                </td>
                            </tr>
                        </table>
                        <br/>
                        <fieldset class='resumo'>
                            <legend><b>Resumo:</b></legend>
                            <?php 
                            $editor = new FCKeditor("resumo");      //Nomeia a area de texto
                            $editor-> BasePath = "./fckeditor/";    //Informa a pasta do FKC Editor
                            $editor-> Value = "$resumo";            //Informa o valor inicial do campo, no exemplo est� vazio 
                            $editor-> Width = "685";                //informa a largura do editor  
                            $editor-> Height = "500";               //informa a altura do editor
                            $editor-> Create();                     // Cria o editor 
                            ?>
                        </fieldset>
                        <b>* Este resumo ser&aacute; apresentado junto com o link para a not&iacute;cia.</b>
                        <br/><br/><br/>
                        <fieldset class='noticiaCompleta'>
                            <legend><b>Not&iacute;cia Completa:</b></legend>
                            <?php
                            $editor = new FCKeditor("noticia_completa");    //Nomeia a area de texto
                            $editor-> BasePath = "./fckeditor/";            //Informa a pasta do FKC Editor
                            $editor-> Value = "$noticia_completa";          //Informa o valor inicial do campo, no exemplo est� vazio 
                            $editor-> Width = "685";                        //informa a largura do editor  
                            $editor-> Height = "500";                       //informa a altura do editor
                            $editor-> Create();                             // Cria o editor 
                            ?>
                            <!--<div id='noticia_completa'></div>
                            <script>
                                ClassicEditor
                                    .create( document.querySelector( '#noticia_completa' ) )
                                    .then( editor => {
                                        console.log( editor );
                                    } )
                                    .catch( error => {
                                        console.error( error );
                                    } );
                            </script>-->
                        </fieldset>
                        
                        <b>* Este &eacute; o texto exibido com a not&iacute;cia completa.</b><br/><br/>
                        <center><input type='submit' name='botaoSubmit' id='botaoSubmit' class='botaoSubmit' value='SALVAR' size="10px" /></center>
                        </form>
                </div>
                <?php }else{echo "<h1>".$erro."</h1>";}?>
            </div>

            <div id='menu_inferior'>
                <ul>
                    <li><a href="../perfil/index.php" target="_self">PERFIL</a></li>					
                    <li><a href="../forum/index.php" target="_self">F&Oacute;RUM</a></li>
                    <?php if ($oSessao->getVariavelSessao("nivelAcesso") == "Administrador" || $oSessao->getVariavelSessao("nivelAcesso") == "Coordenador" || $oSessao->getVariavelSessao("nivelAcesso") == "Funcion�rio - Secretaria") { ?>
                    <li><a href="../secretaria/index.php" target="_self">SECRETARIA</a></li>
                    <?php } ?>					
                    <li><a href="../portal/index.php" target="_self">SAIR</a></li>						
                </ul>
            </div>
        </div>
    </body>
</html>