<header>
  <nav>
    <a href="personnages.php"><img src="./img/logo.png" alt="Logo de Snk" height="60" width="100"></a>
    <a href="eldiens.php?origine=Eldiens" id="eldiens">Eldiens</a>
    <a href="mahr.php?origine=Mahr" id="mahr">Mahr</a>
    <a href="titans.php?origine=Titans" id="titans">Titans</a>
    <a href="creation_personnage.php" id="creation">Crée un personnage</a>
  </nav>
</header>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap');

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Fredoka One';
  }

  a {
    color: black;
    font-weight: 600;
    text-decoration: none;
    font-size: 1.5em;
  }

  a:visited {
    color: black;
  }

  header {
    box-shadow: 5px 5px 10px rgba(0, 0, 0, .2);
    width: 100%;
    padding-top: 1em;
  }

  nav {
    display: flex;
    justify-content: space-around;
  }

  nav #eldiens,
  #mahr,
  #titans,
  #creation {
    position: relative;
    top: .7em;
  }

  #eldiens {
    color: #8c6f1d;
  }

  #mahr {
    color: #d54324;
  }

  #titans {
    color: gray;
  }

  @media screen and (max-width:992px) {

    header {
      padding-bottom: 4em;
      transition: padding-bottom ease-in 700ms;
    }

    nav {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1.5em;
      transition: all ease-in 700ms;
    }

  }
</style>