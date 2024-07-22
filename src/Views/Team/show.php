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
                <th colspan="2">Membres</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Nom</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($team->getMembres() as $membre) {?>
                <tr id="mambre<?= $admin->getId();?>">
                    <td><?= $membre->getId();?></td>
                    <td><?= $membre->getName();?></td>
                </tr>
            <?php }?>
        </tbody>
        <?php if(isset($_SESSION['admin'])) {?>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <a href="/membres" class="btn-yellow text-xs">Modifier</a>
                    </td>
                </tr>
            </tfoot>
        <?php }?>
    </table>
</section>