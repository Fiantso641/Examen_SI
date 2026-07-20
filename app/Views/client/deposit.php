<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Dépôt d'argent</h4>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('client/deposit') ?>">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Montant (Ar)</label>
                        <input type="number" class="form-control" id="amount" name="amount" required min="1" step="0.01">
                    </div>
                    <div class="alert alert-info">
                        <strong>Note:</strong> Le dépôt est gratuit (0 Ar de frais)
                    </div>
                    <button type="submit" class="btn btn-success w-100">Effectuer le dépôt</button>
                    <a href="<?= base_url('client/dashboard') ?>" class="btn btn-link w-100">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
