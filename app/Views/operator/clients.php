<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2>Situation des Comptes Clients</h2>

<div class="alert alert-info">
    <strong>Solde total des clients:</strong> <?= number_format($total_balance, 2) ?> Ar
</div>

<div class="card">
    <div class="card-header">
        <h5>Liste des clients</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Numéro</th>
                    <th>Nom</th>
                    <th>Solde</th>
                    <th>Statut</th>
                    <th>Date création</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($clients as $client): ?>
                    <tr>
                        <td><?= $client['id'] ?></td>
                        <td><?= $client['phone_number'] ?></td>
                        <td><?= $client['full_name'] ?></td>
                        <td><strong><?= number_format($client['balance'], 2) ?> Ar</strong></td>
                        <td>
                            <span class="badge bg-<?= $client['status'] === 'active' ? 'success' : 'danger' ?>">
                                <?= $client['status'] ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y', strtotime($client['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
