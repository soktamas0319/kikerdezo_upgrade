<?php
include "beallitas.php"; 
//print  "Itt egy lista lesz<br>";
  $temanev= $_POST[Tema];
  $db=$_POST[db];

  $ossz=mysql_connect(DB_HOSZT, DB_FELH_NEV, DB_JELSZO);
  mysql_select_db(DB_NEV, $ossz);	

//Téma azonosító megállapítása
  $sql_lek1="select idTema from temak where Tema_nev='$temanev'";
  $eredmeny1=mysql_query($sql_lek1, $ossz) or die("Hiba az eredmenyek tábla lekérdezésében ".mysql_error());
  $mezo=mysql_fetch_array($eredmeny1);
  $idTema=$mezo['idTema'];

//A napló lekérdezése
  $sql_lek2="select * from naplo where idTema='$idTema' order by $_POST[rend_tipus] $_POST[sorrend], nev";
  $eredmeny2=mysql_query($sql_lek2, $ossz) or die("Hiba a napló lekérdezésében ".mysql_error());

  print "<h3><b>$temanev </b><br>számonkérésének eredményei</h3>";
  print "<h3>1 - $db-ig</h3>";
//elkezdjük felépíteni a táblázatot
  $hatterszin="brown";
  $tablazat_kod="";
  $tablazat_kod.="<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" width=\"90%\" >";
  $tablazat_kod.="<tr bgcolor=\"$hatterszin \" align=\"center\" >";
  $tablazat_kod.=   "<td><b> Hely </b></td><td><b> Név </b></td><td><b> Dátum </b></td><td><b> Eredmény </b></td>";
  $tablazat_kod.= "</tr>";

  $i=0;
  while ($mezo2=mysql_fetch_array($eredmeny2) and ($i<$db)){
    if (($i % 2) ==0){
      $hatterszin="LightGrey";
    }else{
      $hatterszin="White";
    }
    $i++;
    $nev=$mezo2['nev'];
    $eredmeny=$mezo2['eredmeny'];
    $datum_time_stamp=$mezo2['datum'];
    $datum=date("Y M d D   H:i:s", $datum_time_stamp);   
//    print "$i, $nev, $datum, $eredmeny<br>";
    $tablazat_kod.="<tr bgcolor=$hatterszin  align=center>";
    $tablazat_kod.=   "<td > $i </td><td> $nev </td><td> $datum </td><td> $eredmeny% </td>";
    $tablazat_kod.= "</tr>";
  }
  $tablazat_kod.="</table>";



print "$tablazat_kod<br><br>";
print "<a href=\"eredmeny_keret.php\">Vissza</a><br><br>"

?>
