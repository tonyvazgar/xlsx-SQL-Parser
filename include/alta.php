<?php
    function subir($produccion, $maquina){
        include "../../almacen/lib/conexion.php";
        $errores = [];
        $message = '';
        foreach($produccion as $turno){
            foreach($turno as $registro){
                if($registro[1] != $maquina){
                    array_push($errores, "El archivo no es de la máquina que seleccionaste!");
                    break;
                }
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
                    if(mysqli_errno($con) == 1062){
                        $exploded = str_replace("'","", explode(",", $parsed_data));
                        $string_duplicados = "El rollo #{$exploded[0]}-{$exploded[1]}P-{$exploded[2]}g-{$exploded[3]}cm-{$exploded[4]}kg -> NO se agrego porque ya fue dado de alta anteriormente!";
                        array_push($errores, $string_duplicados);
                    }else{
                    array_push($errores, mysqli_error($con));
                    }
                }
                //---------------------------------------------------------------
            }
            echo "<br>";
        }

        //-------------------------- SI SIRVE --------------------------
        if(sizeof($errores) > 0){
            $message .= '<div class="container"><ul class="list-group"><h2 class="display-2 text-center text-danger">Errores en:</h2>';
            foreach($errores as $error){
                $message .=  '<li class="list-group-item"><h4 class="text-danger">'.$error.'</h4></li>';
            }
            $message .= '</ul></div>';
            $_SESSION['message'] = $message;
            header('Location: ../index.php?id='.$maquina);
        }else{
            $message .= '<div class="container"><h1 class="display-1 text-center text-success">Toda la producción fue dada de alta con éxito!</h1></div>';
            $_SESSION['message'] = $message;
            header('Location: ../index.php?id='.$maquina);
        }
        //---------------------------------------------------------------
    }
?>