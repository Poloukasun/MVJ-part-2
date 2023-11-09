<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/create.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="js/inscription.js"></script>
    <title>Inscription</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-left">
                <h1>S'inscrire</h1>
            </div>
            <div class="header-right">
                <a href="">Déjà un compte ?</a>
            </div>
        </div>
        
        <form action="#" method="POST" class="registration-form">
            <div class="form-row">
                <div class="input-field">
                    <input type="text" placeholder="Prénom" required>
                </div>
                <div class="input-field">
                    <input type="text" placeholder="Nom de famille" required>
                </div>
            </div>
            <div class="form-row">
                <div class="input-field">
                    <input type="email" placeholder="Adresse e-mail" required>
                </div>
                <div class="input-field">
                    <input type="password" placeholder="Mot de passe" required>
                </div>
            </div>
            <div class="form-row">
                <div class="input-field">
                    <input type="date" id="birthday" name="birthday" required>
                </div>
                <div class="input-field radio-group">
                        <div class="gender" for="homme">Homme</div>
                        <div class="gender" for="femme">Femme</div>
                        <div class="gender" for="autre">Autre</div>
                </div>
            </div>
            <button type="submit" class="signup-button">Créer un compte</button>
            <div class="policy">
                <p>En cliquant sur créer un compte, vous acceptez nos <a href="">Conditions générales</a> , <a href="">Politique de 
                    confidentialité</a> et notre <a href="">Politique d’utilisation des cookies</a>. Vous recevrez peut-être des 
                    notifications par texto de notre part et vous pouvez à tout moment supprimer votre compte.
                </p>
            </div>
        </form>
    </div>
    
</body>
</html>
