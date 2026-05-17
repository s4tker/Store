<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Codigo de verificacion</title>
</head>
<body style="margin:0;background:#f8fafc;font-family:Arial,sans-serif;color:#0f172a;">
    <div style="max-width:520px;margin:0 auto;padding:32px 18px;">
        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:18px;padding:28px;text-align:center;">
            <p style="margin:0 0 12px;font-size:11px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#64748b;">ElectroShop</p>
            <h1 style="margin:0 0 14px;font-size:24px;">Verifica tu correo</h1>
            <p style="margin:0 0 22px;font-size:14px;line-height:1.5;color:#475569;">
                Usa este codigo para completar tu registro. Expira en {{ $minutes }} minutos.
            </p>
            <div style="display:inline-block;padding:16px 24px;border-radius:14px;background:#0f172a;color:#ffffff;font-size:28px;font-weight:800;letter-spacing:.22em;">
                {{ $code }}
            </div>
            <p style="margin:24px 0 0;font-size:12px;color:#94a3b8;">
                Si no solicitaste esta cuenta, puedes ignorar este mensaje.
            </p>
        </div>
    </div>
</body>
</html>
