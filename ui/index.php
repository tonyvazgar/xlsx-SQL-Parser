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
                        printf('<b>%s</b>', $_SESSION['message']);
                        unset($_SESSION['message']);
                    }
                    echo '<h1 class="display-1 text-center">Importar producción M'.$num_maquina.' desde archivo</h1>';
                ?>
                <div class="container">
                <form method="POST" action="../upload.php" enctype="multipart/form-data">
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
            </body>
            <?php
    }else{
        header("Location: ../../../almacen/index.php");
    }
?>