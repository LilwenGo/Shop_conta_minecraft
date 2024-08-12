<section class="section-purple section-flex-y">
    <span class="important"><?= old('message');?></span>
    <h1><?= $membre->getName();?></h1>
    <table class="table">
        <thead>
            <tr>
                <th colspan="3">Transactions</th>
            </tr>
            <tr>
                <th>Item</th>
                <th>Quantité</th>
                <th>Remboursé</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($membre->getSolds() as $sold) {?>
            <tr id="sold,<?= $sold->getId_item();?>,<?= $sold->getId_membre();?>">
                <td><?= $sold->getItem();?></td>
                <td><?= $sold->getQuantity();?></td>
                <td><?= $sold->getRefunded();?></td>
            </tr>
        <?php }?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    <a href="/solds/membre/<?= $membre->getId();?>/index" class="btn-yellow text-xs">Modifier</a>
                </td>
            </tr>
        </tfoot>
    </table>
    <table class="table">
        <thead>
            <tr>
                <th colspan="4">Catégories gérées</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Role</th>
                <?php if(isset($_SESSION['admin'])) {?>
                    <th>Actions</th>
                <?php }?>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach($membre->getCategories() as $category) {?>
                <tr id="category<?= $category->getId();?>">
                    <td><?= $category->getId();?></td>
                    <td><?= $category->getLibelle();?></td>
                    <td><?= $category->getRole();?></td>
                    <?php if(isset($_SESSION['admin'])) {?>
                        <td>
                            <div>
                                <button class="btn-yellow text-xs" onclick="editRole(<?= $category->getId();?>)" id="cEditButton<?= $category->getId();?>">Modifier</button>
                                <button class="btn-red text-xs" onclick="deleteRole(<?= $category->getId();?>)"><img src="/img/trash.png" alt="Icone poubelle" class="icon-img"></button>
                            </div>
                        </td>
                    <?php }?>
                </tr>
            <?php }?>
        </tbody>
    </table>
    <?php if(isset($_SESSION['admin'])) {?>
        <form class="form" action="" method="post" enctype="multipart/form-data">
            <label for="category" class="text-s">Catégorie:</label>
            <select name="category" id="category" class="input">
            <?php foreach($categories as $category) {?>
                <option value="<?= $category->getId();?>"><?= $category->getLibelle();?></option>
            <?php }?>
            </select>
            <span class="error text-xs"><?= error('category');?></span>
            <label for="role" class="text-s">Role:</label>
            <input type="text" name="role" id="role" value="<?= old('role');?>" class="input" autocomplete="off">
            <span class="error text-xs"><?= error('role');?></span>
            <input type="submit" value="Valider" name="ok" class="btn-blue">
        </form>
    <?php }?>
</section>