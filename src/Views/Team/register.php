<section class="section-purple section-flex-y">
    <h1>Créer une équipe</h1>
    <form class="form" action="/team/register/attempt" method="post" enctype="multipart/form-data">
        <label for="name" class="text-s">Nom d'équipe:</label>
        <input type="text" name="name" id="name" value="<?= old('name');?>" class="input" autocomplete="off">
        <span class="error text-xs"><?= error('name');?></span>
        <label for="password" class="text-s">Mot de passe:</label>
        <input type="password" name="password" id="password" value="<?= old('password');?>" class="input">
        <span class="error text-xs"><?= error('password');?></span>
        <input type="submit" value="Valider" name="ok" class="btn-blue">
    </form>
    <p>Vous avez déja une équipe ? <a href="/team/login" class="text-xs">Connectez-vous !</a></p>
</section>