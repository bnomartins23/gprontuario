<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Req;

use Inertia\Inertia;


class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $pacientes = Paciente::all()->reverse();
        // $pacientes = Paciente::paginate(5)
        //     ->through(fn($paciente) => [
        //         'id' => $paciente->id,
        //         'nome' => $paciente->nome,
        //         'cpf' => $paciente->cpf,
        //     ]);
        $pacientes = Paciente::query()
            ->when(Req::input('search'), function($query, $search){
                $query->where('nome', 'like', '%'.$search.'%')
                    ->OrWhere('cpf', 'like', '%'.$search.'%');
            })->paginate(8)
            ->withQueryString();

        return Inertia::render('Pacientes/index', ['pacientes' => $pacientes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:200',
            'cpf' => 'required|min:11|max:11',
        ]);
        Paciente::create($request->all());
        return redirect()->route('pacientes.index')->with('message', 'Paciente cadastrado com sucesso');
    }

    /**
     * Display the specified resource.
     */
    public function show(Paciente $paciente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paciente $paciente)
    {
        return redirect()->route('pacientes.index', ['paciente' => $paciente]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Paciente $paciente)
    {
        $request->validate([
            'nome' => 'required|string|max:200',
            'cpf' => 'required|min:11|max:11'
        ]);
        $paciente->nome = $request->nome;
        $paciente->cpf = $request->cpf;
        $paciente->save();
        return redirect()->route('pacientes.index')->with('message', 'Os dados do paciente foram atualizados');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paciente $paciente)
    {
        $paciente->delete();
        return redirect()->route('pacientes.index')->with('message', 'Os dados do paciente foram removidos');
    }
}
