<?php

namespace App\Http\Controllers;

use App\Enums\EscolaridadeEnum;
use App\Models\Curriculo;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCurriculos = Curriculo::count();
        $novosEstaSemana = Curriculo::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek(),
        ])->count();

        // Para "Aguardando Análise", vamos considerar currículos dos últimos 7 dias
        $aguardandoAnalise = Curriculo::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        // Dados para gráfico de currículos por dia (últimos 7 dias)
        $curriculosPorDia = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $data = Carbon::now()->subDays($i);
            $count = Curriculo::whereDate('created_at', $data)->count();
            $curriculosPorDia[] = $count;
            $labels[] = $data->format('d/m');
        }

        // Dados para gráfico de currículos por escolaridade
        $curriculosPorEscolaridade = Curriculo::select('escolaridade')
            ->get()
            ->groupBy('escolaridade')
            ->map(function ($curriculos, $escolaridade) {
                // O $escolaridade aqui já é o enum graças ao cast do modelo
                $label = $escolaridade instanceof EscolaridadeEnum
                    ? $escolaridade->getLabel()
                    : (EscolaridadeEnum::options()[$escolaridade] ?? ucfirst(str_replace('_', ' ', $escolaridade)));

                return [
                    'label' => $label,
                    'value' => $curriculos->count(),
                ];
            })
            ->values(); // Reindexar o array

        // Dados para gráfico de currículos por cargo desejado (top 10)
        $curriculosPorCargo = Curriculo::select('cargo_desejado')
            ->get()
            ->groupBy('cargo_desejado')
            ->map(function ($curriculos, $cargo) {
                return [
                    'label' => $cargo,
                    'value' => $curriculos->count(),
                ];
            })
            ->sortByDesc('value')
            ->take(10) // Apenas os top 10 cargos mais desejados
            ->values(); // Reindexar o array

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_curriculos' => $totalCurriculos,
                'novos_esta_semana' => $novosEstaSemana,
                'aguardando_analise' => $aguardandoAnalise,
            ],
            'charts' => [
                'curriculos_por_dia' => [
                    'labels' => $labels,
                    'data' => $curriculosPorDia,
                ],
                'curriculos_por_escolaridade' => $curriculosPorEscolaridade,
                'curriculos_por_cargo' => $curriculosPorCargo,
            ],
        ]);
    }
}
