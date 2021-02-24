<?php
    function subir($produccion, $maquina){
        include "../../almacen/lib/conexion.php";
        $errores = [];
        $message = '';
        foreach($produccion as $turno){
            foreach($turno as $registro){
                // echo "<br>";
                // print_r($registro);
                $resultado = mysqli_query($con, $registro);
                if ($resultado) {
                    // header("Location: ../lib/pdf/imprimir.php?&id=$id&numMaquina=$maquina&fecha=$fecha");
                    echo "<h4>Registo insertado con exito!</h4>";
                } else {
                    // or die('<h2> Error --> '.mysqli_error($con).'</h2>');
                    //echo '<label> Hubo un error --> '.mysqli_error($con).'<label>';
                    array_push($errores, mysqli_error($con));
                }
            }
            echo "<br>";
        }
        if(sizeof($errores) > 0){
            echo '<h3>Errores en:</h3>';
            echo '<br>';
            foreach($errores as $error){
                echo '<h4>'.$error.'</h4>';
                echo '<br>';
            }
        }else{
            $message = 'Toda la producción fue dada de alta con éxito!';
            $_SESSION['message'] = $message;
            header('Location: ../index.php?id='.$maquina);
        }
    }
?>