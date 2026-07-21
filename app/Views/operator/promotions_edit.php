<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Modifier la Promotion</h2>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h5>Édition de la promotion</h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('operator/promotions/edit/' . $promotion['id']) ?>">
                    <div class="mb-3">
                        <label for="operator_name" class="form-label">Nom de l'opérateur</label>
                        <input type="text" class="form-control" id="operator_name" name="operator_name" required value="<?= htmlspecialchars($promotion['operator_name']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="discount_percentage" class="form-label">Pourcentage de réduction (%)</label>
                        <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" required min="0" max="100" step="0.01" value="<?= number_format($promotion['discount_percentage'], 2) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($promotion['description'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Date de début (optionnel)</label>
                        <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="<?= $promotion['start_date'] ? date('Y-m-d\TH:i', strtotime($promotion['start_date'])) : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Date de fin (optionnel)</label>
                        <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="<?= $promotion['end_date'] ? date('Y-m-d\TH:i', strtotime($promotion['end_date'])) : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active" <?= $promotion['status'] === 'active' ? 'selected' : '' ?>>Actif</option>
                            <option value="inactive" <?= $promotion['status'] === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="<?= base_url('operator/promotions') ?>" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
