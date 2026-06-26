<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.useMagicLink') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

    <h2 class="auth-title"><?= lang('Auth.useMagicLink') ?></h2>

    <div class="text-center p-3">
        <div class="mb-4" style="font-size: 50px;">📧</div>
        <p class="fw-800 text-dark mb-2"><?= lang('Auth.checkYourEmail') ?></p>
        <p class="fs-14 text-muted fw-700"><?= lang('Auth.magicLinkDetails', [setting('Auth.magicLinkLifetime') / 60]) ?></p>
    </div>

    <div class="mt-4 text-center">
        <p class="mb-0 fs-13 fw-700 text-muted"><a href="<?= url_to('login') ?>" class="text-pink fw-800"><?= lang('Auth.backToLogin') ?></a></p>
    </div>

<?= $this->endSection() ?>
