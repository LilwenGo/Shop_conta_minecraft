<section class="section-purple section-flex-y">
    <h1>Gestion des items</h1>
    <p class="text-s">Bienvenue dans la gestion de items. Ici vous pouvez ajouter/modifier/supprimer les items. (La catégorie ne se change que dans le détail de l'item)</p>
    <table class="table">
        <thead>
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
            foreach($items as $item) {?>
                <tr id="item<?= $item->getId();?>">
                    <td><?= $item->getId();?></td>
                    <td id="libelle<?= $item->getId();?>"><?= escape($item->getLibelle());?></td>
                    <td id="category<?= $item->getId();?>"><?= escape($item->getCategory());?></td>
                    <td id="price<?= $item->getId();?>"><?= $item->getPrice();?></td>
                    <td id="total_selled<?= $item->getId();?>"><?= $item->getTotal_selled();?></td>
                    <td>
                        <div>
                            <a class="btn-purple text-xs" href="/items/<?= $item->getId();?>">Voir</a>
                            <button class="btn-yellow text-xs" onclick="editItem(<?= $item->getId();?>)" id="editButton<?= $item->getId();?>">Modifier</button>
                            <button class="btn-red text-xs" onclick="deleteItem(<?= $item->getId();?>)"><img src="/img/trash.png" alt="Icone poubelle" class="icon-img"></button>
                        </div>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>
    <h2>Ajouter</h2>
    <form action="" method="post" class="form" enctype="multipart/form-data">
        <label for="libelle">Nom:</label>
        <input type="text" name="libelle" id="libelle" class="input" autocomplete="off">
        <span class="error text-xs"></span>
        <label for="category">Catégorie:</label>
        <select name="category" id="category" class="input">
            <?php foreach($categories as $category) {?>
                <option value="<?= $category->getId();?>"><?= escape($category->getLibelle());?></option>
            <?php }?>
        </select>
        <span class="error text-xs"></span>
        <label for="price">Prix:</label>
        <input type="number" name="price" id="price" class="input" autocomplete="off">
        <span class="error text-xs"></span>
        <label for="total_selled">Total vendu:</label>
        <input type="number" name="total_selled" id="total_selled" class="input" autocomplete="off">
        <span class="error text-xs"></span>
        <input type="submit" value="Valider" class="btn-blue">
    </form>
</section>
<script src="/js/Item/index.js"></script>