<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Clinic;
use \Wa72\Url\Url;

class ClinicController extends Controller
{
    public function index(Request $request) {
        $clinics = Clinic::all();
        return view('clinic', compact('clinics'));
    }
      
    public function store(Request $request)
    {
        $request->validate([
            'clinic_name' => 'required|string',
            'email' => 'required|string|email|unique:clinics',
            'clinic_registration_number' => 'required|string',
            'clinic_file1' =>'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            'phone' => 'required|string',
            'address' => 'required|string',
            'country' => 'required|string',

        ]);
        $uploadedFile = $request->file('clinic_file1');
        $filename = time().$uploadedFile->getClientOriginalName();

        Storage::disk('local')->putFileAs('clinic_files', $uploadedFile, $filename);
        $clinic = new Clinic([
            'clinic_name' => $request->clinic_name,
            'email' => $request->email,
            'clinic_registration_number' => $request->clinic_registration_number,
            'phone' => $request->phone,
            'clinic_file1' => $filename,
            'address' => $request->address,
            'country' => $request->country,
        ]);

        $base_html = 'link?http://localhost:8000/storage/app/clinic_files/';
        $my_array = explode('?',$base_html);
        $clinic->save();
        return response()->json([
            'message' => 'Successfully created clinic!',
            'clinic_file1' => $my_array[1].$filename,
        ], 200);
    }
  
}
