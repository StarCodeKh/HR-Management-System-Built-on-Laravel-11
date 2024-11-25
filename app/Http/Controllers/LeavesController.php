<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveInformation;
use App\Models\LeavesAdmin;
use App\Models\Leave;
use DateTime;
use Session;
use DB;

class LeavesController extends Controller
{
    /** Leaves Admin Page */
    public function leavesAdmin()
    {
        // Get the currently authenticated user
        $currentUser = auth()->user();
    
        // Check if the current user has the role 'admin'
        if ($currentUser->role_name === 'admin') {
            // Admin sees all leave requests
            $getLeave = DB::table('leaves')
                ->join('users', 'leaves.staff_id', '=', 'users.id')
                ->select(
                    'leaves.*',
                    'users.name as employee_name',
                    'users.avatar as employee_avatar'
                )
                ->get();
        } else {
            // Non-admins see only their own leave requests
            $getLeave = DB::table('leaves')
                ->join('users', 'leaves.staff_id', '=', 'users.id')
                ->where('leaves.staff_id', $currentUser->id)
                ->select(
                    'leaves.*',
                    'users.name as employee_name',
                    'users.avatar as employee_avatar'
                )
                ->get();
        }
    
        // Pass the data to the view
        return view('employees.leaves_manage.leavesadmin', compact('getLeave'));
    }
    
    
    /** Get Information Leave */
    public function getInformationLeave(Request $request)
    {
        try {

            $numberOfDay = $request->number_of_day;
            $leaveType   = $request->leave_type;
            $leaveDay = LeaveInformation::where('leave_type', $leaveType)->first();
            
            if ($leaveDay) {
                $days = $leaveDay->leave_days - ($numberOfDay ?? 0);
            } else {
                $days = 0; // Handle case if leave type doesn't exist
            }

            $data = [
                'response_code' => 200,
                'status'        => 'success',
                'message'       => 'Get success',
                'leave_type'    => $days,
                'number_of_day' => $numberOfDay,
            ];
            
            return response()->json($data);

        } catch (\Exception $e) {
            // Log the exception and return an appropriate response
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }

    /** Apply Leave */
    public function saveRecordLeave(Request $request)
    {
        // Create an instance of the Leave model
        $leave = new Leave();
        // Call the applyLeave method
        return $leave->applyLeave($request);
    }

    /** Delete Record */
    public function deleteLeave(Request $request)
    {
        // Delete an instance of the Leave model
        $delete = new Leave();
        // Call the delete method
        return $delete->deleteRecord($request);
    }

    /** Leave Settings Page */
    public function leaveSettings()
    {
        return view('employees.leaves_manage.leavesettings');
    }

    /** Attendance Admin */
    public function attendanceIndex()
    {
        return view('employees.attendance');
    }

    /** Attendance Employee */
    public function AttendanceEmployee()
    {
        return view('employees.attendanceemployee');
    }

    /** Leaves Employee Page */
    public function leavesEmployee()
    {
        $leaveInformation = LeaveInformation::all();
        $getLeave = Leave::where('staff_id', Session::get('user_id'))->get();

        return view('employees.leaves_manage.leavesemployee',compact('leaveInformation', 'getLeave'));
    }

    /** Shift Scheduling */
    public function shiftScheduLing()
    {
        return view('employees.shiftscheduling');
    }

    /** Shift List */
    public function shiftList()
    {
        return view('employees.shiftlist');
    }
}
