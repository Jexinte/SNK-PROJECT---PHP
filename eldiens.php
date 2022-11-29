<?php include './config/connexion_bdd2.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=
  , initial-scale=1.0">
  <title>Eldiens</title>
</head>

<body>
  <div class="container">
    <!-- HEADER -->
    <?php include './inc/menu_personnages.php'; ?>

    <div class="container-box">

      <?php
      $origine = $_GET['origine'];
      $recuperation_image = $bdd2->query("SELECT * FROM personnages2 WHERE origine ='$origine' ORDER BY nom");

      while ($data = $recuperation_image->fetch()) :
      ?>

        <a href="personnage.php?id=<?php echo $data['id'] ?>" class="box">

          <?php if ($data['origine'] === "Eldiens") : ?>
            <span class="nom" id="eldiens-nom"><?php echo $data['nom']; ?></span>
          <?php endif; ?>
          <img class="imagecarte" src="<?php echo $data['imageCarte'] ?>" alt="" width="100" height="100">

        </a>

      <?php endwhile; ?>
    </div>

    <?php include './inc/footer_personnages.php' ?>

  </div>
  <?php


  ?>

  <style>
    #eldiens-nom {
      color: #8c6f1d;
    }

    .container-box {
      display: flex;
      flex-wrap: wrap;
      gap: 2em;
      justify-content: center;
      transition: all ease-in 700ms;
      margin-bottom: 4em;
    }

    header {
      margin-bottom: 8em;
    }

    .box {
      box-shadow: 5px 5px 10px rgba(0, 0, 0, .5);
      height: 300px;
      width: 300px;
      border-radius: 20px;
      position: relative;
    }

    .nom {
      position: absolute;
      font-size: .7em;
      color: black;
      background: white;
      width: 100%;
      text-align: center;
      bottom: 0;
      border-bottom-right-radius: 20px;
      border-bottom-left-radius: 20px;
      padding: .5em;
    }

    .box img {
      display: block;
      width: 100%;
      height: 100%;
      border-radius: 20px;
    }
  </style>
</body>

</html>