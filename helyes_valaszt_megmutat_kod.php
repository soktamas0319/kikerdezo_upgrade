<?php
//session_start();

//include 'beallitas.php';
include 'kerdes_megjelenites_gyakorlasra_fuggvenyei.php';

define("SARGA", "#FBEF99");
define("SZURKE", "#D9DAD3");
define("PIROS", "#BB0000");
define("ZOLD", "#58A060");

$i = 1;                   //beállítjuk az alap háttérszínt
while ($i <= 3) {
	$hatterszin[$i] = SARGA;
	$i++;
};


//átvesszük az adatokat az előző oldalról
$idtema = $_SESSION["idtema"];
$kerdesn = $_SESSION["kerdesn"];
$id_tablanev = $_SESSION["id_tablanev"];
$valasztas = $_SESSION["valasztas"];

if ($valasztas == $_SESSION["helyes"]) {    //helyes válasz esetén
	$_SESSION["jo_valaszdb"]++;
	$hatterszin[$valasztas] = ZOLD;
} else {
	$hatterszin[$valasztas] = PIROS;
	$hatterszin[$_SESSION["helyes"]] = ZOLD;
}

if (!vege($_SESSION["kerdesn"], $_SESSION["kerdesDb"])) {
	$celoldal = "gyakorlas_keret.php";
} else {
	$celoldal = "eredmeny_keret.php";
}

/*
print "Súgás: A helyes válasz: ". $_SESSION["helyes"]."<br>";
print "Súgás: A válasz: ". $valasztas."<br>";
print "Helyes válaszok száma: ".$_SESSION['jo_valaszdb']."<br>";
print "Cél: "."$celoldal";
print $hatterszin[1];
print "küldemény:" . $valasztas;
*/
?>



<h2>
	<?php print $_SESSION["temanev"] ?>
</h2>

<!--A form kezdete-->
<form action="<?php print $celoldal ?>" method="POST">

	<h3>Kérdés:<?php print $_SESSION["kerdesn"] . "/" . $_SESSION["kerdesDb"]; ?></h3>

	<table border="0" width="90%" height="100" align="center" cellspacing="3" cellpadding="10">
		<tr>
			<td bgcolor="#D9DAD3" width="" height="50" colspan="2">
				<font face="Arial" size="3">
					<b>
						<?php print $_SESSION["kerdes"] ?>
					</b>
				</font>
			</td>
		</tr>

		<tr>
			<td bgcolor="<?php print $hatterszin[1] ?>" width="1%">
				<input type="radio" name="valasztas" value="1">
			</td>
			<td bgcolor="<?php print $hatterszin[1] ?>">
				<?php print $_SESSION["valasz1"]; ?>
			</td>
		</tr>

		<tr>
			<td bgcolor="<?php print $hatterszin[2] ?>" width="1%">
				<input type="radio" name="valasztas" value="2">
			</td>
			<td bgcolor="<?php print $hatterszin[2] ?>">
				<?php print $_SESSION["valasz2"]; ?>
			</td>
		</tr>

		<?php
		if (!($_SESSION["valasz3"] == '')) {    //Ha nem üres string a 3. válasz, kiíratjuk a 3. sort is
		?>
			<tr>
				<td bgcolor="<?php print $hatterszin[3] ?>" width="1%">
					<input type="radio" name="valasztas" value="3">
				</td>
				<td bgcolor="<?php print $hatterszin[3] ?>">
					<?php print $_SESSION["valasz3"];  ?>
				</td>
			</tr>
		<?php } ?>
	</table>

	<br>
	<center><input type="image" src="tovabb_gomb5.gif" name="elkuld"> </center>
</form>