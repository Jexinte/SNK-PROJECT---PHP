
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modification de personnage </title>
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

     $error_file_upload = "";
     $error_nom = $error_origine = "";
     $origine_final = "";
     $nom_final = "";
     $data_personnage = "";
     
     
     $id_personnage = intval($_GET['id']);
     $req = $bdd2->query("SELECT * FROM personnages2 WHERE id = $id_personnage");
     $data_personnage = $req->fetch();
    
    
   if(isset($_POST['submit'])){

   function gestion_du_contenu_des_inputs() {
    global $error_nom;
    global $error_origine;
    global $origine_final;
    global $nom_final;

    $nom_personnage = $_POST['nom'];
    $origine_personnage = $_POST['origine'];
    $origine_attendu = array('Eldiens','Mahr','Titans');

    if(preg_match('/[a-z]/',$nom_personnage))
      {
        $error_nom = "";
        $nom_final = $nom_personnage;
      }

    else
    {
      $error_nom = '<p id="error">Merci de ne pas inclure de symboles ou de chiffres mais uniquement des lettres en minuscules</p>';
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

  gestion_du_contenu_des_inputs();
   //* Permet le téléchargement de plusieurs fichiers
    function derniere_verification_puis_insertion_des_donnees()
    {
      global $bdd2; 
      global $error_file_upload;
      global $origine_final;
      global $nom_final;
      global $id_personnage;

    
      $histoire = $_POST['histoire'];
      $affiliation = $_POST['affiliation'];

      foreach($_FILES['imagefile']["error"] as $key => $error)
      {
        //* Si aucune erreur d'envoi via la méthode post
        if($error == UPLOAD_ERR_OK)
        {
          // * On récupère le nom des fichiers temporairement sauvegardés côtés serveur pour plus tard
          $tmp_name = $_FILES['imagefile']["tmp_name"][$key];
          $nom_des_fichiers = $_FILES['imagefile']["name"][$key];

          $telechargement_vers_le_dossier_image = "img/$nom_des_fichiers";

          $extensions_autorises = array('png','webp','jpg','jpeg','svg');
          $extension_des_fichiers_telecharges = explode('.',$nom_des_fichiers);
          
          //* Si les fichiers sont bien envoyés
          if(!empty($_FILES['imagefile']['name'][$key]))
          {
            //* On vérifie que l'extension des fichiers téléchargés correspondent avec ceux autorisés
              if(in_array($extension_des_fichiers_telecharges[1],$extensions_autorises) || $nom_final || $histoire || $affiliation)
              {
                $image_carte_file = $_FILES['imagefile']['name'][0];
                $imageCarte = "http://localhost/shingeki-no-kyojin/img/$image_carte_file";
                $image_histoire_file = $_FILES['imagefile']['name'][1];
                $imageHistoire = "http://localhost/shingeki-no-kyojin/img/$image_histoire_file";
                $userid = intval($_POST['userid']);
            
                //? L'objectif maintenant est de trouver le moyen d'effectuer l'enregistrement des donneés une fois les vérifications faites
                
                      move_uploaded_file($tmp_name,$telechargement_vers_le_dossier_image);
                      $req2 = $bdd2->prepare("UPDATE personnages2
                       SET nom = '$nom_final',
                       histoire = '$histoire', 
                       affiliation = '$affiliation', 
                       origine = '$origine_final',
                       imageCarte = '$imageCarte',
                       imageHistoire = '$imageHistoire',
                       id_user ='$userid' WHERE id= $id_personnage"
                       );

                       $req2->execute();
                       
                      header("location:personnage.php?id=$id_personnage");
                    
            
              }
      
              else
                $error_file_upload = '<p id="error">Merci de vérifier que votre image contient bien l\'une des extensions suivantes : png,webp,jpg,jpeg</p> ';
              
          }
         
         
        }

      }


  }
   derniere_verification_puis_insertion_des_donnees();
    }

   ?>
   <div class="container-box">
  <h1>Modification d'un personnage </h1>
  <!-- FORMULAIRE -->
  
  <form  method="POST" enctype="multipart/form-data">


<label for="nom">
  Nom <br>
  <input type="text" id="nom" name="nom" value="<?php echo $data_personnage['nom'];?>" > <br>
</label>
<?php echo $error_nom; ?> <br>

<label for="histoire">
  Histoire <br>
  <textarea name="histoire" id="histoire" class="histoire" cols="30" rows="10" required ><?php echo $data_personnage['histoire'];?></textarea> <br>
</label>

<label for="affiliation">
  Affiliation <br>
  <input type="text" id="affiliation" name="affiliation" value="<?php echo $data_personnage['affiliation']; ?>" ><br>
</label>

<label for="origine">
  Origine <br>
  <input type="text" id="origine" name="origine" value="<?php echo $data_personnage['origine']; ?>"><br>
</label>
<?php echo $error_origine; ?> <br>

<label for="imagecarte">
  ImageCarte<br>
  <input type="file" id="imagecarte" name="imagefile[]"><br>
  <img id="output1" src="<?php echo $data_personnage['imageCarte']; ?>" height="100" width="100" alt="">
</label>
<?php echo $error_file_upload; ?> <br>


<label for="imagehistoire">
  ImageHistoire<br>
  <input type="file" id="imagehistoire" name="imagefile[]" ><br>
  <img id="output2" src="<?php echo $data_personnage['imageHistoire']; ?>" height="100" width="100" alt="">
</label>
<?php echo $error_file_upload; ?> <br>
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