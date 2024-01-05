<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sclass;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class SclassController extends Controller
{

    /////get all class in database
    public function index()
    {
        $sclass = Sclass::all();
        return response()->json($sclass);
    }

    ////insert and get the data.
    public function store(Request $request)
    {
        /////validate
        $validateData = $request->validate([
            "class_name" => "required|unique:sclasses|max:25",

        ]);

        // Insert the section and get the ID
        $classtId = Sclass::insertGetId([
            "class_id" => $request->class_id,
            "subject_name" => $request->subject_name,
            "created_at" => Sclass::now(),
        ]);

        // Retrieve the created section
        $createdcalsstId = Sclass::find($classtId);

        return response()->json([
            'success' => true,
            'message' => 'Student Section has been added',
            'data' => $createdcalsstId,
        ], 201);
    }

    ////find sclass by id
    public function edit($id)
    {
        try {
            $sclass = Sclass::findOrFail($id); // Try to get the data from the table

            return response()->json([
                'success' => true,
                'data' => $sclass,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $exception->getMessage(),
            ], 404);
        }
    }


    ////updated by id
    public function update(Request $request, $id)
    {

        try {
            // Find the subject
            $sclass = Sclass::findOrFail($id); // Corrected method name
            $sclass->update([
                "class_name" => $request->class_name, // Corrected property name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'The Student Class has been updated',
                'data' => $sclass,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $exception->getMessage(),
            ], 404);
        }
    }


    /////Delete subject by id.
    public function deleteClass($id)
    {
        try {
            /////////////// find the subject
            $class = Sclass::findOrFail($id);

            ////////// Delete the subject
            $class->delete();

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


    //////delete classes by array of id
    public function deleteClasses(Request $request)
    {
        try {
            //////// Validate the request data
            $request->validate([
                'classes_id' => 'required|array',
                'classes_id.*' => 'exists:sclasses,id', ////// make sure each id is exists in the sclasses table
            ]);

            /////////// Get the array of student IDs from the request
            $classesId = $request->input('classes_id');

            ///////// Delete the students with the specified IDs
            Sclass::whereIn('id', $classesId)->delete();

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
