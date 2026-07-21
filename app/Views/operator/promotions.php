<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Gestion des Promotions par Opérateur</h2>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Ajouter une promotion</h5>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('operator/promotions') ?>">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label for="operator_name" class="form-label">Nom de l'opérateur</label>
                        <input type="text" class="form-control" id="operator_name" name="operator_name" required placeholder="Ex: Orange, Airtel, Telma">
                    </div>
                    <div class="mb-3">
                        <label for="discount_percentage" class="form-label">Pourcentage de réduction (%)</label>
                        <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" required min="0" max="100" step="0.01" placeholder="Ex: 10.5">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description de la promotion..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Date de début (optionnel)</label>
                        <input type="datetime-local" class="form-control" id="start_date" name="start_date">
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Date de fin (optionnel)</label>
                        <input type="datetime-local" class="form-control" id="end_date" name="end_date">
                    </div>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Promotions existantes</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Opérateur</th>
                            <th>Réduction</th>
                            <th>Dates</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($promotions)): ?>
                            <?php foreach($promotions as $promotion): ?>
                                <tr>
                                    <td><?= htmlspecialchars($promotion['operator_name']) ?></td>
                                    <td><?= number_format($promotion['discount_percentage'], 2) ?>%</td>
                                    <td>
                                        <?php if($promotion['start_date']): ?>
                                            <?= date('d/m/Y H:i', strtotime($promotion['start_date'])) ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                        <?php if($promotion['end_date']): ?>
                                            <br>→ <?= date('d/m/Y H:i', strtotime($promotion['end_date'])) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $promotion['status'] === 'active' ? 'success' : 'secondary' ?>">
                                            <?= $promotion['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= base_url('operator/promotions/edit/' . $promotion['id']) ?>" class="btn btn-warning btn-sm me-1">Modifier</a>
                                        <form method="post" action="<?= base_url('operator/promotions') ?>" class="d-inline">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $promotion['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette promotion ?')">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Aucune promotion enregistrée</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
