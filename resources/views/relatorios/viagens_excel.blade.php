@include('components.relatorio-cabecalho')
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
