<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Currículo Recebido</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4f46e5;
            margin: 0;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        .info-item {
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border-left: 4px solid #4f46e5;
        }
        .info-label {
            font-weight: bold;
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            color: #1f2937;
            font-size: 14px;
            margin-top: 4px;
        }
        .observacoes {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }
        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✉️ Novo Currículo Recebido</h1>
            <p>Um novo currículo foi enviado através do portal</p>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nome Completo</div>
                <div class="info-value">{{ $curriculo->nome }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">E-mail</div>
                <div class="info-value">{{ $curriculo->email }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Telefone</div>
                <div class="info-value">{{ $curriculo->telefone }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Cargo Desejado</div>
                <div class="info-value">{{ $curriculo->cargo_desejado }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Escolaridade</div>
                <div class="info-value">{{ $curriculo->escolaridade_formatada }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Data de Envio</div>
                <div class="info-value">{{ $curriculo->submitted_at->format('d/m/Y H:i') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">IP de Origem</div>
                <div class="info-value">{{ $curriculo->ip_address }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Arquivo</div>
                <div class="info-value">{{ $curriculo->arquivo_original_name }}</div>
            </div>
        </div>

        @if($curriculo->observacoes)
        <div class="observacoes">
            <div class="info-label">Observações</div>
            <div class="info-value" style="white-space: pre-wrap;">{{ $curriculo->observacoes }}</div>
        </div>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <p><strong>Para visualizar o currículo completo, acesse o painel administrativo.</strong></p>
        </div>

        <div class="footer">
            <p>Este e-mail foi enviado automaticamente pelo sistema Portal de Currículos</p>
            <p>Não responda este e-mail. Para contato, utilize os canais oficiais.</p>
        </div>
    </div>
</body>
</html>
