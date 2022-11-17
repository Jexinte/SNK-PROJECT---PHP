<?php 

// define('DB_HOST','localhost');
// define('DB_USER','root');
// define('DB_PASSWORD','');
// define('DB_NAME','php_training');

// $host = 'localhost';

try{

  $bdd = new PDO('mysql:host=localhost;dbname=php_training','root','',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  
}

catch(Exception $e){
  die('Erreur :'.$e->getMessage());
}

?>