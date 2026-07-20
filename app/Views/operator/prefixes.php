<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Gestion des Préfixes</h2>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Ajouter un préfixe</h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('index.php/operator/prefixes') ?>">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label for="prefix" class="form-label">Préfixe</label>
                        <input type="text" class="form-control" id="prefix" name="prefix" required placeholder="034">
                    </div>
                    <div class="mb-3">
                        <label for="operator_name" class="form-label">Opérateur</label>
                        <input type="text" class="form-control" id="operator_name" name="operator_name" required placeholder="Orange">
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Préfixes existants</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Préfixe</th>
                            <th>Opérateur</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($prefixes as $prefix): ?>
                            <tr>
                                <td><?= $prefix['prefix'] ?></td>
                                <td><?= $prefix['operator_name'] ?></td>
                                <td>
                                    <form method="post" action="<?= base_url('index.php/operator/prefixes') ?>" class="d-inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $prefix['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
