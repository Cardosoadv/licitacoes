<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.login') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

    <h2 class="auth-title"><?= lang('Auth.login') ?></h2>

    <?php if (session('error') !== null) : ?>
        <div class="alert alert-custom alert-danger-custom" role="alert"><?= esc(session('error')) ?></div>
    <?php elseif (session('errors') !== null) : ?>
        <div class="alert alert-custom alert-danger-custom" role="alert">
            <?php if (is_array(session('errors'))) : ?>
                <?php foreach (session('errors') as $error) : ?>
                    <?= esc($error) ?>
                    <br>
                <?php endforeach ?>
            <?php else : ?>
                <?= esc(session('errors')) ?>
            <?php endif ?>
        </div>
    <?php endif ?>

    <?php if (session('message') !== null) : ?>
        <div class="alert alert-custom alert-success-custom" role="alert"><?= esc(session('message')) ?></div>
    <?php endif ?>

    <form action="<?= url_to('login') ?>" method="post">
        <?= csrf_field() ?>

        <!-- Email -->
        <div class="form-group-custom">
            <label for="floatingEmailInput"><?= lang('Auth.email') ?></label>
            <input type="email" class="input-custom" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="exemplo@licitacoes.com.br" value="<?= old('email') ?>" required>
        </div>

        <!-- Password -->
        <div class="form-group-custom">
            <label for="floatingPasswordInput"><?= lang('Auth.password') ?></label>
            <input type="password" class="input-custom" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="current-password" placeholder="••••••••" required>
        </div>

        <!-- Remember me -->
        <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
            <div class="form-check mb-4">
                <input type="checkbox" name="remember" class="form-check-input" id="rememberMe" <?php if (old('remember')): ?> checked<?php endif ?>>
                <label class="form-check-label fs-13 fw-700 text-mid" for="rememberMe">
                    <?= lang('Auth.rememberMe') ?>
                </label>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-auth"><?= lang('Auth.login') ?></button>

        <div class="mt-4 text-center">
            <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
                <p class="mb-1 fs-13 fw-700 text-muted"><?= lang('Auth.forgotPassword') ?> <a href="<?= url_to('magic-link') ?>" class="text-pink fw-800"><?= lang('Auth.useMagicLink') ?></a></p>
            <?php endif ?>

            <?php if (setting('Auth.allowRegistration')) : ?>
                <p class="mb-0 fs-13 fw-700 text-muted"><?= lang('Auth.needAccount') ?> <a href="<?= url_to('register') ?>" class="text-pink fw-800"><?= lang('Auth.register') ?></a></p>
            <?php endif ?>
        </div>
    </form>

<?= $this->endSection() ?>
