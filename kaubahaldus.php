<?php
require("abifunktsioonid.php");
if(isSet($_REQUEST["grupilisamine"])){
    lisaGrupp($_REQUEST["uuemaakonnakeskus"]);
    header("Location: kaubahaldus.php");
    exit();
}
if(isSet($_REQUEST["kaubalisamine"])){
    $date = date("Y-m-d", strtotime($_REQUEST["kuupaev"]));
    lisaKaup($_REQUEST["temperatuur"], $_REQUEST["makonna_id"], $date);
    header("Location: kaubahaldus.php");
    exit();
}
if(isSet($_REQUEST["kustutusid"])){
    kustutaKaup($_REQUEST["kustutusid"]);
}
if(isSet($_REQUEST["muutmine"])){
    muudaKaup($_REQUEST["muudetudid"], $_REQUEST["temperatuur"],
        $_REQUEST["makonna_id"], $_REQUEST["kuupaev"]);
}
$kaubad=kysiKaupadeAndmed();
?>
<!DOCTYPE html>
<head>
    <title>Temperatuur halduse leht</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div id="menuArea">
    <a class="bn40" href="registr.php#modal-opened">Loo uus kasutaja</a>
    <?php
    if(isset($_SESSION['unimi'])){
        ?>
        <h1>Tere, <?="$_SESSION[unimi]"?></h1>
        <a href="logout.php">Logi välja</a>
        <?php
    } else {
        ?>
        <a href="login.php#modal-opened">Logi sisse</a>
        <?php
    }

    ?>
</div>

<div class="column">
    <form action="kaubahaldus.php">

        <h2>Temperatuur lisamine</h2>
        <dl>
            <dt>Temperatuur:</dt>
            <dd><input type="number" name="temperatuur" /></dd>
            <dt>Linn:</dt>
            <dd><?php
                echo looRippMenyy("SELECT id, maakonnakeskus FROM maakondade",
                    "makonna_id");
                ?>
            </dd>
            <dt>Kuupaev:</dt>
            <dd><input type="date" name="kuupaev" /></dd>
        </dl>

        <input type="submit" name="kaubalisamine" value="Lisa Temperatuur" />
    </form>
</div>

<div class="column">
    <h2>Grupi lisamine</h2>
    <input type="text" name="uuemaakonnakeskus" />
    <input type="submit" name="grupilisamine" value="Lisa Linn/Maakond" />
</div>
<div class="column2">
    <form action="kaubahaldus.php">


        <h2>Kaupade loetelu</h2>
        <table>
            <tr>
                <th></th>
                <th>Temperatuur</th>
                <th>Linn</th>
                <th>Kuupaev</th>
            </tr>
            <?php foreach($kaubad as $temp): ?>
                <tr>
                    <?php if(isSet($_REQUEST["muutmisid"]) &&
                        intval($_REQUEST["muutmisid"])==$temp->id): ?>
                        <td>
                            <input type="submit" name="muutmine" value="Muuda" />
                            <input type="submit" name="katkestus" value="Katkesta" />
                            <input type="hidden" name="muudetudid" value="<?=$temp->id ?>" />
                        </td>
                        <td><input type="text" name="temperatuur" value="<?=$temp->temperatuur ?>" /></td>
                        <td><?php
                            echo looRippMenyy("SELECT id, maakonnakeskus FROM maakondade",
                                "makonna_id", $temp->id);
                            ?></td>
                        <td><input type="text" name="kuupaev" value="<?=$temp->kuupaev ?>" /></td>
                    <?php else: ?>
                        <td>
                            <?php
                            if(isset($_SESSION['unimi'])){
                                ?>
                            <a href="kaubahaldus.php?kustutusid=<?=$temp->id ?>"
                               onclick="return confirm('Kas ikka soovid kustutada?')">x</a>
                            <a href="kaubahaldus.php?muutmisid=<?=$temp->id ?>">m</a>
                            <?php } ?>
                        </td>
                        <td><?=$temp->temperatuur ?></td>
                        <td><?=$temp->maakonnakeskus ?></td>
                        <td><?=$temp->kuupaev ?></td>
                    <?php endif ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </form>
</div>
</body>
</html>
