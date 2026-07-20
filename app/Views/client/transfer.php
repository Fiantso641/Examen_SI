<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Transfert d'argent</h4>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('index.php/client/transfer') ?>">
                    <div class="mb-3">
                        <label for="recipient_phone" class="form-label">Numéro du destinataire</label>
                        <input type="text" class="form-control" id="recipient_phone" name="recipient_phone" required placeholder="03334000002">
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Montant (Ar)</label>
                        <input type="number" class="form-control" id="amount" name="amount" required min="1" step="0.01">
                    </div>
                    <div class="alert alert-info">
                        <strong>Barème des frais de transfert:</strong><br>
                        0 - 1 000 Ar: 100 Ar<br>
                        1 001 - 5 000 Ar: 200 Ar<br>
                        5 001 - 10 000 Ar: 400 Ar<br>
                        10 001 - 50 000 Ar: 800 Ar<br>
                        Plus de 50 000 Ar: 1%
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Effectuer le transfert</button>
                    <a href="<?= base_url('index.php/client/dashboard') ?>" class="btn btn-link w-100">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
