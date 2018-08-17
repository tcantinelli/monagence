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
        
        <nav>
            <ul class="nav nav-pills">
                <li role="presentation" class="active"><a href="#1" data-toggle="tab"><span class="glyphicon glyphicon-search"></span> Disponibilites</a></li>
                <li role="presentation"><a href="#2" data-toggle="tab"><span class="glyphicon glyphicon-home"></span> Logements</a></li>
                <li role="presentation"><a href="#3" data-toggle="tab"><span class="glyphicon glyphicon-user"></span> Locataires</a></li>
                <li role="presentation"><a href="logement/estimation.php"><span class="glyphicon glyphicon-eur"></span> Estimation loyer</a></li>
                <li role="presentation"><a href="commune.php"><span class="glyphicon glyphicon-info-sign"></span> Info Ville</a></li>
            </ul>
        </nav>

        <div class="tab-content">

            <div class="tab-pane active" id="1">
                
                <?php
                require 'database.php';

                $villeID = $loyerMax = $superficieMini = $chargesType = $villeLat = $villeLong = "";
                
                $dispo = "false";
                
                $attributs = "";

                if(!empty($_POST)) {
                    
                    $villeID = $_POST['ville'];
                    $chargesType = $_POST['chargesType'];
                    $loyerMax = $_POST['loyerMax'];
                    $superficieMini = $_POST['superficieMini'];
                    $dispo = $_POST['dispo'];
                    
                    if($dispo == "true"){
                        
                        $attributs = " WHERE locataireID IS NULL";
                            
                    }
                    
                    if($villeID <> "all") {
                        
                        if($attributs == "") {
                            $attributs = " WHERE q.communeID = " . $villeID;  
                        }else{
                            $attributs = $attributs . " AND q.communeID = " . $villeID; 
                        }
                    }
                    
                    if($chargesType <> "all") {
                        
                        if($attributs == "") {
                            $attributs = " WHERE Type = '" . $chargesType . "'";  
                        }else{
                            $attributs = $attributs . " AND Type = '" . $chargesType . "'";  
                        }
                    } 
                    
                    if($loyerMax <> 0) {
                        
                        if($attributs == "") {
                            $attributs = " WHERE loyerHC <= " . $loyerMax;  
                        }else{
                            $attributs = $attributs . " AND loyerHC <= " . $loyerMax;   
                        }
                    }
                        
                    if($superficieMini <> 0) {
                        
                        if($attributs == "") {
                            $attributs = " WHERE Superficie >= " . $superficieMini;    
                        }else{
                            $attributs = $attributs . " AND Superficie >= " . $superficieMini;  
                        }
                    }
                        
 
                }

                $db = Database::connect();
                $statement = $db->query(
                'SELECT log.ID AS ID, CONCAT(log.numero, ", ", log.adresse1, " ", log.adresse2) AS Adresse, log.CP AS CodePostal, c.nom AS Ville, q.nom AS Quartier, log.superficie AS Superficie, log.chargesType AS Type, log.loyerHC AS Loyer, log.latitude AS latitude, log.longitude AS longitude, log.locataireID AS locataireID
                FROM logement AS log
                INNER JOIN quartier AS q ON log.quartierID = q.ID
                INNER JOIN commune AS c ON q.communeID = c.ID
                INNER JOIN charges AS ch ON log.chargesType = ch.type' .  $attributs . ' ORDER BY ID DESC'
                );
                $items = $statement->fetchAll();
                Database::disconnect();
                    
                

                ?>
                                
                <div class="row">
                    
                    <div class="col-sm-6">

                        <form class="form" action="index.php" role="form" method="post">
                            
                            <div class="form-group">
                                <label for="ville">Ville:</label>
                                <select class="form-control" id="ville" name="ville">
                                    <?php
                                        $db = Database::connect();
                                        echo '<option selected="selected" value="all">Toutes</option>';
                                        foreach($db->query('SELECT ID, nom FROM commune') as $row) {
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
                            
                            <div class="form-group">
                                <label for="chargesType">Type:</label>
                                <select class="form-control" id="chargesType" name="chargesType">
                                    <?php
                                        $db = Database::connect();
                                        echo '<option selected="selected" value="all">Tous</option>';
                                        foreach($db->query('SELECT type FROM charges') as $row) {
                                            if($row['type'] == $chargesType){
                                                echo '<option selected="selected" value="'. $row['type'] . '">' . $row['type'] . '</option>';
                                            }else{
                                                echo '<option value="'. $row['type'] . '">' . $row['type'] . '</option>';
                                            }
                                        }
                                    Database::disconnect();
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="loyerMax">Maximum loyer:</label>
                                <input type="number" class="form-control" id="loyerMax" name="loyerMax" placeholder="Maximum" value="<?php echo $loyerMax; ?>">
                            </div>

                            <div class="form-group">
                                <label for="superficieMini">minimum superficie:</label>
                                <input type="number" class="form-control" id="superficieMini" name="superficieMini" placeholder="Minimum" value="<?php echo $superficieMini; ?>">
                            </div>

                        <br>
                            
                            <div class="form-check">
                                
                                <?php
                                
                                if($dispo == "true") {
                                    echo '<input class="form-check-input" type="checkbox" value=true id="dispo" name="dispo" checked>';
                                }else{
                                    echo '<input class="form-check-input" type="checkbox" value=true id="dispo" name="dispo">';
                                }
                                
                                ?>
                              
                              <label class="form-check-label" for="dispo">
                                Voir seulement les logements disponibles
                              </label>
                            </div>
                        <br>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-pencil"></span> Rechercher</button>
                        </div>
                        </form>
                </div>
                    
                    <div class="col-sm-6">
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <div id="compte">
                        <?php echo "Nombre de logements sélectionnés: " . sizeof($items); ?>
                        </div>
                    </div>
            </div>

                <br>
                
                 <div class="row">
                     
                     <div class="legende">
                     <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png" /> Disponible  <img src="https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png" /> Occupé
                     </div>
<br>
                    <div id="mapbig"></div>
                    
                        <script>
                            
                            var greenIcon = new L.Icon({
                              iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png',
                              shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                              iconSize: [25, 41],
                              iconAnchor: [12, 41],
                              popupAnchor: [1, -34],
                              shadowSize: [41, 41]
                            });
                            
                            var redIcon = new L.Icon({
                              iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                              shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                              iconSize: [25, 41],
                              iconAnchor: [12, 41],
                              popupAnchor: [1, -34],
                              shadowSize: [41, 41]
                            });
                            
                            <?php
                            
                        
                            if(!empty($_POST) && $villeID <> "all") {
                                
                                $db = Database::connect();
                                $statement = $db->query('SELECT latitude, longitude FROM commune WHERE ID = ' . $villeID);
                                $coord = $statement->fetch();
                                Database::disconnect();
                                $villeLat = number_format((float)$coord['latitude'],3, '.', '');
                                $villeLong = number_format((float)$coord['longitude'],3, '.', '');
                                if($villeID == 1) {
                                    $zoom = 12;
                                }else{
                                    $zoom = 13;
                                }
                                
                            }else{
                                $villeLat = 48.89;
                                $villeLong = 2.30;
                                $zoom = 11;
                            }
                            
                            echo "var mymap = L.map('mapbig').setView([" . $villeLat . "," . $villeLong . "], " . $zoom . ");";
            
                            

                                foreach($items as $item) {
                                    
                                    if(!empty($item['locataireID'])) {
                                        
                                        echo "var marker = L.marker([" . number_format((float)$item['latitude'],6, '.', '') . "," . number_format((float)$item['longitude'],6, '.', '') . "], {icon: redIcon}).addTo(mymap);";
                                        
                                    }else{
                                        
                                        echo "var marker = L.marker([" . number_format((float)$item['latitude'],6, '.', '') . "," . number_format((float)$item['longitude'],6, '.', '') . "], {icon: greenIcon}).addTo(mymap);";
                                    }

                                    

                                }

                            ?>
              
                            L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiY2Ftb3QiLCJhIjoiY2pjNTB0b3pvMTdoOTJxcjY5aXgyb3I2bSJ9.lH6VKr5-wyiIgbNX9Ujsbg', {
                            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
                            maxZoom: 18,
                            id: 'mapbox.streets',
                            accessToken: 'pk.eyJ1IjoiY2Ftb3QiLCJhIjoiY2pjNTB0b3pvMTdoOTJxcjY5aXgyb3I2bSJ9.lH6VKr5-wyiIgbNX9Ujsbg'
                            }).addTo(mymap);

                        </script>
                
                </div>
                
                <br>
                <br>
                
                <div class="row">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Disponibilte</th>
                            <th>Adresse</th>
                            <th>Code Postal</th>
                            <th>Ville</th>
                            <th>Quartier</th>
                            <th>Superficie (m²)</th>
                            <th>Type</th>
                            <th>Loyer (€)</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
  
                        foreach($items as $item) {
                            echo '<tr>';
                            echo '<td>' .$item['ID'] . '</td>';
                            
                            if(empty($item['locataireID'])) {
                                echo '<td class="success" width=60>';
                            }else{
                                echo '<td class="danger" width=60>';
                            }
                            echo '</td>';
                            
                            echo '<td>' .$item['Adresse'] . '</td>';
                            echo '<td>' .$item['CodePostal'] . '</td>';
                            echo '<td>' .$item['Ville'] . '</td>';
                            echo '<td>' .$item['Quartier'] . '</td>';
                            echo '<td>' .$item['Superficie'] . '</td>';
                            echo '<td>' .$item['Type'] . '</td>';
                            echo '<td>' .$item['Loyer'] . '</td>';
                            echo '<td width=90>';
                            echo '<a class="btn btn-default" href="logement/view.php?id=' . $item['ID'] . '"><span class="glyphicon glyphicon-eye-open"></span> Voir</a>';
                            echo '</td>';
                            echo '</tr>';

                        }

                        ?>
                    </tbody>
                </table>
            </div>
                    
<!-- Page 2 Logements-->
                
        </div>
            
            <div class="tab-pane" id="2">

                <div class="row">

                    <h1><strong>Liste des logements </strong><a href="logement/insert.php" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Ajouter</a></h1>
                    
                    <h4>(En vert les logements disponibles)</h4>

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                            <th>ID</th>
                            <th>Adresse</th>
                            <th>Code Postal</th>
                            <th>Ville</th>
                            <th>Quartier</th>
                            <th>Superficie (m²)</th>
                            <th>Type</th>
                            <th>Loyer (€)</th>
                            <th>Actions</th>
                            </tr>
                        </thead>

                    <tbody>

                        <?php

                        $db3 = Database::connect();

                        $statement3 = $db3->query(
                            'SELECT log.ID AS ID, CONCAT(log.numero, ", ", log.adresse1, " ", log.adresse2) AS Adresse, log.CP AS CodePostal, c.nom AS Ville, q.nom AS Quartier, log.superficie AS Superficie, log.chargesType AS Type, log.loyerHC AS Loyer, log.locataireID AS locataireID
                            FROM logement AS log
                            INNER JOIN quartier AS q ON log.quartierID = q.ID
                            INNER JOIN commune AS c ON q.communeID = c.ID
                            INNER JOIN charges AS ch ON log.chargesType = ch.type
                            ORDER BY ID DESC
                            ');


                        while($item3 = $statement3->fetch()) {
                            
                            if($item3['locataireID'] == 0) {
                                echo '<tr class="success">';
                            }else{
                                echo '<tr>';
                            }
                            echo '<td>' .$item3['ID'] . '</td>';
                            echo '<td>' .$item3['Adresse'] . '</td>';
                            echo '<td>' .$item3['CodePostal'] . '</td>';
                            echo '<td>' .$item3['Ville'] . '</td>';
                            echo '<td>' .$item3['Quartier'] . '</td>';
                            echo '<td>' .$item3['Superficie'] . '</td>';
                            echo '<td>' .$item3['Type'] . '</td>';
                            echo '<td>' .$item3['Loyer'] . '</td>';
                            echo '<td width=300>';
                            echo '<a class="btn btn-default" href="logement/view.php?id=' . $item3['ID'] . '"><span class="glyphicon glyphicon-eye-open"></span> Voir</a>';
                            echo ' <a class="btn btn-primary" href="logement/update.php?id=' . $item3['ID'] . '"><span class="glyphicon glyphicon-pencil"></span> Modifier</a>';
                            echo ' <a class="btn btn-danger" href="logement/delete.php?id=' . $item3['ID'] . '"><span class="glyphicon glyphicon-remove"></span> Supprimer</a>';
                            echo '</td>';
                            echo '</tr>';

                        }

                        Database::disconnect();
                        ?>
                    </tbody>
                    </table>
                </div>
        </div>
            
 <!-- Page 3 Locataires--> 
            
            <div class="tab-pane" id="3">

                <div class="row">

                    <h1><strong>Liste des locataires </strong><a href="locataire/insert.php" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Ajouter</a></h1>

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Prenom</th>
                                <th>Telephone</th>
                                <th>Date de Naissance</th>
                                <th>ID logement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php

                            $db2 = Database::connect();

                            $statement2 = $db2->query('SELECT * FROM locataire ORDER BY ID DESC');

                            while($item2 = $statement2->fetch()) {
                                
                                $dateNaissance2 = date("d/m/Y", strtotime($item2['dateNaissance']));

                                echo '<tr>';
                                echo '<td>' .$item2['ID'] . '</td>';
                                echo '<td>' .$item2['nom'] . '</td>';
                                echo '<td>' .$item2['prenom'] . '</td>';
                                echo '<td>' .$item2['telephone'] . '</td>';
                                echo '<td>' .$dateNaissance2 . '</td>';
                                echo '<td>' .$item2['logementID'] . '</td>';
                                echo '<td width=300>';
                                echo '<a class="btn btn-default" href="locataire/view.php?id=' . $item2['ID'] . '"><span class="glyphicon glyphicon-eye-open"></span> Voir</a>';
                                echo ' <a class="btn btn-primary" href="locataire/update.php?id=' . $item2['ID'] . '"><span class="glyphicon glyphicon-pencil"></span> Modifier</a>';
                                echo ' <a class="btn btn-danger" href="locataire/delete.php?id=' . $item2['ID'] . '"><span class="glyphicon glyphicon-remove"></span> Supprimer</a>';
                                echo '</td>';
                                echo '</tr>';

                            }

                            Database::disconnect();
                            ?>
                        </tbody>
                    </table>
                </div>
        </div>
            
    </div>
        
</div>
    

    
</body>
</html>

