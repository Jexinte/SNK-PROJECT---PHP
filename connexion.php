<?php include './config/connexion_bdd.php' ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - SNK</title>
  <link rel="stylesheet" href="./css/main.css">
</head>
<body>
  <div class="container">
    <?php 
    
    $errorMail = $errorPassword = "";
    if(isset($_POST['submit'])){


        function verifieLadresseMail() {
         
          global $errorMail ;
          if(empty($_POST['mail']))
            $errorMail = "Ce champ ne peut être vide";
          
        }

        verifieLadresseMail();
        function verifieLeMotDePasse() {
         global $errorPassword;
           if(empty($_POST['motdepasse']))
              $errorPassword = "Ce champ ne peut être vide !";
      }
        

        verifieLeMotDePasse();


     
        function siToutEstBon()
        {
            global $bdd;
           if (!empty($_POST['motdepasse']) && preg_match('/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/',$_POST['mail']))
           {
                $mail = $_POST['mail'];
                $motdepasse = $_POST['motdepasse'];
              
                $requete_verification_des_informations_dans_la_bdd = $bdd->query(" SELECT * FROM users WHERE mail = '$mail'  ");
       
              while($data = $requete_verification_des_informations_dans_la_bdd->fetch()){
                      if(password_verify($motdepasse , $data['motdepasse'])) {
                  //* La redirection sera faite plus tard !
               
                  echo 'Nice !';
                  }
        
                   else
                   global  $errorPassword;
                   $errorPassword = "Le mot de passe est incorrect !"; 
              }
             
            }
      }

      siToutEstBon();

      
      
      function siToutNestPasBon(){
        global $bdd;
        global $errorMail;
        if (!empty($_POST['motdepasse']) && preg_match('/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/',$_POST['mail'])){
          $mail = $_POST['mail'];
          
          $requete_verification_des_informations_dans_la_bdd = $bdd->query(" SELECT * FROM users WHERE mail != '$mail'  ");
          
          while($data = $requete_verification_des_informations_dans_la_bdd->fetch()){
            if($data['mail'] !== $mail)
            $errorMail = "Cette adresse mail n'existe pas !";
          }
        }
      }
      
      siToutNestPasBon();
      //! END OF ISSET CONDITON
    }

  
     
    ?>
<fieldset>
    <legend>Connexion</legend>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">

      <label for="mail">
        Adresse mail : <br>
        <input type="mail" name="mail" id="mail"> <br>
        <span id="errorMail"><?php echo $errorMail; ?></span>
      </label>

      <label for="motdepasse">
        Mot de passe : <br>
        <input type="password" name="motdepasse" id="motdepasse"> <br>
        <span id="errorPassword"><?php echo $errorPassword; ?></span>
      </label>

      <input type="submit" name="submit" id="submit" value="Envoyer">
    </form>
  </fieldset>

  </div>
</body>
</html>