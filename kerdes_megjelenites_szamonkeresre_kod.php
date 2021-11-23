<?php
// session_start();

include 'beallitas.php';

function tema_id_meghat($temanev) {
  global $idtema;
  $ossz = mysqli_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  mysqli_select_db(DB_NEV, $ossz);
  $id_sql = "select idTema from temak where (Tema_nev='$temanev')";
  $eredmeny = mysqli_query($id_sql, $ossz) or die("Hiba!!! az idtema lekérdezésben: " . mysqli_error());
  $tomb = mysqli_fetch_array($eredmeny);
  return $tomb[idTema];
}


function kerdest_beolvas($idtema, $kerdesn, $id_tablanev) {
  global $kerdes, $valasz1, $valasz2, $valasz3, $helyes, $k_sorszamok, $kerdes_azon;
  $ossz = mysqli_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  mysqli_select_db(DB_NEV, $ossz);
  $sql_sorsz = "select kerdes_n from $id_tablanev where n=$kerdesn ";  //a soron következő kérdést kiválasztjuk
  $eredmeny = mysqli_query($sql_sorsz, $ossz) or die("Hiba a kérdés kiválasztásánál az ideiglenes táblázatban " . mysqli_error());
  $mezo = mysqli_fetch_array($eredmeny);
  $kerdes_n = $mezo[kerdes_n];                                            //egy véletlen sorszámot kapunk
  $kerd_sql = "select * from kerd_val where (idTema=$idtema) and (KerdesN=$kerdes_n)";
  print "<br>$n";
  $result = mysqli_query($kerd_sql, $ossz) or die("Hiba a kérdés lekérdezésében: " . mysqli_error());
  $eredmeny = mysqli_fetch_array($result);
  $kerdes = $eredmeny[kerdes];
  $valasz1 = $eredmeny[valasz1];
  $valasz2 = $eredmeny[valasz2];
  $valasz3 = $eredmeny[valasz3];
  $helyes = $eredmeny[helyes];
  //$kerdes_azon=$kerdes_n;    //megkapjuk a kérdés azonosító számát (a rossz válaszokhoz kell)      
}

function max_kerdes($idtema) {
  $ossz = mysqli_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  mysqli_select_db(DB_NEV, $ossz);
  $kerd_sql = "select * from kerd_val where (idTema=$idtema)";
  $result = mysqli_query($kerd_sql, $ossz) or die("Hiba a max_kérdés megállapításában: " . mysqli_error());
  $max = mysqli_num_rows($result);
  return $max;
}

function vege($kerdesn, $max) {
  return  $kerdesn >= $max;
}


function veletlen_sorsz_tablat_letrehoz($max, $id_tablanev) {
  $ossz = mysqli_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  mysqli_select_db(DB_NEV, $ossz);

  $sql = "create table $id_tablanev (n integer, kerdes_n integer, helyes_e integer)";
  $result = mysqli_query($sql, $ossz) or die("Hiba a sorszámok tábla létrehozásában: " . mysqli_error());

  $k_sorszamok = range(1, $max);      //véletlen sorrend
  srand((float)microtime() * 100000);
  shuffle($k_sorszamok);
  $i = 1;
  while (list(, $k_sorszam) = each($k_sorszamok)) {        //feltöltjük a kapott értékeket
    $sql = "insert into $id_tablanev values ($i , $k_sorszam, 0)";   //0 nem helyes, 1 helyes
    $result = mysqli_query($sql, $ossz) or die("Hiba a sorszámok tábla feltöltésében: " . mysqli_error());
    $i++;
  }
}


function jo_valaszt_megjegyez($kerdesn, $id_tablanev) {
  $ossz = mysqli_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  mysqli_select_db(DB_NEV, $ossz);
  $kerdesn--;
  $sql = "update $id_tablanev set helyes_e=1 where n='$kerdesn'";
  $result = mysqli_query($sql, $ossz) or die("Hiba a helyes válasz sorszámának megjegyzésében: " . mysqli_error());
}


function van_tabla($id_tablanev) {
  $ossz = mysqli_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  $db = mysqli_select_db(DB_NEV, $ossz);
  $eredmeny = mysqli_list_tables(DB_NEV);
  $i = 0;
  $vanilyen = false;
  while ($i < mysqli_num_rows($eredmeny)) {
    $tb_nevek[$i] = mysqli_tablename($eredmeny, $i);
    if (strstr($tb_nevek[$i], $id_tablanev)) {
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
  $ossz = mysqli_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  $db = mysqli_select_db(DB_NEV, $ossz);
  $eredmeny = mysqli_list_tables(DB_NEV);
  $sql_torles = "drop table $id_tablanev";
  $torles_eredmeny = mysqli_query($sql_torles, $ossz) or die("Hiba az ideiglenes tábla törlésekor " . mysqli_error());
}




//ITT KEZDŐDIK A FŐPROGRAM

$temanev = $_POST[Tema];
$idtema = $_POST[tema_azon];
$kerdesn = $_POST[kerdes_szam];
$max = $_POST[kerdesek_szama];
$jo_valaszdb = $_POST[jo_valasz_szamlalo];
$id_tablanev = $_POST[tablanev_mezo];

if (!isset($idtema)) {               //ha még nincs téma azonosító beállítva, lekérdezzük
  $idtema = tema_id_meghat($temanev); //kezdeti állapot
  $max = max_kerdes($idtema);          //ennyi az összes kérdés
  $jo_valaszdb = 0;
  $_POST[helyes] = 1;
  $id_tablanev = "felhasznalo_" . session_id();
  if (van_tabla($id_tablanev)) {                            //Ha van ideiglenes tábla
    tablat_torol($id_tablanev);                 //először kitöröljük, hogy ne legyen gond
    veletlen_sorsz_tablat_letrehoz($max, $id_tablanev);
  } else {
    veletlen_sorsz_tablat_letrehoz($max, $id_tablanev);
  }
}

if (!isset($kerdesn)) {              //ideiglenes véletlenszám legyen
  $kerdesn = 1;
} else {
  $kerdesn++;
}

kerdest_beolvas($idtema, $kerdesn, $id_tablanev);
if (isset($_POST[helyes])) {                  //Ha már elküldtünk egy adatot (Ha még nem küldtünk semmit akkor is egyformák)
  if ($_POST[valasztas] == $_POST[helyes]) {    //helyes válasz esetén
    $jo_valaszdb++;
    jo_valaszt_megjegyez($kerdesn, $id_tablanev);
  } //else{array_push($rosszvalaszok, kerdes_azon)}  //rossz válasz esetén berakjuk a rosszválaszokhoz a kérdés azonosító számát
}

if (!vege($kerdesn, $max)) {
  $celoldal = $_Server[PHP_SELF];
} else {
  $celoldal = "nevet_beker_keret.php";
  //$celoldal="eredmeny_keret.php";
}



//print "Súgás: A helyes válasz: ". $helyes."<br>";
//print "Helyes válaszok száma: ".$jo_valaszdb."<br>";
//print "Cél: "."$celoldal";
?>



<html>

<head>
  <title>Kérdések</title>
</head>

<body>
  <h3>
    <?php print $temanev ?>
  </h3>

  <!--A form kezdete-->
<form action="<?php print "$celoldal?" ?>" method="POST"> 
   <input type="hidden"  name="Tema" value="<?php print "$temanev" ?>">
   <input type="hidden"  name="tema_azon" value="<?php print $idtema ?>">
   <input type="hidden"  name="kerdes_szam" value="<?php print $kerdesn ?>">
   <input type="hidden"  name="kerdesek_szama" value="<?php print $max ?>">
   <input type="hidden"  name="jo_valasz_szamlalo" value="<?php print $jo_valaszdb ?>">
   <input type="hidden"  name="helyes" value="<?php print $helyes ?>">
   <input type="hidden"  name="tablanev_mezo" value="<?php print $id_tablanev ?>">
   
  <h4>Kérdés:<?php print "$kerdesn" . "/" . "$max"; ?></h4>   

<table border="0" width="90%" height="100" align="center" cellspacing="3" cellpadding="10">
<tr>  
  <td bgcolor="#D9DAD3"  width="" height="50" colspan="2"> 
        <font face="Arial" size="">
          <b>
            <?php print "$kerdes" ?>
          </b>
        </font>
  </td>
</tr>

<tr>
  <td bgcolor="#FBEF99" width="1%">
    <input type="radio" name="valasztas" value="1">
  </td>
  <td bgcolor="#FBEF99">
    <?php print "$valasz1" ?>
  </td>
</tr>

<tr>
  <td bgcolor="#FBEF99" width="1%">
    <input type="radio" name="valasztas" value="2">
  </td>
  <td bgcolor="#FBEF99">
    <?php print "$valasz2" ?>
  </td>
</tr>

<?php
if (!($valasz3 == '')) {    //Ha nem üres string a 3. válasz, kiíratjuk a 3. sort is
?>
  <tr>
    <td bgcolor="#FBEF99" width="1%">
      <input type="radio" name="valasztas" value="3">
    </td>
    <td bgcolor="#FBEF99">
      <?php print "$valasz3"
      ?>
    </td>
  </tr> 
   <?php } ?>
   


</table>

<br>
<center><input type="image" src="tovabb_gomb5.gif" name="elkuld" > </center>

</form> 

</body>
</html>