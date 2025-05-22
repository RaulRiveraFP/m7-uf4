<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['error' => 'Usuari no trobat'], 404);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['error' => 'Usuari no trobat'], 404);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:100|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:5|confirmed',
            'role' => 'sometimes|in:admin,user',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 400);

        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('password')) $user->password = bcrypt($request->password);
        if ($request->has('role')) $user->role = $request->role;

        $user->save();

        return response()->json(['message' => 'Usuari actualitzat correctament', 'data' => $user]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['error' => 'Usuari no trobat'], 404);

        $user->delete();
        return response()->json(['message' => 'Usuari eliminat correctament']);
    }
}
