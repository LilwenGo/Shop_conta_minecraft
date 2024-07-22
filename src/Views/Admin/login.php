<section class="section-purple section-flex-y">
    <h1>Se connecter</h1>
    <form class="form" action="/admins/login/attempt" method="post" enctype="multipart/form-data">
        <label for="name" class="text-s">Nom d'administrateur:</label>
        <input type="text" name="name" id="name" value="<?= old('name');?>" class="input" autocomplete="off">
        <span class="error text-xs"><?= error('name');?></span>
        <label for="password" class="text-s">Mot de passe:</label>
        <input type="password" name="password" id="password" value="<?= old('password');?>" class="input">
        <span class="error text-xs"><?= error('password');?></span>
        <input type="submit" value="Valider" name="ok" class="btn-blue">
    </form>
</section>