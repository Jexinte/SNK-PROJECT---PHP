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

      $valeur_image_carte_apres_verification = $valeur_image_histoire_apres_verification = "";

//* À partir d'ici la logique de vérification des inputs files à été découper en plusieurs fonctions afin d'éviter trop de répétitions 

      //* Les inputs ne contiennent pas de nouvelles images
      function si_les_images_ne_changent_pas()
      {

        global $bdd2;
        global $valeur_image_carte_apres_verification;
        global $valeur_image_histoire_apres_verification;
        global $id_personnage;


        foreach($_FILES['imagefile']['name'] as $file):
          if(empty($file)):
            $req = $bdd2->query("SELECT imageCarte , imageHistoire from personnages2 WHERE id = $id_personnage");
            $data = $req->fetch();
            $valeur_image_carte_apres_verification = $data[0];
            $valeur_image_histoire_apres_verification = $data[1];
          endif;
        endforeach;
         
      }

      si_les_images_ne_changent_pas();

       function si_image_carte_à_une_nouvelle_image(){
         global $valeur_image_carte_apres_verification;
         global $error_file_upload;
         global $bdd2;
         global $id_personnage;

         $filename_carte = "";
         $tmp_carte = "";
         $extensions_autorisés = array('jpg','png','webp','jpeg','PNG');
         $extension_du_fichier_telechargé = "";
         $dossier_img = "img/";

         $req = $bdd2->query("SELECT imageCarte from personnages2 WHERE id = $id_personnage");
         $data = $req->fetch();
         
         $traitement_nom_image_carte_dans_la_bdd = explode('/',$data[0]);
         $nom_image_carte_dans_la_bdd = $traitement_nom_image_carte_dans_la_bdd[5];
        
          $filename_carte = $_FILES['imagefile']['name'][0];
          $tmp_carte = $_FILES['imagefile']['tmp_name'][0];
          $extension_du_fichier_telechargé = explode('.',$filename_carte);
        

         //* Si le nom du fichier est présent alors on procède à la vérification de son extension plus bas et des vérifications plus poussées par la suite
         if(!empty($extension_du_fichier_telechargé[0])):
         
              if(in_array($extension_du_fichier_telechargé[1],$extensions_autorisés))
              {
                
                if($handle = opendir($dossier_img)): 
                  
                  //* Si le dossier est accessible alors on peut effectuer le traitement que l'on souhaite sur le dossier
                while(false !== ($nom_du_fichier_dans_le_dossier_image = readdir($handle))):
                    
                    if($nom_du_fichier_dans_le_dossier_image != "." && $nom_du_fichier_dans_le_dossier_image != ".."):

                        if($nom_du_fichier_dans_le_dossier_image === $nom_image_carte_dans_la_bdd):

                            unlink("$dossier_img/$nom_du_fichier_dans_le_dossier_image"); //* Suppression du fichier
                            move_uploaded_file($tmp_carte,"$dossier_img/$filename_carte"); //* Téléchargement du nouveau fichier
                            $valeur_image_carte_apres_verification = "http://localhost/shingeki-no-kyojin/img/$filename_carte"; //* Assignation du nom du nouveau fichier à sauvegarder dans la bdd
                            $error_file_upload = "";

                          endif;
                        
                      endif;
                endwhile;
                closedir($handle);
                
              endif;
              
            }

            else{
              $error_file_upload = '<p id="error">Merci de vérifier que votre image contient bien l\'une des extensions suivantes : png,webp,jpg,jpeg</p> ';
            }
      
        endif;

     }
     si_image_carte_à_une_nouvelle_image();

       function si_image_histoire_à_une_nouvelle_image(){
         global $valeur_image_histoire_apres_verification;
         global $error_file_upload2;
         global $bdd2;
         global $id_personnage;

         $filename_histoire = "";
         $tmp_histoire = "";
         $extensions_autorisés = array('jpg','png','webp','jpeg');
         $extension_du_fichier_telechargé = "";
         $dossier_img= "img/";

         $req = $bdd2->query("SELECT imageHistoire from personnages2 WHERE id = $id_personnage");
         $data = $req->fetch();
      
         $traitement_nom_image_histoire_dans_la_bdd = explode('/',$data[0]);
         $nom_image_histoire_dans_la_bdd = $traitement_nom_image_histoire_dans_la_bdd[5];
      
         $filename_histoire = $_FILES['imagefile']['name'][1];
         $tmp_histoire = $_FILES['imagefile']['tmp_name'][1];

         $extension_du_fichier_telechargé = explode('.',$filename_histoire);
     
  
         //* Si le nom du fichier est présent alors on procède à la vérification de son extension plus bas et des vérifications plus poussées par la suite
         if(!empty($extension_du_fichier_telechargé[0])):
         

            if(in_array($extension_du_fichier_telechargé[1],$extensions_autorisés))
            {
              
              if($handle = opendir($dossier_img)): 
                
                //* Si le dossier est accessible alors on peut effectuer le traitement que l'on souhaite sur le dossier
              while(false !== ($nom_du_fichier_dans_le_dossier_image = readdir($handle))):
                  
                  if($nom_du_fichier_dans_le_dossier_image != "." && $nom_du_fichier_dans_le_dossier_image != ".."):

                      if($nom_du_fichier_dans_le_dossier_image === $nom_image_histoire_dans_la_bdd):
                          unlink("$dossier_img/$nom_du_fichier_dans_le_dossier_image");  //* Suppression du fichier
                          move_uploaded_file($tmp_histoire,"$dossier_img/$filename_histoire");//* Téléchargement du nouveau fichier
                          $valeur_image_histoire_apres_verification = "http://localhost/shingeki-no-kyojin/img/$filename_histoire";//* Assignation du nom du nouveau fichier à sauvegarder dans la bdd
                          $error_file_upload2 = "";
                         
                      endif;
 
                    endif;
              endwhile;
              closedir($handle);
              
            endif;
            
          }

          else{
            $error_file_upload2 = '<p id="error">Merci de vérifier que votre image contient bien l\'une des extensions suivantes : png,webp,jpg,jpeg</p> ';
          }
      
        endif;

     }
     si_image_histoire_à_une_nouvelle_image();
     


    
      function mis_à_jour_des_donnees()
      {
          global $valeur_image_carte_apres_verification;
          global $valeur_image_histoire_apres_verification;
          global $nom_final;
          global $origine_final;
          global $id_personnage;
          global $bdd2;

          $histoire = $_POST['histoire'];
          $affiliation = $_POST['affiliation'];
          
          $req = $bdd2->query("UPDATE personnages2 SET nom = '$nom_final',histoire = '$histoire',affiliation = '$affiliation',origine = '$origine_final',imageCarte = '$valeur_image_carte_apres_verification',imageHistoire='$valeur_image_histoire_apres_verification' WHERE id=$id_personnage");
          $req->execute();

      }

      mis_à_jour_des_donnees();

      
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