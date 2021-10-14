<?php
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>
	<title>Gyakorlás</title>
	<?php include 'meta_adatok.php' ?>
</head>

<body leftmargin="0" topmargin="0" bgcolor="#757575">
	<table align="center" width="768" border="0" height="100%" cellpadding="0" cellspacing="0">
		<?php include "fejlec.php" ?>
		<?php include "menu.php"  ?>
		<tr>
			<td valign="top" background="images/bg_content.gif">
				<center>
					<div align="left" id="imTitle" style="width:730">Gyakorlás</div>
					<div style="height:0px; width:730px; position: relative">
						<?php include 'helyes_valaszt_megmutat_kod.php' ?>
					</div>
				</center>
			</td>
		</tr>
		<?php include "lablec_gyak.php" ?>

	</table>
</body>

</html>