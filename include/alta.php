<?php
    function subir($produccion, $maquina){
        include "../../almacen/lib/conexion.php";
        $errores = [];
        $message = '';
        foreach($produccion as $turno){
            foreach($turno as $registro){
                $parsed_data = '';
                for ($i=0; $i < sizeof($registro); $i++) { 
                    if($i == sizeof($registro)-1){
                        $parsed_data .= "'" . $registro[$i] . "');";
                    }else{
                        $parsed_data .= "'" . $registro[$i] . "', ";
                    }
                }
                $query = "INSERT INTO `Rollo` (`ID`, `numMaquina`, `tipoPapel`, `ancho`, `peso`, `numAlmacen`, `numUniones`, `fechaFabricacion`, `cliente`, `inventariado`, `JefeTurno`, `Turno`, `Observaciones`) VALUES (" . $parsed_data;
                
                //-------------------------- SI SIRVE --------------------------
                $resultado = mysqli_query($con, $query);
                if (mysqli_affected_rows($con) > 0)  {
                    $link     = '../../almacen/lib/pdf/imprimir.php?&id='.$registro[0].'&numMaquina='.$registro[1].'&fecha='.$registro[7];
                    $message .= '<script type="text/javascript">window.open("';
                    $message .= $link;
                    $message .= '", "_blank");</script>';
                } else {
                    array_push($errores, mysqli_error($con));
                }
                //---------------------------------------------------------------
            }
            echo "<br>";
        }

        //-------------------------- SI SIRVE --------------------------
        if(sizeof($errores) > 0){
            $message .= '<h2 class="display-2 text-center text-danger">Errores en:</h2>';
            $message .=  '<br>';
            foreach($errores as $error){
                $message .=  '<h2 class="display-2 text-center text-danger">'.$error.'</h2>';
                $message .=  '<br>';
            }
            $_SESSION['message'] = $message;
            header('Location: ../index.php?id='.$maquina);
        }else{
            $message .= '<h1 class="display-1 text-center text-success">Toda la producción fue dada de alta con éxito!</h1>';
            $_SESSION['message'] = $message;
            header('Location: ../index.php?id='.$maquina);
        }
        //---------------------------------------------------------------
    }
?>