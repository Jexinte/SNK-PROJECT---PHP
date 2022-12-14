<?php include './config/connexion_bdd2.php' ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Personnage</title>
</head>

<body>
  <div class="container">
    <?php include './inc/menu_personnages.php' ?>
    <?php
    $id_personnage = $_GET['id'];
    $req = $bdd2->query("SELECT * FROM personnages2 WHERE id = '$id_personnage'");
    while ($personnage = $req->fetch()) :
    ?>
      <div class="container-personnage">

        <div class="left">
          <img src="<?php echo $personnage['imageHistoire'] ?>" alt="">
        </div>

        <div class="right">
          <p><?php echo $personnage['nom'] ?></p> <br>
          <p><?php echo $personnage['histoire'] ?></p>
        </div>
      </div>
      <?php


      ?>
      <?php
      if (intval($personnage['id_user']) === intval($_COOKIE['userid'])) :

      ?>
        <div class="rights">
          <a href="modifier_un_personnage.php?id=<?php echo $id_personnage ?>" id="modifier">Modifier</a>

          <form action="personnage.php?id=<?php echo $id_personnage ?>" method="post" enctype="multipart/form-data">
            <button type="submit" name="submit" id="supprimer">Supprimer</button>
            </label>
            </label>
          </form>
        </div>


      <?php
      endif;
      ?>
    <?php endwhile; ?>
    <?php

    if (isset($_POST['submit'])) :
      $req = "DELETE FROM personnages2 WHERE id = '$id_personnage'";
      $bdd2->exec($req);
      header('location:personnages.php');
    endif;


    ?>
    <?php include './inc/footer_personnages.php' ?>
  </div>

  <style>
    .container-personnage {
      display: flex;
      justify-content: center;
      width: 975px;
      margin: 0 auto 3em;
      gap: 2em;

      box-shadow: 5px 5px 10px rgba(0, 0, 0, .2);

    }

    .left {
      width: 50%;
      transition: all ease-in 500ms;
    }

    .right {
      width: 50%;
      text-align: center;
      padding: 1em;
      transition: all ease-in 500ms;
    }

    .right p {
      white-space: break-spaces;
      line-height: 1.8em;
    }

    .left img {
      width: 100%;
      height: 100%;
    }


    header {
      margin-bottom: 8em;
    }


    .rights {
      display: flex;
      justify-content: center;
      gap: 4.5em;
      transition: all ease-in 700ms;
    }

    #modifier {
      background: lightgreen;
      color: white;
      padding: .5em;
      border-radius: 10px;
    }

    #supprimer {
      background: crimson;
      color: white;
      padding: .5em;
      border-radius: 10px;
      font-size: 1.5em;
      border: none;
      font-family: 'Fredoka One';
      cursor: pointer;
    }

    @media screen and (max-width:992px) {
      .container-personnage {
        box-shadow: 0 0 0 0;
        width: 100%;
        flex-direction: column;
        align-items: center;
      }

      .left {
        box-shadow: 5px 5px 10px rgba(0, 0, 0, .5);
        margin-inline: 1em;
        transition: all ease-in 700ms;
        width: 95%;
      }

      .right {
        text-align: center;
        padding: 1em;
        box-shadow: 5px 5px 10px rgba(0, 0, 0, .2);
        margin-inline: 1em;
        transition: all ease-in 700ms;
        width: 95%;
      }

      .right p {
        line-height: 2.5em;
      }

      .rights {
        display: flex;
        flex-direction: column;
        gap: 2.5em;
        align-items: center;
        transition: all ease-in 700ms;
      }
    }
  </style>
</body>

</html>