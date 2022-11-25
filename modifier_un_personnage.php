
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

     $error_file_upload =  $error_file_upload2 = "";
     $error_nom = $error_origine = "";
     $origine_final = "";
     $nom_final = "";
     $data_personnage = "";
     
     
     $id_personnage = intval($_GET['id']);
     //* Sert à afficher les données par défaut des inputs de type text notamment
     $req = $bdd2->query("SELECT * FROM personnages2 WHERE id = $id_personnage");
     $data_personnage = $req->fetch();
    
    
   if(isset($_POST['submit'])){
     
      
      function gestion_contenu_des_inputs_textes()
      {
        global $error_nom;
        global $nom_final;
        global $origine_final;
        global $error_origine;

        $nom = $_POST['nom'];
        $origine = $_POST['origine'];
        $origine_disponible = array ('Eldiens','Mahr','Titans');
        
        if(preg_match('/[A-Z\sa-z]/',$nom)):
          $error_nom = "";
          $nom_final = $nom;

        else:
          $error_nom = '<p id="error">Merci de ne pas inclure d\'accent ou de chiffres mais uniquement des lettres </p>';
        endif;

        $resultat = match(true) {
          in_array($origine,$origine_disponible) => $origine_final = $origine,
          default => $error_origine = '<p id="error">Merci de n\'inscrire que les origines suivantes : Eldiens , Mahr ou Titans</p>'
        };

      }

      gestion_contenu_des_inputs_textes();

      $valeur_image_carte_apres_verification= $valeur_image_histoire_apres_verification = "";

      //* À partir d'ici la logique de vérification des inputs files à été découper en plusieurs fonctions afin d'éviter trop de répétitions 
      function si_les_inputs_file_sont_vides(){

        global $bdd2;
        global $valeur_image_carte_apres_verification;
        global $valeur_image_histoire_apres_verification;
        global $id_personnage;

        $filenameCarte = $_FILES['imagefile']['name'][0];
        $filenameHistoire = $_FILES['imagefile']['name'][1];

        //* Récupère les valeurs existantes pour les réinsérer si aucun nouveau fichier n'est téléchargé
        if(empty($filenameCarte) && empty($filenameHistoire)):
          $req = $bdd2->query("SELECT imageCarte,imageHistoire from personnages2 WHERE id = $id_personnage");
          $data = $req->fetch();
          $valeur_image_carte_apres_verification = $data['imageCarte'];
          $valeur_image_histoire_apres_verification = $data['imageHistoire'];
        endif;
         
      }

      si_les_inputs_file_sont_vides();

     
      function si_image_carte_ne_change_pas_mais_que_image_histoire_change()
      {
        global $bdd2;
        global $error_file_upload2;
        global $id_personnage;
        global $valeur_image_histoire_apres_verification;
        
        
        //* Emplacement du dossier image
        $dir = "img";
        
        //* La méthode scandir permet de récupérer le contenu du dossier img sous forme de tableau et array_diff retire la ponctuation inutile rajouté par scandir dans ce cas là 
        $files = array_diff(scandir($dir),array('..','.'));
        
        $filenameCarte = $_FILES['imagefile']['name'][0];
        $filenameHistoire = $_FILES['imagefile']['name'][1];
        $extensions_autorises = array('jpg','jpeg','png','webp');
        $destination_imageHistoire = "img/$filenameHistoire";

         //* Si un fichier est pris en compte via le champ "ImageHistoire"
         if(empty($filenameCarte) && $filenameHistoire):

          $tmp_name_histoire = $_FILES['imagefile']['tmp_name'][1];

          $extension_du_fichier_telecharge = explode('.',$filenameHistoire);
   
            //* Vérifie que l'extension du fichier est la bonne
             if(in_array($extension_du_fichier_telecharge[1],$extensions_autorises))
             {

              //* Récupération du nom de l'image dans le bon champ
              $req = $bdd2->query("SELECT imageHistoire from personnages2 WHERE id = $id_personnage");
              $data = $req->fetch();

              //* Récupération du nom de l'image avec son extension sans l'adresse http...
              $nom_du_fichier_dans_la_bdd =  explode('/',$data['imageHistoire']);
              
              //* On vérifie que le nom de l'image dans la bdd correspond avec celui dans le dossier img
                foreach($files as $nom_du_fichier_histoire_dans_le_dossier_image_a_supprimer_en_cas_de_correspondance)
                {
                  //* En cas de correspondance on supprime l'image du dossier et on enregistre la nouvelle dont le nom sera sauvegardé dans une variable qui servira pour la mise à jour
                  if($nom_du_fichier_histoire_dans_le_dossier_image_a_supprimer_en_cas_de_correspondance === $nom_du_fichier_dans_la_bdd[5]):
       
                        //* On supprime l'ancien fichier et sauvegarde le nouveau
                     unlink("$dir/$nom_du_fichier_histoire_dans_le_dossier_image_a_supprimer_en_cas_de_correspondance");
                     
                  else : 
                      move_uploaded_file($tmp_name_histoire,$destination_imageHistoire);
                      $valeur_image_histoire_apres_verification = "http://localhost/shingeki-no-kyojin/img/$filenameHistoire";
                        header('location:personnages.php');
                 
                  endif;
              
                }

              }

               else 
               {
                 $error_file_upload2 = '<p id="error">Merci de vérifier que votre image contient bien l\'une des extensions suivantes : png,webp,jpg,jpeg</p> ';
               }
               
         
         endif;
       
      }

      si_image_carte_ne_change_pas_mais_que_image_histoire_change();
//? Les fonctions fonctionnent correctement mais d'autres test devront être effectué à nouveau !
      function testUpdate() {
        global $valeur_image_carte_apres_verification;
        global $valeur_image_histoire_apres_verification;
        global $nom_final;
        global $origine_final;
        echo var_dump($valeur_image_histoire_apres_verification).'<br>';
        echo $valeur_image_carte_apres_verification;
      }

      testUpdate();

      
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
  
</label>
<?php echo $error_file_upload; ?> <br>


<label for="imagehistoire">
  ImageHistoire<br>
  <input type="file" id="imagehistoire" name="imagefile[]" ><br>
  
</label>
<?php echo $error_file_upload2; ?> <br>
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