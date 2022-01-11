<?php
//lisame oma kasutajanimi, parooli, ja ab_nimi
$yhendus=new mysqli("localhost", "maks20", "123456", "maks20");
//session_start();
$error= $_SESSION["error"] ?? "";

function puhastaAndmed($data){
    //trim()- eemaldab tühikud
    $data=trim($data);
    //htmlspecialchars - ignoreerib <käsk>
    $data=htmlspecialchars($data);
    //stripslashes - eemaldab \
    $data=stripslashes($data);
    return $data;
}
if(isset($_REQUEST["knimi"])&& isset($_REQUEST["psw"])) {

    $login = puhastaAndmed($_REQUEST["knimi"]);
    $pass = puhastaAndmed($_REQUEST["psw"]);
    $sool = 'vagavagatekst';
    $krypt = crypt($pass, $sool);

//kasutajanimi kontroll
    $kask = $yhendus->prepare("SELECT id, unimi, psw FROM uuedkasutajad
WHERE unimi=?");
    $kask->bind_param("s", $login);
    $kask->bind_result($id, $kasutajanimi, $parool);
    $kask->execute();
    if ($kask->fetch()) {
        $_SESSION["error"] = "Kasutaja on juba olemas";
        header("Location: $_SERVER[PHP_SELF]");
        $yhendus->close();
        exit();

    } else {
        $_SESSION["error"] = " ";
    }


// uue kasutaja lisamine andmetabeli sisse
    $kask = $yhendus->prepare("
INSERT INTO uuedkasutajad(unimi, psw, isadmin) 
VALUES (?,?,?)");
    $kask->bind_param("ssi", $login, $krypt, $_REQUEST["admin"]);
    $kask->execute();
    $_SESSION['unimi'] = $login;
    $_SESSION['admin'] = true;
    header("location: kaubahaldus.php");
    $yhendus->close();
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registreerimisvorm</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
<h1>Uue kasutaja registreerimine</h1>
<br>
<a href="#modal-opened" class="link-1" id="modal-closed">Register</a>

<div class="modal-container" id="modal-opened">
    <div class="modal">

        <div class="modal__details">
            <h1 class="modal__title">Uue kasutaja registreerimine</h1>
        </div>

        <p class="modal__text"> <form action="registr.php" method="post">
            <label for="knimi">Kasutajanimi</label>
            <input type="text" placeholder="Sisesta kasutajanimi"
                   name="knimi" id="knimi" required>
            <br>
            <label for="psw">Parool</label>
            <input type="password" placeholder="Sisesta parool"
                   name="psw" id="psw" required>
            <br>
            <label for="admin">Kas teha admin?</label>
            <input type="checkbox" name="admin" id="admin" value="1">
            <br>
            <input type="submit" value="Loo kasutaja">
            <button type="button"
                    onclick="window.location.href='kaubahaldus.php'"
                    class="cancelbtn">Lobu</button>

            <strong> <?=$error ?></strong>

        </form> </p>
        <a href="#modal-closed" class="link-2"></a>

    </div>
</div>



</body>
</html>
