<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Modifier le Barème de Frais</h2>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Modifier le barème</h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('index.php/operator/fees/edit/' . $fee_schedule['id']) ?>">
                    <div class="mb-3">
                        <label for="operation_type_id" class="form-label">Type d'opération</label>
                        <select class="form-select" id="operation_type_id" name="operation_type_id" required>
                            <?php foreach($operations as $operation): ?>
                                <option value="<?= $operation['id'] ?>" <?= $operation['id'] == $fee_schedule['operation_type_id'] ? 'selected' : '' ?>>
                                    <?= $operation['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="min_amount" class="form-label">Montant minimum</label>
                        <input type="number" class="form-control" id="min_amount" name="min_amount" required min="0" step="0.01" value="<?= $fee_schedule['min_amount'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="max_amount" class="form-label">Montant maximum</label>
                        <input type="number" class="form-control" id="max_amount" name="max_amount" required min="0" step="0.01" value="<?= $fee_schedule['max_amount'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="fee_amount" class="form-label">Frais fixe (Ar)</label>
                        <input type="number" class="form-control" id="fee_amount" name="fee_amount" required min="0" step="0.01" value="<?= $fee_schedule['fee_amount'] ?>">
                    </div>
                    <div class="mb-3">
                        <label for="fee_percentage" class="form-label">Pourcentage (%)</label>
                        <input type="number" class="form-control" id="fee_percentage" name="fee_percentage" required min="0" step="0.01" value="<?= $fee_schedule['fee_percentage'] ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="<?= base_url('index.php/operator/fees') ?>" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
