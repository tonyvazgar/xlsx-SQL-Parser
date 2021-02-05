<?php
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', true);
    include "SimpleXLSX.php";
    
    if ( $xlsx = SimpleXLSX::parse('produccionm2.xlsx') ) {
        echo "<pre>";	
		// print_r($xlsx->rows(1));
		// print_r($xlsx->dimension(2));
		print_r($xlsx->sheetNames());
        for ($sheet=0; $sheet < sizeof($xlsx->sheetNames()) ; $sheet++) { 
            $rowcol=$xlsx->dimension($sheet);
            $i=0;
            if($rowcol[0]!=1 &&$rowcol[1]!=1){
                // print_r($xlsx->rows($sheet));
                foreach ($xlsx->rows($sheet) as $key => $row) {
                    $q="";
                    foreach ($row as $key => $cell) {
                        //print_r($cell);echo "<br>";
                        if($i==0){
                            $q.=$cell. " varchar(50),";
                        }else{
                            if($cell != ""){
                                $q.="'".$cell. "',";
                                $query="INSERT INTO ".$xlsx->sheetName($sheet)." values (".rtrim($q,",").");";
                            }
                        }
                    }
                    // if($i==0){
                    //     $query="CREATE table ".$xlsx->sheetName($sheet)." (".rtrim($q,",").");";
                    // }else{
                    //     $query="INSERT INTO ".$xlsx->sheetName($sheet)." values (".rtrim($q,",").");";
                    // }
                    echo $query;
                    echo "<br>";
                    $i++;
        
                }
            }
        }
    } else {
        echo SimpleXLSX::parseError();
    }
?>