<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせ確認</title>
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
                                Contact Confirmation
                            </p>
                            <h1 style="color: #1a1a1a; margin: 0; font-size: 20px; font-weight: 500; letter-spacing: 2px;">
                                お問い合わせ確認
                            </h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 50px 40px;">
                            <h2 style="margin: 0 0 16px 0; color: #1a1a1a; font-size: 20px; font-weight: 500; line-height: 1.6;">
                                {{ $contact->name }} 様
                            </h2>

                            <p style="margin: 0 0 4px 0; width: 40px; border-top: 2px solid #1a1a1a;"></p>

                            <p style="margin: 24px 0 20px 0; color: #666666; font-size: 15px; line-height: 1.9;">
                                この度は、お問い合わせいただき誠にありがとうございます。<br>
                                以下の内容で承りましたことをご報告申し上げます。
                            </p>

                            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top: 1px solid #ededed; border-bottom: 1px solid #ededed; margin: 0 0 30px 0;">
                                <tr>
                                    <td style="padding: 16px 0; color: #999999; font-size: 13px; width: 120px; vertical-align: top;">お問い合わせ区分</td>
                                    <td style="padding: 16px 0; color: #1a1a1a; font-size: 14px;">{{ $contact->category }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 0; color: #999999; font-size: 13px; border-top: 1px solid #ededed; vertical-align: top;">ご登録メールアドレス</td>
                                    <td style="padding: 16px 0; color: #1a1a1a; font-size: 14px; border-top: 1px solid #ededed;">{{ $contact->email }}</td>
                                </tr>
                            </table>

                            <p style="margin: 0; color: #666666; font-size: 15px; line-height: 1.9;">
                                担当者より内容を確認の上、改めてご連絡を差し上げますので、<br>
                                今しばらくお待ちくださいますようお願い申し上げます。
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px 40px; text-align: center; border-top: 1px solid #ededed;">
                            <p style="margin: 0; color: #aaaaaa; font-size: 12px; line-height: 1.8; letter-spacing: 0.3px;">
                                本メールは、お問い合わせフォームよりご送信いただいた方へ<br>
                                自動的にお送りしております。<br>
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