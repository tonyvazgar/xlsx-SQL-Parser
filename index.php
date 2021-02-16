<?php
    $maquina = $_GET['id'];
    echo $maquina;
    header("Location: ./ui/index.php?id=$maquina");
?>