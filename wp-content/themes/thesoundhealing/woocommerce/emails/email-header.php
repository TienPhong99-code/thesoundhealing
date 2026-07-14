<?php defined('ABSPATH') || exit; ?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo esc_html(get_bloginfo('name')); ?></title>
  <style>
    body,
    table,
    td,
    a {
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%
    }

    body {
      margin: 0;
      padding: 0;
      background: #f5f3ee;
      font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif
    }

    img {
      border: 0;
      outline: none;
      text-decoration: none;
      display: block
    }

    a {
      color: #c2a056
    }

    p,
    td,
    th,
    span,
    div {
      word-break: break-word;
      overflow-wrap: break-word
    }

    @media only screen and (max-width:600px) {
      .email-wrap {
        width: 100% !important
      }

      .email-body {
        padding: 28px 20px !important
      }

      .tsh-qcol {
        display: block !important;
        width: 100% !important;
        box-sizing: border-box !important;
        border-right: 0 !important;
        border-bottom: 1px solid #e8e4db !important
      }

      .tsh-qcol--last {
        border-bottom: 0 !important
      }

      .email-heading-band {
        padding: 14px 20px !important
      }

      .email-heading {
        font-size: 13px !important;
        letter-spacing: .5px !important
      }
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
            <td style="background:#ffffff;padding:26px 36px;text-align:center">
              <?php
              $_logo_id  = get_theme_mod('custom_logo');
              $_logo_url = $_logo_id
                ? wp_get_attachment_image_url($_logo_id, 'full')
                : MONA_THEME_PATH_URI . '/assets/images/logo2.png';
              ?>
              <img src="<?php echo esc_url($_logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="150" style="display:block;width:150px;max-width:60%;height:auto;margin:0 auto">
            </td>
          </tr>

          <!-- Heading band -->
          <tr>
            <td class="email-heading-band" style="background:#c2a056;padding:18px 36px;text-align:center">
              <h1 class="email-heading" style="margin:0;font-size:16px;font-weight:600;letter-spacing:1px;line-height:1.5;color:#ffffff !important;-webkit-text-fill-color:#ffffff !important;text-transform:uppercase;text-align:center"><?php echo wp_kses($email_heading, ['br' => []]); ?></h1>
            </td>
          </tr>

          <!-- Body start -->
          <tr>
            <td class="email-body" style="padding:36px 36px 0">