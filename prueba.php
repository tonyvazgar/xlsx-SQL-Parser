<!doctype>
<html>

<head>
</head>

<body>
    <?php

    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
    $tmpfname = "test.xlsx";
    $filecontent = file_get_contents($tmpfname);
    // $tmpfname = tempnam(sys_get_temp_dir(),"tmpxls");
    file_put_contents($tmpfname, $filecontent);

    $obj = \PhpOffice\PhpSpreadsheet\IOFactory::load("produccionm1.xlsx");
    $worksheet = $obj->getSheet(0);
    $highestRow = $worksheet->getHighestRow(); // e.g. 10
    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
    $highestColumn++;

    echo '<table>' . "\n";
    $x = 15;
    $acc = 0;
    $suma_produccion = 0;
    while(!str_contains(strval($worksheet->getCell("B" . $x) ->getValue()), "º") && $x < $highestRow){
        $id_rollo = strval($worksheet->getCell("D" . $x) ->getValue());
        if ($id_rollo == ""){
            $x++;
        }else{
            $almacen = "2";
            $uniones = 0;
            if (str_contains($id_rollo, "inv")){
                $almacen = "3";
                $id_rollo = str_replace("inv", "", $id_rollo);
            }
            $ancho = strval($worksheet->getCell("F" . $x) ->getValue());
            $peso  = (int)$worksheet->getCell("E" . $x) ->getValue();
            echo '<tr><td>' . $id_rollo . ", " . $ancho . ", " . $peso . ", " . $almacen . '</td>' . PHP_EOL; echo '</tr>';
            $suma_produccion += $peso; 
            $x++;
        }
    }
    echo $suma_produccion;
    // for ($row = 14; $row <= $highestRow; ++$row) {
    //     echo '<tr>' . PHP_EOL;
    //     for ($col = 'A'; $col != $highestColumn; ++$col) {
    //         $celda = strval($worksheet->getCell($col . $row)
    //         ->getValue());
    //         if(!str_contains($celda, "º")){
    //             echo '<td>' . $celda
    //             .
    //         '</td>' . PHP_EOL;
    //         }else{
    //             break;
    //         }
                
    //     }
    //     echo '</tr>' . PHP_EOL;
    // }
    echo '</table>' . PHP_EOL;
    ?>

</body>

</html>