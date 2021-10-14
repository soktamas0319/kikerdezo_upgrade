<?php
function  createConfirmationmbox() {
    echo '<script type="text/javascript"> ';
    echo 'var inputname = prompt("Please enter your name", "");';
    echo 'alert(inputname);';
    echo '</script>';
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>JavaScript Prompt Box by PHP</title>
    <?php
    createConfirmationmbox();
    ?>
</head>

<body>
</body>

</html>