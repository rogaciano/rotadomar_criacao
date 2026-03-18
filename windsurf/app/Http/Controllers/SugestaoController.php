<?php

namespace App\Http\Controllers;

use App\Models\Sugestao;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SugestaoController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $status = $request->get('status');
        $assunto = trim((string) $request->get('assunto', ''));
        $usuario = trim((string) $request->get('usuario', ''));

        $query = Sugestao::with(['usuario', 'localizacao', 'lidoPor'])
            ->visiveisPara($user)
            ->orderByDesc('created_at');

        if ($status && in_array($status, Sugestao::STATUS_VALIDOS, true)) {
            $query->where('status', $status);
        }

        if ($assunto !== '') {
            $query->where('assunto', 'like', '%' . $assunto . '%');
        }

        if ($usuario !== '') {
            $query->whereHas('usuario', function ($subQuery) use ($usuario) {
                $subQuery->where('name', 'like', '%' . $usuario . '%');
            });
        }

        $sugestoes = $query->paginate(15)->appends($request->query());

        return view('sugestoes.index', [
            'sugestoes' => $sugestoes,
            'statusSelecionado' => $status,
            'assuntoSelecionado' => $assunto,
            'usuarioSelecionado' => $usuario,
            'statusValidos' => Sugestao::STATUS_VALIDOS,
        ]);
    }

    public function minhasSugestoes(Request $request): View
    {
        $status = $request->get('status');
        $assunto = trim((string) $request->get('assunto', ''));
        $usuario = trim((string) $request->get('usuario', ''));

        $query = Sugestao::with(['usuario', 'localizacao', 'lidoPor'])
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at');

        if ($status && in_array($status, Sugestao::STATUS_VALIDOS, true)) {
            $query->where('status', $status);
        }

        if ($assunto !== '') {
            $query->where('assunto', 'like', '%' . $assunto . '%');
        }

        if ($usuario !== '') {
            $query->whereHas('usuario', function ($subQuery) use ($usuario) {
                $subQuery->where('name', 'like', '%' . $usuario . '%');
            });
        }

        $sugestoes = $query->paginate(15)->appends($request->query());

        return view('sugestoes.index', [
            'sugestoes' => $sugestoes,
            'statusSelecionado' => $status,
            'assuntoSelecionado' => $assunto,
            'usuarioSelecionado' => $usuario,
            'statusValidos' => Sugestao::STATUS_VALIDOS,
            'somenteMinhas' => true,
        ]);
    }

    public function create(): View
    {
        return view('sugestoes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'assunto' => 'required|string|max:255',
            'texto' => 'required|string|max:5000',
        ]);

        $user = auth()->user();

        Sugestao::create([
            'user_id' => $user->id,
            'localizacao_id' => $user->localizacao_id,
            'assunto' => $validated['assunto'],
            'texto' => $validated['texto'],
            'status' => Sugestao::STATUS_NAO_LIDA,
        ]);

        return redirect()->route('sugestoes.minhas')->with('success', 'Sugestão enviada com sucesso!');
    }

    public function show(Sugestao $sugestao): View
    {
        $sugestao = Sugestao::with(['usuario', 'localizacao', 'lidoPor'])
            ->visiveisPara(auth()->user())
            ->where('id', $sugestao->id)
            ->firstOrFail();

        if ($sugestao->status === Sugestao::STATUS_NAO_LIDA) {
            $sugestao->update([
                'status' => Sugestao::STATUS_LIDA,
                'lido_por_user_id' => auth()->id(),
                'lido_em' => now(),
            ]);

            $sugestao->refresh();
        }

        return view('sugestoes.show', [
            'sugestao' => $sugestao,
            'statusValidos' => Sugestao::STATUS_VALIDOS,
        ]);
    }

    public function updateStatus(Request $request, Sugestao $sugestao): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', Sugestao::STATUS_VALIDOS),
        ]);

        $sugestao = Sugestao::visiveisPara(auth()->user())
            ->where('id', $sugestao->id)
            ->firstOrFail();

        $dadosAtualizacao = [
            'status' => $validated['status'],
        ];

        if ($validated['status'] === Sugestao::STATUS_NAO_LIDA) {
            $dadosAtualizacao['lido_por_user_id'] = null;
            $dadosAtualizacao['lido_em'] = null;
        } else {
            $dadosAtualizacao['lido_por_user_id'] = auth()->id();
            $dadosAtualizacao['lido_em'] = now();
        }

        $sugestao->update($dadosAtualizacao);

        return redirect()->route('sugestoes.show', $sugestao)->with('success', 'Status da sugestão atualizado com sucesso!');
    }

    public function countNaoLidas(): JsonResponse
    {
        $count = Sugestao::visiveisPara(auth()->user())
            ->where('status', Sugestao::STATUS_NAO_LIDA)
            ->count();

        return response()->json(['count' => $count]);
    }
}
