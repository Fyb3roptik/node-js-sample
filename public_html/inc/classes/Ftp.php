<?php
  
  class Ftp {
    
    public function sendFile($filename) {
      
      $dir = DIR_ROOT . "tmp/" . $filename;
      $ftp_server = "ftp.scapromotions.com";
      $username = "73394";
      $password = "beast73394";
      
      
      // set up basic connection
      $conn_id = ftp_connect($ftp_server);
      
      // login with username and password
      $login_result = ftp_login($conn_id, $username, $password);

     	$txtFile = $dir . ".txt";
     	$hashFile = $dir . ".hsh";

      // turn passive mode on
      ftp_pasv($conn_id, true);

     	$put = ftp_put($conn_id, $filename . ".txt", $txtFile, FTP_BINARY);
     	$put2 = ftp_put($conn_id, $filename . ".hsh", $hashFile, FTP_BINARY);
     	
     	// close the connection
      ftp_close($conn_id);
    }      
  }
?>