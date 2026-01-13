<?php

namespace App\Http\Controllers\Api\Student;


use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $student = $user->student;

        if(!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Student profile not found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'data' => [
                'user' => [
                    'name' => $user->name,
                    'email'=> $user->email
                ],
                'student' => [
                    'student_code' => $student->student_code,
                    'gender' => $student->gender,
                    'phone' => $student->phone,
                    'address' => $student->address,
                    'date of birth' => $student->dob
                ]
            ]
        ]);
    }

    public function update(Request $request){
        $request->validate([
            'gender',
            'phone',
            'address',
            'dob'
        ]);

        $user = Auth::user();
        $student = $user->student;

        if(!$student) {
            return response()->json([
                'status'=> false,
                'message'=> 'Profile not found'
            ],404);
        }

        $student->update($request->all());
        return response()->json([
            'status'=> true,
            'message' => 'Profile update successfully',
            'data' => $student->toArray()
        ]);
    }
}
