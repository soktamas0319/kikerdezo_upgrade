<?php
  $nev=$_POST[nev];
  $jelszo=$_POST[jelszo];
  $jelszo_conf=$_POST[jelszo_conf];  
  $rendes_nev=$_POST[rendes_nev];
  $email=$_POST[email];
?>


<form enctype="multipart/form-data" method="post" action="regisztracio_ell_keret.php" >
		<table cellspacing="5" cellpadding="5" border="0">
		<tr>
			<td valign="top">
				<strong>Az igénylő neve:</strong>
			</td>
			<td valign="top">
				<input type="text" name="rendes_nev"  size="40" value="<?php print "$rendes_nev" ?>" />				
			</td>
		</tr>
		<tr>
			<td valign="top">
				<strong>Az igénylő e-mail címe:</strong><br>
                                        (Ide kap értesítést,<br> ha elkészült a regisztráció)        
			</td>
			<td valign="top">
				<input type="text" name="email"  size="40" value="<?php print "$email" ?>" />				
			</td>
		</tr>    
		<tr>
			<td valign="top">
				<strong>Felhasználónév:</strong><br>
                                (ékezetek és szóköz nélkül)
			</td>
			<td valign="top">
				<input type="text" name="nev"  size="40" value="<?php print "$nev" ?>" />				
			</td>
		</tr>
		<tr>
			<td valign="top">
				<strong>Jelszó:</strong><br>
                                (ékezetek és szóköz nélkül)
			</td>
			<td valign="top">
				<input type="password" name="jelszo"  size="40" value="" />				
			</td>
		</tr>
		<tr>
			<td valign="top">
				<strong>Jelszó megerősítése:</strong>
			</td>
			<td valign="top">
				<input type="password" name="jelszo2"  size="40" value="" />				
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" value=" Regisztráció " />
			</td>
		</tr>
	</table>
</form>
<br><br>
</center>

