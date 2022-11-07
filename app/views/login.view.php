<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once '../includes/head.php' ?>
    <link rel="stylesheet" href="/css/auth-login.css">
    <title>Connexion</title>
</head>
<body>
    <div class="container">
        <?php require_once '../includes/header.php' ?>
        <div class="content">
        <div class="block p-20 form-container">
                <h1>Connexion</h1>
                <form action="/login" method="POST">
                    
                    <div class="form-control">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="<?= $email ?? '' ?>">
                        <?php if ($errors['email']) : ?>
                            <p class="text-danger"><?= $errors['email'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-control">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password">
                        <?php if ($errors['password']) : ?>
                            <p class="text-danger"><?= $errors['password'] ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-actions">
                        <a href="/" class="btn btn-secondary" type="button">Annuler</a>
                        <button class="btn btn-primary" type="submit">Connexion</button>
                    </div>

                </form>
            </div>
        </div>
        <?php require_once '../includes/footer.php' ?>
    </div>

</body>

</html>