<?php 

$temanev=$_POST[Tema];
$idtema=$_POST[tema_azon];
$kerdesn=$_POST[kerdes_szam];
$max=$_POST[kerdesek_szama];
$jo_valaszdb=$_POST[jo_valasz_szamlalo];
$id_tablanev=$_POST[tablanev_mezo];
$valasztas=$_POST[valasztas];
$helyes=$_POST[helyes];


$celoldal="eredmeny_keret.php";
?>


<html>
 <head>
 <title>Kérdések</title>
</head>

 <body>
<h2>
   <?php print $temanev ?>
</h2>

<!--A form kezdete>
<form action="<?php print "$celoldal" ?>" method="POST"> 
   <input type="hidden"  name="Tema" value="<?php print "$temanev" ?>">
   <input type="hidden"  name="tema_azon" value="<?php print $idtema ?>">
   <input type="hidden"  name="kerdes_szam" value="<?php print $kerdesn ?>">
   <input type="hidden"  name="kerdesek_szama" value="<?php print $max ?>">
   <input type="hidden"  name="jo_valasz_szamlalo" value="<?php print $jo_valaszdb ?>">
   <input type="hidden"  name="helyes" value="<?php print $helyes ?>">
   <input type="hidden"  name="tablanev_mezo" value="<?php print $id_tablanev ?>">
   
   <input type="text"  name="nev_mezo" size="100">
   
   
   <input type="image" src="tovabb_gomb5.gif" name="elkuld" >
</form>   
