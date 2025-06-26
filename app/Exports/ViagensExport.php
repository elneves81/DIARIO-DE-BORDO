<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ViagensExport implements FromView
{
    protected $viagens;
    public function __construct($viagens)
    {
        $this->viagens = $viagens;
    }
    public function view(): View
    {
        return view('relatorios.viagens_excel', [
            'viagens' => $this->viagens
        ]);
    }
}
