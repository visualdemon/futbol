<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartidoController extends Controller
{
    public function index()
    {
        $partidos = DB::table('partidos')
            ->orderBy('fecha')
            ->get();

        return response()->json($partidos);
    }

    public function guardar(Request $request)
    {
        try {
            $fecha = $request->input('fecha');
            $oficial = $request->input('es_oficial', 1);
            $jugado = $request->input('jugado', 0);

            if (!$fecha) {
                return response()->json(['error' => 'La fecha es obligatoria'], 422);
            }

            DB::table('partidos')->updateOrInsert(
                ['fecha' => $fecha],
                ['es_oficial' => $oficial, 'jugado' => $jugado]
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }

    public function eliminar($fecha)
    {
        try {
            $deleted = DB::table('partidos')->where('fecha', $fecha)->delete();

            if ($deleted) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['error' => 'Partido no encontrado'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }

}
