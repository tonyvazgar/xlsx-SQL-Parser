<?php
    function subir($produccion){
        include "../almacen/lib/conexion.php";
        foreach($produccion as $turno){
            foreach($turno as $registro){
                echo "<br>";
                print_r($registro);
                $resultado = mysqli_query($con, $registro) or die(mysqli_error($con));
                if ($resultado) {
                    // header("Location: ../lib/pdf/imprimir.php?&id=$id&numMaquina=$maquina&fecha=$fecha");
                } else {
                    echo "<label>Hubo un error</label>";
                }
            }
            echo "<br>";
        }
    }
?>