<?php
require_once('connect.php');
if(isset($_POST['enregistrer'])){
    $nom_activite = htmlspecialchars(addslashes($_POST['nom_activite']));
    $date = $_POST['date_activite'];
    $nbre_hommes = $_POST['nbre_hommes'];
    $nbre_femmes = $_POST['nbre_femmes'];
    $effectif = $nbre_hommes + $nbre_femmes;
    $mc = htmlspecialchars(addslashes($_POST['mc']));
    $predicateur = htmlspecialchars(addslashes($_POST['predicateur']));
    $theme = htmlspecialchars(addslashes($_POST['theme']));
    $textes = htmlspecialchars(addslashes($_POST['texte_biblique']));
    
    $requete = "INSERT INTO detail_activite(nom_activite, date, nbre_hommes, nbre_femmes, effectif, mc, orateur, texte, theme) VALUES('$nom_activite', '$date', '$nbre_hommes', '$nbre_femmes', '$effectif', '$mc', '$predicateur', '$textes', '$theme')";
    $resultat = $bdd->exec($requete);

    if($resultat){
        echo '<script> alert("Data Saved"); </script>';
        header('Location:detail_activite.php?nom_activite='.$nom_activite);
    }else{
        echo '<script> alert("Data Not Saved"); </script>';
    }
} 

?>
