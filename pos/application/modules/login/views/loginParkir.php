<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mengalihkan ke aplikasi parkir</title>
</head>
<body>
  <body onload="document.forms[0].submit()">
        <form action="<?= $_ENV['PARKIR_URL'].'auth/' ?>" method="post">
            <?php foreach( $_POST as $key => $val ): ?>
                <input type="hidden" name="<?= htmlspecialchars($key, ENT_COMPAT, 'UTF-8') ?>" value="<?= htmlspecialchars($val, ENT_COMPAT, 'UTF-8') ?>">
            <?php endforeach; ?>
        </form>
    </body>
</body>
</html>