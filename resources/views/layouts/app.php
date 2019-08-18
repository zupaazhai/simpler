<!DOCTYPE html>
<html lang="en" style="height: auto;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo asset('css/adminlte.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo asset('css/toastr.min.css') ?>">
    <link rel="stylesheet" href="<?php echo asset('css/app.css') ?>">
    <title><?php echo empty($title) ? '' : $title ?></title>
</head>
<body class="layout-fixed hold-transition <?php echo empty($bodyClass) ? '' : $bodyClass ?>" style="height: auto;">
    
    <div class="wrapper">
        <?php partial('nav') ?>
        <?php partial('sidebar') ?>

        <div class="content-wrapper">
            <?php echo $content ?>
        </div>
    </div>

    <script src="<?php echo asset('js/jquery.min.js') ?>"></script>
    <script src="<?php echo asset('js/bootstrap.min.js') ?>"></script>
    <script src="<?php echo asset('js/adminlte.min.js') ?>"></script>
    <script src="<?php echo asset('js/bootbox.min.js') ?>"></script>
    <script src="<?php echo asset('js/toastr.min.js') ?>"></script>
    <script>
        <?php
            $flashStatus = bind('status');
            if ($flashStatus):
        ?>
        toastr.options = {
            positionClass: "toast-top-right"
        }
        toastr.<?php __($flashStatus['status']) ?>("<?php __($flashStatus['message']) ?>")
        <?php endif; ?>
    </script>
    <?php echo !empty($scripts) ? $scripts : '' ?>
</body>
</html>