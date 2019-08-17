<!DOCTYPE html>
<html lang="en" style="height: auto;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
    <title><?php echo empty($title) ? '' : $title ?></title>
</head>
<body class="layout-fixed hold-transition <?php echo empty($bodyClass) ? '' : $bodyClass ?>" style="height: auto;">
    <?php echo $content ?>

    <script src="<?php echo asset('js/jquery.min.js') ?>"></script>
    <script src="<?php echo asset('js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo asset('js/adminlte.min.js') ?>"></script>
</body>
</html>