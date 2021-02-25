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
    return $year . "-" . $month . "-" . $day;
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

function remove_inv($id_rollo)
{
    $almacen = "2";
    if (str_contains($id_rollo, "inv")) {
        $almacen = "3";
        $id_rollo = str_replace("inv", "", $id_rollo);
    }
    return [$almacen, $id_rollo];
}

function to_string($id_rollo, $num_maquina, $gramaje, $ancho, $peso, $almacen, $uniones, $fecha, $num_cliente, $extra)
{
    // (1610, 2, '350', '46.0', 460, 3, 2, '2021-02-12', '1112', 'Almacen', '3', '2', '60 B.RCT-MBC'),
    return 'INSERT INTO `Rollo` (`ID`, `numMaquina`, `tipoPapel`, `ancho`, `peso`, `numAlmacen`, `numUniones`, `fechaFabricacion`, `cliente`, `inventariado`, `JefeTurno`, `Turno`, `Observaciones`) VALUES (' . $id_rollo . ", " . $num_maquina . ", '" . $gramaje . "', '" . $ancho . "', " . $peso . ", " . $almacen . ", " . $uniones . ", " . $fecha . ", '" . $num_cliente . "', " . $extra;
     
}

function read($sheet, $num_maquina, $fecha_column, $limit_column, $id_rollo_column, $gamaje_column, $num_cliente_cloumn, $ancho_cloumn, $peso_column, $vel_column, $numTurno){
    $info = [];
    $highestRow      = $sheet->getHighestRow(); // e.g. 10
    $highestColumn   = $sheet->getHighestColumn(); // e.g 'F'
    $highestColumn++;
    $x               = 15;
    $acc             = 0;
    $suma_produccion = 0;

    $fecha = formatDate(strval($sheet->getCell($fecha_column . "10")->getValue()));
    while (!str_contains(strval($sheet->getCell($limit_column . $x)->getValue()), "º") && $x < $highestRow) {
        $id_rollo = strval($sheet->getCell($id_rollo_column . $x)->getValue());
        if ($id_rollo == "" or $id_rollo == "0") {
            $x++;
        } else {
            $gramaje     = parsearGramaje(strval($sheet->getCell($gamaje_column . $x)->getValue()));
            if ($gramaje != ""){
                $temp_inv    = remove_inv($id_rollo);
                $almacen     = $temp_inv[0];
                $id_rollo    = $temp_inv[1];
                $temp        = getNumeroUniones($id_rollo);
                $uniones     = $temp[1];
                $id_rollo    = $temp[0];
                $num_cliente = strval($sheet->getCell($num_cliente_cloumn . $x)->getValue());
            

                $ancho = galleta(strval($sheet->getCell($ancho_cloumn . $x)->getValue()));
                $peso  = (int)$sheet->getCell($peso_column . $x)->getValue();
                $vel   = strval($sheet->getCell($vel_column . $x)->getValue());
                $extra = "";
                if ($numTurno == 1){
                    $data = [$id_rollo, $num_maquina, $gramaje, $ancho, $peso, $almacen, $uniones, $fecha, $num_cliente, 'Almacen', '4', '1', $vel];
                } else if ($numTurno == 2){
                    $data = [$id_rollo, $num_maquina, $gramaje, $ancho, $peso, $almacen, $uniones, $fecha, $num_cliente, 'Almacen', '3', '2', $vel];
                } else if ($numTurno == 3){
                    $data = [$id_rollo, $num_maquina, $gramaje, $ancho, $peso, $almacen, $uniones, $fecha, $num_cliente, 'Almacen', '2', '3', $vel];
                }
                array_push($info, $data);
                $suma_produccion += $peso;
                $x++;
            }else{
                $x++;
            }
        }
    }
    return $info;
}

function rollosTurno($sheet, $num_maquina, $numTurno)
{
    if ($numTurno == 1) {
        return read($sheet, $num_maquina, "E", "B", "D", "B", "C", "F", "E", "G", $numTurno);
    } else if ($numTurno == 2) {
        return read($sheet, $num_maquina, "E", "I", "K", "I", "J", "M", "L", "N", $numTurno);
    } else {
        return read($sheet, $num_maquina, "E", "P", "R", "P", "Q", "T", "S", "U", $numTurno);
    }
}
