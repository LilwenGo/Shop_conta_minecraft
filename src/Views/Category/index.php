<section class="section-purple section-flex-y">
    <h1>Gestion des catégories</h1>
    <p class="text-s">Bienvenue dans la gestion de catégories. Ici vous pouvez ajouter/modifier/supprimer les catégories.</p>
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
            foreach($categories as $category) {?>
                <tr id="category<?= $category->getId();?>">
                    <td><?= $category->getId();?></td>
                    <td id="libelle<?= $category->getId();?>"><?= escape($category->getLibelle());?></td>
                    <td>
                        <div>
                            <a class="btn-purple text-xs" href="/categories/<?= $category->getId();?>">Voir</a>
                            <button class="btn-yellow text-xs" onclick="editCategory(<?= $category->getId();?>)" id="editButton<?= $category->getId();?>">Modifier</button>
                            <button class="btn-red text-xs" onclick="deleteCategory(<?= $category->getId();?>)"><img src="/img/trash.png" alt="Icone poubelle" class="icon-img"></button>
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
        <input type="submit" value="Valider" class="btn-blue">
    </form>
</section>
<script src="/js/category.js"></script>