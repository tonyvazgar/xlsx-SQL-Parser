<?php
session_start();
require 'vendor/autoload.php';
require 'parser.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$message = '';
if (isset($_POST['uploadBtn']) && $_POST['uploadBtn'] == 'Upload') {
    if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath   = $_FILES['uploadedFile']['tmp_name'];
        $fileName      = $_FILES['uploadedFile']['name'];
        $fileSize      = $_FILES['uploadedFile']['size'];
        $fileType      = $_FILES['uploadedFile']['type'];
        $fileNameCmps  = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // $newFileName   = md5(time() . $fileName) . '.' . $fileExtension;
        $newFileName   = $fileName;

        $allowedfileExtensions = array('xlsx');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // directory in which the uploaded file will be moved
            $uploadFileDir = './uploaded_files/';
            $dest_path     = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $message = 'File is successfully uploaded. <br>' . 'Filename: ' . $newFileName. '<br>' . 'Path: ' . $dest_path;
                //------------------------------------------------------------------------------------------------------------------
                $fecha   = $_POST['fecha'];
                $maquina = $_POST['maquina'];

                $sheet = idate('d', strtotime($fecha))-1;


                $message .= $maquina . "----". $sheet;
                $obj = \PhpOffice\PhpSpreadsheet\IOFactory::load($dest_path);
                $worksheet = $obj->getSheet($sheet);
                echo "La hoja es: ".$sheet;

                echo '<table>' . "\n";
                rollosTurno($worksheet, $maquina, 1);
                rollosTurno($worksheet, $maquina, 2);
                rollosTurno($worksheet, $maquina, 3);
                echo '</table>' . PHP_EOL;
                //------------------------------------------------------------------------------------------------------------------
            } else {
                $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
            }
        } else {
            $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
        }
    } else {
        $message = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
    }
} else {
    $message = 'Hubo un error al cargar el archivo, Please check the following error.<br>';
    $message = 'Error: ' . $_FILES['uploadedFile']['error'];
}
$_SESSION['message'] = $message;
// header('Location: importar.php');
