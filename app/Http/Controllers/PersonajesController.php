<?php

namespace App\Http\Controllers;

use App\Models\Personaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonajesController extends Controller
{
    // Crear personaje
    public function addPersonaje(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $personaje = Personaje::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json(['message' => 'Personatge creat', 'data' => $personaje], 201);
    }

    // Actualizar personaje
    public function updatePersonaje(Request $request, $id)
    {
        $personaje = Personaje::find($id);
        if (!$personaje) {
            return response()->json(['error' => 'Personatge no trobat'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $personaje->update($request->only('nombre', 'descripcion'));

        return response()->json(['message' => 'Personatge actualitzat', 'data' => $personaje]);
    }

    // Eliminar personaje
    public function deletePersonaje($id)
    {
        $personaje = Personaje::find($id);
        if (!$personaje) {
            return response()->json(['error' => 'Personatge no trobat'], 404);
        }

        $personaje->delete();

        return response()->json(['message' => 'Personatge eliminat correctament']);
    }
}
