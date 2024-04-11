<?php

session_start();

if(@$_SESSION['autoriser'] != "oui"){

   header('location:login.php'); 

   exit();

}

// Connexion à la base des données

require_once('connect.php');



// Somme des participants

$requete_total = "SELECT SUM(nbre_participants) as total FROM culte_jeunes";

$reponse_total = $bdd->query($requete_total);

$resultat_total = $reponse_total->fetch();

$total = $resultat_total['total'];



// Nombre total des cultes dans la base des données

$requete_culte = "SELECT COUNT(*) as total_culte FROM culte_jeunes";

$reponse_culte = $bdd->query($requete_culte);

$resultat_culte = $reponse_culte->fetch();

$somme_culte = $resultat_culte['total_culte'];



// Fonction de division

function moy($a, $b){

    $moyenne = $a/$b;

    return $moyenne;

}

if(isset($_GET['page'])){

    $page = $_GET['page'];

}else{

    $page = 1;

}



$cultejeunes_par_page = 5;

$start_from = ($page-1)*5;





$cultejeunes = $bdd->query("SELECT * FROM culte_jeunes ORDER BY date_culte DESC limit $start_from, $cultejeunes_par_page");

if(isset($_GET['s']) AND !empty($_GET['s'])){

    $recherche = htmlspecialchars($_GET['s']);

    $cultejeunes = $bdd->query('SELECT * FROM culte_jeunes WHERE nbre_participants LIKE "%'.$recherche.'%" OR theme LIKE "%'.$recherche.'%" OR texte_biblique LIKE "%'.$recherche.'%" OR predicateur LIKE "%'.$recherche.'%"');

}

if(isset($_GET['filtrer']) && isset($_GET['from_date']) && isset($_GET['to_date']))

{

    $from_date = $_GET['from_date'];

    $to_date = $_GET['to_date'];

    $cultejeunes = $bdd->query("SELECT * FROM culte_jeunes WHERE date_culte BETWEEN '$from_date' AND '$to_date'");

}

?>

<!DOCTYPE html>

<html>

    <head>

        <title>Ajout Culte des Jeunes</title>

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

        #deletemodal{

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



        tbody{

            text-align:center;

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

                <div class = "dashboard-content px-3 pt-4" style = "margin-left:10px;">

                    <h5>Culte des jeunes</h5>

                    <button type = "button" class = "btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouter_cultejeunes">Ajouter Culte des Jeunes</button>

                    <div align = "right">

                        <a href="excel_cultejeune.php">

                            <button type = "button" class = "btn btn-success">Exporter vers Excel</button>

                        </a>

                    </div>

                    <!-- Modale d'ajout d'un culte des jeunes -->

                    <div class="modal fade" id="ajouter_cultejeunes" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <div class="modal-header">

                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ajouter Culte des Jeunes</h1>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                                </div>

                                <div class="modal-body">

                                    <form action="culte_jeunes.php" method = "POST">

                                        <label for="date_etude"><strong>Date du culte des jeunes</strong></label>

                                        <input type="date" name = "date_cultejeunes" class="form-control" aria-label="date seminaire"></br>

                                        <div class = "row">

                                            <div class="col-6">

                                                <label for="nbre_hommes"><strong>Nombre d'hommes</strong></label>

                                                <input type="number" name = "nbre_hommes" value = "0" class="form-control" aria-label="nbre hommes">

                                            </div>

                                            <div class="col-6">

                                                <label for="nbre_femmes"><strong>Nombre de femmes</strong></label>

                                                <input type="number" name = "nbre_femmes" value = "0" class="form-control" aria-label="nbre hommes">

                                            </div>

                                        </div></br>

                                        <div class = "row">

                                            <div class = "col">

                                                <label for="thème"><strong>Thème</strong></label>

                                                <input type="text" name = "theme" class="form-control" aria-label="theme">

                                            </div>

                                            <div class = "col">

                                                <label for="texte_biblique"><strong>Texte Biblique</strong></label>

                                                <input type="text" name = "texte_biblique" class="form-control" aria-label="texte biblique">

                                            </div>

                                        </div></br>

                                        <label for="predicateur"><strong>Prédicateur</strong></label>

                                        <input type="text" name = "predicateur" class="form-control" aria-label="predicateur"></br>

                                        <label for="offrandes_fc"><strong>Offrandes en Francs</strong></label>

                                        <input type="number" name = "offrandes_fc" class="form-control" aria-label="offrandes_fc"></br>                                        

                                </div>

                                <div class="modal-footer">

                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>

                                        <input type="submit" class="btn btn-primary" name = "enregistrer" value = "Enregistrer">

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                    <hr>

                    <!-- Modale Modification du culte des jeunes -->

                    <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <div class="modal-header">

                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier Culte des Jeunes</h1>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                                </div>

                                <div class="modal-body">

                                    <form action="updatecultejeunes.php" method = "POST">

                                    <input type="hidden" name = "update_id" id = "update_id">

                                    <label for="date_culte"><strong>Date du culte</strong></label>

                                    <input type="date" name = "date_cultejeunes" id = "date_cultejeunes" class="form-control" aria-label="Last name"></br>

                                    <div class = "row">

                                        <div class="col-6">

                                            <label for="nbre_hommes"><strong>Nombre d'hommes</strong></label>

                                            <input type="number" name = "nbre_hommes" id = "nbre_hommes" class="form-control" aria-label="nbre hommes">

                                        </div>

                                        <div class="col-6">

                                            <label for="nbre_femmes"><strong>Nombre de femmes</strong></label>

                                            <input type="number" name = "nbre_femmes" id = "nbre_femmes" class="form-control" aria-label="nbre hommes">

                                        </div>

                                    </div></br>

                                    <div class = "row">

                                        <div class = "col">

                                            <label for="theme"><strong>Thème</strong></label>

                                            <input type="text" name = "theme" id = "theme" class="form-control" aria-label="theme">

                                        </div>

                                        <div class = "col">

                                            <label for="texte_bilique"><strong>Texte Biblique</strong></label>

                                            <input type="text" name = "texte_biblique" id = "texte_biblique" class="form-control" aria-label="texte biblique">

                                        </div>

                                    </div></br>

                                    <label for="theme"><strong>Prédicateur</strong></label>

                                    <input type="text" name = "predicateur" id = "predicateur" class="form-control" aria-label="predicateur"></br>

                                    <label for="offrandes_fc"><strong>Offrandes en Francs</strong></label>

                                    <input type="number" name = "offrandes_fc" id = "offrandes_fc" class="form-control" aria-label="offrandes_fc"></br>

                                </div>

                                <div class="modal-footer">

                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>

                                    <input type="submit" class="btn btn-success" name = "updatedata" value = "Modifier">

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                    <form action="" method = "GET">

                        <div class = "row">

                            <div class = "col-2">

                                <div class = "form-group">

                                    <label for="">Du</label>

                                    <input type="date" name = "from_date" class = "form-control">

                                </div>

                            </div>

                            <div class = "col-2">

                                <div class = "form-group">

                                    <label for="">Au</label>

                                    <input type="date" name = "to_date" class = "form-control">

                                </div>

                            </div>

                            <div class = "col-2">

                                <div class = "form-group">

                                    <button type = "submit" name = "filtrer" class = "btn btn-outline-primary mt-4"><i class="las la-filter"></i>

                                    </form>

                                </div>

                            </div>

                            <div class = "col-6 pt-4" align = "center">

                                <form role="search" method = "GET">

                                    <input type="search" placeholder="Rechercher nom..." name = "s">

                                    <button class="btn btn-outline-success" type="submit" name = "search"><i class="las la-search"></i></button>

                                </form>

                            </div>

                        </div>    

                    <table class = "table table-bordered table-striped mt-2">

                        <tbody>

                            <tr>

                                <th>Id</th>

                                <th>Date Culte</th>

                                <th>Nombre d'hommes</th>

                                <th>Nombre de femmes</th>

                                <th>Thème</th>

                                <th>Texte</th>

                                <th>Predicateur</th>

                                <th>Offrandes en Francs</th>

                                <th>Actions</th>

                            </tr>

                        </tbody>

                        <tbody>

                            <?php

                            if($cultejeunes->rowCount() > 0){

                                while($cultejeune = $cultejeunes->fetch()){

                            ?>

                            <tr>

                                <td><?php echo $cultejeune['id']; ?></td>

                                <td><?php echo $cultejeune['date_culte']; ?></td>

                                <td><?php echo $cultejeune['nbre_hommes']; ?></td>

                                <td><?php echo $cultejeune['nbre_femmes']; ?></td>

                                <td><?php echo $cultejeune['theme']; ?></td>

                                <td><?php echo $cultejeune['texte_biblique']; ?></td>

                                <td><?php echo $cultejeune['predicateur']; ?></td>

                                <td><?php echo $cultejeune['offrandes_fc']; ?></td>

                                <td>

                                    <button type = "button" class = "btn btn-light editbtn" data-bs-toggle="modal" data-bs-target="#editmodal"><i class="las la-edit" style = "font-size:24px;"></i></button></br>

                                </td>

                            </tr>

                            <?php

                                }

                            }else{

                            ?>

                            <tr>

                                <td colspan = "9">Aucun resultat</td>

                            </tr>

                            <?php

                            }

                            ?>

                        </tbody>

                    </table>

                    <strong>Moyenne</strong> : <?php echo round(moy($total, $somme_culte),1); ?>

                    <?php

                    $pr_query = "select * from culte_jeunes";

                    $pr_result = $bdd->query($pr_query);

                    $total_records = $pr_result->rowCount();

                    $total_page = ceil($total_records/$cultejeunes_par_page);

                    ?>

                    <div align = "center">

                        <?php

                        if($page>1){

                            echo "<a href = 'ajout_cultejeunes.php?page=".($page-1)."' class='btn btn-danger'>Prec</a>"; 

                        }

 

                        for($i=1; $i<$total_page; $i++){

                            echo "<a href = 'ajout_cultejeunes.php?page=".$i."' class = 'btn btn-primary'>$i</a>";

                        }



                        if($i>$page){

                            echo "<a href = 'ajout_cultejeunes.php?page=".($page+1)."' class='btn btn-danger'>Suiv</a>"; 

                        }

                        ?>

                    </div>

                </div>

            </div>

        </div>

        <script src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

        <script>

            $(document).ready(function(){

                $('.editbtn').on('click', function(){

                    $('#editmodal').modal('show');



                    $tr = $(this).closest('tr');



                    var data = $tr.children("td").map(function(){

                        return $(this).text();

                    }).get();



                    console.log(data);

                    $('#update_id').val(data[0]);

                    $('#date_cultejeunes').val(data[1]);

                    $('#nbre_hommes').val(data[2]);

                    $('#nbre_femmes').val(data[3]);

                    $('#theme').val(data[4]);

                    $('#texte_biblique').val(data[5]);

                    $('#predicateur').val(data[6]);

                    $('#offrandes_fc').val(data[7]);

                });

            });

        </script>

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
