<header>
  <nav>
    <a href="personnages.php"><img src="./img/logo.png" alt="Logo de Snk" height="60" width="100"></a>
    <a href="#" id="eldiens">Eldiens</a>
    <a href="#" id="mahr">Mahr</a>
    <a href="#" id="titans">Titans</a>
  </nav>
</header>

<style>
@import url('https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap');

* {
  margin:0;
  padding:0;
  box-sizing: border-box;
}

body{
  font-family:'Fredoka One';
}
  a{
    color:black;
    font-weight:600;
    text-decoration: none;
    font-size: 1.5em;
  }

  a:visited{
    color:black;
  }

  header{
    box-shadow: 5px 5px 10px rgba(0,0,0,.2);
    width: 100%;
    padding-top:1em;
  }
  nav {
    display: flex;
    justify-content: space-around;
  }

  nav #eldiens,#mahr,#titans {
    position: relative;
    top:.7em;
  }
</style>

