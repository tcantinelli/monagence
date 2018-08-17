<?php
    require '../database.php';

    if(!empty($_GET['id'])) {
        
        $id = checkInput($_GET['id']);
        
        $db = Database::connect();
        $statement = $db->prepare('SELECT locataireID FROM logement WHERE ID = ?');
        $statement->execute(array($id));
        $item = $statement->fetch();
        Database::disconnect();
        $locID = $item['locataireID'];
    }

    if(!empty($_POST)) {
        $id = checkInput($_POST['id']);
        
        $db = Database::connect();
        $statement = $db->prepare("DELETE FROM logement WHERE ID = ?");
        $statement->execute(array($id));
        Database::disconnect();
        header("location: ../index.php#2");
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
                                    
                <h1><strong>Supprimer le logement n° <?php echo $id; ?></strong></h1>
                <br>
                <form class="form" role="form" action="delete.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $id;?>"/>
                    <input type="hidden" name="locID" value="<?php echo $locID;?>"/>
                    <p class="alert alert-warning">Etes vous sûr de vouloir supprimer  ?
                    
                        <?php
                            if(!empty($locID)) {
                                
                                echo "<br>";
                                echo "Le locataire n° " . $locID . " sera également supprimé";  
                            }
                        ?>
                        
                    </p>

                <br>
                <div class="form-actions">
                    <button type="submit" class="btn btn-warning">Oui</button>
                    <a class="btn btn-default" href="../index.php">Non</a>
                </div>
                </form>
            </div>
        
        </div>

    </body>
</html>