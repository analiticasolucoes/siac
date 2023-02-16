<?php
    require "../vendor/autoload.php";

    use SIAC\Sessao;
    use SIAC\Noticia;
    use SIAC\Paginacao;

	$oSessao = new Sessao();
	$oNoticia = new Noticia();
	$aListaDeNoticias = array();

	if ($oSessao->estaLogado() == null) {
		$oSessao->expulsar();
	}
	
	$iIdUsuario = $oSessao->getVariavelSessao("iIdUsuario");	

	$opcao = @$_GET['opcao'];

	switch ($opcao) {
		case 1://LISTAR RESULTADO DA BUSCA
			$sQuery = $_GET['campoPesquisar'];
			$parametro = $_GET['radioParametro'];

			if ($parametro == 1) {//BUSCA POR ID
				$oNoticia->setIdnoticia((int)$sQuery);
				$aListaDeNoticias = $oNoticia->listar() ? $oNoticia : null;
			}
			if ($parametro == 2) {//BUSCA POR TITULO, RESUMO, NOTICIA_COMPLETA
				$oNoticia->setTitulo($sQuery);
				$aListaDeNoticias = $oNoticia->listarPorTitulo();
			}
			if ($parametro == 3) {//BUSCA POR DATA
				//converte a data para formato MySQL (YYYY-mm-dd)
				$sQuery = implode('-', array_reverse(explode('/', $sQuery)));
				$oNoticia->setData_noticia($sQuery);
				$aListaDeNoticias = $oNoticia->listarPorData();
			}
			if ($parametro == 4) {//BUSCA POR STATUS
				$sQuery = $_GET['status'];
				$oNoticia->setStatus($sQuery);
				$aListaDeNoticias = $oNoticia->listarPorStatus();
			}

			$oPaginacao = new Paginacao(5, @sizeof($aListaDeNoticias), "pesquisarNoticia.php?");

			if (!$pagina = @$_GET['pagina']){
				$oPaginacao->setINumeroPagina();
			} else {
				$oPaginacao->setINumeroPagina($pagina);
			}

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
	</head>	
	
	<body>		
		<div id='geral'>
	
			<div id='menu_superior'>
				<div id='text_menu_esquerda'>Seja bem-vindo(a) <?php echo $oSessao->getVariavelSessao("sNomeUsuario");?></div>
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
					<form name='formPesquisa' action='./pesquisar.php?opcao=1' method='get' onSubmit='return pesquisar();'>
						<b>PESQUISAR NOT&Iacute;CIAS</b><br/><br/>
						<input type='text' size=80 name='campoPesquisar' id='campoPesquisar' class='campoPesquisar' value=''/>&nbsp;
						<input type='submit' id='botaoSubmit' value='PESQUISAR'/><br/>
						<input type='radio' name='radioParametro' id='radioParametro1' value='1' checked/><b>ID</b>&nbsp;
						<input type='radio' name='radioParametro' id='radioParametro2' value='2'/><b>TITULO</b>&nbsp;
						<input type='radio' name='radioParametro' id='radioParametro3' value='3'/><b>DATA (dd/mm/aaaa)</b>&nbsp;
						<input type='radio' name='radioParametro' id='radioParametro4' value='4'/><b>STATUS</b>&nbsp;
                        <select id='status' NAME='status'>
                            <option value="ABERTA">ABERTA</option>
                            <option value="REVISADA">REVISADA</option>
                            <option value="PUBLICADA">PUBLICADA</option>
                            <option value="INATIVA">INATIVA</option>
                        </select>
                        <input type='hidden' name='opcao' value='1'/>
					</form>

					<table class='tabelaPostarNoticia'>
						<tr class='titulo'>
							<td colspan=2 class='celTituloData'>Data</td>
							<td class='celTituloNoticia'>Titulo</td>								
							<td class='celTituloStatus'>Status</td>
						</tr>
						<?php
						if ($opcao == null || $aListaDeNoticias == null) {
							echo "
							<tr class='linhaDados'>
								<td colspan=5><b>NENHUMA NOT&Iacute;CIA ENCONTRADA</b></td>
							</tr>";
						} else {
							switch($parametro){
								case 1:
									$oNoticia->setIdnoticia($sQuery);
									$aListaDeNoticias = $oNoticia->listar($oPaginacao->getIPrimeiroRegistro(), $oPaginacao->getIQtdRegistrosPorPagina());
									$aListaDeNoticias = get_object_vars($oNoticia);
									var_dump($aListaDeNoticias);
									var_dump($oNoticia);
								break;
								
								case 2:
									$oNoticia->setTitulo($sQuery);
									$aListaDeNoticias = $oNoticia->listarPorTitulo($oPaginacao->getIPrimeiroRegistro(), $oPaginacao->getIQtdRegistrosPorPagina());
								break;

								case 3:
									$sQuery = implode('-', array_reverse(explode('/', $sQuery)));
									$oNoticia->setData_noticia($sQuery);
									$aListaDeNoticias = $oNoticia->listarPorData($oPaginacao->getIPrimeiroRegistro(), $oPaginacao->getIQtdRegistrosPorPagina());
								break;

								case 4:
									$sQuery = $_GET['status'];
									$oNoticia->setStatus($sQuery);
									$aListaDeNoticias = $oNoticia->listarPorStatus($oPaginacao->getIPrimeiroRegistro(), $oPaginacao->getIQtdRegistrosPorPagina());
								break;
							}
							
							foreach ($aListaDeNoticias as $noticia) {
								list($idnoticia,, $titulo,,, $data,, $status) = $noticia;
								$data = date("d/m/Y H:i", strtotime($data));
								echo "
								<tr class='linhaDados'>
									<td class='celCheck'><input type='checkbox' name='check$idnoticia' value='$idnoticia'/></td>
									<td class='celData'>$data</td>
									<td class='celTitulo'><a href='./postarNoticia.php?opcao=2&idnoticia=$idnoticia'>$titulo</a></td>
									<td class='celStatus'>$status</td>
								</tr>";
							}
						}
						?>
					</table><br/>
				</div>
				<div id='paginacao'>
                    <?php
                    // exibir painel de navegacao
                    if($opcao != 0){ 
						echo $oPaginacao->getSPainelNavegacao();
					}
                    ?>
				</div>
			</div>
			
			<div id='menu_inferior'>
				<ul>
					<li><a href="../perfil/index.php" target="_self">PERFIL</a></li>					
					<li><a href="../forum/index.php" target="_self">F&Oacute;RUM</a></li>
					<?php if($oSessao->getVariavelSessao("nivelAcesso") == "Administrador" || $oSessao->getVariavelSessao("nivelAcesso") == "Coordenador" || $oSessao->getVariavelSessao("nivelAcesso") == "Funcion�rio - Secretaria"){?>
					<li><a href="../secretaria/index.php" target="_self">SECRETARIA</a></li>
					<?php } ?>					
					<li><a href="../portal/index.php" target="_self">SAIR</a></li>						
				</ul>
			</div>
			
		</div>
	</body>
</html>