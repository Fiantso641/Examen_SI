<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Modifier le Préfixe</h2>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Modifier le préfixe</h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('operator/prefixes/edit/' . $prefix['id']) ?>">
                    <div class="mb-3">
                        <label for="prefix" class="form-label">Préfixe</label>
                        <input type="text" class="form-control" id="prefix" name="prefix" required value="<?= esc($prefix['prefix']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="operator_name" class="form-label">Opérateur</label>
                        <input type="text" class="form-control" id="operator_name" name="operator_name" required value="<?= esc($prefix['operator_name']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" <?= $prefix['status'] === 'active' ? 'selected' : '' ?>>Actif</option>
                            <option value="inactive" <?= $prefix['status'] === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="<?= base_url('operator/prefixes') ?>" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
