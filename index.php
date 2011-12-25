<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Challenge VolRando 2012 CHVD</title>
    <link rel="stylesheet" href="challenge.css">
    <script src="fonctions_challenge.js" type="text/javascript"> </script>
</head>
<body>

<!--
La page est d�coup�e de la fa�on suivante:

           ----  ---------------------------------
 BANDEAU ->     |             div_haut
           ---- |---------------------------------
                |                |
 CONTENU ->     |   div_gauche   |   div_droite
                |                |
           ---- |---------------------------------
 PIED PAGE ->   |             div_bas
           ----  ---------------------------------

Dans la partie gauche on retrouvera la zone de saisies des vols ainsi
qu'une zone console pour les messages d'erreurs.

Dans la partie droite on affichera les r�sutlats
-->

<!-- *******************
            BANDEAU
     *******************-->
<div id="div_haut">

  <h2> Bienvenue au challenge VolRando 2012 du CHVD </h2>

  <p> le principe du challenge est de varier les vols randos en essayant de
  d&eacute;couvrir de nouveaux points d'envol. Les vols des autres peuvent
  donner des id&eacute;es pour plus tard et renseigner sur la faisabilit&eacute;.

  Toutes les d&eacute;clarations sont faites sur l'honneur. Si vous ne vous souvenez plus
  des r&egrave;gles concernant la validit&eacute; d'un vol et son nombre de points,
  vous pouvez consultez le
  <a href="http://volbivouac.free.fr/challengeCHVD/reglement.html">r&egrave;glement 2012</a>.

  Si certains s'int�ressent � l'aspect technique de la mise en oeuvre de la saisie des
  volrandos, il y a le classique
  <a href="http://volbivouac.free.fr/challengeCHVD/README.html">README</a> qui
  fait le point sur l'�tat du d�veloppement.

  <p> Bonnes randos et bons vols </p>

</div> <!-- Fin de div_haut -->


<!-- *******************
            CONTENU
     *******************-->
<div id="div_gauche">

  <h2> Zone de saisie des VolRandos </h2>

    <p> Si votre massif ou votre sommet n'apparaissent pas dans la liste, n'h�sitez
    pas � le faire d�couvrir en utilisant la zone pr�vue � cette effet. Vous aurez un
    point de bonus. Si vous n'�tes pas encore enregistr�, votre nom n'apparar�tera pas dans
    la liste. Choisissez "Nouveau Pilote" et entrer un pseudonyme.
    </p>

    <form action="#" id="formulaire_volrando">
    <fieldset>
    <legend> D�claration du volrando </legend>

      <p id="zone_saisie_massif"> <!-- Choix du massif -->  </p>
      <p id="zone_saisie_sommet"> </p>
      <p id="zone_saisie_nouveau_massif"> </p>
      <p id="zone_saisie_nouveau_sommet"> </p>
      <p id="zone_saisie_sommet_altitude"> </p>
      <p id="zone_saisie_sommet_points"> </p>
      <p id="zone_saisie_sommet_commentaire"> </p>
      <p id="zone_saisie_pilote"> </p>
      <p id="zone_saisie_nouveau_pilote">  </p>

      <!-- Toute l'initialisation des zones massif, sommet et pilote sont
           dynamique et geree par un script -->
      <script type="text/javascript"> 
      <!--
        init_zone_saisie()
      -->
      </script>

      <p><label for="choix_date_id">Date (Jour/Mois)</label>
      <script type="text/javascript">
      <!--
        today=new Date();
        txt  = '<input type="text"';
        txt += ' value ="' + today.getDate() + '/' + (today.getMonth() + 1) + '"';
        txt += ' id="choix_date_id" name="choix_date_name">';
        txt += '<br>';
        document.write(txt);
      -->
      </script>
      </p>

      <!-- Bonus biplace -->
      <p>
      <input type="checkbox" id="choix_biplace_id">
      Vol effectu� en biplace (1 point de bonus)
      </p>

      <!-- Info sur mobilite douce -->
      <p>
      <input type="checkbox" id="choix_mobilitedouce_id">
      Vol effectu� en mobilit� dite douce (c'est � titre indicatif)
      </p>

      <p>
      Un commentaire sur le vol <br>
      (sur une seule ligne mais qui peut �tre longue) <br>
      <input type="text" size=40 id="choix_commentaire_id">
      </p>

      <p class="submit">
        <input type="submit" value="Soumettre votre volrando" onclick="check_volrando()">
      </p>

    </fieldset>
    </form>

</div> <!-- Fin de div_gauche -->

<div id="div_droite">

  <h2> Affichage des r�sultats </h2>

  <form action="">
    <select id="choix_affichage_id" onChange="affichage_table()">
      <option value="afficher_volrandos"> Afficher la liste des vols randos </option>
      <option value="afficher_massifs"> Afficher la liste des massifs </option>
      <option value="afficher_sommets"> Afficher la liste des sommets </option>
      <option value="afficher_pilotes"> Afficher la liste des pilotes </option>
      <option value="afficher_classement" disabled="disabled"> 
        Afficher le classement
      </option>
    </select>
  </form>
        
  <form action="">
    <select id="choix_tri_id" onChange="choix_tri()">
      <option value="tri_massif" disabled="disabled"> tri par massif </option>
      <option value="tri_pilote" disabled="disabled"> tri par pilote </option>
      <option value="tri_sommet" disabled="disabled"> tri par sommet </option>
    </select>
  </form>

  <p id="zone_resultats">
  <!--
      Au chargement de la page, on recupere les tables dans la base de donnees
      et on affiche directement le tableau des resultats. Ensuite les mises a jour
      viendront ecraser cette zone en utilisant l'id "resultat".
  -->
  <script type="text/javascript">
    <!--
    ask_to_server('volrandos', 'zone_resultats');
    -->
  </script>

  </p>

</div> <!-- Fin de div_droite -->

<!-- *******************
          PIEDPAGE
     *******************-->
<div id="div_bas">
  <p> Pas de copyright, pompez, diffusez, faite bien ce que vous voulez avec le code qui est
  <a href="https://github.com/gthouvenin/challengeCHVD">dispo sur github</a>.
  </p>
</div> <!-- Fin de div_bas -->

</body>
</html>
