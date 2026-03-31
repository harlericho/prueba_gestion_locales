<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Local;
use Illuminate\Http\Request;

class LocalController extends Controller
{
    public function index(Request $request)
    {
        $query = Local::query();

        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('estado') && $request->estado !== '') {
            $query->where('estado', $request->estado);
        }

        $locales = $query->orderBy('nombre')->paginate(5);

        return response()->json($locales);
    }

    public function update(Request $request, $id)
    {
        $local = Local::findOrFail($id);

        $validated = $request->validate([
            'nombre'         => 'required|string|max:255',
            'direccion'      => 'required|string|max:255',
            'estado'         => 'required|integer|in:0,1',
            'tipo_documento' => 'nullable|in:RUC,CEDULA',
            'nro_documento'  => 'nullable|string|max:20',
        ]);

        $local->update($validated);

        return response()->json([
            'message' => 'Local actualizado correctamente.',
            'data'    => $local,
        ]);
    }
}
