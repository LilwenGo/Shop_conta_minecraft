<section class="section-purple section-flex-y">
    <h1>Gestion des ventes</h1>
    <p class="text-s">Bienvenue dans la gestion de ventes. Ici vous pouvez ajouter/modifier/supprimer les ventes.</p>
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Fournisseur</th>
                <th>Quantité</th>
                <th>Remboursé</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach($solds as $sold) {?>
                <tr id="sold<?= $sold->getId_item();?>,<?= $sold->getId_membre();?>">
                    <td><?= $sold->getItem();?></td>
                    <td><?= $sold->getMembre();?></td>
                    <td id="quantity<?= $sold->getId_item();?>,<?= $sold->getId_membre();?>"><?= $sold->getQuantity();?></td>
                    <td id="refunded<?= $sold->getId_item();?>,<?= $sold->getId_membre();?>"><?= $sold->getRefunded();?></td>
                    <td>
                        <div>
                            <button class="btn-yellow text-xs" onclick="editSold(<?= $sold->getId_item();?>,<?= $sold->getId_membre();?>)" id="editButton<?= $sold->getId_item();?>,<?= $sold->getId_membre();?>">Modifier</button>
                            <button class="btn-red text-xs" onclick="deleteSold(<?= $sold->getId_item();?>,<?= $sold->getId_membre();?>)"><img src="/img/trash.png" alt="Icone poubelle" class="icon-img"></button>
                        </div>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>
    <h2>Ajouter</h2>
    <form action="" method="post" class="form" enctype="multipart/form-data">
        <label for="item">Item:</label>
        <select name="item" id="item" class="input">
            <?php foreach($items as $item) {?>
                <option value="<?= $item->getId();?>"><?= escape($item->getLibelle());?></option>
            <?php }?>
        </select>
        <span class="error text-xs"></span>
        <label for="membre">Membre:</label>
        <select name="membre" id="membre" class="input">
            <?php foreach($membres as $membre) {?>
                <option value="<?= $membre->getId();?>"><?= escape($membre->getName());?></option>
            <?php }?>
        </select>
        <span class="error text-xs"></span>
        <label for="quantity">Quantité:</label>
        <input type="number" name="quantity" id="quantity" class="input" autocomplete="off" value="0">
        <span class="error text-xs"></span>
        <label for="refunded">Remboursé:</label>
        <input type="number" name="refunded" id="refunded" class="input" autocomplete="off" value="0">
        <span class="error text-xs"></span>
        <input type="submit" value="Valider" class="btn-blue">
    </form>
</section>
<script src="/js/sold.js"></script>