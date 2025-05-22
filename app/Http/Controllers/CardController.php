<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CardController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'url_imagen' => 'required|url',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $card = Card::create([
            'nombre' => $request->nombre,
            'url_imagen' => $request->url_imagen,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Targeta creada',
            'data' => $card
        ], 201);
    }

    public function myCards()
    {
        $cards = Card::where('user_id', Auth::id())->get();

        return response()->json([
            'message' => 'Les teves targetes',
            'data' => $cards
        ]);
    }

    public function publicCards()
    {
        $cards = Card::whereNull('user_id')->get();

        return response()->json([
            'message' => 'Targetes públiques',
            'data' => $cards
        ]);
    }

    public function destroy(Card $card)
    {
        $user = Auth::user();

        if ($card->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['error' => 'No autoritzat'], 403);
        }

        $card->delete();

        return response()->json(['message' => 'Targeta eliminada']);
    }

    public function all()
    {
        return Card::with('user', 'category')->get();
    }

    public function adminDestroy(Card $card)
    {
        $card->delete();
        return response()->json(['message' => 'Targeta eliminada per admin']);
    }

    public function adminUpdate(Request $request, Card $card)
    {
        $request->validate([
            'nombre' => 'sometimes|string|max:100',
            'url_imagen' => 'sometimes|url',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $card->update($request->all());

        return response()->json([
            'message' => 'Targeta actualitzada per admin',
            'data' => $card
        ]);
    }

}
