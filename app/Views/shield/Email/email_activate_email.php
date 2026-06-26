<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<head>
    <meta name="x-apple-disable-message-reformatting">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?= lang('Auth.emailActivateSubject') ?></title>
</head>

<body>
    <p style="font-family: 'Nunito', sans-serif; color: #6fa89f; font-weight: 700;"><?= lang('Auth.emailActivateMailBody') ?></p>
    <div style="text-align: center; background: #fff6f5; padding: 20px; border-radius: 16px; border: 2px dashed #f4756e; margin: 20px 0;">
        <h1 style="font-family: 'Nunito', sans-serif; color: #f4756e; font-size: 42px; margin: 0; letter-spacing: 5px;"><?= $code ?></h1>
    </div>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
        <tbody>
            <tr>
                <td style="line-height: 20px; font-size: 20px; width: 100%; height: 20px; margin: 0;" align="left" width="100%" height="20">
                    &#160;
                </td>
            </tr>
        </tbody>
    </table>
    <b><?= lang('Auth.emailInfo') ?></b>
    <p><?= lang('Auth.username') ?>: <?= esc($user->username) ?></p>
    <p><?= lang('Auth.emailIpAddress') ?> <?= esc($ipAddress) ?></p>
    <p><?= lang('Auth.emailDevice') ?> <?= esc($userAgent) ?></p>
    <p><?= lang('Auth.emailDate') ?> <?= esc($date) ?></p>
</body>

</html>
