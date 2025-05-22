<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Card;  // <-- Añade esta línea

class CategoryController extends Controller
{
    // Mostrar todas las categorías con sus cartas
    public function index()
    {
        return Category::with('cards')->get();
    }

    // Crear una categoría nueva
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        return Category::create($request->all());
    }

    // Actualizar una categoría
    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:100']);
        $category->update($request->all());
        return $category;
    }

    // Eliminar una categoría
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Categoria eliminada']);
    }

    public function getByCategory($categoryId)
    {
        $cards = Card::where('category_id', $categoryId)->get();
        return response()->json($cards);
    }
}

