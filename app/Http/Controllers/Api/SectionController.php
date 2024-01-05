<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /////get all sybject from database
    public function index()
    {
        $sections = Section::all(); ////get all from database
        return response()->json($sections);
    }

    ////insert and get the data.
    public function store(Request $request)
    {
        /////validate
        $validateData = $request->validate([
            "class_id" => "required|exists:sclasses,id", // make sure class_id is exists in classes table
            "section_name" => "required|unique:sections|max:45",
        ]);

        // Insert the section and get the ID
        $sectionId = Section::insertGetId([
            "class_id" => $request->class_id,
            "section_name" => $request->section_name,
            "created_at" => Carbon::now(),
        ]);

        // Retrieve the created section
        $createdSection = Section::find($sectionId);

        return response()->json([
            'success' => true,
            'message' => 'Student Section has been added',
            'data' => $createdSection,
        ], 201);
    }


    ////find by id
    public function edit($id)
    {
        try {
            $section = Section::findOrFail($id); // Try to get the data from the table
            ////return the frontend
            return response()->json([
                'success' => true,
                'data' => $section,
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
            $section = Section::findOrFail($id);

            // Update the subject
            $section->update([
                "class_id" => $request->class_id,
                "section_name" => $request->section_name,
                "updated_at" => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'The section has been updated',
                'data' => $section,
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $exception->getMessage(),
            ], 404);
        }
    }

    /////Delete section by id.
    public function deleteSection($id)
    {
        try {
            // Find the section
            $subject = Section::findOrFail($id);

            // Delete the section
            $subject->delete();

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
    public function deleteSections(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'section_ids' => 'required|array',
                'section_ids.*' => 'exists:sections,id', // make sure each id is exists in the sections table
            ]);

            /////////// Get the array of student IDs from the request
            $sectionsIds = $request->input('section_ids');

            ///////// Delete the students with the specified IDs
            Section::whereIn('id', $sectionsIds)->delete();

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
