<?php use CodeIgniter\Shield\Entities\User; ?>
<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.email2FATitle') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

    <h2 class="auth-title"><?= lang('Auth.email2FATitle') ?></h2>

    <?php if (session('error')) : ?>
        <div class="alert alert-custom alert-danger-custom" role="alert"><?= esc(session('error')) ?></div>
    <?php endif ?>

    <p class="fs-14 text-muted fw-700 text-center mb-4"><?= lang('Auth.confirmEmailAddress') ?></p>

    <form action="<?= url_to('auth-action-handle') ?>" method="post">
        <?= csrf_field() ?>

        <!-- Email -->
        <div class="form-group-custom">
            <label for="emailInput"><?= lang('Auth.email') ?></label>
            <input type="email" class="input-custom" id="emailInput" name="email"
                inputmode="email" autocomplete="email" placeholder="exemplo@licitacoes.com.br"
                <?php /** @var User $user */ ?>
                value="<?= old('email', $user->email) ?>" required>
        </div>

        <button type="submit" class="btn btn-auth"><?= lang('Auth.send') ?></button>
    </form>

<?= $this->endSection() ?>
