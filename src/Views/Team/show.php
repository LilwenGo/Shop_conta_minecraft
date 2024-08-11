<section class="section-purple section-flex-y">
    <span class="important"><?= old('message');?></span>
    <h1><?= $team->getLogin();?></h1>
    <table class="table">
        <thead>
            <tr>
                <th colspan="2">Administrateurs</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Nom</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach($team->getAdmins() as $admin) {?>
                <tr id="admin<?= $admin->getId();?>">
                    <td><?= $admin->getId();?></td>
                    <td><?= $admin->getLogin();?></td>
                </tr>
            <?php }?>
        </tbody>
        <?php if(isset($_SESSION['admin'])) {?>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <a href="/admins" class="btn-yellow text-xs">Modifier</a>
                    </td>
                </tr>
            </tfoot>
        <?php }?>
    </table>
    <table class="table">
        <thead>
            <tr>
                <th colspan="3">Membres</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($team->getMembres() as $membre) {?>
                <tr id="membre<?= $admin->getId();?>">
                    <td><?= $membre->getId();?></td>
                    <td><?= $membre->getName();?></td>
                    <td><a href="/membres/<?= $membre->getId();?>" class="btn-purple text-xs">Voir</a></td>
                </tr>
            <?php }?>
        </tbody>
        <?php if(isset($_SESSION['admin'])) {?>
            <tfoot>
                <tr>
                    <td colspan="3">
                        <a href="/membres" class="btn-yellow text-xs">Modifier</a>
                    </td>
                </tr>
            </tfoot>
        <?php }?>
    </table>
    <table class="table">
        <thead>
            <tr>
                <th colspan="3">Catégories</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Libelle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($team->getCategories() as $category) {?>
                <tr id="category<?= $admin->getId();?>">
                    <td><?= $category->getId();?></td>
                    <td><?= escape($category->getLibelle());?></td>
                    <td><a href="/categories/<?= $category->getId();?>" class="btn-purple text-xs">Voir</a></td>
                </tr>
            <?php }?>
        </tbody>
        <?php if(isset($_SESSION['admin'])) {?>
            <tfoot>
                <tr>
                    <td colspan="3">
                        <a href="/categories" class="btn-yellow text-xs">Modifier</a>
                    </td>
                </tr>
            </tfoot>
        <?php }?>
    </table>
</section>