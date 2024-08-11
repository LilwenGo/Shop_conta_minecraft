<section class="section-purple section-flex-y">
    <span class="important"><?= old('message');?></span>
    <h1><?= $item->getLibelle();?></h1>
    <?php if(isset($_SESSION['admin'])) {?>
        <label for="category" class="text-s">Catégorie:</label>
        <select name="category" id="category" class="input">
        <?php foreach($categories as $category) {?>
            <option value="<?= $category->getId();?>" <?php if(escape($category->getLibelle()) === escape($item->getCategory())) {echo 'selected';}?>><?= escape($category->getLibelle());?></option>
        <?php }?>
        </select>
        <span class="error text-xs"></span>
        <button class="btn-yellow text-xs" onclick="updateCategory(<?= $item->getId();?>)">Changer</button>
    <?php } else {?>
        <h2>Catégorie: <?= $item->getCategory();?></h2>
    <?php }?>
    <table class="table">
        <thead>
            <tr>
                <th colspan="3">Transactions</th>
            </tr>
            <tr>
                <th>Membre</th>
                <th>Quantité</th>
                <th>Remboursé</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($item->getSolds() as $sold) {?>
            <tr id="sold,<?= $sold->getId_item();?>,<?= $sold->getId_membre();?>">
                <td><?= $sold->getMembre();?></td>
                <td><?= $sold->getQuantity();?></td>
                <td><?= $sold->getRefunded();?></td>
            </tr>
        <?php }?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">
                    <a href="/solds/item/<?= $item->getId();?>/index" class="btn-yellow text-xs">Modifier</a>
                </td>
            </tr>
        </tfoot>
    </table>
    <p class="text-s">Prix: <?= $item->getPrice();?></p>
    <p class="text-s">Total vendu: <?= $item->getTotal_selled();?></p>
</section>
<script src="/js/Item/show.js"></script>