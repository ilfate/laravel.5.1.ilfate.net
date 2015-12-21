<?php
/**
 * Created by PhpStorm.
 * User: ilfate
 * Date: 17.11.15
 * Time: 17:42
 */

$handle = fopen("php://stdin", "r");
$t = fgets($handle);

//$stairs = [];
//for ($i = 1; $i <= $t; $i++) {
//
//    //$stairs[] = str_pad('', $i, '#') . "\n";
//    print(str_pad('', $t - $i, ' ') . str_pad('', $i, '#') . "\n");
//
//}

function modulo( $limit) {
    for ($i = 1; $i <= $limit; $i++) {
        $isNumer = true;
        if ($i % 3 === 0) {
            $isNumer = false;
            echo "MeinFernBus";
        }
        if ($i % 5 === 0) {
            $isNumer = false;
            echo "FlixBus";
        }

        if ($isNumer) {
            echo $i . "\n";
        } else {
            echo "\n";
        }
    }
}

//function reverse( $INPUT) {
//    $strlen = strlen($INPUT);
//
//    for($i = 0; $i < $strlen / 2; $i++) {
//        if ($strlen -  (2 * $i) - 2 < 0) {
//            break;
//        }
//        $startLetter = $INPUT[$i];
//        $endLetter = $INPUT[$strlen - $i - 1];
//        $INPUT = substr($INPUT, 0, $i)
//            . $endLetter . substr($INPUT, $i + 1, $strlen -  (2 * $i) - 2)
//            . $startLetter . substr($INPUT, $strlen - $i, $i);
//    }
//
//    return $INPUT;
//}
//$t = '123456789';

modulo($t);
echo "\n";
//fclose($handle);









//$filename = "myfile.txt";
//if(file_exists($filename)) {
//    $data = file_get_contents( $filename );
//    print $data;
//    $filewrite = fopen( $filename, 'a') or die('Cannot open file:'.$filename);
//    fwrite( $filewrite, "write something here");
//    fclose( $filewrite );
//} else {
//    $filewrite = fopen( $filename, 'w') or die('Cannot open file:'.$filename);
//    fwrite( $filewrite, "write something here");
//    fclose( $filewrite );
//}
