<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規お問い合わせ通知</title>
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
                                New Inquiry
                            </p>
                            <h1 style="color: #1a1a1a; margin: 0; font-size: 20px; font-weight: 500; letter-spacing: 2px;">
                                新規お問い合わせ通知
                            </h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 50px 40px;">
                            <p style="margin: 0 0 4px 0; width: 40px; border-top: 2px solid #1a1a1a;"></p>

                            <p style="margin: 24px 0 30px 0; color: #666666; font-size: 15px; line-height: 1.9;">
                                ウェブサイトより、新しいお問い合わせが届きましたのでご報告いたします。
                            </p>

                            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top: 1px solid #ededed; border-bottom: 1px solid #ededed; margin: 0 0 30px 0;">
                                <tr>
                                    <td style="padding: 14px 0; color: #999999; font-size: 13px; width: 130px; vertical-align: top;">お名前</td>
                                    <td style="padding: 14px 0; color: #1a1a1a; font-size: 14px;">{{ $contact->name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 14px 0; color: #999999; font-size: 13px; border-top: 1px solid #ededed; vertical-align: top;">会社名</td>
                                    <td style="padding: 14px 0; color: #1a1a1a; font-size: 14px; border-top: 1px solid #ededed;">{{ $contact->company ?? '個人のお客様' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 14px 0; color: #999999; font-size: 13px; border-top: 1px solid #ededed; vertical-align: top;">メールアドレス</td>
                                    <td style="padding: 14px 0; color: #1a1a1a; font-size: 14px; border-top: 1px solid #ededed;">{{ $contact->email }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 14px 0; color: #999999; font-size: 13px; border-top: 1px solid #ededed; vertical-align: top;">区分</td>
                                    <td style="padding: 14px 0; border-top: 1px solid #ededed;">
                                        <span style="background-color: #f0f0f0; color: #1a1a1a; padding: 3px 12px; font-size: 13px; letter-spacing: 0.5px;">{{ $contact->category }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 14px 0; color: #999999; font-size: 13px; border-top: 1px solid #ededed; vertical-align: top;">利用規約同意</td>
                                    <td style="padding: 14px 0; color: #1a1a1a; font-size: 14px; border-top: 1px solid #ededed;">{{ $contact->agree ? '同意済み' : '未同意' }}</td>
                                </tr>
                            </table>

                            <p style="margin: 0 0 10px 0; color: #999999; font-size: 13px;">お問い合わせ内容</p>
                            <p style="margin: 0; color: #1a1a1a; font-size: 14px; line-height: 1.9; background-color: #fafafa; padding: 20px; border-left: 2px solid #1a1a1a;">
                                {{ $contact->content }}
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px 40px; text-align: center; border-top: 1px solid #ededed;">
                            <p style="margin: 0; color: #aaaaaa; font-size: 12px; line-height: 1.8; letter-spacing: 0.3px;">
                                本メールは、ウェブサイトのお問い合わせフォームより<br>
                                自動的に送信されています。<br>
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