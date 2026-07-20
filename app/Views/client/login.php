<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Connexion Client</h4>
            </div>
            <div class="card-body">
                <form method="post" action="<?= base_url('client/login') ?>">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Numéro de téléphone</label>
                        <input type="text" class="form-control" id="phone" name="phone" required placeholder="0333537214" value="0333537214">
                        <small class="text-muted">Préfixes acceptés: 033, 037</small>
                    </div>
                    <div class="mb-3">
                        <label for="pin" class="form-label">PIN</label>
                        <input type="password" class="form-control" id="pin" name="pin" required placeholder="1234">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
