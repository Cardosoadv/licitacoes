<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.email2FATitle') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

    <h2 class="auth-title"><?= lang('Auth.emailEnterCode') ?></h2>

    <?php if (session('error') !== null) : ?>
        <div class="alert alert-custom alert-danger-custom" role="alert"><?= esc(session('error')) ?></div>
    <?php endif ?>

    <p class="fs-14 text-muted fw-700 text-center mb-4"><?= lang('Auth.emailConfirmCode') ?></p>

    <form action="<?= url_to('auth-action-verify') ?>" method="post">
        <?= csrf_field() ?>

        <!-- Code -->
        <div class="form-group-custom">
            <label for="tokenInput"><?= lang('Auth.token') ?></label>
            <input type="text" class="input-custom text-center fs-24 fw-900" style="letter-spacing: 8px;" id="tokenInput" name="token" placeholder="000000"
                inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" required>
        </div>

        <button type="submit" class="btn btn-auth"><?= lang('Auth.confirm') ?></button>
    </form>

<?= $this->endSection() ?>
