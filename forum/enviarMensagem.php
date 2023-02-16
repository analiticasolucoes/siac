<?php
    require "../vendor/autoload.php";

	use SIAC\Mensagem;
	
	$oMensagem = new Mensagem();
	$opcao = $_GET['opcao'];
	if($opcao == 1){
		$mensagem = $_GET['mensagem'];
		$remetente = $_GET['remetente'];
		$destinatario = $_GET['destinatario'];
		
		echo "REMETENTE: ".$remetente;
		echo "<br/>DESTINATARIO: ".$destinatario;
		echo "<br/>MENSAGEM: ".$mensagem;
		echo "<br/>ENDERECO DE ORIGEM: ".$_SERVER['HTTP_REFERER'];
		$oMensagem->setRemetente($remetente);
		$oMensagem->setDestinatario($destinatario);
		$oMensagem->setConteudo($mensagem);
		$oMensagem->setTitulo("Voce recebeu uma advertencia da Moderacao do Forum!");
		
		if($oMensagem->inserir()){
			echo "
			<script>
				alert('Advertencia enviada com sucesso!');
				history.back();
			</script>";
		}else{
			$erro = "<h1>ERRO AO ENVIAR ADVERTENCIA!</h1>";
		}
		die();
	}else{
		
	}
?>