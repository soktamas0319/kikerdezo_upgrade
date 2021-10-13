<?php 
include 'beallitas.php';
//létrehozunk egy ideiglenes táblát
/*  $ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  mysql_select_db(DB_NEV, $ossz);
  $id_tablanev=$_POST[felh_nev_mezo]."_user_table";
  $sql="create table $id_tablanev (
                                           KerdesN integer, 
                                           Kerdes varchar(250),
                                           valasz1 varchar(250), 
                                           valasz2 varchar(250), 
                                           valasz3 varchar(250), 
                                           helyes integer)";
                                           
  $eredmeny=mysql_query($sql, $ossz) or die ("Hiba az ideiglenes tábla létrehozásában:" .mysql_error());*/
?>



<html>
<head>
<title>Új kérdéssor hozzáadása</title>
</head>
<body>

<br><br><br>
<center>
<form action="tesztepito_keret.php" method="POST">
Téma neve: <input type="text" name="Tema_neve_box" size="70">
<br><br><br>
<input type="hidden" name="felh_nev_mezo" value="<?php print $_POST[felh_nev_mezo] ?>">
<input type="hidden" name="jelszo" value="<?php print $_POST[jelszo] ?>">

<input type="submit" value="Téma hozzáadása az adatbázishoz!">
</form>
</center>

</body>
</html>
