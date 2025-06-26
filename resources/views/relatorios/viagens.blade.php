<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Viagens</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    @include('components.relatorio-cabecalho')
    <h2>Relatório de Viagens</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Destino</th>
                <th>Data</th>
                <th>Condutor</th>
                <th>Tipo de Veículo</th>
                <th>Placa</th>
                <th>KM Saída</th>
                <th>KM Chegada</th>
            </tr>
        </thead>
        <tbody>
            @foreach($viagens as $viagem)
            <tr>
                <td>{{ $viagem->id }}</td>
                <td>{{ $viagem->destino }}</td>
                <td>{{ \Carbon\Carbon::parse($viagem->data)->format('d/m/Y') }}</td>
                <td>{{ $viagem->user->name ?? '-' }}</td>
                <td>{{ $viagem->tipo_veiculo ?? '-' }}</td>
                <td>{{ $viagem->placa ?? '-' }}</td>
                <td>{{ $viagem->km_saida ?? '-' }}</td>
                <td>{{ $viagem->km_chegada ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
