<html>

<head>
  <title>Kérdések összeállítása</title>
</head>

<body>
  <br>
  <h2>
    <?php print $Temanev; ?>
  </h2>
  <b>
    <form action="<?php print "$celoldal" ?>" method="POST">

      <?php // Ha nincs vége a kérdések szerkesztésének, akkor felépítjük a táblázatot, ha a $celoldal a kezdo_oldal, 
      // akkor vége van a szerkesztésnek és továbblépünk egy gombnyomással a kezdőoldalra 
      if (!$celoldal == "user_ell_keret.php") { ?>

        <font color="<?php print $szink ?>">Kérdés (<?php print $KerdesN . "/" . $max ?>):</font>
        <input type="text" name="Kerdes_box" size="100" value="<?php print $k ?>">

        <br><br>Kérem írja be a kérdéseket, majd jelölje be a helyes választ!<br><br>

        <input type="radio" name="Helyes" value="1" <?php if ($helyes == 1) {
                                                      print "checked";
                                                    } ?>>
        <font color="<?php print $szinv1 ?>">Válasz 1:
        </font><input type="text" name="Valasz1_box" size="100" value="<?php print $v1 ?>">
        <br><br>

        <input type="radio" name="Helyes" value="2" <?php if ($helyes == 2) {
                                                      print "checked";
                                                    } ?>>
        <font color="<?php print $szinv2 ?>">Válasz 2:
        </font><input type="text" name="Valasz2_box" size="100" value="<?php print $v2 ?>">
        <br><br>

        <input type="radio" name="Helyes" value="3" <?php if ($helyes == 3) {
                                                      print "checked";
                                                    } ?>>
        Válasz 3: <input type="text" name="Valasz3_box" size="100" value="<?php print $v3 ?>">


        <input type="hidden" name="Tema_neve_mezo" value="<?php print $Temanev ?>">
        <input type="hidden" name="Kerdes_szama_mezo" value="<?php print $KerdesN ?>">
        <input type="hidden" name="Tema_azonosito" value="<?php print $IdTema ?>">
        <input type="hidden" name="Kerdesek_szama_mezo" value="<?php print $max ?>">
        <input type="hidden" name="felh_nev_mezo" value="<?php print $tulajdonos ?>">
        <input type="hidden" name="tablanev_mezo" value="<?php print $id_tablanev ?>">
        <input type="hidden" name="felh_nev_mezo" value="<?php print $felh_nev ?>">
        <input type="hidden" name="jelszo" value="<?php print $jelszo ?>">

        <br>
        <table border="0" cellpadding="10" colspec="c200" units="pixel">
          <tr>
            <td align="center">Elejére</td>
            <td align="center">Vissza</td>
            <td align="center">Tovább</td>
            <td align="center">Végére</td>
            <td align="center">Kész</td>
            <td align="center">Aktuális kérdés törlése</td>
          </tr>
          <tr>
            <td align="center"><input type="radio" name="mozgas" value="Elejére" <?php if ($mozgas == "Elejére") {
                                                                                    print "checked";
                                                                                  } ?>></td>
            <td align="center"><input type="radio" name="mozgas" value="Vissza" <?php if ($mozgas == "Vissza") {
                                                                                  print "checked";
                                                                                } ?>></td>
            <td align="center"><input type="radio" name="mozgas" value="Tovább" <?php if ($mozgas == "Tovább") {
                                                                                  print "checked";
                                                                                } ?>></td>
            <td align="center"><input type="radio" name="mozgas" value="Végére" <?php if ($mozgas == "Végére") {
                                                                                  print "checked";
                                                                                } ?>></td>
            <td align="center"><input type="radio" name="mozgas" value="Kész" <?php if ($mozgas == "Kész") {
                                                                                print "checked";
                                                                              } ?>></td>
            <td align="center"><input type="radio" name="mozgas" value="Kérdés_törlése" <?php if ($mozgas == "Kérdés_törlése") {
                                                                                          print "checked";
                                                                                        } ?>></td>
          </tr>
        </table>
      <?php
        //Itt a táblázatépítés vége
      } else { ?>
        <input type="hidden" name="felh_nev_mezo" value="<?php print $felh_nev ?>">
        <input type="hidden" name="jelszo" value="<?php print $jelszo ?>">

        <br><br><br>
        <h3>A kérdéssor sikeresen bekerült az adatbázisba!</h3>
        <br><br>
      <?php
      }
      ?>

      <?php  //kitesszük a megfelelő gombot
      if ($celoldal == "user_ell_keret.php") {
        $ertek = "Tovább!";
      } else {
        $ertek = "Mehet!";
      }
      ?>

      <input type="submit" value="<?php print $ertek ?>" name="Gomb">

    </form>
  </b>
</body>

</html>