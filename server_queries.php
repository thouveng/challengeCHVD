<?php

function ajout_volrando($dbh, $info)
{
    //foreach ($info as $cle => $valeur) {
    //    echo 'INFO VOL: ', $cle , ' = ', $valeur , '<br />';
    //}
    $current_mid = $info["mid"];
    $current_sid = $info["sid"];
    $current_pid = $info["pid"];

    // Si mid == 0 on doit creer une entree pour le nouveau
    // massif et recuperer son nouvel mid
    if ($current_mid == 0) {

        // On verifie si le massif est deja present
        $found = false;

        $qry_select = $dbh->prepare('SELECT * FROM massifs WHERE m_nom = ?');
        $qry_select->execute(array($info["nm"]));
        $res = $qry_select->fetchAll();
        if (count($res) != 0) {
            // c'est pas un nouveau massif
            $found = true;
            $current_mid = $res[0]["m_id"];
        }

        if ($found == false) {
            // on peut l'inserer
            $qry_insert = $dbh->prepare('INSERT INTO massifs (m_nom) VALUES (?)');
            $qry_insert->execute(array($info["nm"]));

            // et on recupere son nouvel mid
            $qry_select->execute(array($info["nm"]));
            $res = $qry_select->fetchAll();
            if (count($res) != 0) {
                $found = true;
                $current_mid = $res[0]["m_id"];
            }
        }

        if (!$found) {
            echo "<p> An error occured when inserting the new massif </p>";
            return false;
        }
    }

    // A ce point, $current_mid est OK

    // On fait le meme genre de verification pour le sommet
    if ($current_sid == 0) {

        $found = false;
        $qry_select = $dbh->prepare('SELECT * FROM sommets WHERE s_nom = ?');
        $qry_select->execute(array($info["ns"]));
        $res = $qry_select->fetchAll();
        if (count($res) != 0) {
            // c'est pas un nouveau sommet
            $found = true;
            $current_sid = $res[0]["s_id"];
        }

        if ($found == false) {
            // on peut l'inserer
            $qry_insert = $dbh->prepare('INSERT INTO sommets 
                (s_nom, s_mid, s_alti, s_pts, s_annee, s_comment)
                VALUES (?,?,?,?,?,?);');
            $qry_insert->execute(array($info["ns"], $current_mid, $info["alti"],
                                       $info["pts"], "2012", $info["cs"]));

            // et on recupere son nouvel sid
            $qry_select->execute(array($info["ns"]));
            $res = $qry_select->fetchAll();
            if (count($res) != 0) {
                $found = true;
                $current_sid = $res[0]["s_id"];
            }
        }

        if (!$found) {
            echo "<p> Erreur lors de l'insertion du nouveau sommet </p>";
            return false;
        }
    }

    // A ce point, $current_sid est OK

    // On verifie le pilote
    if ($current_pid == 0) {

        $found = false;
        $qry_select = $dbh->prepare('SELECT * FROM pilotes WHERE p_pseudo = ?');
        $qry_select->execute(array($info["np"]));
        $res = $qry_select->fetchAll();
        if (count($res) != 0) {
            // c'est pas un nouveau pilote
            $found = true;
            $current_pid = $res[0]["p_id"];
        }

        if ($found == false) {
            // on peut l'inserer
            $qry_insert = $dbh->prepare('INSERT INTO pilotes (p_pseudo) VALUES (?)');
            $qry_insert->execute(array($info["np"]));

            // et on recupere son nouveau pid
            $qry_select->execute(array($info["np"]));
            $res = $qry_select->fetchAll();
            if (count($res) != 0) {
                $found = true;
                $current_pid = $res[0]["p_id"];
            }
        }

        if (!$found) {
            echo "<p> Erreur d'inserion du nouveau pilote </p>";
            return false;
        }
    }

    // A ce point $current_pid est OK

    // On ajoute le vol
    $qry_insert = $dbh->prepare('INSERT INTO volrandos
        (v_sid, v_pid, v_date, v_bi, v_but, v_co2, v_comment)
        VALUES (?,?,?,?,?,?,?);');
    $qry_insert->execute(array($current_sid, $current_pid, $info["date"],
                               $info["bi"], 0, $info["md"], $info["cv"]));

    return true;
}

function select_massifs($dbh)
{
    $res = $dbh->query('SELECT * FROM massifs ORDER BY m_nom');

    echo '<label for="choix_massif_id"> Choix du massif </label>';
    echo '<select id="choix_massif_id" onChange="gmd_sommets()">';
    echo '<option value="0"> selectionner un massif </option>';
    foreach ($res as $massif) {
        echo '<option value="', $massif["m_id"], '">', $massif["m_nom"], '</option>';
    }
    echo '</select>';
}

function select_sommets($dbh, $mid)
{
    $qry = $dbh->prepare('SELECT * FROM sommets WHERE s_mid = ? ORDER BY s_nom');
    $qry->execute(array($mid));
    $res = $qry->fetchAll();

    echo '<label for="choix_sommet_id"> Choix du sommet </label>';
    echo '<select id="choix_sommet_id" onChange="sommet_selected()">';
    echo '<option value="0"> Choisir un sommet </option>';
    foreach ($res as $sommet) {
        echo '<option value="', $sommet["s_id"], '">', $sommet["s_nom"], '</option>';
    }
    echo '</select>';
}

function select_pilotes($dbh)
{
    $res = $dbh->query('SELECT * FROM pilotes ORDER BY p_pseudo');

    echo '<label for="choix_pilote_id"> Choix du pilote </label>';
    echo '<select id="choix_pilote_id" onChange="pilote_selected()">';
    echo '<option value="0"> selectionner un pilote </option>';
    foreach ($res as $pilote) {
        echo '<option value="', $pilote["p_id"], '">', $pilote["p_pseudo"], '</option>';
    }
    echo '</select>';
}

function pilotes_to_html($dbh)
{
    $result = $dbh->query('SELECT * FROM pilotes ORDER BY p_pseudo');
    $tab = $result->fetchAll();

    echo '<table>';
    echo '<tr>';
    echo '<th> Pseudo </th>';
    echo '</tr>';

    foreach ($tab as $pilote) {
        echo '<tr>';
        echo '<td>', $pilote["p_pseudo"], '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

function massifs_to_html($dbh)
{
    $result = $dbh->query('SELECT * FROM massifs ORDER BY m_nom');
    $tab = $result->fetchAll();

    echo '<table>';
    echo '<tr>';
    echo '<th> Massif ID </th>';
    echo '<th> Nom du massif </th>';
    echo '</tr>';

    foreach ($tab as $massif) {
        echo '<tr>';
        echo '<td>', $massif["m_id"], '</td>';
        echo '<td>', $massif["m_nom"], '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

function sommets_to_html($dbh)
{
    echo '<table>';
    echo '<tr>';
    echo '<th> Nom </th>';
    echo '<th> Massif </th>';
    echo '<th> Altitude </th>';
    echo '<th> Points </th>';
    echo '<th> Année </th>';
    echo '<th> Commentaire </th>';
    echo '</tr>';

    $result = $dbh->query('SELECT * FROM sommets
                           INNER JOIN massifs ON s_mid = m_id
                           ORDER BY s_nom');
    $tab = $result->fetchAll();

    foreach ($tab as $sommet) {
        echo '<tr>';
        echo '<td>', $sommet["s_nom"], '</td>';
        echo '<td>', $sommet["m_nom"], '</td>';
        echo '<td>', $sommet["s_alti"], '</td>';
        echo '<td>', $sommet["s_pts"], '</td>';
        echo '<td>', $sommet["s_annee"], '</td>';
        echo '<td>', $sommet["s_comment"], '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

function volrandos_to_html($dbh)
{
    $result = $dbh->query('SELECT * FROM volrandos
                           INNER JOIN pilotes ON v_pid = p_id
                           INNER JOIN sommets ON v_sid = s_id
                           INNER JOIN massifs ON m_id = s_mid
                           ORDER BY v_id DESC');
    $tab = $result->fetchAll();

    echo '<table>';
    echo '<tr>';
    echo '<th> Vol # </th>';
    echo '<th> Massif </th>';
    echo '<th> Sommet </th>';
    echo '<th> Altitude </th>';
    echo '<th> Pilote </th>';
    echo '<th> Date </th>';
    echo '<th> Biplace </th>';
    //echo '<th> Carbone </th>';
    echo '<th> Commentaire </th>';
    echo '</tr>';

    foreach ($tab as $volrando) {
            echo '<tr>';
            echo '<td>', $volrando["v_id"], '</td>';
            echo '<td>', $volrando["m_nom"], '</td>';
            echo '<td>', $volrando["s_nom"], '</td>';
            echo '<td>', $volrando["s_alti"], '</td>';
            echo '<td>', $volrando["p_pseudo"], '</td>';
            echo '<td>', $volrando["v_date"], '</td>';
            echo '<td>', $volrando["v_bi"], '</td>';
            //echo '<td>', $volrando["v_co2"]     , '</td>';
            echo '<td>', $volrando["v_comment"] , '</td>';
            echo '</tr>';
    }
    echo '</table>';

    /*
    echo "DEBUG ON <br />";
    foreach ($tab as $volrando) {
        var_dump($volrando);
        echo "<br />";
        echo "<br />";
    }
     */
}

try {
    $dbh = new PDO('sqlite:challengeCHVD.sqlite3');

    $val    =  $_GET['param'];

    // Info pour l'ajout du vol
    if (0 == strcmp($val, "ajout_volrando")) {
        $info = array (
            "mid"  => $_GET['mid'],
            "nm"   => $_GET['nm'],
            "sid"  => $_GET['sid'],
            "ns"   => $_GET['ns'],
            "alti" => $_GET['alti'],
            "pts"  => $_GET['pts'],
            "cs"   => $_GET['cs'],
            "pid"  => $_GET['pid'],
            "np"   => $_GET['np'],
            "date" => $_GET['date'],
            "bi"   => $_GET['bi'],
            "md"   => $_GET['md'],
            "cv"   => $_GET['cv'] );
        ajout_volrando($dbh, $info);
    }
    elseif (0 == strcmp($val, "select_pilotes")) {
        select_pilotes($dbh);
    }
    elseif (0 == strcmp($val, "select_massifs")) {
        select_massifs($dbh);
    }
    elseif (0 == strcmp($val, "select_sommets")) {
        select_sommets($dbh, $_GET['mid']);
    }
    elseif (0 == strcmp($val, "pilotes")) {
        pilotes_to_html($dbh);
    }
    elseif (0 == strcmp($val, "massifs")) {
        massifs_to_html($dbh);
    }
    elseif (0 == strcmp($val, "sommets")) {
       sommets_to_html($dbh);
    }
    elseif (0 == strcmp($val, "volrandos")) {
        volrandos_to_html($dbh);
    }
    else {
        echo '<p class="invalide"> LE SERVEUR NE PEUT REPONDRE A VOTRE DEMANDE </p>';
    }

    $dbh = NULL;
}
catch (PDOException $err) {
    echo "Catch exception from create_database.php \n";
    echo "   ==> Message:", $err->getMessage(), "\n";
}

?>
