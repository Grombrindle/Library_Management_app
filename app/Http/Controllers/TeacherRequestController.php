<?php

namespace App\Http\Controllers;

use App\Models\TeacherRequest;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\Resource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeacherRequestController extends Controller
{
    /**
     * Display a listing of pending requests for admins
     */
    public function index()
    {
        $requests = TeacherRequest::with('teacher')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('Admin.FullAdmin.Requests.index', compact('requests'));
    }
    
    /**
     * Show a specific request
     */
    public function show($id)
    {
        $request = TeacherRequest::with('teacher')->findOrFail($id);
        return view('Admin.FullAdmin.Requests.show', compact('request'));
    }
    
    /**
     * Create a new teacher request for adding content
     */
    public function storeAddRequest(Request $request, $targetType)
    {
        // Get the authenticated teacher's ID
        $teacherId = session('teacher');
        
        $payload = $request->except(['_token']);
        
        // Create the request
        TeacherRequest::create([
            'teacher_id' => $teacherId,
            'action_type' => 'add',
            'target_type' => $targetType,
            'payload' => $payload,
        ]);
        
        return redirect()->back()->with('success', 'Your request has been submitted for approval.');
    }
    
    /**
     * Create a new teacher request for editing content
     */
    public function storeEditRequest(Request $request, $targetType, $targetId)
    {
        // Get the authenticated teacher's ID
        $teacherId = session('teacher');
        
        $payload = $request->except(['_token']);
        
        // Create the request
        TeacherRequest::create([
            'teacher_id' => $teacherId,
            'action_type' => 'edit',
            'target_type' => $targetType,
            'target_id' => $targetId,
            'payload' => $payload,
        ]);
        
        return redirect()->back()->with('success', 'Your edit request has been submitted for approval.');
    }
    
    /**
     * Create a new teacher request for deleting content
     */
    public function storeDeleteRequest($targetType, $targetId)
    {
        // Get the authenticated teacher's ID
        $teacherId = session('teacher');
        
        // Get basic information about the target for record
        $payload = [];
        
        switch ($targetType) {
            case 'course':
                $target = Course::find($targetId);
                $payload['name'] = $target->name;
                break;
            case 'lecture':
                $target = Lecture::find($targetId);
                $payload['title'] = $target->title;
                break;
            case 'resource':
                $target = Resource::find($targetId);
                $payload['title'] = $target->title;
                break;
        }
        
        // Create the request
        TeacherRequest::create([
            'teacher_id' => $teacherId,
            'action_type' => 'delete',
            'target_type' => $targetType,
            'target_id' => $targetId,
            'payload' => $payload,
        ]);
        
        return redirect()->back()->with('success', 'Your deletion request has been submitted for approval.');
    }
    
    /**
     * Approve a teacher request
     */
    public function approve($id)
    {
        $teacherRequest = TeacherRequest::findOrFail($id);
        $admin = Auth::guard('admin')->user();
        
        // Only Full Admin (privileges=0) can approve requests
        if ($admin->privileges != 0) {
            return redirect()->back()->with('error', 'Only Full Admins can approve requests.');
        }
        
        // Process the request based on action type
        try {
            $result = $this->processRequest($teacherRequest);
            
            if ($result) {
                $teacherRequest->status = 'approved';
                $teacherRequest->admin_id = $admin->id;
                $teacherRequest->save();
                
                return redirect()->route('teacher-requests.index')
                    ->with('success', 'Request approved and processed successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to process the request.');
            }
            
        } catch (\Exception $e) {
            Log::error('Error processing teacher request: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error processing request: ' . $e->getMessage());
        }
    }
    
    /**
     * Decline a teacher request
     */
    public function decline(Request $request, $id)
    {
        $teacherRequest = TeacherRequest::findOrFail($id);
        $admin = Auth::guard('admin')->user();
        
        // Only Full Admin (privileges=0) can decline requests
        if ($admin->privileges != 0) {
            return redirect()->back()->with('error', 'Only Full Admins can decline requests.');
        }
        
        $teacherRequest->status = 'declined';
        $teacherRequest->admin_response = $request->reason;
        $teacherRequest->admin_id = $admin->id;
        $teacherRequest->save();
        
        return redirect()->route('teacher-requests.index')
            ->with('success', 'Request has been declined.');
    }
    
    /**
     * Process the request based on action type
     */
    private function processRequest(TeacherRequest $teacherRequest)
    {
        $action = $teacherRequest->action_type;
        $targetType = $teacherRequest->target_type;
        $payload = $teacherRequest->payload;
        
        switch ($action) {
            case 'add':
                return $this->processAddRequest($targetType, $payload);
                
            case 'edit':
                return $this->processEditRequest($targetType, $teacherRequest->target_id, $payload);
                
            case 'delete':
                return $this->processDeleteRequest($targetType, $teacherRequest->target_id);
                
            default:
                throw new \Exception("Unknown action type: {$action}");
        }
    }
    
    /**
     * Process an add request
     */
    private function processAddRequest($targetType, $payload)
    {
        switch ($targetType) {
            case 'course':
                $course = new Course();
                foreach ($payload as $key => $value) {
                    $course->$key = $value;
                }
                return $course->save();
                
            case 'lecture':
                $lecture = new Lecture();
                foreach ($payload as $key => $value) {
                    $lecture->$key = $value;
                }
                return $lecture->save();
                
            case 'resource':
                $resource = new Resource();
                foreach ($payload as $key => $value) {
                    $resource->$key = $value;
                }
                return $resource->save();
                
            default:
                throw new \Exception("Unknown target type for add: {$targetType}");
        }
    }
    
    /**
     * Process an edit request
     */
    private function processEditRequest($targetType, $targetId, $payload)
    {
        switch ($targetType) {
            case 'course':
                $course = Course::findOrFail($targetId);
                foreach ($payload as $key => $value) {
                    $course->$key = $value;
                }
                return $course->save();
                
            case 'lecture':
                $lecture = Lecture::findOrFail($targetId);
                foreach ($payload as $key => $value) {
                    $lecture->$key = $value;
                }
                return $lecture->save();
                
            case 'resource':
                $resource = Resource::findOrFail($targetId);
                foreach ($payload as $key => $value) {
                    $resource->$key = $value;
                }
                return $resource->save();
                
            default:
                throw new \Exception("Unknown target type for edit: {$targetType}");
        }
    }
    
    /**
     * Process a delete request
     */
    private function processDeleteRequest($targetType, $targetId)
    {
        switch ($targetType) {
            case 'course':
                $course = Course::findOrFail($targetId);
                return $course->delete();
                
            case 'lecture':
                $lecture = Lecture::findOrFail($targetId);
                return $lecture->delete();
                
            case 'resource':
                $resource = Resource::findOrFail($targetId);
                return $resource->delete();
                
            default:
                throw new \Exception("Unknown target type for delete: {$targetType}");
        }
    }
} 