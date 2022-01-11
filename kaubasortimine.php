<?php
//require - kasutatakse abifunktsioonid.php sisu
require("abifunktsioonid.php");
$kaubad=kysiKaupadeAndmed();
//sortimine
if(isSet($_REQUEST["sort"])){
    $kaubad=kysiKaupadeAndmed($_REQUEST["sort"]);
} else {
    $kaubad=kysiKaupadeAndmed();
}

?>
<!DOCTYPE html>
<head>
    <title>Kaupade leht</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
<body>
<h1>Tabelite Kaubad+kaubagrupide sisu</h1>
<table>
    <tr>
        <th><a href="kaubasortimine.php?sort=temperatuur">temperatuur</a></th>
        <th><a href="kaubasortimine.php?sort=maakonnakeskus">Maakonnakeskus</a></th>
        <th><a href="kaubasortimine.php?sort=kuupaev">Kuupaev</a></th>
    </tr>

    <!-- tagastab massivis andmed -->
    <?php foreach($kaubad as $temp): ?>
        <tr>
            <td><?=$temp->temperatuur ?></td>
            <td><?=$temp->maakonnakeskus ?></td>
            <td><?=$temp->kuupaev ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
