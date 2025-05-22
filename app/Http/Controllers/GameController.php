<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    // 1. Llistar les partides de l'usuari autenticat
    public function index()
    {
        $userId = Auth::id();
        $games = Game::where('user_id', $userId)->get();

        return response()->json([
            'message' => 'Llistat de partides',
            'data' => $games
        ], 200);
    }

    // 2. Crear una nova partida
    public function store(Request $request)
    {
        $game = Game::create([
            'user_id' => $request->user_id,
            'clicks' => $request->clicks,
            'points' => $request->points,
            'duration' => $request->duration
        ]);

        return response()->json([
            'message' => 'Partida creada',
            'data' => $game
        ], 201);
    }

    // 3. Finalitzar una partida
    public function update(Request $request, Game $game)
    {
        if ($game->user_id !== Auth::id()) {
            return response()->json(['error' => 'No autoritzat'], 403);
        }

        $validated = $request->validate([
            'clicks' => 'required|integer|min:0',
            'points' => 'required|integer|min:0',
            'duration' => 'required|integer|min:1',
        ]);

        $game->update($validated);

        return response()->json([
            'message' => 'Partida finalitzada',
            'data' => $game
        ], 200);
    }

    // 4. Eliminar una partida (usuari o admin)
    public function destroy(Game $game)
    {
        $user = Auth::user();

        if ($user->id !== $game->user_id && $user->role !== 'admin') {
            return response()->json(['error' => 'No autoritzat'], 403);
        }

        $game->delete();

        return response()->json(['message' => 'Partida eliminada'], 200);
    }

    // 5. Rànquing TOP 5 jugadors
    public function ranking()
    {
        $ranking = Game::select('user_id')
            ->selectRaw('MIN(duration) as best_time')
            ->selectRaw('MIN(clicks) as min_clicks')
            ->selectRaw('MAX(points) as max_points')
            ->groupBy('user_id')
            ->orderBy('best_time')
            ->orderBy('min_clicks')
            ->with('user:id,name')
            ->take(5)
            ->get();

        return response()->json([
            'message' => 'Top 5 jugadors',
            'data' => $ranking
        ], 200);
    }

    // Funció extra: veure partides d’un usuari (només admin)
    public function getGamesByUserId($id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Només per admins'], 403);
        }

        $games = Game::where('user_id', $id)->get();

        return response()->json([
            'message' => "Partides de l’usuari $id",
            'data' => $games
        ]);
    }
}
