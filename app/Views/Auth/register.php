<?= $this->extend('layouts/public_layout') ?>
<?= $this->section('title') ?>
Register
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<?= view('Myth\Auth\Views\_message_block') ?>
<div class="">
    <div class="row">
        <div class="col-sm-6 offset-sm-3">

            <div class="card">
                <h2 class="card-header"><?= lang('Auth.register') ?></h2>
                <div class="card-body">


                    <form action="<?= url_to('register') ?>" id="formData" method="post" class="d-grid gap-2">
                        <?= csrf_field() ?>

                        <h5>Account Information</h5>

                        <div class="row row-cols-2">
                            <div class="col">
                                <div class="form-element mb-3">
                                    <label for="email"><?= lang('Auth.email') ?></label>
                                    <input
                                        type="email"
                                        class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>"
                                        name="email"
                                        aria-describedby="emailHelp"
                                        placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>"
                                        data-pristine-required
                                        data-pristine-required-message="Email harus diisi!">
                                    <!-- <small id="emailHelp" class="form-text text-muted"><?= lang('Auth.weNeverShare') ?></small> -->
                                </div>

                                <div class="form-element mb-3">
                                    <label for="username"><?= lang('Auth.username') ?></label>
                                    <input type="text" class="form-control <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>"
                                        data-pristine-required
                                        data-pristine-required-message="Username harus diisi!">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-element mb-3">
                                    <label for="password"><?= lang('Auth.password') ?></label>
                                    <input type="password" name="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.password') ?>" autocomplete="off"
                                        data-pristine-required
                                        data-pristine-required-message="Password harus diisi!">
                                </div>

                                <div class="form-element mb-3">
                                    <label for="pass_confirm"><?= lang('Auth.repeatPassword') ?></label>
                                    <input type="password" name="pass_confirm" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.repeatPassword') ?>" autocomplete="off"
                                        data-pristine-required
                                        data-pristine-required-message="Konfirmasi password harus diisi!">
                                </div>
                            </div>
                        </div>


                        <!-- <div class="form-group">
                            <label for="role_group">Role</label>
                            <select class="form-select" id="role_group" name="role_group" aria-label="Default select example">
                                <option selected hidden>Select role</option>
                                <option value="administrator">administrator</option>
                                <option value="product_manager">product_manager</option>
                                <option value="customer">customer</option>
                            </select>
                        </div> -->

                        <br>

                        <button type="submit" class="btn custom-primary btn-block"><?= lang('Auth.register') ?></button>
                    </form>


                    <hr>

                    <p><?= lang('Auth.alreadyRegistered') ?> <a href="<?= url_to('login') ?>"><?= lang('Auth.signIn') ?></a></p>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let pristine;
    window.onload = function() {
        let form = document.getElementById("formData");
        var pristine = new Pristine(form, {
            classTo: 'form-element',
            errorClass: 'is-invalid',
            successClass: 'is-valid',
            errorTextParent: 'form-element',
            errorTextTag: 'div',
            errorTextClass: 'text-danger'
        });

        form.addEventListener('submit', function(e) {
            var valid = pristine.validate();
            if (!valid) {
                e.preventDefault();
            }
        });

    };
</script>
<?= $this->endSection() ?>