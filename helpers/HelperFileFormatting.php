<?php
/**
 * Created by PhpStorm.
 * User: maurice
 * Date: 19/10/2015
 * Time: 17:09
 */

class HelperFileFormatting {

/////////////////////CSV/////////////////////////

    //$source is an array multidimencional
    public static function createCsv($source,$csvName,$delimiter=','){
        $fp = fopen($csvName, 'w+');
        foreach ($source as  $row) {
            fputcsv($fp,$row,$delimiter);
        }
        fclose($fp);
    }

    public static function csvToArray($pathCsv,$delimiter=','){
        $csv = [];
        $manager = fopen($pathCsv, "r");
        while ( ($row = fgetcsv($manager,0,$delimiter) ) !== FALSE) {
            array_push($csv, $row);
        }
        fclose($manager);
        return $csv;
    }

    public static function unshiftColumDefinition($batches,$columDefinition){
        return array_map(function($batch) use ($columDefinition) {
            array_unshift($batch,$columDefinition);
            return $batch;
        },$batches);
    }


/////////////////////ZIP/////////////////////////

    public static function createZip($name,$files,$filesPath,$deleteFile=false){
        $zip = new ZipArchive();
        $zip->open($name,ZipArchive::CREATE);
        foreach ($files as $value) {
            $zip->addFile( $filesPath.$value,$value);
            if($deleteFile)unlink($filesPath.$value);
        }
        $zip->close();
    }

/////////////////////STRINGS/////////////////////

    public static function getBetween($source,$start,$end){
        preg_match_all('/(?<='.$start.')(.*?)(?='.$end.')/s',$source,$matches);
        $result =  strip_tags($matches[0][0]);
        return  $result;
    }

/////////////////////GET FILES//////////////////

    public static function downloadFile($source,$destination){
        $file = file_get_contents($source);
        file_put_contents($destination, $file);
        return $destination;
    }

    public static function sendClientFile($filePath,$contentType){
        $fileName = basename($filePath);
        header("Content-Type: application/$contentType");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Length: " . filesize($filePath));
        readfile($filePath);
        exit();
    }

    public static function  uploadFilesToServer($destinationPaht,$inputName){
        if ($_FILES[$inputName]["name"][0]) {
            for ($i = 0; $i < count($_FILES[$inputName]["name"]); $i++) {
                $fileDestination = $destinationPaht . $i.$_FILES[$inputName]['name'][$i];
                move_uploaded_file($_FILES[$inputName]['tmp_name'][$i], $fileDestination);
            }
            return $_FILES[$inputName];
        }
    }

    ///////////////////////ARRAYS/////////////////

    public static function objectsToArray($arObjects){
        return array_map(function($item){
            return get_object_vars($item);
        },$arObjects);
    }




}


