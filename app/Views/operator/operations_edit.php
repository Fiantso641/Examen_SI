<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Modifier le Type d'Opération</h2>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Modifier le type d'opération</h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('operator/operations/edit/' . $operation['id']) ?>">
                    <div class="mb-3">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" class="form-control" id="code" name="code" required value="<?= esc($operation['code']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="name" name="name" required value="<?= esc($operation['name']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= esc($operation['description']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" <?= $operation['status'] === 'active' ? 'selected' : '' ?>>Actif</option>
                            <option value="inactive" <?= $operation['status'] === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="<?= base_url('operator/operations') ?>" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
