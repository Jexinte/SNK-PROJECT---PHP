
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Création de personnage </title>
</head>
<body>
<?php


include './config/connexion_bdd2.php';
include './config/connexion_bdd.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Personnages</title>
</head>
<body>

 <div class="container">
   <!-- HEADER -->
   <?php include './inc/menu_personnages.php';?>
   <?php 

    //* ERREURS
        $error_file_upload = "";
        $error_file_upload_2 = "";
        $error_nom = $error_origine = "";

    //* NECESSAIRE POUR LA PARTIE ENREGISTREMENT
        $origine_final = "";
        $nom_final = "";
        $filename_image_carte_final_result = $filename_image_histoire_final_result = "";
        $tmp_nom_0 = $tmp_nom_1 = "";
        $destination_image_carte =  $destination_image_histoire = "";
     
     if(isset($_POST['submit'])){

     //* NECESSAIRE POUR LA PARTIE ENREGISTREMENT DU PERSONNAGE DANS LA BASE DE DONNES 
     $user_id = intval($_POST['userid']);

   function gestion_contenu_des_inputs_textes() {
    global $error_nom;
    global $error_origine;
    global $origine_final;
    global $nom_final;

    $nom_personnage = $_POST['nom'];
    $origine_personnage = $_POST['origine'];
    $origine_attendu = array('Eldiens','Mahr','Titans');

    if(preg_match('/[A-Z\sa-z]/',$nom_personnage))
      {
        $error_nom = "";
        $nom_final = $nom_personnage;
      }

    else
    {
      $error_nom = '<p id="error">Merci de ne pas inclure d\'accent ou de chiffres mais uniquement des lettres </p>';
    }

    if(in_array($origine_personnage,$origine_attendu))
    {
      $error_origine = "";
      $origine_final = $origine_personnage;
    }

    else
    {
      $error_origine = '<p id="error">Merci de n\'inscrire que les origines suivantes : Eldiens , Mahr ou Titans</p>';
    }

  }

  gestion_contenu_des_inputs_textes();


    function gestion_contenu_fichiers(){

      global $error_file_upload;
      global $error_file_upload_2;
      global $filename_image_carte_final_result;
      global $filename_image_histoire_final_result;
      global $destination_image_carte;
      global $destination_image_histoire;
      global $tmp_nom_0;
      global $tmp_nom_1;

     //* 1 - On récupère les valeurs des inputs
      $filename_image_carte = $_FILES['imagefile']['name'][0];
      $filename_image_histoire = $_FILES['imagefile']['name'][1];
    
    //* 2 - Récupération de l'extension des fichiers téléchargés
      $explode_sur_filename_image_carte = explode('.',$filename_image_carte);
      $explode_sur_filename_image_histoire = explode('.',$filename_image_histoire);
   
    //* Processus de vérification
      
      $extensions_autorises = array('png','webp','jpg','jpeg');
      if(!in_array($explode_sur_filename_image_carte[1],$extensions_autorises)){
        $error_file_upload = '<p id="error">Merci de vérifier que votre image contient bien l\'une des extensions suivantes : png,webp,jpg,jpeg</p> ';
      }

      else {
        $error_file_upload = "";
        $filename_image_carte_final_result = $filename_image_carte;
        $tmp_nom_0 = $_FILES['imagefile']['tmp_name'][0];
        $destination_image_carte = "img/$filename_image_carte_final_result"; 
       
      }

      if(!in_array($explode_sur_filename_image_histoire[1],$extensions_autorises)){
        $error_file_upload_2 = '<p id="error">Merci de vérifier que votre image contient bien l\'une des extensions suivantes : png,webp,jpg,jpeg</p> ';
      }
      
      else{
        $filename_image_histoire_final_result = $filename_image_histoire;
        $tmp_nom_1 = $_FILES['imagefile']['tmp_name'][1];
        $destination_image_histoire = "img/$filename_image_histoire_final_result"; 
        $error_file_upload_2 = "";
      }

      
    }

    gestion_contenu_fichiers();

  //* Enregistrement des éléments dans la base de données

    function enregistrement_personnage(){

      global $origine_final;
      global $nom_final;
      global $tmp_nom_0;
      global $tmp_nom_1;
      global $destination_image_carte;
      global $destination_image_histoire;
      global $filename_image_carte_final_result;
      global $filename_image_histoire_final_result;
      global $user_id;
      global $bdd2;

      $histoire = $_POST['histoire'];
      $affiliation = $_POST['affiliation'];
      $image_carte_url = "http://localhost/shingeki-no-kyojin/img/$filename_image_carte_final_result";
      $image_histoire_url = "http://localhost/shingeki-no-kyojin/img/$filename_image_histoire_final_result";

      if(!empty($nom_final) && !empty($origine_final) && !empty($tmp_nom_0) && !empty($tmp_nom_1) && !empty($filename_image_carte_final_result) && !empty($filename_image_histoire_final_result)){
        move_uploaded_file($tmp_nom_0,$destination_image_carte);
        move_uploaded_file($tmp_nom_1,$destination_image_histoire);
        $bdd2->query("INSERT INTO personnages2 (nom,histoire,affiliation,origine,imageCarte,imageHistoire,id_user) VALUES('$nom_final','$histoire','$affiliation','$origine_final','$image_carte_url','$image_histoire_url','$user_id')");
         header('location:personnages.php');
      }

    }
    enregistrement_personnage();
  }
   ?>
   <div class="container-box">
  <h1>Création d'un personnage </h1>

<!-- FORMULAIRE -->

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">

<label for="nom">
  Nom <br>
  <input type="text" id="nom" name="nom" required> <br>
</label>
<?php echo $error_nom; ?> <br>

<label for="histoire">
  Histoire <br>
  <textarea name="histoire" id="histoire" cols="30" rows="10" required></textarea> <br>
</label>

<label for="affiliation">
  Affiliation <br>
  <input type="text" id="affiliation" name="affiliation" required><br>
</label>

<label for="origine">
  Origine <br>
  <input type="text" id="origine" name="origine" required><br>
</label>
<?php echo $error_origine; ?> <br>

<label for="imagecarte">
  ImageCarte<br>
  <input type="file" id="imagecarte" name="imagefile[]" required><br>
</label>
<?php echo $error_file_upload; ?> <br>


<label for="imagehistoire">
  ImageHistoire<br>
  <input type="file" id="imagehistoire" name="imagefile[]" required><br>
</label>
<?php echo $error_file_upload_2; ?> <br>
<?php   ?>
<label for="iduser">
  <input type="text" value="<?php echo $_COOKIE['userid']?>" name="userid" hidden>  <br>
</label>

<input type="submit" value="Envoyer" name="submit">
</form>

</div>
<?php include './inc/footer_personnages.php'?>
</div>
<style>
.container-box
{
 display: flex;
 flex-direction: column;
 gap: 2em;
 transition: all ease-in 700ms;
 margin-bottom: 4em;
}

form{
    width: 80%;
  margin: 0 auto;
}

#error{
  color:red;
}
input,textarea{
  width: 100%;
  padding: 1em;
}

textarea{
  resize: none;
}

header{
 margin-bottom: 8em;
}

label{
  line-height: 2em;
}
/* label:nth-child(7){
  display: none;
} */
h1{
  text-align: center;
  font-size: 2.5em;
}
</style>
</body>
</html>