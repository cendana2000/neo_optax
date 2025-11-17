<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title><?= $page_title ?></title>
    <?php foreach ($css_plugin as $key => $val) : ?>
        <link rel="stylesheet" href="<?= $val ?>">
    <?php endforeach ?>

    <script src="<?= base_url('assets/plugin/jquery/jquery.min.js') ?>"></script>
</head>

<body>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <?= $side_bar ?>

            <?= $content ?>
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->


    <?php foreach ($js_plugin as $key => $val) : ?>
        <script src="<?= $val ?>"></script>
    <?php endforeach ?>

    <?= $javascript; ?>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>