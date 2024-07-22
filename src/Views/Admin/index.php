<section class="section-purple section-flex-y">
    <h1>Gestion administrateur</h1>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach($admins as $admin) {?>
                <tr id="admin<?= $admin->getId();?>">
                    <td><?= $admin->getId();?></td>
                    <td><?= $admin->getLogin();?></td>
                    <td>
                        <div>
                            <button class="btn-yellow text-xs" onclick="editAdmin(<?= $admin->getId();?>)">Modifier</button>
                            <button class="btn-red text-xs" onclick="deleteAdmin(<?= $admin->getId();?>)"><img src="/img/trash.png" alt="Icone poubelle" class="icon-img"></button>
                        </div>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>
    <h2>Ajouter</h2>
    <form action="" method="post" class="form" enctype="multipart/form-data">
        <label for="name">Nom:</label>
        <input type="text" name="name" id="name" class="input" autocomplete="off">
        <span class="error text-xs"></span>
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password" class="input" autocomplete="current-password">
        <span class="error text-xs"></span>
        <input type="submit" value="Valider" class="btn-blue">
    </form>
</section>
<script src="/js/admin.js"></script>