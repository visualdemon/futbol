<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $password = $request->input('password');

        if (!$password) {
            return response()->json(['error' => 'Contraseña requerida'], 400);
        }

        // Compara con la contraseña del .env
        $adminPassword = env('ADMIN_PASSWORD', 'admin123');

        if ($password !== $adminPassword) {
            return response()->json(['error' => 'Contraseña incorrecta'], 401);
        }

        // Generar un token simple (puedes mejorarlo luego)
        $token = base64_encode('admin:' . $adminPassword);

        return response()->json(['token' => $token], 200);
    }
}
