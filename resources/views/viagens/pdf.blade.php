<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Viagens</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: center; }
        th { background: #111; color: #fff; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Registros de Viagens</h2>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Saída (Hora/KM)</th>
                <th>Destino</th>
                <th>Chegada (Hora/KM)</th>
                <th>Condutor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($viagens as $viagem)
            <tr>
                <td>{{ \Carbon\Carbon::parse($viagem->data)->format('d/m/Y') }}</td>
                <td>{{ $viagem->hora_saida }}<br><span style="color:#888;">{{ $viagem->km_saida }} KM</span></td>
                <td>{{ $viagem->destino }}</td>
                <td>{{ $viagem->hora_chegada }}<br><span style="color:#888;">{{ $viagem->km_chegada }} KM</span></td>
                <td>{{ $viagem->condutor }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
