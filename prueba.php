<!doctype>
<html>

<head>
</head>

<body>
    <?php

    function parsearGramaje($gramaje) {
        $gramajes_default = [90, 115, 120, 127, 130, 140, 150, 160, 180, 195, 200,
            280, 300, 320, 330, 350, 380, 400, 430, 450, 480, 530, 550, 600];
        
        for ($i = 0; $i < sizeof($gramajes_default); $i++){
            if (str_contains($gramaje, $gramajes_default[$i])){
                return strval($gramajes_default[$i]);
            }
        }
        return $gramaje;
    }

    function getNumeroUniones($id_rollo){
        // numero_uniones = {"(u)": 1, "(2u)": 2, "(3u)": 3}
        $numero_uniones = [
            "(u)" => "1",
            "(2u)" => "2",
            "(3u)" => "3",
        ];

        foreach ($numero_uniones as $s => $i){
            if (str_contains($id_rollo, $s)){
                return $i;
            }
        }
        return "0";
    }

    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
    $tmpfname = "test.xlsx";
    $filecontent = file_get_contents($tmpfname);
    // $tmpfname = tempnam(sys_get_temp_dir(),"tmpxls");
    file_put_contents($tmpfname, $filecontent);

    $obj = \PhpOffice\PhpSpreadsheet\IOFactory::load("produccionm2.xlsx");
    $worksheet = $obj->getSheet(0);
    $highestRow = $worksheet->getHighestRow(); // e.g. 10
    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
    $highestColumn++;

    echo '<table>' . "\n";
    $x = 15;
    $acc = 0;
    $suma_produccion = 0;
    
    $num_maquina = "2";
    $fecha = strval($worksheet->getCell("E" . "10") ->getValue());
    while(!str_contains(strval($worksheet->getCell("B" . $x) ->getValue()), "º") && $x < $highestRow){
        $id_rollo = strval($worksheet->getCell("D" . $x) ->getValue());
        if ($id_rollo == ""){
            $x++;
        }else{
            $almacen = "2";
            $uniones = getNumeroUniones($id_rollo);
            $gramaje = parsearGramaje(strval($worksheet->getCell("B" . $x) ->getValue()));
            $num_cliente = strval($worksheet->getCell("C" . $x) ->getValue());
            if (str_contains($id_rollo, "inv")){
                $almacen = "3";
                $id_rollo = str_replace("inv", "", $id_rollo);
            }

            $ancho = strval($worksheet->getCell("F" . $x) ->getValue());
            $peso  = (int)$worksheet->getCell("E" . $x) ->getValue();
            echo '<tr><td>' . $id_rollo . ", " . $num_maquina . ", " . $gramaje . ", " . $ancho . ", " . $peso . ", " . $almacen . ", " . $uniones . ", " . $fecha . ", " . $num_cliente . '</td>' . PHP_EOL; echo '</tr>';
            $suma_produccion += $peso; 
            $x++;
        }
    }
    echo $suma_produccion;
    echo '</table>' . PHP_EOL;
    ?>

</body>

</html>