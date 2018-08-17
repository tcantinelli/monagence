<?php
require '../database.php';

if(!empty($_GET['id'])) {
    $id = checkInput($_GET['id']);
}

$nomError = $prenomError = $telephoneError = $dateNaissanceError = $logementIDError = $nom = $prenom = $telephone = $dateNaissance = $logementID = "";

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

        $db = Database::connect();
        $statement = $db->prepare("UPDATE locataire SET 
        nom = ?,
        prenom = ?,
        telephone = ?,
        dateNaissance = ?,
        logementID = ?
        WHERE ID = ?
        ");
        $statement->execute(array($nom,$prenom,$telephone,$dateNaissance,$logementID,$id));
        Database::disconnect();
        header("Location: ../index.php");
    }

}else{
    
    $db = Database::connect();
    $statement = $db->prepare("SELECT * FROM locataire WHERE ID = ?");
    $statement->execute(array($id));
    $item = $statement->fetch();
    
    $nom = $item['nom'];
    $prenom = $item['prenom'];
    $telephone = $item['telephone'];
    $dateNaissance = $item['dateNaissance'];
    $logementID = $item['logementID']; 
        
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
                 <h1><strong>Modifier un locataire</strong></h1>
                <br>
                <form class="form" action="<?php echo 'update.php?id=' . $id; ?>" role="form" method="post">
                    <div class="form-group">
                        <label for="nom">Nom:</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" value="<?php echo $nom; ?>">
                        <span class="help-inline"><?php echo $nomError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Loyer HC:</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prenom" value="<?php echo $prenom; ?>">
                        <span class="help-inline"><?php echo $prenomError; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="telephone">Telephone (format XX XX XX XX XX):</label>
                        <input type="text" class="form-control" id="telephone" name="telephone" placeholder="Numero de telephone" value="<?php echo $telephone; ?>">
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