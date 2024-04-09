<?php
session_start();
if(@$_SESSION['autoriser'] != "oui"){
   header('location:login.php'); 
   exit();
}

// Connexion à la base des données

require_once('connect.php');
$autres_activites = $bdd->query("SELECT * FROM autres_activites ORDER BY id DESC");

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Autres Activités</title>
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
                    <h5>Autres activités de l'Eglise</h5>
                    <button type = "button" class = "btn btn-primary" data-bs-toggle="modal" data-bs-target="#ajouter_intercession">Ajouter Activité</button>
                    <div align = "right">
                        <a href="excel_intercession.php">
                            <button type = "button" class = "btn btn-success">Exporter vers Excel</button>
                        </a>
                    </div>
                    <!-- Modale d'ajout d'une activité -->
                    <div class="modal fade" id="ajouter_intercession" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Ajouter Activité</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="autres_activites.php" method = "POST">
                                    <label for="nom_activite"><strong>Nom Activité</strong></label>
                                    <input type="text" name = "nom_activite" class="form-control" placeholder="Nom de l'activité">
                                    <label for="organisateur"><strong>Ministère/Commission Organisateur</strong></label>
                                    <input type="text" name = "organisateur" class="form-control" placeholder="Organisateur">
                                    </br>
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
                    <!-- Modale Modification de l'activité -->
                    <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modifier Culte</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="updateautreactivite.php" method = "POST">
                                    <input type="hidden" name = "update_id" id = "update_id">
                                    <label for="nom_activite"><strong>Nom Activité</strong></label>
                                    <input type="text" name = "nom_activite" id = "nom_activite" class="form-control">
                                    <label for="nbre_hommes"><strong>Ministère/Commission Organisateur</strong></label>
                                    <input type="text" name = "organisateur" id = "organisateur" class="form-control">
                                    </br>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <input type="submit" class="btn btn-success" name = "updatedata" value = "Modifier">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class = "table table-bordered table-striped mt-2">
                        <tbody>
                            <tr>
                                <th>Id</th>
                                <th>Nom de l'activité</th>
                                <th>Organisateur</th>
                                <th>Actions</th>
                            </tr>
                        </tbody>
                        <tbody>
                            <?php
                            if($autres_activites->rowCount() > 0){
                                while($autre_activite = $autres_activites->fetch()){
                            ?>
                            <tr>
                                <td><?php echo $autre_activite['id']; ?></td>
                                <td><?php echo $autre_activite['nom_activite']; ?></td>
                                <td><?php echo $autre_activite['organisateur']; ?></td>
                                <td>
                                    <button type = "button" class = "btn btn-light editbtn" data-bs-toggle="modal" data-bs-target="#editmodal"><i class="las la-edit" style = "font-size:24px;"></i></button>
                                    <a href = "detail_activite.php?nom_activite=<?php echo $autre_activite['nom_activite']; ?>"><button type = "button" class = "btn btn-light"><i class="las la-ellipsis-h" style = "font-size:24px;"></i></button></a>
                                </td>
                            </tr>
                            <?php
                                }
                            }else{
                            ?>
                                <tr>
                                    <td colspan = "4">Aucun resultat</td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                $('.deletebtn').on('click', function(){
                    $('#deletemodal').modal('show');
                    $tr = $(this).closest('tr');
                    var data = $tr.children("td").map(function(){
                        return $(this).text();
                    }).get();
                    console.log(data);
                    $('#delete_id').val(data[0]);
                });
            });
        </script>
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
                    $('#nom_activite').val(data[1]);
                    $('#organisateur').val(data[2]);
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
