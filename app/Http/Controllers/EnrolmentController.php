<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrolment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;

class EnrolmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:manage-enrolments');
        $this->middleware('throttle:enrolments');
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $enrolments = Enrolment::paginate($perPage);

        return response()->json($enrolments, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $enrolment = new Enrolment();
        $enrolment->user_id = Auth::id();
        $enrolment->course_id = $request->input('course_id');
        $enrolment->save();

        return response()->json(['message' => 'Enrolment created successfully'], 201);
    }

    public function show($id)
    {
        $enrolment = Enrolment::find($id);

        if (!$enrolment) {
            return response()->json(['error' => 'Enrolment not found'], 404);
        }

        $this->authorize('view', $enrolment);

        return response()->json($enrolment, 200);
    }

    public function update(Request $request, $id)
    {
        $enrolment = Enrolment::find($id);

        if (!$enrolment) {
            return response()->json(['error' => 'Enrolment not found'], 404);
        }

        $this->authorize('update', $enrolment);

        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $enrolment->course_id = $request->input('course_id');
        $enrolment->save();

        return response()->json(['message' => 'Enrolment updated successfully'], 200);
    }

    public function destroy($id)
    {
        $enrolment = Enrolment::find($id);

        if (!$enrolment) {
            return response()->json(['error' => 'Enrolment not found'], 404);
        }

        $this->authorize('delete', $enrolment);

        $enrolment->delete();

        return response()->json(['message' => 'Enrolment deleted successfully'], 200);
    }

    public function listByCourse($courseId)
    {
        $enrolment = Enrolment::where('course_id', $courseId)->get();
        if (!$enrolment) {
            return response()->json(['error' => 'Enrolment not found'], 404);
        }

        return response()->json($enrolment, 200);
    }

    public function listByUser($userId)
    {
        $enrolment = Enrolment::where('user_id', $userId)->get();

        if (!$enrolment) {
            return response()->json(['error' => 'Enrolment not found'], 404);
        }

        return response()->json($enrolment, 200);
    }

}
