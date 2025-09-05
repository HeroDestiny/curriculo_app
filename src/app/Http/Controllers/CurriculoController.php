<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCurriculoRequest;
use App\Models\Curriculo;
use Inertia\Inertia;

class CurriculoController extends Controller
{
    public function create()
    {
        $escolaridades = [
            'fundamental_incompleto' => 'Ensino Fundamental Incompleto',
            'fundamental_completo' => 'Ensino Fundamental Completo',
            'medio_incompleto' => 'Ensino Médio Incompleto',
            'medio_completo' => 'Ensino Médio Completo',
            'superior_incompleto' => 'Ensino Superior Incompleto',
            'superior_completo' => 'Ensino Superior Completo',
            'pos_graduacao' => 'Pós-graduação',
            'mestrado' => 'Mestrado',
            'doutorado' => 'Doutorado',
        ];

        return Inertia::render('Curriculos/Create', [
            'escolaridades' => $escolaridades,
        ]);
    }

    public function store(StoreCurriculoRequest $request)
    {
        $validatedData = $request->validated();

        // Upload do arquivo
        $arquivo = $request->file('arquivo');
        $nomeArquivo = time().'_'.$arquivo->getClientOriginalName();
        $caminhoArquivo = $arquivo->storeAs('curriculos', $nomeArquivo, 'public');

        // Criar o registro no banco
        Curriculo::create([
            'nome' => $validatedData['nome'],
            'email' => $validatedData['email'],
            'telefone' => $validatedData['telefone'],
            'cargo_desejado' => $validatedData['cargo_desejado'],
            'escolaridade' => $validatedData['escolaridade'],
            'observacoes' => $validatedData['observacoes'],
            'arquivo_path' => $caminhoArquivo,
            'arquivo_original_name' => $arquivo->getClientOriginalName(),
        ]);

        return redirect()->route('curriculos.sucesso')->with('success', 'Currículo enviado com sucesso!');
    }

    public function sucesso()
    {
        return Inertia::render('Curriculos/Sucesso');
    }

    public function index()
    {
        $search = request('search');

        $query = Curriculo::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', '%'.$search.'%')
                    ->orWhere('cargo_desejado', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $curriculos = $query->orderBy('created_at', 'desc')->paginate(10);
        $curriculos->appends(['search' => $search]);

        // Adicionar escolaridade formatada para cada currículo
        $curriculos->getCollection()->transform(function ($curriculo) {
            $curriculo->escolaridade_formatada = $curriculo->escolaridade_formatada;

            return $curriculo;
        });

        return Inertia::render('Curriculos/Index', [
            'curriculos' => $curriculos,
            'search' => $search,
        ]);
    }

    public function show(Curriculo $curriculo)
    {
        $curriculo->escolaridade_formatada = $curriculo->escolaridade_formatada;

        return Inertia::render('Curriculos/Show', [
            'curriculo' => $curriculo,
        ]);
    }

    public function downloadArquivo(Curriculo $curriculo)
    {
        $filePath = storage_path('app/public/'.$curriculo->arquivo_path);

        if (! file_exists($filePath)) {
            abort(404, 'Arquivo não encontrado');
        }

        return response()->download($filePath, $curriculo->arquivo_original_name);
    }
}
