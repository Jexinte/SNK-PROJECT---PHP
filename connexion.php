<?php include './config/connexion_bdd.php' ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - SNK</title>

  <link rel="stylesheet" href="./css/main.css">
    <!-- MEDIA QUERIES -->
  <link rel="stylesheet" href="./css/form_mediaqueries.css">
</head>
<body>
  <div class="container">
    <?php include './inc/header_connexion.php';?>
    <?php 
    
    $errorMail = $errorPassword = "";

    if(isset($_POST['submit'])){


        function verifieLadresseMail() 
        {
  
          global $errorMail ;

          if(empty($_POST['mail']))
            $errorMail = "Ce champ ne peut être vide";
          
        }

        verifieLadresseMail();

        function verifieLeMotDePasse() 
        {
        
          global $errorPassword;
          
          if(empty($_POST['motdepasse']))
              $errorPassword = "Ce champ ne peut être vide !";
        }
        

        verifieLeMotDePasse();

        
      function siLadresseMailNexistePas()
      {
        global $bdd;
        global $errorMail;
      
        $mail = $_POST['mail'];
        $mail_in_database = "";
        $requete_verification_des_informations_dans_la_bdd = $bdd->query(" SELECT * FROM users WHERE mail != '$mail'  ");
          
          while($data = $requete_verification_des_informations_dans_la_bdd->fetch())
          {
            $mail_in_database = $data['mail'];

              if($mail != $mail_in_database)
                $errorMail = "L'adresse mail n'existe pas !";

          }

      }
      
      siLadresseMailNexistePas();


      //* Une dernière vérification est effectué notamment sur le format de l'adresse mail puis une recherche de l'adresse mail est effectué dans la base de données afin de savoir si elle existe
        function siToutEstBon()
        {
            global $bdd;
            global $errorMail;
            global  $errorPassword;
           
            if (!empty($_POST['motdepasse']) && preg_match('/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/',$_POST['mail']))
           {
                $mail = $_POST['mail'];
                $motdepasse = $_POST['motdepasse'];
                $requete_verification_des_informations_dans_la_bdd = $bdd->query(" SELECT * FROM users WHERE mail = '$mail'  ");
       
              while($data = $requete_verification_des_informations_dans_la_bdd->fetch())
              {
                  if(password_verify($motdepasse , $data['motdepasse']))
                  {
                    //* La redirection sera faite plus tard !
                    $errorMail = ""; 
                    echo 'Les données correspondent';
                  }
        
                   else
                     $errorPassword = "Le mot de passe est incorrect !"; 
                   
              }
             
            }
      }

      siToutEstBon();

      
      
      //! END OF ISSET CONDITON
    }

  
     
    ?>
  <h1 class="h1">Connexion</h1>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
    <span class="info-form">Tous les champs munis d'un * sont obligatoires</span>
      <label for="mail">
        Adresse mail * <br>
        <input type="mail" name="mail" id="mail"> <br>
        <span id="errorMail"><?php echo $errorMail; ?></span>
      </label>

      <label for="motdepasse">
        Mot de passe * <br>
        <input type="password" name="motdepasse" id="motdepasse"> <br>
        <span id="errorPassword"><?php echo $errorPassword; ?></span>
      </label>

      <input type="submit" name="submit" id="submit" value="Envoyer">
    </form>

  </div>

  <style>

    body{
      background-image: url('./img/mikasa.jpg');
      background-size: cover;
    }
  </style>
</body>
</html>