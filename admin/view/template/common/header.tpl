<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<script type="text/javascript">
    var user_access = <?php echo $access; ?>;
    var is_admin    = <?php echo $is_admin ? 'true' : 'false'; ?>;
</script>
<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/javascript/bootstrap/less/bootstrap.less" rel="stylesheet/less" />
<script src="view/javascript/bootstrap/less-1.7.4.min.js"></script>
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<script src="view/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
<link type="text/css" href="view/javascript/k.checkbox/k.checkbox.css" rel="stylesheet" media="screen" />
<?php foreach ($styles as $style) { ?>
<link type="text/css" href="<?php echo $style['href']; ?>" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="view/javascript/k.checkbox/k.checkbox.js" type="text/javascript"></script>
<script src="view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
</head>
<body>
<div id="container">
<header id="header" class="navbar navbar-static-top">
  <div class="navbar-header">
    <?php if ($logged) { ?>
    <a type="button" id="button-menu" class="pull-left"><i class="fa fa-indent fa-lg"></i></a>
    <?php } ?>
    <a href="<?php echo $home; ?>" class="navbar-brand"><img src="view/image/logo.png" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" /></a></div>
  <?php if ($logged) { ?>
  <ul class="nav pull-right">
    <?php if($is_admin) { ?>
    <li class="dropdown admin-menu"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cutlery fa-lg"> Доступы</i></a>
        <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
            <li class="dropdown-header">
                <button id="admin-activate" type="button" onclick="adminMode();" title="" class="btn btn-block btn-danger"><i class="fa fa-unlock"></i>&nbsp;Настроить</button>
                <button id="admin-save"     type="button" data-toggle="tooltip" onclick="saveUserGroupAccess();" title="" class="uacc-mode-action hidden btn btn-primary" data-original-title="Сохранить"><i class="fa fa-save"></i></button>
                <button id="admin-cancel"   type="button" data-toggle="tooltip" onclick="cancelUserGroupAccess();" title="" class="uacc-mode-action hidden btn btn-default" data-original-title="Отменить"><i class="fa fa-reply"></i></button>
            </li>
            <li class="dropdown-header uacc-mode-action hidden">Доступ:</li>
            <?php foreach($user_groups as $user_group) { ?>
            <li class="uacc-mode-action hidden">
                <label>
                    <input ug-id="<?php echo $user_group['user_group_id']; ?>" id="access-<?php echo $user_group['user_group_id']; ?>" type="hidden" class="checkbox-3-value"><?php echo $user_group['name']; ?>
                </label>
            </li>
            <?php } ?>
        </ul>
    </li>
<script type="text/javascript">
    $('.admin-menu .dropdown-menu button, .admin-menu .dropdown-menu input, .admin-menu .dropdown-menu label, .admin-menu .dropdown-menu a').click(function(e) {
        e.stopPropagation();
    });
    var uacc_html = '';
            <?php foreach($user_groups as $user_group) { ?>
    uacc_html += '<li>';
    uacc_html += '  <label>';
    uacc_html += '      <input onclick="checkCheckboxes(<?php echo $user_group['user_group_id']; ?>);" type="checkbox" name="<?php echo $user_group['user_group_id']; ?>" value="1" user-group="<?php echo $user_group['user_group_id']; ?>">&nbsp;<?php echo $user_group['name']; ?>';
    uacc_html += '  </label>';
    uacc_html += '</li>';
            <?php } ?>
    uacc_html += '';
</script>
    <?php } ?>
    <?php if(0) { ?>
    <li class="dropdown" uacc="tm-menu"><a class="dropdown-toggle" data-toggle="dropdown"><span class="label label-danger pull-left"><?php echo $alerts; ?></span> <i class="fa fa-bell fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
        <li class="dropdown-header" uacc="top-menu-order"><?php echo $text_order; ?></li>
        <li uac="top-menu-order"><a href="<?php echo $order_status; ?>" style="display: block; overflow: auto;"><span class="label label-warning pull-right"><?php echo $order_status_total; ?></span><?php echo $text_order_status; ?></a></li>
        <li uac="top-menu-order"><a href="<?php echo $complete_status; ?>"><span class="label label-success pull-right"><?php echo $complete_status_total; ?></span><?php echo $text_complete_status; ?></a></li>
        <li uac="top-menu-order"><a href="<?php echo $return; ?>"><span class="label label-danger pull-right"><?php echo $return_total; ?></span><?php echo $text_return; ?></a></li>
        <li uac="top-menu-order" class="divider"></li>
        <li class="dropdown-header"><?php echo $text_customer; ?></li>
        <li><a href="<?php echo $online; ?>"><span class="label label-success pull-right"><?php echo $online_total; ?></span><?php echo $text_online; ?></a></li>
        <li><a href="<?php echo $customer_approval; ?>"><span class="label label-danger pull-right"><?php echo $customer_total; ?></span><?php echo $text_approval; ?></a></li>
        <li class="divider"></li>
        <li class="dropdown-header"><?php echo $text_product; ?></li>
        <li><a href="<?php echo $product; ?>"><span class="label label-danger pull-right"><?php echo $product_total; ?></span><?php echo $text_stock; ?></a></li>
        <li><a href="<?php echo $review; ?>"><span class="label label-danger pull-right"><?php echo $review_total; ?></span><?php echo $text_review; ?></a></li>
        <li class="divider"></li>
        <li class="dropdown-header"><?php echo $text_affiliate; ?></li>
        <li><a href="<?php echo $affiliate_approval; ?>"><span class="label label-danger pull-right"><?php echo $affiliate_total; ?></span><?php echo $text_approval; ?></a></li>
      </ul>
    </li>
    <?php } ?>
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-life-ring fa-lg"></i></a>
      <ul class="dropdown-menu dropdown-menu-right">
        <li class="dropdown-header"><?php echo $text_store; ?> <i class="fa fa-shopping-cart"></i></li>
        <?php foreach ($stores as $store) { ?>
        <li><a href="<?php echo $store['href']; ?>" target="_blank"><?php echo $store['name']; ?></a></li>
        <?php } ?>

      </ul>
    </li>
    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown">
    <?php if ($sandbox) { ?>
       Тестовый режим <i class="fa fa-bug fa-lg"></i>
    <?php } else { ?>
        Боевой режим <i class="fa fa-child fa-lg"></i>
    <?php } ?>
    </a>
      <ul class="dropdown-menu dropdown-menu-right">
        
        
        <li><a href="<?php echo $sandbox_off; ?>">Боевой режим <i class="fa fa-child"></i></a></li>
        <li><a href="<?php echo $sandbox_on; ?>">Тестовый режим <i class="fa fa-bug"></i></a></li>
        

      </ul>
    </li>
    <li><a href="<?php echo $logout; ?>"><span class="hidden-xs hidden-sm hidden-md"><?php echo $text_logout; ?></span> <i class="fa fa-sign-out fa-lg"></i></a></li>
  </ul>
  <?php } ?>
</header>
