<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.useMagicLink') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>

    <h2 class="auth-title"><?= lang('Auth.useMagicLink') ?></h2>

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

    <form action="<?= url_to('magic-link') ?>" method="post">
        <?= csrf_field() ?>

        <!-- Email -->
        <div class="form-group-custom">
            <label for="floatingEmailInput"><?= lang('Auth.email') ?></label>
            <input type="email" class="input-custom" id="floatingEmailInput" name="email" autocomplete="email" placeholder="exemplo@licitacoes.com.br" value="<?= old('email', auth()->user()->email ?? null) ?>" required>
        </div>

        <button type="submit" class="btn btn-auth"><?= lang('Auth.send') ?></button>

        <div class="mt-4 text-center">
            <p class="mb-0 fs-13 fw-700 text-muted"><a href="<?= url_to('login') ?>" class="text-pink fw-800"><?= lang('Auth.backToLogin') ?></a></p>
        </div>
    </form>

<?= $this->endSection() ?>
