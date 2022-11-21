<?php 



try{

  $bdd2 = new PDO('mysql:host=localhost;dbname=api_snk','root','',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

  
}

catch(Exception $e){
  die('Erreur :'.$e->getMessage());
}

?>