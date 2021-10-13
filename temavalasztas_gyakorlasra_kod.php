<?php 
//include 'beallitas.php'; 
include 'alap_fuggvenyek.php'; //ebben van az sql_lekerdezes() függvény
$eredmeny = sql_lekerdezes("SELECT * FROM temak WHERE latszik=1 ORDER BY Tema_nev", "Hiba a téma kiválasztásánál.");
?>

<center>
	<form method="post" action="gyakorlas_keret.php">
		<b>Válassz egy témát: </b> 
		<select name="Tema">
			<?php
				while ($ujTomb = mysqli_fetch_array($eredmeny, MYSQLI_ASSOC)) {
					$Tema_nev=$ujTomb['Tema_nev'];
					print "\t<option>$Tema_nev\n";
				}
			?>
		</select>
			<input type="submit" value="A kérdéssor betöltése!">
		
	</form>		
</center>

