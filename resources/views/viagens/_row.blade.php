@foreach($viagens as $viagem)
<tr>
    <td class="fw-semibold align-middle py-3">{{ \Carbon\Carbon::parse($viagem->data)->format('d/m/Y') }}</td>
    <td class="align-middle py-3">
        <div>{{ $viagem->hora_saida }}</div>
        <small class="text-muted">{{ $viagem->km_saida }} KM</small>
    </td>
    <td class="fw-semibold align-middle py-3">{{ $viagem->destino }}</td>
    <td class="align-middle py-3">
        <div>{{ $viagem->hora_chegada }}</div>
        <small class="text-muted">{{ $viagem->km_chegada }} KM</small>
    </td>
    <td class="align-middle py-3">{{ $viagem->condutor }}</td>
    <td class="align-middle py-3">
        <div class="d-flex justify-content-center align-items-center gap-2">
            <a href="{{ route('viagens.show', $viagem->id) }}" class="btn btn-sm btn-outline-info d-flex align-items-center gap-1" title="Visualizar">
                <i class="bi bi-eye-fill"></i>
                <span class="d-none d-lg-inline">Ver</span>
            </a>
            <a href="{{ route('viagens.edit', $viagem->id) }}" class="btn btn-sm btn-outline-warning d-flex align-items-center gap-1" title="Editar">
                <i class="bi bi-pencil-fill"></i>
                <span class="d-none d-lg-inline">Editar</span>
            </a>
            @if(auth()->check() && auth()->user()->isAdmin())
            <form action="{{ route('viagens.destroy', $viagem->id) }}" method="POST" class="mb-0 d-flex align-items-center">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir esta viagem?')">
                    <i class="bi bi-trash-fill"></i>
                    <span class="d-none d-lg-inline">Excluir</span>
                </button>
            </form>
            @endif
        </div>
    </td>
</tr>
@endforeach
