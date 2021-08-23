<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" name="viewport" /><!--[if !mso]--><!-- -->
    <link href='https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,600,700' rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Quicksand:300,400,700' rel="stylesheet" /><!--<![endif]-->
    <title>Material Design for Bootstrap</title>
    <style type="text/css">
        body{width:100%;background-color:#fff;margin:0;padding:0;-webkit-font-smoothing:antialiased;mso-margin-top-alt:0;mso-margin-bottom-alt:0;mso-padding-alt:0 0 0 0}p,h1,h2,h3,h4{margin-top:0;margin-bottom:0;padding-top:0;padding-bottom:0}span.preheader{display:none;font-size:1px}html{width:100%}table{font-size:14px;border:0}@media only screen and (max-width: 640px){.main-header{font-size:20px!important}.main-section-header{font-size:28px!important}.show{display:block!important}.hide{display:none!important}.align-center{text-align:center!important}.no-bg{background:none!important}.main-image img{width:440px!important;height:auto!important}.divider img{width:440px!important}.container590{width:440px!important}.container580{width:400px!important}.main-button{width:220px!important}.section-img img{width:320px!important;height:auto!important}.team-img img{width:100%!important;height:auto!important}}@media only screen and (max-width: 479px){.main-header{font-size:18px!important}.main-section-header{font-size:26px!important}.divider img{width:280px!important}.container590{width:280px!important;width:280px!important}.container580{width:260px!important}.section-img img{width:280px!important;height:auto!important}}
        body {
            font-family: arial, sans-serif !important;
        }
    </style>
</head>
<body class="respond">
<!-- header -->
<table bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center">
            <table align="center" border="0" cellpadding="0" cellspacing="0" class="container590" width="590">
                <tr>
                    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="container590" width="590">
                            <tr>
                                <td align="center" height="70" style="height:70px;"><a href="" style="display: block; border-style: none !important; border: 0 !important;"><img alt="" border="0" src="{{ asset('assets/media/logo.png') }}" style="display: block; width: 100px;" width="100" /></a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table><!-- end header -->
<!-- big image section -->
<table bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" class="bg_color" width="100%">
    <tr>
        <td align="center">
            <table align="center" border="0" cellpadding="0" cellspacing="0" class="container590" width="590">
                <tr>
                    <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="center">
                        <table align="center" bgcolor="#EEEEEE" border="0" cellpadding="0" cellspacing="0" width="40">
                            <tr>
                                <td height="2" style="font-size: 2px; line-height: 2px;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                </tr>
                <tr>
                    <td align="left">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="container590" width="590">
                            <tr>
                                <td align="left" style="color: #888888; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                    <p style="line-height: 24px; margin-bottom:15px;">Hello Biggles Support,</p>
                                    <p style="line-height: 24px;margin-bottom:15px;"> Found some error on payment process from background script. Below are the error details:</p>
                                    <p><b>Move ID:</b> {{ $move->id }}</p>
                                    <p><b>Customer:</b> {{ $move->customer->first_name . " " . $move->customer->last_name }}</p>
                                    <p><b>Error Code:</b> {{ $errorCode }}</p>
                                    <p><b>Error Message:</b> {{ $errorMessage }}</p>
                                    <br /><br />
                                    <p style="line-height: 24px">Thanks & Regards,<br />
                                        The Biggles team.</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td>
    </tr>
</table><!-- end section -->
</body>
</html>
