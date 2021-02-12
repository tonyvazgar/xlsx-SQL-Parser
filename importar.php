<?php
    session_start(); 
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Importar producción</title>
        <link href="../almacen/lib/css/bootstrap.min.css" rel="stylesheet">
        <link href="style/style.css" rel="stylesheet">
    </head>
    <body>
        <?php
            include '../almacen/ui/header.php';
            if (isset($_SESSION['message']) && $_SESSION['message'])
            {
                printf('<b>%s</b>', $_SESSION['message']);
                unset($_SESSION['message']);
            }
        ?>
        <div class="container">
        <form method="POST" action="upload.php" enctype="multipart/form-data">
            <table class="table table-borderless text-center" frame='box'>
                <tbody>
                    <th scope="row" colspan="2">
                        <h3 class='display-1 text-center'>Selecciona un archivo:</h3>
                    </th>
                    <tr>
                        <td colspan="2">
                            <input type="file" name="uploadedFile" class="form-control" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" name="uploadBtn" value="Upload" class="btn btn-success" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </body>
</html>