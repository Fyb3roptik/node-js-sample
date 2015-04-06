<?php
 
 class File_Writer {
   
   public function writeFile($filename, $contents, $hash = false) {
    try {
      $newFile = fopen(DIR_ROOT . "tmp/" . $filename . ".txt", "a");
      $wrote = fwrite($newFile, $contents);
      fclose($newFile);
      
      if($hash == true) {
        system("md5sum " . DIR_ROOT . "tmp/" . $filename . ".txt > " . DIR_ROOT . "tmp/" . $filename . ".hsh");
      }
      
      return true;
    } catch (Exception $e) {
      //var_dump($e);
    }
   }
   
 }
  
?>