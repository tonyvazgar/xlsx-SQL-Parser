<?php
    $num_maquina = isset($_GET["id"]) ? $_GET["id"] : 0;
    if ($num_maquina == 1 or $num_maquina == 2){
        session_start(); 
?>
        <!DOCTYPE html>
        <html>
            <head>
                <title>Importar producción</title>
                <link href="../../almacen/lib/css/bootstrap.min.css" rel="stylesheet">
                <link href="../style/style.css" rel="stylesheet">
            </head>
            <body>
                <?php
                    include '../../almacen/ui/header.php';
                    if (isset($_SESSION['message']) && $_SESSION['message'])
                    {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    }
                    echo '<h2 class="display-2 text-center">Importar producción M'.$num_maquina.' desde archivo de reporte</h2>';
                ?>
                <div class="container">
                    <form method="POST" action="../include/upload.php" enctype="multipart/form-data">
                        <table class="table table-borderless text-center" frame='box'>
                            <tbody>
                                <th scope="row" colspan="4">
                                    <h3 class='display-1 text-center'>Selecciona un archivo:</h3>
                                </th>
                                <tr>
                                    <td colspan="4">
                                        <input type="file" name="uploadedFile" class="form-control text-center" />
                                    </td>
                                </tr>
                                <th scope="row" colspan="4">
                                    <h3 class='display-1 text-center'>Selecciona la fecha y máquina:</h3>
                                    <?php
                                        include "../../almacen/lib/conexion.php";
                                        $ultimo = 0;
                                        $fecha  = "";
                                        $turno  = "";
                                        $consulta = "SELECT * 
                                                     FROM Rollo 
                                                     WHERE numMaquina='$num_maquina'
                                                     AND YEAR(fechaFabricacion) = YEAR(CURDATE()) 
                                                     AND Rollo.inventariado!='ReemAlta' 
                                                     AND Rollo.Observaciones!='REEMBOBINADO' 
                                                     ORDER BY ABS(`Rollo`.`ID`) DESC LIMIT 1";
                                        $resultado = mysqli_query($con, $consulta) or die("Algo ha ido mal en la consulta a la base de datos");
                                        while ($fila = mysqli_fetch_assoc($resultado)) {
                                            $ultimo = intval($fila['ID']);
                                            $fecha  = date($fila['fechaFabricacion']);
                                            $turno  = $fila['Turno'];
                                        }
                                        echo '<h4 class="display-1 text-center">Último rollo M'.$num_maquina.': #' . number_format($ultimo) .' del '. $fecha .' en el turno '. $turno .'</h4>';
                                    ?>
                                </th>
                                <tr>
                                    <td colspan="3">
                                        <!-- <input type="file" name="uploadedFile" class="form-control" /> -->
                                        <?php
                                            $first = date('Y-m-01');
                                            $today = date("Y-m-d");
                                            $last  = date("Y-m-d", strtotime($today));
                                            echo '<input type="date" id="fecha" name="fecha" value='.$today.' min='.$first.' max='.$last.' class="form-control text-center">';
                                        ?>
                                    </td>
                                    <td colspan="1">
                                        <!-- <input type="file" name="uploadedFile" class="form-control" /> -->
                                        <?php
                                            echo '<input type="text" class="form-control text-center" name="maquina" id="maquina" value='.$num_maquina.' readonly>';
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <input type="submit" name="uploadBtn" value="Upload" class="btn btn-success" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    <div class="footer" style='z-index:5;'>
                        <div class="text-right">
                            <?php
                                $gitBasePath   = '../.git'; // e.g in laravel: base_path().'/.git';
                                $gitStr        = file_get_contents($gitBasePath . '/HEAD');
                                $gitBranchName = rtrim(preg_replace("/(.*?\/){2}/", '', $gitStr));
                                $gitPathBranch = $gitBasePath . '/refs/heads/' . $gitBranchName;
                                $gitHash       = file_get_contents($gitPathBranch);
                                $gitDate       = date(DATE_ATOM, filemtime($gitPathBranch));

                                echo "<p>$gitBranchName ==>[$gitHash]</p>";
                            ?>
                        </div>
                    </div>
                </div>
            </body>
            <?php
    }else{
        header("Location: ../../../almacen/index.php");
    }
?>