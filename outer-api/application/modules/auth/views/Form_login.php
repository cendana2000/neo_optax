<?php ?>
<!-- Content -->
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Register -->
            <div class="card">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center" style="flex-direction: column;">
                        <a href="<?= base_url() ?>" class="app-brand-link gap-2">
                            <span class="app-brand-text demo text-body fw-bolder text-center">API SYNCRONIZER</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <p class="mb-4">Silahkan Login Terlebih Dahulu!</p>

                    <?php if ($this->session->flashdata("info")) : ?>
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <?= $this->session->flashdata("info")["msg"]; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <form id="formAuthentication" class="mb-3" action="<?= base_url('auth/login') ?>" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Username</label>
                            <input type="text" class="form-control" id="email" name="username" placeholder="Masukkan Username" autofocus="">
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password" placeholder="Masukkan Password" aria-describedby="password">
                                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /Register -->
        </div>
    </div>
</div>