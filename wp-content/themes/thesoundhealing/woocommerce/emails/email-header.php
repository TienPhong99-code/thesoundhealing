<?php defined('ABSPATH') || exit; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo esc_html(get_bloginfo('name')); ?></title>
<style>
  body,table,td,a{-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%}
  body{margin:0;padding:0;background:#f5f3ee;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif}
  img{border:0;outline:none;text-decoration:none;display:block}
  a{color:#c2a056}
  @media only screen and (max-width:600px){
    .email-wrap{width:100%!important}
    .email-body{padding:28px 20px!important}
  }
</style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f5f3ee;padding:40px 20px 0">
  <tr>
    <td align="center">
      <table class="email-wrap" width="560" cellpadding="0" cellspacing="0" border="0" style="background:#fff;border-radius:12px 12px 0 0;overflow:hidden">

        <!-- Header -->
        <tr>
          <td style="background:#1b1c19;padding:28px 36px;text-align:center">
            <p style="margin:0 0 4px;font-size:10px;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:rgba(255,255,255,0.45)">THE SOUND</p>
            <p style="margin:0;font-size:22px;font-weight:300;letter-spacing:6px;text-transform:uppercase;color:#c2a056">HEALING</p>
          </td>
        </tr>

        <!-- Heading band -->
        <tr>
          <td style="background:#c2a056;padding:18px 36px;text-align:center">
            <h1 style="margin:0;font-size:16px;font-weight:600;letter-spacing:1px;color:#fff;text-transform:uppercase"><?php echo esc_html($email_heading); ?></h1>
          </td>
        </tr>

        <!-- Body start -->
        <tr>
          <td class="email-body" style="padding:36px 36px 0">
