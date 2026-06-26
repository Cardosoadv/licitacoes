<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Perfil<?= $this->endSection() ?>

<?= $this->section('main') ?>
    <h2 class="auth-title">Perfil</h2>

    <div class="card p-4">
        <p><strong>Nome:</strong> <?= esc($user->username ?? $user->name ?? '—') ?></p>
        <p><strong>Email:</strong> <?= esc($user->email ?? '—') ?></p>
        <?php if (isset($user->last_active)) : ?>
            <p><strong>Último acesso:</strong> <?= esc($user->last_active) ?></p>
        <?php endif ?>

        <div class="mt-3">
            <a href="<?= route_to('logout') ?>" class="btn btn-secondary">Logout</a>
        </div>
    </div>
<?= $this->endSection() ?>
