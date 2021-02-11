<!doctype>
<html>

<head>
</head>

<body>
    <?php

    require 'vendor/autoload.php';
    require 'parser.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $tmpfname = "test.xlsx";
    $filecontent = file_get_contents($tmpfname);
    // $tmpfname = tempnam(sys_get_temp_dir(),"tmpxls");
    file_put_contents($tmpfname, $filecontent);

    $obj = \PhpOffice\PhpSpreadsheet\IOFactory::load("produccionm1.xlsx");
    $worksheet = $obj->getSheet(0);

    echo '<table>' . "\n";
    rollosTurno($worksheet, "2", 1);
    rollosTurno($worksheet, "2", 2);
    rollosTurno($worksheet, "2", 3);
    echo '</table>' . PHP_EOL;
    ?>

</body>

</html>