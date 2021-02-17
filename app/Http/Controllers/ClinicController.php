<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Clinic;
use App\Models\Covid;

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
        public function covidgenerate(Request $request) 
        {
            $request->validate([
                'date1' => 'required|string',
                'name1' => 'required|string',
                'license_no' => 'required|string',
                'name2' =>'required|string',
                'date2' => 'required|string',
                'name3' => 'required|string',
                'date3' => 'required|string',
                'name4' => 'required|string',
                'name5' => 'required|string',
                'address1' => 'required|string',
    
            ]);
            $original_name = "covid.docx";
            $filename = time().$original_name;
            $farray = explode('.',$filename);
            $pdfextension = ".pdf";
            $filenamePDF = $farray[0].$pdfextension;
            $date1= $request->date1;
            $name1= $request->name1;
            $license_no = $request->license_no;
            $name2 = $request->name2;
            $date2 = $request->date2;
            $name3 = $request->name3;
            $date3 = $request->date3;
            $name4 = $request->name4;
            $name5 = $request->name5;
            $address1 = $request->address1;

            $covid = new Covid ([
                'date1' => $request->date1,
                'name1' => $request->name1,
                'license_no' => $request->license_no,
                'name2' => $request->name2,
                'date2' => $request->date2,
                'name3' => $request->name3,
                'date3' => $request->date3,
                'name4' => $request->name4,
                'name5' => $request->name5,
                'address1' => $request->address1,
            ]);
            $covid->save();

            $file_covid = public_path('/storage/covid.docx');
            $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file_covid);
            $phpWord->setValue('{date1}', $date1);
            $phpWord->setValue('{name1}', $name1);
            $phpWord->setValue('{license_no}', $license_no);
            $phpWord->setValue('{name2}', $name2);
            $phpWord->setValue('{date2}', $date2);
            $phpWord->setValue('{name3}', $name3);
            $phpWord->setValue('{date3}', $date3);
            $phpWord->setValue('{name4}', $name4);
            $phpWord->setValue('{name5}', $name5);
            $phpWord->setValue('{address1}', $address1);
            $phpWord->saveAs($filename);
            
            $FilePath = "/storage/app/public/".$filename;
            $FilePathPdf = "/storage/app/public/covid/".$filenamePDF;
            $phpWordM = \PhpOffice\PhpWord\IOFactory::load($FilePath);
            $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter( $phpWordM, 'PDF' );
            $pdfWriter->save($FilePathPdf);

            $base_html = 'link?http://localhost:8000/storage/app/clinic_files/';
            $my_array = explode('?',$base_html);
            return response()->json([
                'message' => 'Successfully generated CovidTemplate!',
                'covid_file' => $my_array[1].$filenamePDF,
            ], 200);

        }

  
}
