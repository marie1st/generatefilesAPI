<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Clinic;
use App\Models\Covid;
use NcJoes\OfficeConverter\OfficeConverter;

class ClinicController extends Controller
{
    public function __construct($tempPath = null, $bin = 'libreoffice7.1')
    {
        //$this->filename = $filename;
        $this->tempPath = $tempPath;
    }
    protected function makeCommand($outputDirectory, $outputExtension,$filename) { 
        $oriFile = escapeshellarg($this->file); 
        $outputDirectory = escapeshellarg($outputDirectory); 
        if (pathinfo($this->file,PATHINFO_EXTENSION) == 'docx' || pathinfo($this->file,PATHINFO_EXTENSION) == 'doc' || pathinfo($this->file,PATHINFO_EXTENSION) == 'DOCX' || pathinfo($this->file,PATHINFO_EXTENSION) == 'DOC' ) 
            { return "soffice --headless --convert-to {$outputExtension} {$oriFile} --outdir {$outputDirectory}"; } 
            else if (pathinfo($this->file,PATHINFO_EXTENSION) == 'xlsx' || pathinfo($this->file,PATHINFO_EXTENSION) == 'xls' || pathinfo($this->file,PATHINFO_EXTENSION) == 'XLSX' || pathinfo($this->file,PATHINFO_EXTENSION) == 'XLS') 
                { return "scalc --headless --convert-to {$outputExtension}:calc_pdf_Export {$oriFile} --outdir {$outputDirectory}"; } 
    }
    public function index(Request $request) {
        $clinics = Clinic::all();
        return view('clinic', compact('clinics'));
    }

    public function covidview(Request $request) {
        $covid = Covid::all();
        return view('covidg', compact('covid'));
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
            $name7 = $request->license_no;
            $name2 = $request->name2;
            $date2 = $request->date2;
            $name3 = $request->name3;
            $date3 = $request->date3;
            $name4 = $request->name4;
            $name5 = $request->name5;
            $name6 = $request->address1;

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
            $phpWord->setValue('date1', $date1);
            $phpWord->setValue('name1', $name1);
            $phpWord->setValue('name7', $name7);
            $phpWord->setValue('name2', $name2);
            $phpWord->setValue('date2', $date2);
            $phpWord->setValue('name3', $name3);
            $phpWord->setValue('date3', $date3);
            $phpWord->setValue('name4', $name4);
            $phpWord->setValue('name5', $name5);
            $phpWord->setValue('name6', $name6);
            $phpWord->saveAs($filename);
            
            $FilePath = '/public'.$filename;
            $FilePathPdf = "/storage/app/public/covid/".$filenamePDF;
            $FilePathPdfm = "/storage/app/public/covid/";

            $filenamem = time()."&".$original_name;
            $farraym = explode('.',$filename);
            $filenamePDFm = $farraym[0].$pdfextension;
            $farray = explode('.',$filename);
            $pdfextension = ".pdf";
            $filenamePDF = $farray[0].$pdfextension;
            $phpWordM = \PhpOffice\PhpWord\IOFactory::load($filename);
            $domPdfPath = base_path( 'vendor/dompdf/dompdf');
            \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
            \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
            $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWordM, 'PDF' );
            $pdfWriter->save($filenamePDF);
            //$converter = new OfficeConverter($filename);
            //$converter->convertTo($filenamePDFm);

            $base_html = 'link?http://localhost:8000/storage/app/clinic_files/';
            $my_array = explode('?',$base_html);
            return response()->json([
                'message' => 'Successfully generated CovidTemplate!',
                'covid_file' => $my_array[1].$filenamePDF,
            ], 200);

        }

        public function fitgenerate(Request $request) 
        {
            $request->validate([
                'name1' => 'required|string',
                'hnumber' => 'required|string',
                'bday' => 'required|string',
                'date1' =>'required|string',
                'agerange' => 'required|string',
                'roomno' => 'required|string',
                'gendertype' => 'required|string',
                'name2' => 'required|string',
                'date2' => 'required|string',
                'timerange' => 'required|string',
                'name3' => 'required|string',
                'D1' => 'required|string',
                'D2' => 'required|string',
                'D3' => 'required|string',
                'D4' => 'required|string',
                'D5' => 'required|string',
                'D6' => 'required|string',
                'E1' => 'required|string',
                'E2' => 'required|string',
                'E3' => 'required|string',
                'E4' => 'required|string',
                'E5' => 'required|string',
                'E6' => 'required|string',
                'E7' => 'required|string',
                'E8' => 'required|string',
                'E9' => 'required|string',
                'name4' => 'required|string',
                'licensem' => 'required|string',
                'phone1' => 'required|string',
                'name5' => 'required|string',
                'name6' => 'required|string',
                'date3' => 'required|string',
                'name6' => 'required|string',
                'passport_no' => 'required|string',
                'relationship1' => 'required|string',
                'language1' => 'required|string',
                'witness1' => 'required|string',
                'witness2' => 'required|string'
    
            ]);
            $original_name = "fit.docx";
            $filename = time().$original_name;
            $farray = explode('.',$filename);
            $pdfextension = ".pdf";
            $filenamePDF = $farray[0].$pdfextension;
            $name1 = $request->name1;
            $hnumber = $request->hnumber;
            $bday = $request->bday;
            $date1 = $request->date1;
            $agerage = $request->agerange;
            $roomno = $request->roomno;
            $gendertype = $request->gendertype;
            $name2 = $request->name2;
            $date2 = $request->date2;
            $timerange = $request->timerange;
            $name3 = $request->name3;
            $D1 = $request->D1;
            $D2 = $request->D2;
            $D3 = $request->D3;
            $D4 = $request->D4;
            $D5 = $request->D5;
            $D6 = $request->D6;
            $E1 = $request->E1;
            $E2 = $request->E2;
            $E3 = $request->E3;
            $E4 = $request->E4;
            $E5 = $request->E5;
            $E6 = $request->E6;
            $E7 = $request->E7;
            $E8 = $request->E8;
            $E9 = $request->E9;
            $name4 = $request->name4;
            $licensem = $request->licensem;
            $phone1 = $request->phone1;
            $name5 = $request->name5;
            $name6 = $request->name6;
            $date3 = $request->date3;
            $name7 = $request->name7;
            $passport_no = $request->passport_no;
            $relationship1 = $request->relationship1;
            $language1 = $request->language1;
            $witness1 = $request->witness1;
            $witness2 = $request->witness2;

            $fit = new Fit ([
                'name1' => $request->name1,
                'hnumber' => $request->hnumber,
                'bday' => $request->bday,
                'date1' =>$request->date1,
                'agerange' => $request->agerange,
                'roomno' => $request->roomno,
                'gendertype' => $request->gendertype,
                'name2' => $request->name2,
                'date2' => $request->date2,
                'timerange' => $request->timerange,
                'name3' => $request->name3,
                'D1' => $request->D1,
                'D2' => $request->D2,
                'D3' => $request->D3,
                'D4' => $request->D4,
                'D5' => $request->D5,
                'D6' => $request->D6,
                'E1' => $request->E1,
                'E2' => $request->E2,
                'E3' => $request->E3,
                'E4' => $request->E4,
                'E5' => $request->E5,
                'E6' => $request->E6,
                'E7' => $request->E7,
                'E8' => $request->E8,
                'E9' => $request->E9,
                'name4' => $request->name4,
                'licensem' => $request->licensem,
                'phone1' => $request->phone1,
                'name5' => $request->name5,
                'name6' => $request->name6,
                'date3' => $request->date3,
                'name6' => $request->name6,
                'passport_no' => $request->passport_no,
                'relationship1' => $request->relationship1,
                'language1' => $request->language1,
                'witness1' => $request->witness1,
                'witness2' => $request->witness2
    
            ]);
            $fit->save();

            $file_fit = public_path('/storage/fit.docx');
            $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file_fit);
            $phpWord->setValue('name1', $name1);
            $phpWord->setValue('hnumber', $hnunber1);
            $phpWord->setValue('bday', $bday);
            $phpWord->setValue('roomno', $roomno);
            $phpWord->setValue('date1', $date1);
            $phpWord->setValue('agerange', $agerange);
            $phpWord->setValue('gendertype', $gendertype);
            $phpWord->setValue('name2', $name2);
            $phpWord->setValue('date2', $date2);
            $phpWord->setValue('timerange', $timerange);
            $phpWord->setValue('name3', $name3);
            $phpWord->setValue('D1', $D1);
            $phpWord->setValue('D2', $D2);
            $phpWord->setValue('D3', $D3);
            $phpWord->setValue('D4', $D4);
            $phpWord->setValue('D5', $D5);
            $phpWord->setValue('D6', $D6);
            $phpWord->setValue('E1', $E1);
            $phpWord->setValue('E2', $E2);
            $phpWord->setValue('E3', $E3);
            $phpWord->setValue('E4', $E4);
            $phpWord->setValue('E5', $E5);
            $phpWord->setValue('E6', $E6);
            $phpWord->setValue('E7', $E7);
            $phpWord->setValue('E8', $E8);
            $phpWord->setValue('E9', $E9);
            $phpWord->setValue('name4', $name4);
            $phpWord->setValue('licensem', $licensem);
            $phpWord->setValue('phone1', $phone1);
            $phpWord->setValue('name5', $name5);
            $phpWord->setValue('name6', $name6);
            $phpWord->setValue('date3', $date3);
            $phpWord->setValue('name7', $name7);
            $phpWord->setValue('passport_no', $passport_no);
            $phpWord->setValue('relationship1', $relationship1);
            $phpWord->setValue('language1', $language1);
            $phpWord->setValue('witness1', $witness1);
            $phpWord->setValue('witness2', $witness2);

            $phpWord->saveAs($filename);
            
            $FilePath = '/public'.$filename;
            $FilePathPdf = "/storage/app/public/fit/".$filenamePDF;
            $phpWordM = \PhpOffice\PhpWord\IOFactory::load($filename);
            $domPdfPath = base_path( 'vendor/dompdf/dompdf');
            \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
            \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
            $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWordM, 'PDF' );
            $pdfWriter->save($filenamePDF);

            $base_html = 'link?http://localhost:8000/storage/app/clinic_files/';
            $my_array = explode('?',$base_html);
            return response()->json([
                'message' => 'Successfully generated CovidTemplate!',
                'fit_file' => $my_array[1].$filenamePDF,
            ], 200);

        }

  
}
