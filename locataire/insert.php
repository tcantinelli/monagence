<?php
require '../database.php';

$nomError = $prenomError = $telephoneError = $dateNaissanceError = $logementIDError = $nom = $prenom = $telephone = $dateNaissance = $logementID = "";

if(!empty($_GET['id'])) {
    $logementID = checkInput($_GET['id']);
}

if(!empty($_POST)) {
    
    $nom = checkInput($_POST['nom']);
    $prenom = checkInput($_POST['prenom']);
    $telephone = checkInput($_POST['telephone']);
    $dateNaissance = checkInput($_POST['dateNaissance']);
    $logementID = checkInput($_POST['logementID']);
    $isSuccess = true;
    
    if(empty($nom)) {
        $nomError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    
    if(empty($prenom)) {
        $prenomError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }

    if(empty($telephone)) {
        $telephoneError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    
    if(empty($dateNaissance)) {
        $dateNaissanceError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    
    if(empty($logementID)) {
        $logementIDError = "Ce champ ne peut etre vide";
        $isSuccess = false;
    }
    
    if($isSuccess) {
        
        $idDispo = true;
        
        $db = Database::connect();
            
        foreach($db->query('SELECT ID FROM logement WHERE locataireID IS NOT NULL') as $row) {
            if($logementID == $row['ID']) {
                $logementIDError = "Ce logement est déjà attribué";
                $idDispo = false;
            }

        }
        Database::disconnect();
        
        if($idDispo) {
            $db = Database::connect();
            $statement = $db->prepare("INSERT INTO locataire (nom,prenom,telephone,dateNaissance,logementID) VALUES(?, ?, ?, ?, ?)");
            $statement->execute(array($nom,$prenom,$telephone,$dateNaissance,$logementID));
            Database::disconnect();
            header("Location: ../index.php");
        }
    }

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
                                    
                <h1><strong>Ajouter un locataire</strong></h1>
                <br>
                <form class="form" role="form" action="insert.php" method="post">
                    <div class="form-group">
                        <label for="nom">Nom:</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" value="<?php echo $nom; ?>">
                        <span class="help-inline"><?php echo $nomError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prenom:</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prenom" value="<?php echo $prenom; ?>">
                        <span class="help-inline"><?php echo $prenomError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="telephone">Telephone:</label>
                        <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Telephone" value="<?php echo $telephone; ?>">
                        <span class="help-inline"><?php echo $telephoneError; ?></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="dateNaissance">Date de naissance:</label>
                        <input type="date" class="form-control" id="dateNaissance" name="dateNaissance" placeholder="Date de naissance" value="<?php echo $dateNaissance; ?>">
                        <span class="help-inline"><?php echo $dateNaissanceError; ?></span>
                    </div>
                
                    <div class="form-group">
                        <label for="logementID">ID logement:</label>
                        <input type="number" class="form-control" id="logementID" name="logementID" placeholder="ID logement" value="<?php echo $logementID; ?>">
                        <span class="help-inline"><?php echo $logementIDError; ?></span>
                    </div>
                <br>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Ajouter</button>
                    <a class="btn btn-primary" href="../index.php"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
                </div>
                </form>
            </div>
        
        </div>
        </div>
    </body>
</html>