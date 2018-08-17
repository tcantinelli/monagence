<?php
    require 'database.php';

    $villeID = 1;
    $distance = 0;

    if(!empty($_POST)) {

        $villeID = $_POST['villeID'];
    }

    $db = Database::connect();

    $statement = $db->query('SELECT * FROM commune WHERE ID = ' . $villeID);

    $item = $statement->fetch();
    Database::disconnect();

    // renvoi la distance en mètres
    function get_distance_m($lat1, $lng1, $lat2, $lng2) {
      $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
      $rlo1 = deg2rad($lng1);
      $rla1 = deg2rad($lat1);
      $rlo2 = deg2rad($lng2);
      $rla2 = deg2rad($lat2);
      $dlo = ($rlo2 - $rlo1) / 2;
      $dla = ($rla2 - $rla1) / 2;
      $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo
    ));
      $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
      return ($earth_radius * $d);
    }

    $distance = round(get_distance_m(48.869952, 2.307377, $item['latitude'], $item['longitude']) / 1000, 3)

?>

<!DOCTYPE html>

<html>

    <head>
        <title>Mon Agence</title>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link href='https://fonts.googleapis.com/css?family=Ranga' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/styles.css">
        
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css"
   integrity="sha512-M2wvCLH6DSRazYeZRIm1JnYyh22purTM+FDB5CsyxtQJYeKq83arPe5wgbNmcFXGqiSH2XR8dT/fJISVA1r/zQ=="
   crossorigin=""/>
        

             <!-- Make sure you put this AFTER Leaflet's CSS -->
 <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"
   integrity="sha512-lInM/apFSqyy1o6s89K4iQUKg6ppXEgsVxT35HbzUupEVRh2Eu9Wdl4tHj7dZO0s1uvplcYGmt3498TtHq+log=="
   crossorigin=""></script>

        
</head>
    
    <body>
        
        <div class="container">
            <h4 class="name">UE NFA008 Projet Cantinelli Thomas</h4>
        </div>
        
        <div class="container titre">
            <h1 class="text-logo">Mon Agence</h1>
        </div>
        
        <div class="container admin">
            <h1><strong>Infos ville</strong></h1>
            <br>

            <div class="row">

                    <div class="col-sm-6">

                        <form class="form" action="commune.php" role="form" method="post">

                            <div class="form-group">
                                <label for="villeID">Ville: </label>
                                <select class="form-control" id="villeID" name="villeID">
                                    <?php
                                        $db2 = Database::connect();
                                        foreach($db2->query('SELECT ID, nom FROM commune') as $row) {
                                            if($row['ID'] == $villeID){
                                                echo '<option selected="selected" value="'. $row['ID'] . '">' . $row['nom'] . '</option>';
                                            }else{
                                                echo '<option value="'. $row['ID'] . '">' . $row['nom'] . '</option>';
                                            }
                                        }
                                    Database::disconnect();
                                    ?>
                                </select>
                            </div>
                            <div class="form-actions">

                                <button type="submit" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-info-sign"></span> Afficher</button>
                            </div>
                        </form>
                    </div>
            </div>

            <div class="row">

                <div class="col-sm-6">
                <br>
                    <form>
                        <div class="form-group">
                            <label>Nombre d'habitants: </label><?php echo ' ' . $item['nbHabitants']; ?>
                        </div>
                        <div class="form-group">
                            <label>Distance de l'agence: </label>
                            <?php
                            if($villeID == 1){
                                echo " 0 km";
                            }else{
                            echo ' ' . $distance . " km";
                            }
                            ?>
                        </div>
                        <label>Liste des quartiers: </label>
                    </form> 

                    <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Popularité (/10)</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                            $db2 = Database::connect();
                            $statement2 = $db2->query('SELECT nom, popularite FROM quartier WHERE communeID = ' . $villeID);
                            $items = $statement2->fetchAll();
                            Database::disconnect();

                        foreach($items as $item2) {
                            echo '<tr>';
                            echo '<td>' .$item2['nom'] . '</td>';
                            echo '<td>' .$item2['popularite'] . '</td>';
                            echo '</tr>';

                        }

                        ?>
                    </tbody>
                </table>

                    <div class="form-actions">
                        <a class="btn btn-primary" href="index.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
                    </div>
                </div>

                <div class="col-sm-6">

                    <div id="mapid"></div>

                    <script>

                        var coordVx = <?php echo ' ' . number_format((float)$item['latitude'],2, '.', ''); ?>;                
                        var coordVy = <?php echo ' ' . number_format((float)$item['longitude'],2, '.', ''); ?>;
                        var coordx = <?php echo ' ' . number_format((float)$item['latitude'],6, '.', ''); ?>;                
                        var coordy = <?php echo ' ' . number_format((float)$item['longitude'],6, '.', ''); ?>;

                        var mymap = L.map('mapid').setView([coordVx, coordVy], 13);

                        var marker = L.marker([coordx, coordy]).addTo(mymap);

                        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiY2Ftb3QiLCJhIjoiY2pjNTB0b3pvMTdoOTJxcjY5aXgyb3I2bSJ9.lH6VKr5-wyiIgbNX9Ujsbg', {
                        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                        maxZoom: 18,
                        id: 'mapbox.streets',
                        accessToken: 'pk.eyJ1IjoiY2Ftb3QiLCJhIjoiY2pjNTB0b3pvMTdoOTJxcjY5aXgyb3I2bSJ9.lH6VKr5-wyiIgbNX9Ujsbg'
                        }).addTo(mymap);

                    </script>


                </div>

            </div>
        </div>
    </body>
</html>

