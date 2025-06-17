<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Oswald, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #333; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f8f8; }
        .total { font-weight: bold; }
        .footer { text-align: center; margin-top: 20px; color: #777; }
        @media (max-width: 600px) { .container { padding: 10px; } table { font-size: 14px; } }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cotización - IntegralTech Consulting Spa</h1>
        <p>Estimado/a {{ $quotation->client_name }},</p>
        <p>A continuación, presentamos su cotización generada el {{ $quotation->quotation_date->format('d/m/Y H:i') }}:</p>

        <table>
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Código Fonasa</th>
                    <th>Precio Fonasa</th>
                    <th>Precio Particular</th>
                    <th>Cantidad</th>
                    <th>Subtotal Fonasa</th>
                    <th>Subtotal Particular</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quotation->products as $product)
                    <tr>
                        <td>{{ $product['description'] }}</td>
                        <td>{{ $product['fonasa_code'] }}</td>
                        <td>{{ number_format($product['fonasa_patient_price'], 2, ',', '.') }}</td>
                        <td>{{ number_format($product['private_price'], 2, ',', '.') }}</td>
                        <td>{{ $product['quantity'] }}</td>
                        <td>{{ number_format($product['fonasa_patient_price'] * $product['quantity'], 2, ',', '.') }}</td>
                        <td>{{ number_format($product['private_price'] * $product['quantity'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total">
                    <td colspan="5">Total</td>
                    <td>{{ number_format($quotation->total_fonasa_price, 2, ',', '.') }}</td>
                    <td>{{ number_format($quotation->total_private_price, 2, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <p><strong>Información de Contacto:</strong></p>
        <p>Nombre: {{ $quotation->client_name }}</p>
        <p>Email: {{ $quotation->client_email }}</p>
        <p>Teléfono: {{ $quotation->client_phone ?? 'No proporcionado' }}</p>

        <div class="footer">
            <p>Gracias por confiar en IntegralTech Consulting Spa.</p>
            <p>Contacte con nosotros en tecnologia@integraltech.cl</p>
        </div>
    </div>
</body>
</html>