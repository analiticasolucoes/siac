<?php
	require "../vendor/autoload.php";

    use SIAC\Mensagem;
	
	$oMensagem = new Mensagem();

	$campoOculto = $_POST['campoOculto'];
	
	if($campoOculto == 1){
		foreach($_POST as $dado){
			$oMensagem->setIdMensagem($dado);
			$oMensagem->excluir();
		}
		
		echo "
			<script>
				alert('Mensagem(ns) excluída(s) com sucesso!'); document.location = './index.php';
			</script>";
	}else{
		$oMensagem->setStatus("NAO-LIDA");
		
		foreach($_POST as $dado){
			$oMensagem->setIdMensagem($dado);
			$oMensagem->marcarMensagem();
		}
		
		echo "
			<script>
				alert('Mensagem(ns) marcada(s) como não-lida(s)!'); document.location = './index.php';
			</script>";
	}
?>