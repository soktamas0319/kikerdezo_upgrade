<?php
//$kimenet= "Itt egy lista lesz<br>";

include 'beallitas.php';

$ossz = mysqli_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
mysqli_select_db(DB_NEV, $ossz);
$sql = "SELECT * FROM temak Order By Tema_nev";
$eredmeny = mysqli_query($sql, $ossz) or die(mysqli_error());

?>
<html>

<head>
  <title>Új oldal</title>
  <META charset="UTF-8">
</head>

<body bgcolor="white">

  <br><br><br>
  <h3>Válassza ki azt a témát, amelynek eredményeit látni szeretné</h3>
  <br>
  <form method="post" action="eredmeny_lekerdezes_keret.php">
    Téma:
    <select name="Tema">
      <?php
      while ($ujTomb = mysqli_fetch_array($eredmeny)) {
        $Tema_nev = $ujTomb['Tema_nev'];
        print "\t<option>$Tema_nev\n";
      }
      ?>
    </select>
    <br><br>
    Az első <select name="db">
      <option>10
      <option>20
      <option>50
      <option>75
      <option>100
      <option>150
      <option>200
      <option>300
      <option>400
      <option>500
    </select> listázása
    <br><br>

    <table border="0">
      <tr>
        <td>
          <input type="radio" name="rend_tipus" value="eredmeny" checked> eredmény szerint,
        </td>
      </tr>
      <tr>
        <td><input type="radio" name="rend_tipus" value="datum"> dátum szerint, </td>
      </tr>
      <tr>
        <td><input type="radio" name="sorrend" value="desc" checked> csökkenő sorrendben.</td>
      </tr>
      <tr>
        <td><input type="radio" name="sorrend" value="asc"> növekvő sorrendben.</td>
      </tr>
    </table>
    <br>

    <input type="submit" value="Listázás!">
  </form>

</body>

</html>