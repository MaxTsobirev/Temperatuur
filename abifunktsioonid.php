<?php
//lisame oma kasutajanimi, parooli, ja ab_nimi
$yhendus=new mysqli("localhost", "maks20", "123456", "maks20");
//sorttulp - sorteerimise verq
//otsisõna - otsingsõna
function kysiKaupadeAndmed($sorttulp="temperatuur", $otsisona=""){
    global $yhendus;
    $lubatudtulbad=array("temperatuur", "maakonnakeskus", "kuupaev");
    if(!in_array($sorttulp, $lubatudtulbad)){
        return "lubamatu tulp";
    }
    //addslashes - stripslashes - lisab langjooned - kustutab langjoone
    $otsisona=addslashes(stripslashes($otsisona));
    $kask=$yhendus->prepare("SELECT ilmatemperatuur.id, temperatuur, maakonnakeskus, kuupaev
       FROM ilmatemperatuur, maakondade
       WHERE ilmatemperatuur.makonna_id=maakondade.id
        AND (temperatuur LIKE '%$otsisona%' OR maakonnakeskus LIKE '%$otsisona%')
       ORDER BY $sorttulp");
    //echo $yhendus->error;
    $kask->bind_result($id, $temperatuur, $maakonnakeskus, $kuupaev);
    $kask->execute();
    $hoidla=array();
    while($kask->fetch()){
        $temp=new stdClass();
        $temp->id=$id;
        $temp->temperatuur=htmlspecialchars($temperatuur);
        $temp->maakonnakeskus=htmlspecialchars($maakonnakeskus);
        $temp->kuupaev=$kuupaev;
        array_push($hoidla, $temp);
    }
    return $hoidla;
}
//loorippmenyy - dropdownlist - выпадающий список -
function looRippMenyy($sqllause, $valikunimi, $valitudid=""){
    global $yhendus;
    $kask=$yhendus->prepare($sqllause);
    $kask->bind_result($id, $sisu);
    $kask->execute();
    $tulemus="<select name='$valikunimi'>";
    while($kask->fetch()){
        $lisand="";
        if($id==$valitudid){$lisand=" selected='selected'";}
        $tulemus.="<option value='$id' $lisand >$sisu</option>";
    }
    $tulemus.="</select>";
    return $tulemus;
}

//lisab grupp
function lisaGrupp($maakonnakeskus){
    global $yhendus;
    $kask=$yhendus->prepare("INSERT INTO maakondade (maakonnakeskus)
                      VALUES (?)");
    $kask->bind_param("s", $maakonnakeskus);
    $kask->execute();
}
//lisab kaup
function lisaKaup($temperatuur, $makonna_id, $kuupaev){
    global $yhendus;
    $kask=$yhendus->prepare("INSERT INTO
       ilmatemperatuur (temperatuur, makonna_id, kuupaev)
       VALUES (?, ?, ?)");
    $kask->bind_param("sis", $temperatuur, $makonna_id, $kuupaev);
    $kask->execute();
}
//kustutab kaup
function kustutaKaup($ilma_id){
    global $yhendus;
    $kask=$yhendus->prepare("DELETE FROM ilmatemperatuur WHERE id=?");
    $kask->bind_param("i", $ilma_id);
    $kask->execute();
}
//muudab kaup
function muudaKaup($ilma_id, $temperatuur, $makonna_id, $kuupaev){
    global $yhendus;
    $kask=$yhendus->prepare("UPDATE ilmatemperatuur SET temperatuur=?, makonna_id=?, kuupaev=?
                      WHERE id=?");
    $kask->bind_param("sidi", $temperatuur, $makonna_id, $kuupaev, $ilma_id);
    $kask->execute();
}






