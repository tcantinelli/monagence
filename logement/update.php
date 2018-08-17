<?php
require '../database.php';

if(!empty($_GET['id'])) {
    $id = checkInput($_GET['id']);
}

$loyerHCError = $superficieError = $numeroError = $adresse1Error = $adresse1Error = $codePostalError = $quartierIDError = $chargesTypeError = $latitudeError = $longitudeError = "";
$loyerHC = $superficie = $numero = $adresse1 = $adresse2 = $codePostal = $quartierID = $chargesType = $latitude = $longitude = "";

if(!empty($_POST)) {
    
    $loyerHC = checkInput($_POST['loyerHC']);
    $superficie = checkInput($_POST['superficie']);
    $numero = checkInput($_POST['numero']);
    $adresse1 = checkInput($_POST['adresse1']);
    $adresse2 = checkInput($_POST['adresse2']);
    $codePostal = checkInput($_POST['codePostal']);
    $quartierID = checkInput($_POST['quartierID']);
    $chargesType = checkInput($_POST['chargesType']);
    $latitude = checkInput($_POST['latitude']);
    $longitude = checkInput($_POST['longitude']);
    
    $isSuccess = true;

    if(empty($numero)) {
        $numeroError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    
    if(empty($adresse1)) {
        $adresse1Error = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }

    if(empty($codePostal)) {
        $codePostalError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    
    if(empty($quartierID)) {
        $quartierIDError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    
    if(empty($chargesType)) {
        $chargesTypeError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    
    if(empty($latitude)) {
        $latitudeError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    
    if(empty($longitude)) {
        $longitudeError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    
    if(empty($loyerHC)) {
        $loyerHCError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    
    if(empty($superficie)) {
        $superficieError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    } 

    if($isSuccess) {
        
        $db = Database::connect();
        $statement = $db->prepare("UPDATE logement SET
        superficie = ?,
        loyerHC = ?,
        numero = ?,
        adresse1 = ?,
        adresse2 = ?,
        CP = ?,
        quartierID = ?,
        chargesType = ?,
        latitude = ?,
        longitude = ?
        WHERE ID = ?
        ");
        $statement->execute(array($superficie,$loyerHC,$numero,$adresse1,$adresse2,$codePostal,$quartierID,$chargesType,$latitude,$longitude,$id));
        Database::disconnect();
        header("Location: ../index.php");
    }

}else{
    
    $db = Database::connect();
    $statement = $db->prepare("SELECT * FROM logement WHERE ID = ?");
    $statement->execute(array($id));
    $item = $statement->fetch();
    
    $loyerHC = $item['loyerHC'];
    $superficie = $item['superficie'];
    $numero = $item['numero'];
    $adresse1 = $item['adresse1'];
    $adresse2 = $item['adresse2']; 
    $codePostal = $item['CP'];
    $quartierID = $item['quartierID'];
    $chargesType = $item['chargesType'];
    $latitude = $item['latitude'];
    $longitude = $item['longitude'];
    
    Database::disconnect();
}

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
                 <h1><strong>Modifier un logement</strong></h1>
                <br>
                <form class="form" action="<?php echo 'update.php?id=' . $id; ?>" role="form" method="post">
                    <div class="form-group">
                        <label for="superficie">Superficie (en m2):</label>
                        <input type="number" class="form-control" id="superficie" name="superficie" placeholder="Superficie" value="<?php echo $superficie; ?>">
                        <span class="help-inline"><?php echo $superficieError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="loyerHC">Loyer HC:</label>
                        <input type="number" class="form-control" id="loyerHC" name="loyerHC" placeholder="Loyer Hors Charges" value="<?php echo $loyerHC; ?>">
                        <span class="help-inline"><?php echo $loyerHCError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="numero">Numero:</label>
                        <input type="text" class="form-control" id="numero" name="numero" placeholder="Numero" value="<?php echo $numero; ?>">
                        <span class="help-inline"><?php echo $numeroError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="adresse1">Adresse 1:</label>
                        <input type="text" class="form-control" id="adresse1" name="adresse1" placeholder="Adresse 1" value="<?php echo $adresse1; ?>">
                        <span class="help-inline"><?php echo $adresse1Error; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="adresse2">Adresse 2:</label>
                        <input type="text" class="form-control" id="adresse2" name="adresse2" placeholder="Adresse 2 (Falcultatif)" value="<?php echo $adresse2; ?>">
                    </div>
                    <div class="form-group">
                        <label for="codePostal">Code postal:</label>
                        <input type="text" class="form-control" id="codePostal" name="codePostal" placeholder="Code postal" value="<?php echo $codePostal; ?>">
                        <span class="help-inline"><?php echo $codePostalError; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="quartierID">ID quartier:</label>
                        <select class="form-control" id="quartierID" name="quartierID">
                            <?php
                                $db = Database::connect();
                                foreach($db->query('SELECT ID, nom FROM quartier') as $row) {
                                    if($row['ID'] == $quartierID){
                                        echo '<option selected="selected" value="'. $row['ID'] . '">' . $row['nom'] . '</option>';
                                    }else{
                                        echo '<option value="'. $row['ID'] . '">' . $row['nom'] . '</option>';
                                    }
                                }
                            Database::disconnect();
                            ?>
                        </select>
                        <span class="help-inline"><?php echo $quartierIDError; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="chargesType">Charges:</label>
                        <select class="form-control" id="chargesType" name="chargesType">
                            <?php
                                $db = Database::connect();
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
                        <span class="help-inline"><?php echo $chargesTypeError; ?></span>
                    </div>
        
                    <div class="form-group">
                        <label for="latitude">Latitude:</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" placeholder="48.86" value="<?php echo $latitude; ?>">
                        <span class="help-inline"><?php echo $latitudeError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="longitude">Longitude:</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" placeholder="2.33" value="<?php echo $longitude; ?>">
                        <span class="help-inline"><?php echo $coordyError; ?></span>
                    </div>
                <br>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Modifier</button>
                    <a class="btn btn-primary" href="../index.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
                </div>
                </form>
                </div>
                <div class="col-sm-6">
                
                
                </div>
                                    
               
            </div>
        
        </div>

    </body>
</html>