<section class="section-purple section-flex-y">
    <h1>Gestion des membres</h1>
    <p class="text-s">Bienvenue dans la gestion de membres. Ici vous pouvez ajouter/modifier/supprimer les membres. (On ne peut pas se connecter en tant que membre)</p>
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
            foreach($membres as $membre) {?>
                <tr id="membre<?= $membre->getId();?>">
                    <td><?= $membre->getId();?></td>
                    <td id="name<?= $membre->getId();?>"><?= $membre->getName();?></td>
                    <td>
                        <div>
                            <a href="/membres/<?= $membre->getId();?>" class="btn-purple text-xs">Voir</a>
                            <button class="btn-yellow text-xs" onclick="editMembre(<?= $membre->getId();?>)" id="editButton<?= $membre->getId();?>">Modifier</button>
                            <button class="btn-red text-xs" onclick="deleteMembre(<?= $membre->getId();?>)"><img src="/img/trash.png" alt="Icone poubelle" class="icon-img"></button>
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
        <input type="submit" value="Valider" class="btn-blue">
    </form>
</section>
<script src="/js/membre.js"></script>