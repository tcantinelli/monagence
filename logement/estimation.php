<?php
require '../database.php';

$quartierID = 1;
$superficie = $superficieError = "";
$chargesType = 'Maison';
$loyer = 0;


if(!empty($_POST)) {

    $quartierID = $_POST['quartier'];
    $chargesType = $_POST['chargesType'];
    $superficie = $_POST['superficie'];
    
    $isSuccess = true;
    
    if(empty($superficie)) {
        $superficieError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    } 
    
    if($isSuccess) {
        
        $db = Database::connect();
        $statement = $db->query(
        "SELECT ROUND(AVG(l.loyerHC),2) AS loyer, ROUND(AVG(l.superficie),2) AS super
            FROM quartier AS q
            INNER JOIN logement AS l ON q.ID = l.quartierID
            WHERE l.chargesType = '" . $chargesType . "' AND q.popularite = (SELECT popularite FROM quartier WHERE ID = " . $quartierID . ")"
        );
        
        $item = $statement->fetch();
        Database::disconnect();
        
        if(empty($item['loyer'])) {
            
            $db = Database::connect();
            $statement = $db->query(
            "SELECT ROUND(AVG(l.loyerHC),2) AS loyer, ROUND(AVG(l.superficie),2) AS super
                FROM quartier AS q
                INNER JOIN logement AS l ON q.ID = l.quartierID
                WHERE q.popularite = (SELECT popularite FROM quartier WHERE ID = " . $quartierID . ")"
            );
            
            $item = $statement->fetch();
            Database::disconnect();
            
        }
        
        $prixM2 = $item['loyer'] / $item['super'];
        
        $loyer = number_format((float)($superficie * $prixM2),2, '.', '');
                    
    }
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
                                    
                <h1><strong>Estimer un loyer</strong></h1>
                <br>
                <form class="form" role="form" action="estimation.php" method="post">

                    <div class="form-group">
                        <label for="quartier">Ville / Quartier:</label>
                        <select class="form-control" id="quartier" name="quartier">
                            <?php
                                $db = Database::connect();
                            
                                foreach($db->query('SELECT ID, nom FROM commune') as $row) {
                                    
                                    echo '<optgroup label="' . $row['nom'] . '">';
                                    
                                        foreach($db->query('SELECT ID, nom FROM quartier WHERE communeID = ' . $row['ID']) as $row2) {
                                            
                                            if($row2['ID'] == $quartierID){
                                                echo '<option selected="selected" value="'. $row2['ID'] . '">' . $row2['nom'] . '</option>';
                                            }else{
                                                echo '<option value="'. $row2['ID'] . '">' . $row2['nom'] . '</option>';
                                            }
   
                                        }
                                    
                                    echo '</optgroup>';
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
                        <label for="superficie">Superficie (en m2):</label>
                        <input type="number" class="form-control" id="superficie" name="superficie" placeholder="Superficie" value="<?php echo $superficie; ?>">
                        <span class="help-inline"><?php echo $superficieError; ?></span>
                    </div>
        
                <br>
                <div class="form-actions">
                    <button type="submit" class="btn btn-warning"><span class="glyphicon glyphicon-eur"></span> Estimer</button>
                    <a class="btn btn-primary" href="../index.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
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
                <?php echo "Loyer hors charges éstimé: <br><br>" .  $loyer . " €"; ?>
                </div>

                </div>
        
        </div>
    </div>

    </body>
</html>