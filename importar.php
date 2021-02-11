<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Importar producción</title>
    </head>
    <body>
        <form action="#" method="POST" enctype="multipart/form-data">
            <input type="file" name="excel" id="excel" required>
            <input type="submit" name="submit">
        </form>
        <?php
            if(isset($_FILES['excel']['name'])){
                echo "Hola prro";
                echo realpath($_FILES['excel']['name']);

            }
        ?>

    </body>
</html>