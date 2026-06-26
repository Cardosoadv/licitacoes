<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.register') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

    <h2 class="auth-title"><?= lang('Auth.register') ?></h2>

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

    <form action="<?= url_to('register') ?>" method="post">
        <?= csrf_field() ?>

        <!-- Email -->
        <div class="form-group-custom">
            <label for="floatingEmailInput"><?= lang('Auth.email') ?></label>
            <input type="email" class="input-custom" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="exemplo@licitacoes.com.br" value="<?= old('email') ?>" required>
        </div>

        <!-- Username -->
        <div class="form-group-custom">
            <label for="floatingUsernameInput"><?= lang('Auth.username') ?></label>
            <input type="text" class="input-custom" id="floatingUsernameInput" name="username" inputmode="text" autocomplete="username" placeholder="SeuNome" value="<?= old('username') ?>" required>
        </div>

        <!-- Password -->
        <div class="form-group-custom">
            <label for="floatingPasswordInput"><?= lang('Auth.password') ?></label>
            <input type="password" class="input-custom" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="new-password" placeholder="••••••••" required>
        </div>

        <!-- Password (Again) -->
        <div class="form-group-custom">
            <label for="floatingPasswordConfirmInput"><?= lang('Auth.passwordConfirm') ?></label>
            <input type="password" class="input-custom" id="floatingPasswordConfirmInput" name="password_confirm" inputmode="text" autocomplete="new-password" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-auth"><?= lang('Auth.register') ?></button>

        <div class="mt-4 text-center">
            <p class="mb-0 fs-13 fw-700 text-muted"><?= lang('Auth.haveAccount') ?> <a href="<?= url_to('login') ?>" class="text-pink fw-800"><?= lang('Auth.login') ?></a></p>
        </div>
    </form>

<?= $this->endSection() ?>
