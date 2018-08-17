<?php

    require '../database.php';

    if(!empty($_GET['id'])) {
        $id = checkInput($_GET['id']);
    }

    $db = Database::connect();

    $statement = $db->prepare('SELECT log.ID AS ID, log.numero AS Numero, log.adresse1 AS Adresse1, log.adresse2 AS Adresse2, log.CP AS CodePostal, c.nom AS Ville, q.nom AS Quartier, log.superficie AS Superficie, log.chargesType AS Type, log.loyerHC AS Loyer, ch.valeur AS Charges, log.latitude AS Latitude, log.longitude AS Longitude, log.locataireID AS locataireID
                            FROM logement AS log
                            INNER JOIN quartier AS q ON log.quartierID = q.ID
                            INNER JOIN commune AS c ON q.communeID = c.ID
                            INNER JOIN charges AS ch ON log.chargesType = ch.type
                            WHERE log.ID = ?
                            ');

    $statement->execute(array($id));
    $item = $statement->fetch();

    $loyerTTC = $item['Loyer'] + $item['Charges'];

    Database::disconnect();
    
    function checkInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

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
        <link rel="stylesheet" href="../css/styles.css">
        
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
            
            <div class="row">
                <div class="col-sm-6">
                    
                    <h1><strong>Logement n° <?php echo $item['ID']; ?></strong></h1> 
                    
                    <h2>
                        <?php 
                        if(empty($item['locataireID'])) {
                            echo '<span class="label label-success">Libre</span>';
                        }else{
                            echo '<span class="label label-danger">Occupé</span>';
                        }
                        ?>
                    </h2>
                    <br>
                    <form>
                        <div class="form-group">
                            <label>Numero:</label><?php echo ' ' . $item['Numero']; ?>
                        </div>
                        <div class="form-group">
                            <label>Adresse 1:</label><?php echo ' ' . $item['Adresse1']; ?>
                        </div>
                        <div class="form-group">
                            <label>Adresse 2:</label><?php echo ' ' . $item['Adresse2']; ?>
                        </div>
                        <div class="form-group">
                            <label>Code Postal:</label><?php echo ' ' . $item['CodePostal']; ?>
                        </div>
                        <div class="form-group">
                            <label>Ville:</label><?php echo ' ' . $item['Ville']; ?>
                        </div>
                        <div class="form-group">
                            <label>Quartier:</label><?php echo ' ' . $item['Quartier']; ?>
                        </div>
                        <div class="form-group">
                            <label>Superficie:</label><?php echo ' ' . $item['Superficie'] . ' m2'; ?>
                        </div>
                        <div class="form-group">
                            <label>Type:</label><?php echo ' ' . $item['Type']; ?>
                        </div>
                        <div class="form-group">
                            <label>Loyer HC:</label><?php echo ' ' . $item['Loyer'] . ' €'; ?>
                        </div>
                        <div class="form-group">
                            <label>Charges:</label><?php echo ' ' . $item['Charges'] . ' €'; ?>
                        </div>
                    </form>
                    <br>
                    <div class="form-actions">
                        <a class="btn btn-primary" href="../index.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
                    </div>
                </div>

                <div class="col-sm-6">
                    
                    <div id="mapid"></div>
                    

                    <script>
                        
                        var coordVx = <?php echo ' ' . number_format((float)$item['Latitude'],2, '.', ''); ?>;                
                        var coordVy = <?php echo ' ' . number_format((float)$item['Longitude'],2, '.', ''); ?>;
                        var coordx = <?php echo ' ' . number_format((float)$item['Latitude'],6, '.', ''); ?>;                
                        var coordy = <?php echo ' ' . number_format((float)$item['Longitude'],6, '.', ''); ?>;
                        
                        var mymap = L.map('mapid').setView([coordVx, coordVy], 15);
                                            
                        var marker = L.marker([coordx, coordy]).addTo(mymap);
                                            
                        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiY2Ftb3QiLCJhIjoiY2pjNTB0b3pvMTdoOTJxcjY5aXgyb3I2bSJ9.lH6VKr5-wyiIgbNX9Ujsbg', {
                        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                        maxZoom: 18,
                        id: 'mapbox.streets',
                        accessToken: 'pk.eyJ1IjoiY2Ftb3QiLCJhIjoiY2pjNTB0b3pvMTdoOTJxcjY5aXgyb3I2bSJ9.lH6VKr5-wyiIgbNX9Ujsbg'
                        }).addTo(mymap);

                    </script>
                    
                    <br>
                    
                    <!-- Calcul de la distance de l agence-->
                    <h4><strong>Distance de l'agence: </strong>
                    <?php
                    
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

                    echo (round(get_distance_m(48.869952, 2.307377, $item['Latitude'], $item['Longitude']) / 1000, 3)). ' km';
                    ?>
                    </h4>
                    <br>
                     <h4><strong>Loyer TTC:</strong><?php echo ' ' . $loyerTTC . ' €'; ?></h4>
                    <br>
                    
                        <?php 
                        if(empty($item['locataireID'])) {
                            echo '<h4><strong>Ajouter un locataire</strong></h4>';
                            echo '<a class="btn btn-sm btn-info" href="../locataire/insert.php?id=' . $item['ID'] . '"><span class="glyphicon glyphicon-user"></span> Ajouter</a>';
                        }else{
                            echo '<h4><strong>Occupé par le locataire n° ' . $item['locataireID'] . '</strong></h4>';
                            echo '<a class="btn btn-sm btn-info" href="../locataire/view.php?id=' . $item['locataireID'] . '"><span class="glyphicon glyphicon-user"></span> Voir</a>';
                        }
                        ?>
                    
                </div>
            

                
                
            </div>
        
        </div>
        

        

    
    </body>
</html>

