<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    // GET /pets → Llistar només les mascotes de l'usuari autenticat
    public function index()
    {
        $pets = Auth::user()->pets;
        return response()->json($pets, 200);
    }

    // POST /pets → Crear mascota
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
        ]);

        $pet = new Pet($validated);
        $pet->user_id = Auth::id();
        $pet->save();

        return response()->json($pet, 201);
    }

    // PUT /pets/{id} → Editar completament mascota
    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);

        if ($pet->user_id !== Auth::id()) {
            return response()->json(['error' => 'No tens permís'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
        ]);

        $pet->update($validated);

        return response()->json($pet, 200);
    }

    // PATCH /pets/{id} → Editar parcialment mascota
    public function partialUpdate(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);

        if ($pet->user_id !== Auth::id()) {
            return response()->json(['error' => 'No tens permís'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'species' => 'sometimes|string|max:255',
            'age' => 'sometimes|integer|min:0',
        ]);

        $pet->update($validated);

        return response()->json($pet, 200);
    }

    // DELETE /pets/{id} → Eliminar mascota
    public function destroy($id)
    {
        $pet = Pet::findOrFail($id);

        if ($pet->user_id !== Auth::id()) {
            return response()->json(['error' => 'No tens permís'], 403);
        }

        $pet->delete();

        return response()->json(['message' => 'Mascota eliminada correctament'], 200);
    }
}
