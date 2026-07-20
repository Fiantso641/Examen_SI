<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Gestion des Types d'Opérations</h2>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Ajouter un type d'opération</h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('operator/operations') ?>">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" class="form-control" id="code" name="code" required placeholder="PAYMENT">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Paiement">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Types d'opérations existants</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($operations as $operation): ?>
                            <tr>
                                <td><?= $operation['code'] ?></td>
                                <td><?= $operation['name'] ?></td>
                                <td><?= $operation['description'] ?></td>
                                <td><?= ucfirst($operation['status']) ?></td>
                                <td>
                                    <a href="<?= base_url('operator/operations/edit/' . $operation['id']) ?>" class="btn btn-warning btn-sm me-1">Modifier</a>
                                    <form method="post" action="<?= base_url('operator/operations') ?>" class="d-inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $operation['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Désactiver</button>
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
