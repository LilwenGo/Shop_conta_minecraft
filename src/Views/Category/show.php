<section class="section-purple section-flex-y">
    <h1><?= $category->getLibelle();?></h1>
    <table class="table">
    <thead>
            <tr>
                <th colspan="6">Items</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Total vendu</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach($category->getItems() as $item) {?>
                <tr id="item<?= $item->getId();?>">
                    <td><?= $item->getId();?></td>
                    <td><?= escape($item->getLibelle());?></td>
                    <td><?= escape($item->getCategory());?></td>
                    <td><?= $item->getPrice();?></td>
                    <td><?= $item->getTotal_selled();?></td>
                    <td>
                        <div>
                            <a class="btn-purple text-xs" href="/items/<?= $item->getId();?>">Voir</a>
                        </div>
                    </td>
                </tr>
            <?php }?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">
                    <a href="/items/" class="btn-yellow text-xs">Modifier</a>
                </td>
            </tr>
        </tfoot>
    </table>
    <table class="table">
        <thead>
            <tr>
                <th colspan="4">Transactions</th>
            </tr>
            <tr>
                <th>Item</th>
                <th>Membre</th>
                <th>Quantité</th>
                <th>Remboursé</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($category->getSolds() as $sold) {?>
            <tr id="sold,<?= $sold->getId_item();?>,<?= $sold->getId_membre();?>">
                <td><?= escape($sold->getItem());?></td>
                <td><?= $sold->getMembre();?></td>
                <td><?= $sold->getQuantity();?></td>
                <td><?= $sold->getRefunded();?></td>
            </tr>
        <?php }?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">
                    <a href="/solds" class="btn-yellow text-xs">Modifier</a>
                </td>
            </tr>
        </tfoot>
    </table>
</section>