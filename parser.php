<?php

function parsearGramaje($gramaje)
{
    $gramajes_default = [
        90, 115, 120, 127, 130, 140, 150, 160, 180, 195, 200,
        280, 300, 320, 330, 350, 380, 400, 430, 450, 480, 530, 550, 600
    ];

    for ($i = 0; $i < sizeof($gramajes_default); $i++) {
        if (str_contains($gramaje, $gramajes_default[$i])) {
            return strval($gramajes_default[$i]);
        }
    }
    return $gramaje;
}

function getNumeroUniones($id_rollo)
{
    // numero_uniones = {"(u)": 1, "(2u)": 2, "(3u)": 3}
    $numero_uniones = [
        "(u)" => "1",
        "(2u)" => "2",
        "(3u)" => "3",
    ];

    foreach ($numero_uniones as $s => $i) {
        if (str_contains($id_rollo, $s)) {
            $id = str_replace($s, "", $id_rollo);
            return [$id, $i];
        }
    }
    return [$id_rollo, "0"];
}

function formatDate($fecha)
{
    $meses = [
        "ENERO" => "01", "FEBRERO" => "02", "MARZO" => "03", "ABRIL" => "04", "MAYO" => "05", "JUNIO" => "06",
        "JULIO" => "07", "AGOSTO" => "08", "SEPTIEMBRE" => "09", "OCTUBRE" => "10", "NOVIEMBRE" => "11", "DICIEMBRE" => "12"
    ];

    $year = substr($fecha, -4);
    $month = "";
    $day = substr($fecha, 0, 2);
    foreach ($meses as $s => $v) {
        if (str_contains($fecha, $s)) {
            $month = $v;
        }
    }
    return "'" . $year . "-" . $month . "-" . $day . "'";
}

function galleta($ancho)
{
    if (str_contains($ancho, "x") or str_contains($ancho, "X")) {
        $ancho = str_replace("x", "(", $ancho);
        $ancho = str_replace("X", "(", $ancho);
        $ancho .= ")";
    }
    return $ancho;
}

function rollosTurno($sheet, $num_maquina, $numTurno)
{
    if ($numTurno == 1) {
        $highestRow = $sheet->getHighestRow(); // e.g. 10
        $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
        $highestColumn++;
        $x = 15;
        $acc = 0;
        $suma_produccion = 0;

        $fecha = formatDate(strval($sheet->getCell("E" . "10")->getValue()));
        while (!str_contains(strval($sheet->getCell("B" . $x)->getValue()), "º") && $x < $highestRow) {
            $id_rollo = strval($sheet->getCell("D" . $x)->getValue());
            if ($id_rollo == "") {
                $x++;
            } else {
                $almacen = "2";
                $temp = getNumeroUniones($id_rollo);
                $uniones = $temp[1];
                $id_rollo = $temp[0];
                $gramaje = parsearGramaje(strval($sheet->getCell("B" . $x)->getValue()));
                $num_cliente = strval($sheet->getCell("C" . $x)->getValue());
                if (str_contains($id_rollo, "inv")) {
                    $almacen = "3";
                    $id_rollo = str_replace("inv", "", $id_rollo);
                }

                $ancho = galleta(strval($sheet->getCell("F" . $x)->getValue()));
                $peso  = (int)$sheet->getCell("E" . $x)->getValue();
                $vel   = strval($sheet->getCell("G" . $x)->getValue());
                $extra = "'Almacen', '4', '1', '" . $vel . "'),";
                echo '<tr><td>' . $id_rollo . ", " . $num_maquina . ", " . $gramaje . ", " . $ancho . ", " . $peso . ", " . $almacen . ", " . $uniones . ", " . $fecha . ", " . $num_cliente . ", " . $extra . '</td>' . PHP_EOL;
                echo '</tr>';
                $suma_produccion += $peso;
                $x++;
            }
        }
        echo '<tr><td>' . $suma_produccion . '</td>' . PHP_EOL;
        echo '</tr>';
    } else if ($numTurno == 2) {
        $highestRow = $sheet->getHighestRow(); // e.g. 10
        $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
        $highestColumn++;
        $x = 15;
        $acc = 0;
        $suma_produccion = 0;

        $fecha = formatDate(strval($sheet->getCell("E" . "10")->getValue()));
        while (!str_contains(strval($sheet->getCell("I" . $x)->getValue()), "º") && $x < $highestRow) {
            $id_rollo = strval($sheet->getCell("K" . $x)->getValue());
            if ($id_rollo == "") {
                $x++;
            } else {
                $almacen = "2";
                $temp = getNumeroUniones($id_rollo);
                $uniones = $temp[1];
                $id_rollo = $temp[0];
                $gramaje = parsearGramaje(strval($sheet->getCell("I" . $x)->getValue()));
                $num_cliente = strval($sheet->getCell("J" . $x)->getValue());
                if (str_contains($id_rollo, "inv")) {
                    $almacen = "3";
                    $id_rollo = str_replace("inv", "", $id_rollo);
                }

                $ancho = galleta(strval($sheet->getCell("M" . $x)->getValue()));
                $peso  = (int)$sheet->getCell("L" . $x)->getValue();
                $vel   = strval($sheet->getCell("N" . $x)->getValue());
                $extra = "'Almacen', '3', '2', '" . $vel . "'),";
                echo '<tr><td>' . $id_rollo . ", " . $num_maquina . ", " . $gramaje . ", " . $ancho . ", " . $peso . ", " . $almacen . ", " . $uniones . ", " . $fecha . ", " . $num_cliente . ", " . $extra . '</td>' . PHP_EOL;
                echo '</tr>';
                $suma_produccion += $peso;
                $x++;
            }
        }
        echo '<tr><td>' . $suma_produccion . '</td>' . PHP_EOL;
        echo '</tr>';
    } else {
        $highestRow = $sheet->getHighestRow(); // e.g. 10
        $highestColumn = $sheet->getHighestColumn(); // e.g 'F'
        $highestColumn++;
        $x = 15;
        $acc = 0;
        $suma_produccion = 0;

        $fecha = formatDate(strval($sheet->getCell("E" . "10")->getValue()));
        while (!str_contains(strval($sheet->getCell("P" . $x)->getValue()), "º") && $x < $highestRow) {
            $id_rollo = strval($sheet->getCell("R" . $x)->getValue());
            if ($id_rollo == "") {
                $x++;
            } else {
                $almacen = "2";
                $temp = getNumeroUniones($id_rollo);
                $uniones = $temp[1];
                $id_rollo = $temp[0];
                $gramaje = parsearGramaje(strval($sheet->getCell("P" . $x)->getValue()));
                $num_cliente = strval($sheet->getCell("Q" . $x)->getValue());
                if (str_contains($id_rollo, "inv")) {
                    $almacen = "3";
                    $id_rollo = str_replace("inv", "", $id_rollo);
                }

                $ancho = galleta(strval($sheet->getCell("T" . $x)->getValue()));
                $peso  = (int)$sheet->getCell("S" . $x)->getValue();
                $vel   = strval($sheet->getCell("U" . $x)->getValue());
                $extra = "'Almacen', '2', '3', '" . $vel . "'),";
                echo '<tr><td>' . $id_rollo . ", " . $num_maquina . ", " . $gramaje . ", " . $ancho . ", " . $peso . ", " . $almacen . ", " . $uniones . ", " . $fecha . ", " . $num_cliente . ", " . $extra . '</td>' . PHP_EOL;
                echo '</tr>';
                $suma_produccion += $peso;
                $x++;
            }
        }
        echo '<tr><td>' . $suma_produccion . '</td>' . PHP_EOL;
        echo '</tr>';
    }
}
