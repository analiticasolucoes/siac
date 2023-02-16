<?php

function gerarPainelPerfil($sNivelAcesso, $sNomeUsuario) {
    switch ($sNivelAcesso) {
        case "Usuario":

            break;
        default:
            $sPainelPerfil = "
            <div id='painel_perfil'>
                <center><big><b>Meu Perfil</b></big></center>
                <ul class='menu_perfil_ul'>
                    <li class='menu_perfil_li'><b>Nome:</b> $sNomeUsuario</li>
                    <li class='menu_perfil_li'><b>Turma:</b> </li>
                    <li class='menu_perfil_li'><b>Curso:</b> </li>
                    <li class='menu_perfil_li'><b>Turno:</b></li>
                    <li class='menu_perfil_li'><b>Tel.:</b></li>
                </ul>
                <a href='#5' class='link_alterar'>+ Alterar</a>
                <hr/>
                <center><big><b>Meus Contatos</b></big></center><br/>					
                <img src='../img/icone.png'/><b>Professores</b>
                <ul class='menu_perfil_ul'>
                </ul>
                <br/>
                <img src='../img/icone.png'/><b>Colegas</b>
                <ul class='menu_perfil_ul'>
                </ul>
                <a href='#3'>...</a>
            </div>";
            break;
    }

    return $sPainelPerfil;
}

?>