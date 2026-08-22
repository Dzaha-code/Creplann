<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesan Baru dari CrePlann Contact Form</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f0e8; color: #20241f; margin: 0; padding: 40px 20px; }
        .wrapper { max-width: 560px; margin: 0 auto; }
        .card { background: #fffdf8; border: 1px solid #d7d0ba; border-radius: 16px; padding: 32px 36px; box-shadow: 0 4px 24px rgba(32,36,31,0.08); }
        .brand { font-size: 1.3rem; font-weight: 700; color: #d98f2b; margin-bottom: 24px; letter-spacing: -0.01em; }
        h1 { font-size: 1.15rem; font-weight: 700; color: #20241f; margin: 0 0 20px; }
        .field { margin-bottom: 16px; }
        .label { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #63695c; margin-bottom: 4px; }
        .value { font-size: 0.95rem; color: #20241f; }
        .message-box { background: #f5f0e8; border-radius: 10px; padding: 16px 20px; line-height: 1.7; font-size: 0.92rem; white-space: pre-wrap; }
        .footer { margin-top: 28px; font-size: 0.78rem; color: #63695c; border-top: 1px solid #e8e3d2; padding-top: 18px; }
        .badge { display: inline-block; background: #d98f2b; color: #21251f; padding: 2px 12px; border-radius: 999px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 18px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="brand">CrePlann</div>
            <div class="badge">Pesan Masuk</div>
            <h1>Ada pesan baru dari form kontak</h1>

            <div class="field">
                <div class="label">Nama</div>
                <div class="value">{{ $contact->name }}</div>
            </div>

            <div class="field">
                <div class="label">Email</div>
                <div class="value">
                    <a href="mailto:{{ $contact->email }}" style="color:#d98f2b;">{{ $contact->email }}</a>
                </div>
            </div>

            <div class="field">
                <div class="label">Subjek</div>
                <div class="value">{{ $contact->subject }}</div>
            </div>

            <div class="field">
                <div class="label">Pesan</div>
                <div class="message-box">{{ $contact->message }}</div>
            </div>

            <div class="footer">
                Email ini dikirim otomatis oleh CrePlann pada {{ $contact->created_at->translatedFormat('d F Y, H:i') }} WIB.
                Balas langsung ke email ini untuk merespons pengirim.
            </div>
        </div>
    </div>
</body>
</html>
