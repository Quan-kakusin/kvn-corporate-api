<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ニュース更新</title>
</head>
<body style="margin: 0; padding: 0; background-color: #fafafa; font-family: 'Helvetica Neue', Helvetica, Arial, 'Hiragino Sans', 'Yu Gothic', sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #fafafa; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border: 1px solid #e5e5e5;">
                    
                    <!-- Header -->
                    <tr>
                        <td style="padding: 40px 40px 30px 40px; text-align: center; border-bottom: 1px solid #ededed;">
                            <p style="margin: 0 0 8px 0; color: #999999; font-size: 11px; letter-spacing: 3px; text-transform: uppercase;">
                                News Letter
                            </p>
                            <h1 style="color: #1a1a1a; margin: 0; font-size: 20px; font-weight: 500; letter-spacing: 2px;">
                                お知らせ
                            </h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 50px 40px;">
                            <h2 style="margin: 0 0 16px 0; color: #1a1a1a; font-size: 22px; font-weight: 500; line-height: 1.6;">
                                {{ $newsData['title'] }}
                            </h2>
                            
                            <p style="margin: 0 0 4px 0; width: 40px; border-top: 2px solid #1a1a1a;"></p>

                            <p style="margin: 24px 0 40px 0; color: #666666; font-size: 15px; line-height: 1.9;">
                                {{ $newsData['subtitle'] }}
                            </p>
                            
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $newsData['link'] }}" style="display: inline-block; padding: 14px 48px; background-color: #1a1a1a; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 500; letter-spacing: 1px;">
                                            詳細を見る
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px 40px; text-align: center; border-top: 1px solid #ededed;">
                            <p style="margin: 0; color: #aaaaaa; font-size: 12px; line-height: 1.8; letter-spacing: 0.3px;">
                                このメールは、ニュースレターにご登録いただいた方にお送りしています。<br>
                                © {{ date('Y') }} All Rights Reserved.
                            </p>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
</body>
</html>