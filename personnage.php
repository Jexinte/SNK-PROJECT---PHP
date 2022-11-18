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
    while($personnage = $req->fetch()):
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
    <?php endwhile; ?>
    <?php include './inc/footer_personnages.php' ?>
  </div>

  <style>

.container-personnage{
display: flex;
justify-content: center;
width: 900px;
margin: 0 auto 3em;
gap: 2em;
transform: skew(-5deg);
box-shadow: 5px 5px 10px rgba(0,0,0,.2);

}
.left
{
width: 50%;
}
.right{
width: 50%;
text-align: center;
padding: 1em;
}

.right p {
  white-space: break-spaces;
line-height: 1.8em;
}

.left img {
width: 100%;
height: 100%;
}


header{
margin-bottom: 8em;
}
    </style>
</body>
</html>