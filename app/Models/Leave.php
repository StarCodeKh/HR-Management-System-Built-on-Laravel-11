<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Session;

class Leave extends Model
{
    use HasFactory;
    protected $fillable = [
        'staff_id',
        'employee_name',
        'leave_type',
        'remaining_leave',
        'date_from',
        'date_to',
        'number_of_day',
        'leave_date',
        'leave_day',
        'status',
        'reason',
        'approved_by',
    ];

    /** Save Record Leave or Update */
    public function applyLeave(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'date_from'  => 'required',
            'date_to'    => 'required',
            'reason'     => 'required',
        ]);

        try {
            Leave::updateOrCreate(
                [
                    'id' => $request->id_record, // Unique attribute(s) to check for existing record
                ],
                [
                    'staff_id'        => Session::get('user_id'),
                    'employee_name'   => Session::get('name'),
                    'leave_type'      => $request->leave_type,
                    'remaining_leave' => $request->remaining_leave,
                    'date_from'       => $request->date_from,
                    'date_to'         => $request->date_to,
                    'number_of_day'   => $request->number_of_day,
                    'leave_date'      => json_encode($request->leave_date),
                    'leave_day'       => json_encode($request->select_leave_day),
                    'status'          => 'Pending',
                    'reason'          => $request->reason,
                    'approved_by'     => Session::get('line_manager'),
                ]
            );
    
            flash()->success('Apply Leave successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e); // Log the error
            flash()->error('Failed Apply Leave :)');
            return redirect()->back();
        }

        
    }

    

    /** Delete Record */
    public function deleteRecord(Request $request) {
        try {
            Leave::destroy($request->id_record);
            flash()->success('Leaves deleted successfully :)');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            flash()->error('Leaves delete fail :)');
            return redirect()->back();
        }
    }
}
