<?php
include 'beallitas.php';

function van_tabla($id_tablanev) {
  $ossz = mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  $db = mysql_select_db(DB_NEV, $ossz);
  $eredmeny = mysql_list_tables(DB_NEV);
  $i = 0;
  $vanilyen = false;
  while ($i < mysql_num_rows($eredmeny)) {
    $tb_nevek[$i] = mysql_tablename($eredmeny, $i);
    if ($tb_nevek[$i] == $id_tablanev) {
      $vanilyen = true;
    }
    $i++;
  }
  if ($vanilyen) {
    return true;
  } else {
    return false;
  }
}

function tablat_torol($id_tablanev) {
  $ossz = mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  $db = mysql_select_db(DB_NEV, $ossz);
  $sql_torles = "drop table $id_tablanev";
  $torles_eredmeny = mysql_query($sql_torles, $ossz) or die("Hiba az ideiglenes tábla törlésekor " . mysql_error());
}


function naploba_ir($idTema, $nev, $szazalek, $datum) {
  $ossz = mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  $db = mysql_select_db(DB_NEV, $ossz);
  $sql_naploz = "insert into naplo (nev, datum, eredmeny, idTema) values ('$nev', '$datum', '$szazalek', $idTema)";
  $eredmeny_naploz = mysql_query($sql_naploz, $ossz) or die("Hiba a napló írásakor " . mysql_error());
}


function jo_valaszt_megjegyez($kerdesn, $id_tablanev) {   //ha jó volt a válasz, az ideiglenes táblába 1-est rak a megf mezőbe
  $ossz = mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  mysql_select_db(DB_NEV, $ossz);
  $sql = "update $id_tablanev set helyes_e=1 where n=$kerdesn";
  $result = mysql_query($sql, $ossz) or die("Hiba a $kerdesn helyes válasz sorszámának megjegyzésében: " . mysql_error());
}


function sorokat_keszit($kerdes, $helyes_valasz) {
  $hatterszin = '#439460';
  $kod = "<tr bgcolor=$hatterszin  align=\"left\" >";  //kérdés cella
  $kod .=   "<td><b>Kérdés: </b> $kerdes </td>";
  $kod .= "</tr>";
  $hatterszin = '#B3D3AB';                                    //jó válasz cella
  $kod .= "<tr bgcolor=$hatterszin  align=\"left\" >";
  $kod .=  "<td> <b>Helyes válasz:</b> $helyes_valasz </td>";
  $kod .= "</tr>";
  $hatterszin = '#CCCCCC';                                    //üres sor
  $kod .= "<tr bgcolor=$hatterszin  align=\"left\" >";
  $kod .=  "<td><br> </td>";
  $kod .= "</tr>";

  return $kod;
}


function hibas_valaszok($idTema, $id_tablanev) {
  $kod = "<br>";
  $kod .= "<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" width=\"90%\" >";

  $ossz = mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  mysql_select_db(DB_NEV, $ossz);
  $sql_ideiglenes = "select kerdes_n from $id_tablanev where helyes_e=0";
  $result = mysql_query($sql_ideiglenes, $ossz) or die("Hiba a hibás válaszok ID-jének lekérdezésében: " . mysql_error());
  while ($mezo = mysql_fetch_array($result)) {
    $kerdesn = $mezo['kerdes_n'];              //lekérdezzük a kerd_val adatbázist az aktuális kérdés ID-vel
    $sql_kerd_val = "select kerdes, valasz1, valasz2, valasz3, helyes from kerd_val where (idTema='$idTema') and (KerdesN='$kerdesn')";
    $result2 = mysql_query($sql_kerd_val, $ossz) or die("Hiba a hibás válaszok lekérdezésében: " . mysql_error());
    $mezo2 = mysql_fetch_array($result2);

    $kerdes = $mezo2['kerdes'];  //Beállítjuk a kiírandó változókat
    $valasz[1] = $mezo2['valasz1'];
    $valasz[2] = $mezo2['valasz2'];
    $valasz[3] = $mezo2['valasz3'];
    $helyes = $mezo2['helyes'];
    $helyes_valasz = $valasz[$helyes];
    $kod .= sorokat_keszit($kerdes, $helyes_valasz);
  }
  $kod .= "</table><br><br>";

  return $kod;
}


//
//Főprogram
$temanev = $_POST[Tema];
$max = $_POST[kerdesek_szama];
$jo_valaszdb = $_POST[jo_valasz_szamlalo];
$idTema = $_POST[tema_azon];
$datum = time();
$kerdesn = $_POST[kerdes_szam];
$id_tablanev = $_POST[tablanev_mezo];

if (isset($_POST[helyes])) {                  //Ha már elküldtünk egy adatot (Ha még nem küldtünk semmit akkor is egyformák)
  if ($_POST[valasztas] == $_POST[helyes]) {    //helyes válasz esetén
    $jo_valaszdb++;
    if (isset($_POST[szamonkeres_volt])) {          //Ha számonkérés volt, csak akkor jegyezzük meg a rossz válaszokat;
      jo_valaszt_megjegyez($kerdesn, $id_tablanev);
    }
  }
}

$id_tablanev = $_POST[tablanev_mezo];

if ($_POST[nev_mezo] == '') {
  $nev = "Felhasználó";
} else {
  $nev = $_POST[nev_mezo];
}

if (isset($temanev)) {   //ez történik a teszt kiértékelése után
  $kimenet = "<b>Téma neve:</b> $temanev <br><br>";
  $kimenet .= "<b>Kedves $nev!<b><br>";
  $kimenet .= "<b>$jo_valaszdb</b> jó válasz született <b>$max</b> válaszból<br>";
  $szazalek = round($jo_valaszdb / $max * 100, 0);
  $kimenet .= "<br>Eredmény:<br><h1> $szazalek% </h1>";
  if (isset($_POST[szamonkeres_volt])) {
    naploba_ir($idTema, $nev, $szazalek, $datum);
    $elrontott_valaszok = "<h3>Az elrontott kérdések:</h3>";
    $elrontott_valaszok .= hibas_valaszok($idTema, $id_tablanev);   //felépítjük a hibás válaszok táblázatát
  }
} else {
  include "eredmeny_lekerdezes_kezd_kod.php";
}

if (van_tabla($id_tablanev)) {
  tablat_torol($id_tablanev);
} else {
};

?>


<html>

<head>
  <title>Új oldal</title>
  <META charset="UTF-8">
</head>

<body bgcolor="white">
  <?php print "$kimenet<br><br>";
  if ($jo_valaszdb < $max) {
    print "$elrontott_valaszok";
  }
  ?>

  <a href="kezdo_oldal.php">Vissza a főoldalra</a>
  <br><br><br>

</body>

</html>