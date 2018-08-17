<?php

    require '../database.php';

    $dateNaissance = "";

    if(!empty($_GET['id'])) {
        $id = checkInput($_GET['id']);
    }

    $db = Database::connect();

    $statement = $db->prepare('SELECT * FROM locataire WHERE ID = ?');

    $statement->execute(array($id));
    $item = $statement->fetch();

    $dateNaissance = date("d/m/Y", strtotime($item['dateNaissance']));

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
                    
                    <h1><strong>Locataire n° <?php echo $item['ID']; ?></strong></h1>
                    <br>
                    <form>
                        <div class="form-group">
                            <label>Nom: </label><?php echo ' ' . $item['nom']; ?>
                        </div>
                        <div class="form-group">
                            <label>Prenom: </label><?php echo ' ' . $item['prenom']; ?>
                        </div>
                        <div class="form-group">
                            <label>Telephone: </label><?php echo ' ' . $item['telephone']; ?>
                        </div>
                        <div class="form-group">
                            <label>Date de naissance: </label><?php echo ' ' . $dateNaissance; ?>
                        </div>
                        <div class="form-group">
                            <label>ID logement: </label><?php echo ' ' . $item['logementID']; ?>
                        </div>
                    </form>
                    
                    <div class="form-actions">
                        <a class="btn btn-primary" href="../index.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
                    </div>
                </div>

                <div class="col-sm-6">
        

                </div>
        
        </div>
        
    </div>

    </body>
</html>

