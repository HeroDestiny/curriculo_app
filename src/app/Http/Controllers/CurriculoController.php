<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCurriculoRequest;
use App\Mail\CurriculoRecebido;
use App\Models\Curriculo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        try {
            $validatedData = $request->validated();

            // Upload do arquivo com validações adicionais
            $arquivo = $request->file('arquivo');

            // Verificação adicional de tipo MIME
            $allowedMimeTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ];

            if (! in_array($arquivo->getMimeType(), $allowedMimeTypes)) {
                return back()->withErrors([
                    'arquivo' => 'Tipo de arquivo não permitido. Apenas PDF, DOC e DOCX são aceitos.',
                ])->withInput();
            }

            // Verificação adicional de tamanho (1MB = 1048576 bytes)
            if ($arquivo->getSize() > 1048576) {
                return back()->withErrors([
                    'arquivo' => 'O arquivo não pode ser maior que 1MB.',
                ])->withInput();
            }

            // Gerar nome único para o arquivo
            $nomeArquivo = time().'_'.uniqid().'.'.$arquivo->getClientOriginalExtension();
            $caminhoArquivo = $arquivo->storeAs('curriculos', $nomeArquivo, 'public');

            // Obter IP do usuário
            $ipAddress = $request->ip();

            // Se estiver atrás de um proxy (como Cloudflare), pegar o IP real
            if ($request->header('CF-Connecting-IP')) {
                $ipAddress = $request->header('CF-Connecting-IP');
            } elseif ($request->header('X-Forwarded-For')) {
                $ipAddress = explode(',', $request->header('X-Forwarded-For'))[0];
            } elseif ($request->header('X-Real-IP')) {
                $ipAddress = $request->header('X-Real-IP');
            }

            // Criar o registro no banco
            $curriculo = Curriculo::create([
                'nome' => $validatedData['nome'],
                'email' => $validatedData['email'],
                'telefone' => $validatedData['telefone'],
                'cargo_desejado' => $validatedData['cargo_desejado'],
                'escolaridade' => $validatedData['escolaridade'],
                'observacoes' => $validatedData['observacoes'] ?? null,
                'arquivo_path' => $caminhoArquivo,
                'arquivo_original_name' => $arquivo->getClientOriginalName(),
                'ip_address' => $ipAddress,
                'submitted_at' => Carbon::now(),
            ]);

            // Enviar e-mail de notificação
            try {
                $emailsDestino = config('mail.admin_emails', ['admin@exemplo.com']);

                foreach ($emailsDestino as $email) {
                    Mail::to($email)->send(new CurriculoRecebido($curriculo));
                }

                Log::info('E-mail de currículo enviado com sucesso', [
                    'curriculo_id' => $curriculo->id,
                    'nome' => $curriculo->nome,
                    'email' => $curriculo->email,
                ]);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar e-mail de currículo', [
                    'curriculo_id' => $curriculo->id,
                    'error' => $e->getMessage(),
                ]);
                // Não falha a operação se o e-mail não for enviado
            }

            return redirect()->route('curriculos.sucesso')->with('success', 'Currículo enviado com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao processar currículo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors([
                'erro' => 'Ocorreu um erro interno. Tente novamente em alguns instantes.',
            ])->withInput();
        }
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
        try {
            $filePath = storage_path('app/public/'.$curriculo->arquivo_path);

            // Verificar se o arquivo existe
            if (! file_exists($filePath)) {
                Log::warning('Tentativa de download de arquivo inexistente', [
                    'curriculo_id' => $curriculo->id,
                    'arquivo_path' => $curriculo->arquivo_path,
                ]);
                abort(404, 'Arquivo não encontrado');
            }

            // Verificar se o caminho do arquivo está dentro do diretório permitido (segurança)
            $realPath = realpath($filePath);
            $allowedPath = realpath(storage_path('app/public/curriculos'));

            if (! $realPath || ! str_starts_with($realPath, $allowedPath)) {
                Log::warning('Tentativa de acesso a arquivo fora do diretório permitido', [
                    'curriculo_id' => $curriculo->id,
                    'arquivo_path' => $curriculo->arquivo_path,
                    'real_path' => $realPath,
                ]);
                abort(403, 'Acesso negado');
            }

            // Log do download
            Log::info('Download de currículo realizado', [
                'curriculo_id' => $curriculo->id,
                'nome' => $curriculo->nome,
                'arquivo' => $curriculo->arquivo_original_name,
                'user_ip' => request()->ip(),
            ]);

            return response()->download($filePath, $curriculo->arquivo_original_name);

        } catch (\Exception $e) {
            Log::error('Erro ao fazer download de currículo', [
                'curriculo_id' => $curriculo->id,
                'error' => $e->getMessage(),
            ]);

            abort(500, 'Erro interno do servidor');
        }
    }
}
