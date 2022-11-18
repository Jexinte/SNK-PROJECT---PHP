<?php include './config/connexion_bdd.php' ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription - SNK</title>
  <link rel="stylesheet" href="./css/main.css">
  
  <!-- MEDIA QUERIES -->
  <link rel="stylesheet" href="./css/form_mediaqueries.css">
</head>
<body>
  <div class="container">

    <?php include './inc/header_inscription.php'; ?>
    <?php

    $errorMail = $errorPassword = "";


    //* Si le formulai est envoyé  , on effectue des vérifications
    if(isset($_POST['submit'])) 
    {

      //* Vérifie la pertinence du champ de l'adresse mail
          function siLeChampEmailEstVideEtQueLeFormatDeLadresseMailEstLeBon()
          {
              global $mail_de_lutilisateur_qui_sinscrit;

              //* Si le champ est vide alors
              if(empty($_POST['mail'])){
              global  $errorMail; 
               $errorMail = "Ce champ ne peut-être vide !";
              }

              //* Si l'adresse ne respecte pas le format demandé
              elseif(!empty($_POST['mail']) && !preg_match('/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/',$_POST['mail'])){
                global $errorMail;
                $errorMail = "Le format de l'adresse email doit être le suivant : example@gmail.com !";
              }
        
              //* Si le format est bon on retire tous les caractères inutiles à l'adresse mail
              else 
                $mail_de_lutilisateur_qui_sinscrit = filter_input(INPUT_POST,'mail',FILTER_SANITIZE_EMAIL);
            
          }

          siLeChampEmailEstVideEtQueLeFormatDeLadresseMailEstLeBon();



          function siLeChampMotDePasseNestPasVide()
          {
              global $hash;
              global $errorPassword;
           

              if(empty($_POST['motdepasse']))
                $errorPassword = "Ce champ ne peut-être vide ! <br>";
              

              elseif(!empty($_POST['motdepasse']) && !preg_match('/^[A-Z]{1}[a-z]{8}[0-9]{2}$/',$_POST['motdepasse'])){
                $errorPassword = "Veuillez saisir un mot de passe : <br> - Commençant par une lettre majuscule <br> - Suivi de 8 caractères et se terminant par 2 chiffres <br>";
              }
              else {
                $password = $_POST['motdepasse'];
                $options = [
                  'cost' => 12 //? Cout Algorithme requis pour le hachage du mot de passe
                ];
                $hash = password_hash($password, PASSWORD_BCRYPT,$options);
              }
          }
          
          siLeChampMotDePasseNestPasVide();
          
     
          function siTousLesChampsSontBienRemplis()
          {

              global $hash;
              global $bdd;
              global $mail_de_lutilisateur_qui_sinscrit;
              global $errorMail;
              global $mail_venant_de_la_bdd ;

              $mail_venant_de_la_bdd= "";

              $requete_pour_faire_correspondre_le_nom_de_lutilsateur_avec_celui_qui_tente_de_sinscrire = $bdd->query("SELECT * FROM users WHERE mail = '$mail_de_lutilisateur_qui_sinscrit'");

              while($data = $requete_pour_faire_correspondre_le_nom_de_lutilsateur_avec_celui_qui_tente_de_sinscrire->fetch())
              {
                $mail_venant_de_la_bdd = $data['mail'];
                if($data['mail'] === $mail_de_lutilisateur_qui_sinscrit){
                  $errorMail = "Cette adresse est déjà utilisé , veuillez en utiliser une autre !";
                }
              }

        
              if($mail_de_lutilisateur_qui_sinscrit !== $mail_venant_de_la_bdd && $hash && preg_match('/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/',$mail_de_lutilisateur_qui_sinscrit)){
                $bdd->query("INSERT INTO users (mail,motdepasse) VALUES('$mail_de_lutilisateur_qui_sinscrit','$hash')");
  
                  header('location:connexion.php');
              }
          }

          siTousLesChampsSontBienRemplis();

    
    }
    ?>

    <h1 class="h1">Inscription</h1>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
    <span class="info-form">Tous les champs munis d'un * sont obligatoires</span>
      <label for="mail">
        Adresse mail * <br>
        <input type="mail" name="mail" id="mail" placeholder="john@gmail.com" > <br>
        <span id="errorMail"><?php echo $errorMail; ?></span>
      </label>

      <label for="motdepasse">
        Mot de passe * <br>
        <input type="password" name="motdepasse" id="motdepasse"> <br>
        <span id="errorPassword"><?php echo $errorPassword; ?></span>
        <label for="afficheMotDePasse">
          Afficher le mot de passe 
        </label>
        <input id="afficheMotDePasse" type="checkbox" onclick="passwordCheck()">
      </label>

      <input type="submit" name="submit" id="submit" value="Envoyer">
    </form>


    <?php include './inc/footer.php' ?>
  </div>
  <script>
    const passwordBox = document.getElementById('motdepasse')

  const passwordCheck = () => {
    if(passwordBox.type === "password")
        passwordBox.type = "text";

    else 
        passwordBox.type = "password"
  }

  
  </script>
</body>
</html>