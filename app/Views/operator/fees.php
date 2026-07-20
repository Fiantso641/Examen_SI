<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Gestion des Barèmes de Frais</h2>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Ajouter un barème de frais</h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('index.php/operator/fees') ?>">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label for="operation_type_id" class="form-label">Type d'opération</label>
                        <select class="form-select" id="operation_type_id" name="operation_type_id" required>
                            <?php foreach($operations as $operation): ?>
                                <option value="<?= $operation['id'] ?>"><?= $operation['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="min_amount" class="form-label">Montant minimum</label>
                        <input type="number" class="form-control" id="min_amount" name="min_amount" required min="0" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="max_amount" class="form-label">Montant maximum</label>
                        <input type="number" class="form-control" id="max_amount" name="max_amount" required min="0" step="0.01">
                    </div>
                    <div class="mb-3">
                        <label for="fee_amount" class="form-label">Frais fixe (Ar)</label>
                        <input type="number" class="form-control" id="fee_amount" name="fee_amount" required min="0" step="0.01" value="0">
                    </div>
                    <div class="mb-3">
                        <label for="fee_percentage" class="form-label">Pourcentage (%)</label>
                        <input type="number" class="form-control" id="fee_percentage" name="fee_percentage" required min="0" step="0.01" value="0">
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Barèmes existants</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Opération</th>
                            <th>Min</th>
                            <th>Max</th>
                            <th>Frais fixe</th>
                            <th>%</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($fee_schedules as $fee): ?>
                            <tr>
                                <td><?= $fee['operation_name'] ?></td>
                                <td><?= number_format($fee['min_amount'], 2) ?></td>
                                <td><?= number_format($fee['max_amount'], 2) ?></td>
                                <td><?= number_format($fee['fee_amount'], 2) ?> Ar</td>
                                <td><?= $fee['fee_percentage'] ?>%</td>
                                <td>
                                    <a href="<?= base_url('index.php/operator/fees/edit/' . $fee['id']) ?>" class="btn btn-warning btn-sm me-1">Modifier</a>
                                    <form method="post" action="<?= base_url('index.php/operator/fees') ?>" class="d-inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $fee['id'] ?>">
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
