<?php
include './config/connexion_bdd.php';
include 'vendor/autoload.php';

use ReallySimpleJWT\Token;
?>

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
    <?php include './inc/header_connexion.php'; ?>
    <?php

    $errorMail = $errorPassword = "";

    if (isset($_POST['submit'])) {


      function verifieLadresseMail()
      {

        global $errorMail;

        if (empty($_POST['mail']))
          $errorMail = "Ce champ ne peut être vide";
      }

      verifieLadresseMail();

      function verifieLeMotDePasse()
      {

        global $errorPassword;

        if (empty($_POST['motdepasse']))
          $errorPassword = "Ce champ ne peut être vide !";
      }


      verifieLeMotDePasse();






      function siLadresseMailNexistePas()
      {
        global $bdd;
        global $errorMail;

        $mailDeLutilisateurQuiSeConnecte = $_POST['mail'];

        $requete_verification_des_informations_dans_la_bdd = $bdd->query(" SELECT * FROM users WHERE mail = '$mailDeLutilisateurQuiSeConnecte' ");
        $utilisateur = $requete_verification_des_informations_dans_la_bdd->fetch();

        if (empty($utilisateur['mail']))
          $errorMail = "L'adresse mail n'existe pas !";
        else
          $errorMail = "";
      }

      siLadresseMailNexistePas();



      //* Une dernière vérification est effectué notamment sur le format de l'adresse mail puis une recherche de l'adresse mail est effectué dans la base de données afin de savoir si elle existe
      $identifiantDeLutilisateurDansLePayload = "";
      function siToutEstBon()
      {
        global $bdd;
        global  $errorPassword;
        global $identifiantDeLutilisateurDansLePayload;

        if (!empty($_POST['motdepasse']) && preg_match('/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/', $_POST['mail'])) {
          $mail = $_POST['mail'];
          $motdepasse = $_POST['motdepasse'];
          $requete_verification_des_informations_dans_la_bdd = $bdd->query(" SELECT * FROM users WHERE mail = '$mail'  ");

          while ($data = $requete_verification_des_informations_dans_la_bdd->fetch()) {


            $payload = [

              'iat' => time(),
              'uid' => $data['id'],
              'exp' => time() + 86400,
              'iss' => 'localhost'
            ];
            $identifiantDeLutilisateurDansLePayload = $payload['uid'];
            if (password_verify($motdepasse, $data['motdepasse'])) {
              $secret = "Lg6192*2ew2O!HH4ESK&qiQKhFG&V";
              global $token;
              $token = Token::customPayload($payload, $secret);
              setcookie('token', $token, time() + 86400);
              setcookie('userid', $identifiantDeLutilisateurDansLePayload, time() + 86400);
              header('location:personnages.php');
              echo $token;
            } else
              $errorPassword = "Le mot de passe est incorrect !";
          }
        }

        // echo $identifiantDeLutilisateurDansLePayload;
      }

      siToutEstBon();



      //! END OF ISSET CONDITON
    }



    ?>
    <h1 class="h1">Connexion</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
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
    body {
      background-image: url(https://wallup.net/wp-content/uploads/2016/05/14/66459-Shingeki_no_Kyojin.jpg);
      background-size: cover;
    }
  </style>
</body>

</html>