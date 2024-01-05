<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{

    /////get all sybject from database
    public function index()
    {
        $subjects = Subject::all();
        return response()->json($subjects);
    }

    ////find by id
    public function edit($id)
    {
        try {
            $subject = Subject::findOrFail($id); // Try to get the data from the table

            return response()->json([
                'success' => true,
                'data' => $subject,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $exception->getMessage(),
            ], 404);
        }
    }

    ////insert and get the data.
    public function store(Request $request)
    {
        /////validate
        $validateData = $request->validate([
            "class_id" => "required|exists:sclasses,id", // make sure class_id is exists in classes table
            "subject_name" => "required|unique:sections|max:45",
            "subject_code" => "required|unique:sections|max:45",
        ]);

        // Insert the section and get the ID for send to the frontend
        $subjectId = Subject::insertGetId([
            "class_id" => $request->class_id,
            "subject_name" => $request->subject_name,
            "subject_code" => $request->subject_code,
            "created_at" => Subject::now(),
        ]);

        // fet back the subject that created by id
        $createdSubjectId = Subject::find($subjectId);

        return response()->json([
            'success' => true,
            'message' => 'Student Section has been added',
            'data' => $createdSubjectId,
        ], 201);
    }

    ////updated by id
    public function update(Request $request, $id)
    {
        try {
            // Find the subject
            $subject = Subject::findOrFail($id);

            // Update the subject
            $subject->update([
                "class_id" => $request->class_id,
                "subject_name" => $request->subject_name,
                "subject_code" => $request->subject_code,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'The subject has been updated',
                'data' => $subject,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $exception->getMessage(),
            ], 404);
        }
    }

    /////Delete subject by id.
    public function deleteSubject($id)
    {
        try {
            // Find the subject
            $subject = Subject::findOrFail($id);

            // Delete the subject
            $subject->delete();

            return response()->json([
                'success' => true,
                'message' => 'The subject has been deleted',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $exception->getMessage(),
            ], 404);
        }
    }

    //////delete students by array of id
    public function deleteSubjects(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'subject_ids' => 'required|array',
                'subject_ids.*' => 'exists:subjects,id', // make sure each id is exists in the sections table
            ]);

            /////////// Get the array of student IDs from the request
            $subjectIds = $request->input('subject_ids');

            ///////// Delete the students with the specified IDs
            Subject::whereIn('id', $subjectIds)->delete();

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
