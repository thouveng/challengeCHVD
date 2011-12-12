/*
 * On recupere nos quatre tables sous forme de chaine JSON
 * pour pouvoir les manipuler cote client.
 * On recupere ces valeurs des le chargement de la page. On
 * les mettra a jour si on modifie une table.
 */

/*
 * 
 */
function requete_ajax(callback)
{
    var xhr;

    if (window.XMLHttpRequest) {
        // Code for IE7+, firefox, Chrome, Opera, Safari
        xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
        // code for IE6, IE5
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        alert("Votre navigateur ne supporte pas XMLHTTPRequest");
        return;
    }

    xhr.onreadystatechange = function()
    {
        if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0))
        {
            // On recupere les donnees sous forme de texte brut
            callback(xhr.responseText);
        }
    }

    // true => mode de transfert asynchrone
    //xhr.open("GET","results.json",true);
    xhr.open("GET","get_tables.php",true);
    xhr.send();
}

/*
 * Callback utilise pour traiter les donnees retournees lors du GET
 * de la requete AJAX.
 */
function get_tables(oData)
{
    try {
        var tables_json = eval('(' + oData + ')');
        display_tables(tables_json);
    } catch (e) {
        alert('FATAL: Not a json string');
    }
}

function display_tables(tables)
{
    var affichage, i, s;
    var tableSommets = tables["sommets"];
    var tabsize = tableSommets.length;

    affichage = "<table>";
    affichage += "<tr>";
    affichage += "<th>Nom du sommet</th>";
    affichage += "<th>Massif ID</th>";
    affichage += "<th>Altitude</th>";
    affichage += "<th>Points</th>";
    affichage += "<th>Annee</th>";
    affichage += "<th>Commentaire</th>";
    affichage += "</tr>";
    for (i = 0; i < tabsize; i++) {
        s = tableSommets[i];
        affichage += "<tr>";
        affichage += "<td>" + s["nom"] + "</td>";
        affichage += "<td>" + s["mid"] + "</td>";
        affichage += "<td>" + s["altitude"] + "</td>";
        affichage += "<td>" + s["points"] + "</td>";
        affichage += "<td>" + s["annee"] + "</td>";
        affichage += "<td>" + s["commentaire"] + "</td>";
        affichage += "</tr>";
    }
    affichage += "</table>";

    document.getElementById('resultats').innerHTML= affichage;
}

/*
 * Cette fonction permet de valider un minimum les donnees passees
 * en parametre du vol rando avant d'envoyer la requete d'ajout
 * dans la base de donnee au serveur
 */
function check_volrando()
{
    var x = document.getElementById("saisieVolrando"),
        monTexte = "  -={ VERIFICATION DU VOL }=- <br />";

    // Il y a 7 elements:
    //  sommet, pilote, date, biplace, co2, commentaire et
    //  soumettre_le_volrando
    if (x.length != 7) {
        alert('Fatal Error');
        return false;
    }

    var sommet   = x.elements["sommet"],
        pilote   = x.elements["pilote"],
        datevol  = x.elements["datevol"],
        biplace  = x.elements["biplace"],
        mobdouce = x.elements["mobilitedouce"],
        comment  = x.elements["commentaire"];

    monTexte = monTexte  +
        sommet.name   + ' : ' + sommet.selectedIndex + '<br />' +
        pilote.name   + ' : ' + pilote.selectedIndex + '<br />' +
        datevol.name  + ' : ' + datevol.value        + '<br />' +
        biplace.name  + ' : ' + biplace.checked      + '<br />' +
        mobdouce.name + ' : ' + mobdouce.checked     + '<br />' +
        comment.name  + ' : ' + comment.value        + '<br />';

    //alert(monTexte);
    document.getElementById('status').innerHTML= monTexte;
}
