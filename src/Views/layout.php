<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shop compta Minecraft</title>
    <script src="https://kit.fontawesome.com/c1d0ab37d6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/style/style.css">
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
</head>
<body>
    <header>
        <a href="/" class="logo"><img src="/img/logo.png" alt="Logo du site"></a>
        <nav>
            <a href="/" class="icon" title="Accueil"><i class="fas fa-home"></i></a>
            <?php
                if (isset($_SESSION["team"])) {
            ?>
                    <?php
                        if (isset($_SESSION["admin"])) {
                    ?>
                        <a href="/items" class="icon" title="Gestion items"><i class="fas fa-suitcase"></i></a>
                        <a href="/categories" class="icon" title="Gestion catégories"><i class="fas fa-tags"></i></a>
                        <a href="/membres" class="icon" title="Gestion membres"><i class="fas fa-users-cog"></i></a>
                        <a href="/admins" class="icon" title="Gestion administrateurs"><i class="fas fa-user-shield"></i></a>
                        <a href="/admins/logout" class="icon" title="Quiter mode administration"><i class="fas fa-user-slash"></i></a>
                    <?php
                        } else {
                    ?>
                        <a href="/admins/login" class="icon" title="Mode administration"><i class="fas fa-user-tie"></i></a>
                    <?php
                        }
                    ?>
                <a href="/team/logout" class="icon" title="Se deconnecter"><i class="fas fa-power-off"></i></a>
            <?php
                } else {
            ?>
                <a href="/team/login" class="icon" title="Se connecter"><i class="fas fa-users"></i></a>
            <?php
                }
            ?>
        </nav>
    </header>

    <main>
        <?php echo $content; ?>
    </main>
</body>
</html>
<?php
unset($_SESSION['error']);
unset($_SESSION['old']);
