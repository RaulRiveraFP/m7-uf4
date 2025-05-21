<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index()
    {
        $students = Student::all();
        return response()->json([
            'students' => $students
        ], 200);
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|max:255',
            'email'   => 'required|email|unique:students',
            'phone'   => 'required|digits:10',
            'address' => 'required|in:english,spanish,french',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $student = Student::create($validator->validated());

        return response()->json([
            'student' => $student
        ], 201);
    }

    public function show($id)
    {
        $student = Student::find($id);
        if (!$student) {
        return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }
        return response()->json(['student' => $student], 200);
    }

    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
        return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }
        $validator = Validator::make($request->all(), [ 'name' => 'required|max:255',
        'email' => ['required', 'email',
        Rule::unique('students')->ignore($student->id)], 'phone' => 'required|digits:10',
        'language' => 'required|in:english,spanish,french' ]);
        if ($validator->fails()) {
        return response()->json(['errors' =>
        $validator->errors()], 400);
        }
        $student->update($request->all());
        return response()->json(['student' => $student], 200);
    }

    public function updatePartial(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
        return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }
        $validator = Validator::make($request->all(), [ 'name' => 'sometimes|max:255',
        'email' => ['sometimes', 'email',
        Rule::unique('students')->ignore($student->id)], 'phone' => 'sometimes|digits:10',
        'language' => 'sometimes|in:english,spanish,french' ]);
        if ($validator->fails()) {
        return response()->json(['errors' =>
        $validator->errors()], 400);
        }
        $student->update($request->all());
        return response()->json(['student' => $student], 200);
    }

    public function destroy($id)
    {
        $student = Student::find($id);
        if (!$student) {
        return response()->json(['message' => 'Estudiante no encontrado'], 404);
        }
        $student->delete();
        return response()->json(['message' => 'Estudiante eliminado'], 200);
    }
}
