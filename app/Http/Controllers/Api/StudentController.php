<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /////get all student in the database
    public function index()
    {
        $students = Student::all();
        $students->makeHidden(['password']); ////hide
        return response()->json($students);
    }

    ////insert and get the data.
    public function store(Request $request)
    {
        /////validate the data
        $validateData = $request->validate([
            "class_id" => "required|exists:sclasses,id", // make sure class_id is exists in sclasses table
            "section_id" => "required|exists:sections,id", // make sure section_id is exists in sections table
            "name" => "required|unique:students|min:2",
            "email" => "required|email|unique:students", ///verify email
            "address" => "required|min:2",
            "password" => "required|min:4|max:10",
            "photo" => "nullable", // photo is optional
            "gender" => "required",
            "phone" => "required|regex:/^05[0-9]{8}$/|unique:students", // Phone number validation
        ]);

        // Insert the student to database and get the ID - for sent to the user
        $studentId = Student::insertGetId([
            "class_id" => $request->class_id,
            "section_id" => $request->section_id,
            "name" => $request->name,
            "email" => $request->email,
            "address" => $request->address,
            "password" => Hash::make($request->password),
            "photo" => $request->photo,
            "gender" => $request->gender,
            "phone" => $request->phone,
            "created_at" => Carbon::now(),
        ]);

        // get back the student that created by id
        $createdStudent = Student::find($studentId);

        return response()->json([
            'success' => true,
            'message' => 'Student has been created',
            'data' => $createdStudent,
        ], 201);
    }

    ////find by id
    public function edit($id)
    {
        try {
            $student = Student::findOrFail($id); // Try to get the data from the table
            ////return the frontend
            return response()->json([
                'success' => true,
                'data' => $student,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $exception->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            /////////// Find the student
            $student = Student::findOrFail($id);

            ////////// Update the student
            $updatedData = [];

            //////collect only filed the forntend sent
            if ($request->has('class_id')) {
                $updatedData['class_id'] = $request->class_id;
            } else if ($request->has('section_id')) {
                $updatedData['section_id'] = $request->section_id;
            } else if ($request->has('name')) {
                $updatedData['name'] = $request->name;
            } else if ($request->has('email')) {
                $updatedData['email'] = $request->email;
            } else if ($request->has('address')) {
                $updatedData['address'] = $request->address;
            } else if ($request->has('photo')) {
                $updatedData['photo'] = $request->photo;
            } else if ($request->has('gender')) {
                $updatedData['gender'] = $request->gender;
            } else if ($request->has('phone')) {
                $updatedData['phone'] = $request->phone;
            }
            ////////// Update the student with the new data
            $student->update($updatedData);
            //////// Refresh the student instance to get the updated data from the database
            $student->refresh();
            return response()->json([
                'success' => true,
                'message' => 'The student has been updated',
                'data' => $student,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $exception->getMessage(),
            ], 404);
        }
    }



    /////Delete section by id.
    public function deleteStudent($id)
    {
        try {
            //////// try to find the the student by id
            $student = Student::findOrFail($id);

            /////////// Delete the student from database
            $student->delete();

            return response()->json([
                'success' => true,
                'message' => 'The section has been deleted',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $exception->getMessage(),
            ], 404);
        }
    }


    //////delete students by array of id
    public function deleteStudents(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'student_ids' => 'required|array',
                'student_ids.*' => 'exists:students,id', // make sure each id is exists in the students table
            ]);

            // Get the array of student IDs from the request
            $studentIds = $request->input('student_ids');

            ///////// Delete the students with the specified IDs
            Student::whereIn('id', $studentIds)->delete();

            return response()->json([
                'success' => true,
                'message' => 'The students have been deleted',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $exception->getMessage(),
            ], 404);
        }
    }
}
