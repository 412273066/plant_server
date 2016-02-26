<?php if (!defined('THINK_PATH')) exit();?><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php $_RANGE_VAR_=explode(',',"1,10");if($id>= $_RANGE_VAR_[0] && $id<= $_RANGE_VAR_[1]):?>范围内
    <?php else: ?>
    范围外<?php endif; ?>
</body>