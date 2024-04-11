<?php

session_start();

if(@$_SESSION['autoriser'] != "oui"){

   header('location:login.php'); 

   exit();

}

// Connexion à la base des données

require_once('connect.php');



// Nombre total des baptisés

$requete = "SELECT COUNT(*) as total_baptise FROM bapteme";

$reponse = $bdd->query($requete);

$resultat = $reponse->fetch();

$nb = $resultat['total_baptise']; 



// Nombre d'hommes baptisés

$requete_m = "SELECT COUNT(*) as total_baptise_m FROM bapteme WHERE sexe = 'M'";

$reponse_m = $bdd->query($requete_m);

$resultat_m = $reponse_m->fetch();

$nb_m = $resultat_m['total_baptise_m'];



// Nombre de femmes baptisées

$requete_f = "SELECT COUNT(*) as total_baptise_f  FROM bapteme WHERE sexe = 'F'";

$reponse_f = $bdd->query($requete_f);

$resultat_f = $reponse_f->fetch();

$nb_f = $resultat_f['total_baptise_f']; 



$baptemes = $bdd->query("SELECT * FROM bapteme ORDER BY id DESC");

// Pagination

if(isset($_GET['page'])){

    $page = $_GET['page'];

}else{

    $page = 1;

}



$baptise_par_page = 5;

$start_from = ($page-1)*5;



$baptises = $bdd->query("SELECT * FROM bapteme limit $start_from, $baptise_par_page");





// Barre de recherche

if(isset($_GET['s']) AND !empty($_GET['s'])){

    $recherche = htmlspecialchars($_GET['s']);

    $baptemes = $bdd->query('SELECT * FROM bapteme WHERE noms_baptise LIKE "%'.$recherche.'%" OR sexe LIKE "%'.$recherche.'%" OR lieu_date_naissance LIKE "%'.$recherche.'%" OR nom_pere LIKE "%'.$recherche.'%" or nom_mere LIKE "%'.$recherche.'%" OR collectivite_origine LIKE "%'.$recherche.'%" OR territoire_origine LIKE "%'.$recherche.'%" OR date_bapteme LIKE "%'.$recherche.'%"');

}

?>

<!DOCTYPE html>

<html>

    <head>

        <title>Ajout Baptisé</title>

        <meta charset = "utf-8"/>

        <link rel = "stylesheet" type = "text/css" href = "style.css"/>

        <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">

    </head>

    <style>

        #editmodal{

            transition:all 0.2s;

        }

        #ajouter_mariage{

            transition:all 0.2s;

        }

        #side_nav{

            min-height:100vh;

            max-width:250px;

            transition: all 0.2s;

        }

        .sidebar a:hover{

            background-color: rgb(111,111,255);

        }

        #myDropdown{

            width:230px;

            margin-left:5px;

        }

        #myDropdown li a{

            padding:5px;

        }

        #myDropdown li a:hover{

            width:200px;

            height:30px;

            background-color:rgb(111,111,255);

        }

        .sidebar a{

            margin-left:10px;

            padding:10px;

        }

        @media(max-width:767px){

            #side_nav{

                margin-left:-280px;

                position:fixed;

                min-height:100vh;

                z-index:1;

            }



            #side_nav.active{

                margin-left:0;

            }

            table{

                width:100vw;

                font-size:0.6em;

            }

            h4{

                display:none;

            }

            .total_baptise{

                width:145px;

                

            }

            .homme_baptise{

                width:145px;

                margin-left:20px;

            }

            .femme_baptise{

                width:145px;

                margin-left:20px;

                

            }

        }

        table, hr, .btn{

            margin-left:15px;

        }

    </style>

    <body> 

        <div class = "main-container d-flex">

            <div class = "sidebar bg-light" id = "side_nav">

                <div class = "header-box px-2 pt-3 pb-4 d-flex justify-content-between">

                    <img src = "images/gomaouest.jpg" width = "100px" height= '50px' style = "margin-left:30px;"/>

                    <button class = "btn close-btn" style = "text-align:right;font-size:24px;margin-top:40px;"><i class="las la-align-justify"></i></button>

                </div>

                <ul class = "list-unstyled px-2">

                  <a class="nav-link active" aria-current="page" href="dashboard.php" style = "color:black;"><i class="las la-desktop"></i>&ensp;Dashboard</a>

                  <div class="dropdown">

                    <a class="dropdown-toggle nav-link" type="button" data-bs-toggle="dropdown" aria-expanded="false" style = "color:black;">

                        <i class="las la-user-friends"></i>&ensp;Gestion membre

                    </a>

                    <ul class="dropdown-menu membre" id = "myDropdown">

                        <li><a class="dropdown-item" href="ajout_membre.php">Membre</a></li>

                        <li><a class="dropdown-item" href="ajout_mariage.php">Mariage</a></li>

                        <li><a class="dropdown-item" href="ajout_bapteme.php">Baptême</a></li>

                        <li><a class="dropdown-item" href="dedicace.php">Dédicace des enfants</a></li>

                    </ul>

                </div>

                <div class = "dropdown">

                    <a class="dropdown-toggle nav-link" type="button" data-bs-toggle="dropdown" aria-expanded="false" style = "color:black;">

                        <i class="las la-calendar-check"></i>&ensp;Activités de la semaine

                    </a>

                    <ul class="dropdown-menu" id = "myDropdown">

                        <li><a class = "dropdown-item" id = "dropdown-item" href = "ajout_intercession.php">Intercession</a></li>

                        <li><a class = "dropdown-item" href = "ajout_cultemamans.php">Culte des mamans</a></li>

                        <li><a class = "dropdown-item" href = "ajout_seminaire.php">Seminaire</a></li>

                        <li><a class = "dropdown-item" href = "ajout_etudebiblique.php">Etudes Bibliques</a></li>

                        <li><a class = "dropdown-item" href = "ajout_cultejeunes.php">Culte des jeunes</a></li>

                        <li><a class = "dropdown-item" href = "ajout_cultedominical.php">Culte dominical</a></li>

                        <li><a class = "dropdown-item" href = "ajout_semainepriere.php">Semaine de prière</a></li>

                    </ul>

                </div>

                <div class = "dropdown">

                    <a class="dropdown-toggle nav-link" type="button" data-bs-toggle="dropdown" aria-expanded="false" style = "color:black;">

                        <i class="las la-comment-dollar"></i>&ensp;Finance

                    </a>

                    <ul class="dropdown-menu" id = "myDropdown">

                        <li><a class = "dropdown-item" href = "ajout_depense.php">Dépenses</a></li>

                        <li><a class = "dropdown-item" href = "ajout_don.php">Dons</a></li>

                        <li><a class = "dropdown-item" href = "voir_souscription.php">Souscriptions</a></li>

                    </ul>

                </div>

                <a class="nav-link" href = "planning.php" style = "color:black;"><i class="las la-calendar"></i>&ensp;Planning</a>

                <div class = "dropdown">

                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style = "color:black;">

                        <i class="las la-chart-bar"></i>&ensp;Statistiques

                    </a>

                    <ul class="dropdown-menu" id = "myDropdown">

                        <li><a class = "dropdown-item" href = "charts_membres.php">Membres</a></li>

                        <li><a class = "dropdown-item" href = "charts_cultedominicaux.php">Offrandes et Effectifs</a></li>

                    </ul>

                </div> 

                <a class="nav-link" href = "deconnexion.php" style = "color:black;"><i class="las la-power-off"></i>&ensp;Déconnexion</a>

            </div>

            <div class = "content">

                <nav class="navbar navbar-expand-md bg-light">

                    <div class="container-fluid">

                        <div class = "d-flex justify-content-between d-md-none d-block">

                            <img src = "images/gomaouest.jpg" width = "100px" height= '50px' style = "margin-left:30px;"/>

                        </div>

                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

                            <span class="navbar-toggler-icon"></span>

                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">

                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                                <li class="nav-item">

                                    <h4 style = "margin-left:300px;color:black;">GOMA-OUEST SYSTEM</h4>

                                </li>

                            </ul>

                        </div>

                    </div>

                </nav>

                <h5 style = 'margin-left:15px;'>Baptême</h5>

                <main class = "container">

                    <div class = "bg-light">

                        <div class = "row">

                            <div class = "col-4 total_baptise">

                                <div class = "card bg-primary">

                                    <div class = "card-body">

                                        <h5 class = "card-title">Total Baptisés</h5>

                                        <p class = "card-text"><strong><?php echo $nb; ?></strong></p>

                                    </div>

                                </div>

                            </div>

                            <div class = "col-4 homme_baptise">

                                <div class = "card bg-success">

                                    <div class = "card-body">

                                        <h5 class = "card-title">Hommes baptisés</h5>

                                        <p class = "card-text"><strong><?php echo $nb_m ?></strong></p>

                                    </div>

                                </div>

                            </div>

                            <div class = "col-4 femme_baptise">

                                <div class = "card bg-danger">

                                    <div class = "card-body">

                                        <h5 class = "card-title">Femmes baptisées</h5>

                                        <p class = "card-text"><strong><?php echo $nb_f; ?></strong></p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <hr align = "center">

                </main>

                <!-- Modale de modification -->

                <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <div class="modal-header">

                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier</h1>

                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                            </div>

                            <div class="modal-body">

                                <form action="" method = "POST">

                                <input type="hidden" name = "update_id" id = "update_id"> 

                                <label for="date_bapteme">Date du bapteme</label>

                                <input type="date" name = "date_bapteme" id = "date_bapteme" class="form-control" placeholder="Date de l'intercession" aria-label="Last name">                                

                                <label for="prenom">Noms Baptisé</label>

                                <input type="text" name = "noms_baptise" id =  "noms_baptise" class="form-control" aria-label="noms baptise">

                                <label>Sexe :</label>

                                <input type="radio" name="sexe" id="sexe" value="M">&ensp;Masculin

                                <input type="radio" name="sexe" id="sexe" value="F">&ensp;Féminin</br>

                                <label for="lieu_naissance">Lieu et Date de naissance</label>

                                <input type="text" name = "lieu_date_naissance" id = "lieu_date_naissance" class="form-control" placeholder="" aria-label="Last name">                

                                <div class = "row">

                                    <div class = "col">

                                        <label for="nom pere">Nom du père</label>

                                        <input type="text" name = "nom_pere" id = "nom_pere" class="form-control" aria-label="collectivite">

                                    </div>

                                    <div class = "col">

                                        <label for="nom_mere">Nom de la mère</label>

                                        <input type="text" name = "nom_mere" id = "nom_mere" class = "form-control" aria-label = "territoire">

                                    </div>

                                </div></br>

                                <div class = "row">

                                    <div class = "col">

                                        <label for="collectivite d'origine">Collectivité d'origine</label>

                                        <input type="text" name = "collectivite_origine" id = "collectivite_origine" class="form-control" aria-label="collectivite">

                                    </div>

                                    <div class = "col">

                                        <label for="territoire d'origine">Territoire d'origine</label>

                                        <input type="text" name = "territoire_origine" id = "territoire_origine" class = "form-control" aria-label = "territoire">

                                    </div>

                                </div></br>

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>

                                <input type="submit" class="btn btn-success" name = "enregistrer" value = "Modifier">      

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

                <button type = "button" class = "btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouter_baptise">Ajouter baptisé</button>

                <div align = "right">

                    <a href="excel_bapteme.php">

                        <button type = "button" class = "btn btn-success" href = "excel_mariage.php">Exporter vers Excel</button>

                    </a>

                </div>

                <hr>

                <div class="modal fade" id="ajouter_baptise" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                    <div class="modal-dialog">

                        <div class="modal-content">

                            <div class="modal-header">

                                <h1 class="modal-title fs-5" id="exampleModalLabel">Ajouter Baptisé</h1>

                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                            </div>

                            <div class="modal-body">

                                <form action="bapteme.php" method = "POST">

                                <label for="date_bapteme"><strong>Date du bapteme</strong></label>

                                <input type="date" name = "date_bapteme" class="form-control" placeholder="Date de l'intercession" aria-label="Last name">

                                <div class = "row">

                                    <div class = "col">

                                        <label for="prenom"><strong>Prenom</strong></label>

                                        <input type="text" name = "noms[]" class="form-control" aria-label="prenom baptise">

                                    </div>

                                    <div class = "col">

                                        <label for="nom"><strong>Nom</strong></label>

                                        <input type="text" name = "noms[]" class="form-control" aria-label="nom baptise">

                                    </div>

                                    <div class = "col">

                                        <label for = "post_nom"><strong>Post-Nom</strong></label>

                                        <input type="text" name = "noms[]" class="form-control" aria-label="post nom baptise">

                                    </div>

                                </div>

                                <label><strong>Sexe :</strong></label>

                                <input type="radio" name="sexe" id="sexe" value="M">&ensp;Masculin

                                <input type="radio" name="sexe" id="sexe" value="F">&ensp;Féminin</br>

                                <div class = "row">

                                    <div class = "col">

                                        <label for="lieu_naissance"><strong>Lieu de naissance</strong></label>

                                        <input type="text" name = "naissance[]" class="form-control" placeholder="" aria-label="Last name">

                                    </div>

                                    <div class = "col">

                                        <label for="date_naissance"><strong>Date de naissance</strong></label>

                                        <input type="date" name = "naissance[]" class="form-control" placeholder="" aria-label="Last name">

                                    </div>

                                </div>

                                <label for=""><strong>Etat civil</strong></label>

                                <select class="form-select" aria-label="Default select example" name = "etat_civil">

                                    <option value="celibataire">Célibataire</option>

                                    <option value="marié">Marié(e)</option>

                                    <option value="veuf">Veuf(ve)</option>

                                    <option value="divorcé">Divorcé(e)</option>

                                </select>

                                <div class = "row">

                                    <div class = "col">

                                        <label for="nom pere"><strong>Nom du père</strong></label>

                                        <input type="text" name = "nom_pere" class="form-control" aria-label="collectivite">

                                    </div>

                                    <div class = "col">

                                        <label for="nom_mere"><strong>Nom de la mère</strong></label>

                                        <input type="text" name = "nom_mere" class = "form-control" aria-label = "territoire">

                                    </div>

                                </div>

                                <div class = "row">

                                    <div class = "col">

                                        <label for="collectivite d'origine"><strong>Collectivité d'origine</strong></label>

                                        <input type="text" name = "collectivite_origine" class="form-control" aria-label="collectivite">

                                    </div>

                                    <div class = "col">

                                        <label for="territoire d'origine"><strong>Territoire d'origine</strong></label>

                                        <input type="text" name = "territoire_origine" class = "form-control" aria-label = "territoire">

                                    </div>

                                </div>                                    

                            </div>

                            <div class="modal-footer">

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>

                                <input type="submit" class="btn btn-primary" name = "enregistrer" value = "Enregistrer">

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

                

                <div class = "search" align = "center">

                    <form role="search" method = "GET">

                        <input type="search" placeholder="Rechercher nom..." name = "s">

                        <button class="btn btn-outline-success" type="submit" name = "search">Search</button>

                    </form>

                </div>

        

                <table class = "table table-bordered table-striped">

                    <tbody>

                        <tr>

                            <th>Id</th>

                            <th>Noms Baptisé</th>

                            <th>Sexe</th>

                            <th>Lieu & Date Naissance</th>

                            <th>Etat Civil</th>

                            <th>Date Baptême</th>

                            <th>Plus</th>

                        </tr>

                    </tbody>

                    <tbody>

                        <?php

                        if($baptemes->rowCount() > 0){

                            while($bapteme = $baptemes->fetch()){

                        ?>

                        <tr>

                            <td><?php echo $bapteme['id']; ?></td>

                            <td><?php echo $bapteme['noms_baptise']; ?></td>

                            <td><?php echo $bapteme['sexe']; ?></td>

                            <td><?php echo $bapteme['lieu_date_naissance']; ?></td>

                            <td><?php echo $bapteme['etat_civil']; ?></td>

                            <td><?php echo date_format(date_create($bapteme['date_bapteme']), 'd/m/Y'); ?></td>

                            <td><a href = "info_baptise.php?id=<?php echo $bapteme['id']; ?>">Plus</a></td>

                        </tr>

                        <?php

                        }

                    }else{

                        ?>

                        <tr>

                            <td colspan = "8">Aucun resultat</td>

                        </tr>

                        <?php

                        }

                        ?>

                    </tbody>

                </table>

                <?php

                $pr_query = "select * from bapteme";

                $pr_result = $bdd->query($pr_query);

                $total_records = $pr_result->rowCount();

                $total_page = ceil($total_records/$baptise_par_page);

                ?>

                <div align = "center">

                    <?php

                    if($page>1){

                        echo "<a href = 'ajout_bapteme.php?page=".($page-1)."' class='btn btn-danger'>Prec</a>"; 

                    }



                    for($i=1; $i<$total_page; $i++){

                        echo "<a href = 'ajout_bapteme.php?page=".$i."' class = 'btn btn-primary'>$i</a>";

                    }



                    if($i>$page){

                        echo "<a href = 'ajout_bapteme.php?page=".($page+1)."' class='btn btn-danger'>Suiv</a>"; 

                    }

                    ?>

                </div>

            </div>

        </div>

        <script src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

        <script>

            $(".sidebar ul li").on('click', function(){

                $(".sidebar ul li.active").removeClass('active');

                $(this).addClass('active');

            });



            $('.navbar-toggler').on('click', function(){

                $('.sidebar').addClass('active');

            });



            $('.close-btn').on('click', function(){

                $('.sidebar').removeClass('active');

            })

        </script>

    </body>

</html>

