<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BankInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_name',
        'bank_account_no',
        'account_type',
    ];
}

class BankInformationController extends Controller
{
    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'bank_account_no' => 'required|numeric',
            'account_type' => 'required|string|max:255',
        ]);

        // Save the data to the database
        BankInformation::create([
            'user_id' => $request->user_id,
            'bank_name' => $request->bank_name,
            'bank_account_no' => $request->bank_account_no,
            'account_type' => $request->account_type,
        ]);

        return back()->with('success', 'Bank information saved successfully!');
    }
}
