<?php
namespace App\Observers;

use App\Models\User;
use App\Models\Employee;

class UserObserver
{
    public function created(User $user)
    {
        if ($user->role_name === 'employee') {
            // Generate unique employee ID
            $employeeId = 'EMP-' . strtoupper(uniqid());

            // Create the corresponding employee record
            Employee::create([
                'name' => $user->name,
                'email' => $user->email,
                'birth_date' => null, // Set default or fetch from user data if available
                'gender' => null, // Set default or fetch from user data if available
                'employee_id' => $employeeId,
            ]);
        }
    }
}
