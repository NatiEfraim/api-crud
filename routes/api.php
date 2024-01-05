<?php

use App\Http\Controllers\Api\SclassController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\SubjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

///////Student class route
////get all
Route::get("/class", [SclassController::class, "index"]);
///create class
Route::post("class/store", [SclassController::class, "store"]);
////find by id
Route::get("/class/edit/{id}", [SclassController::class, "edit"]);
///update by id
Route::put("/class/update/{id}", [SclassController::class, "update"]);
////delete bu id
Route::delete("/class/delete/{id}", [SclassController::class, "deleteClass"]);
///////delete classes by array of id
Route::delete('/class/delete', [SclassController::class, 'deleteClass']);



///////Subject class route
////get all
Route::get("/subject", [SubjectController::class, "index"]);
///create subject
Route::post("subject/store", [SubjectController::class, "store"]);
////find by id
Route::get("/subject/edit/{id}", [SubjectController::class, "edit"]);
///update by id
Route::put("/subject/update/{id}", [SubjectController::class, "update"]);
////delete bu id
Route::delete("/subject/delete/{id}", [SubjectController::class, "deleteSubject"]);
///////delete Students by array of id
Route::delete('/subject/delete', [SubjectController::class, 'deleteSubjects']);



///////Section class route
////get all
Route::get("/section", [SectionController::class, "index"]);
///create section
Route::post("section/store", [SectionController::class, "store"]);
////find by id
Route::get("/section/edit/{id}", [SectionController::class, "edit"]);
///update by id
Route::put("/section/update/{id}", [SectionController::class, "update"]);
////delete bu id
Route::delete("/section/delete/{id}", [SectionController::class, "deleteSection"]);
///////delete Sections by array of id
Route::delete('/section/delete', [SectionController::class, 'deleteSections']);



///////Student class route
////get all
Route::get("/student", [StudentController::class, "index"]);
///create student
Route::post("student/store", [StudentController::class, "store"]);
////find by id
Route::get("/student/edit/{id}", [StudentController::class, "edit"]);
///update by id
Route::put("/student/update/{id}", [StudentController::class, "update"]);
////delete by id
Route::delete("/student/delete/{id}", [StudentController::class, "deleteStudent"]);
///////delete Students by array of id
Route::delete('/students/delete', [StudentController::class, 'deleteStudents']);
