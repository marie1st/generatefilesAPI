<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Clinic;
use App\Models\Covid;
use App\Models\Fit;
use App\Models\Files;
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
                'email' => 'required|string',
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

            $docxextension = ".docx";
            $filenameDOCX = $farray[0].$docxextension;
            $JPEGextension = ".jpeg";
            $filenameJPEG = $farray[0].$JPEGextension;
            $email = $request->email;
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
           // $converter = new Converter();
            //$parameters = (new LowrapperParameters())->setInputFile($filename)
                                                   // ->setOutputForm()
                                                   // ->setOutputFile($filenamePDFm);

            $base_html = 'link?http://localhost:8000/public/';
            $my_array = explode('?',$base_html);
            $covid_fileDOCX = $my_array[1].$filename;
            $covid_filePDF = $my_array[1].$filenamePDF;
            $covid_fileJPEG = $my_array[1].$filenameJPEG;

            $covid = new Covid ([
                'email' => $request->email,
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
                'covid_filePDF' => $covid_filePDF,
                'covid_fileDOCX' => $covid_fileDOCX,
                'covid_fileJPEG' => $covid_fileJPEG
            ]);
            $covid->save();
            return response()->json([
                'message' => 'Successfully generated CovidTemplate!',
                'covid_file' => $my_array[1].$filenamePDF,
            ], 200);

        }

        public function fitgenerate(Request $request) 
        {
            $request->validate([
                'email' => 'required|string',
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
                'name7' => 'required|string',
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
            $docxextension = ".docx";
            $filenameDOCX = $farray[0].$docxextension;
            $JPEGextension = ".jpeg";
            $filenameJPEG = $farray[0].$JPEGextension;
            $email = $request->email;
            $name1 = $request->name1;
            $hnumber = $request->hnumber;
            $bday = $request->bday;
            $date1 = $request->date1;
            $agerange = $request->agerange;
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

            $file_fit = public_path('/storage/fit.docx');
            $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file_fit);
            $phpWord->setValue('name1', $name1);
            $phpWord->setValue('hnumber', $hnumber);
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
            

            $base_html = 'link?http://localhost:8000/public/';
            $my_array = explode('?',$base_html);
            $fit_fileDOCX = $my_array[1].$filename;
            $fit_filePDF = $my_array[1].$filenamePDF;
            $fit_fileJPEG = $my_array[1].$filenameJPEG;


            $fit = new Fit ([
                'email' => $request->email,
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
                'name7' => $request->name7,
                'name6' => $request->name6,
                'passport_no' => $request->passport_no,
                'relationship1' => $request->relationship1,
                'language1' => $request->language1,
                'witness1' => $request->witness1,
                'witness2' => $request->witness2,
                'fit_filePDF' => $fit_filePDF,
                'fit_fileDOCX' => $fit_fileDOCX,
                'fit_fileJPEG' => $fit_fileJPEG
    
            ]);
            $fit->save();

            return response()->json([
                'message' => 'Successfully generated CovidTemplate!',
                'fit_file' => $my_array[1].$filenamePDF,
            ], 200);

        }

    public function requestcovidfile(Request $request) {
            $request->validate([
            'email' => 'required|string|email',
            ]);
            $email = $request->email;
            $covids = Covid::where('email', $email)->first();
            $covid_docx = $covids->covid_fileDOCX;
            $covid_pdf = $covids->covid_filePDF;
            $covid_jpeg = $covids->covid_fileJPEG;
            return response()->json([
                    'covid_docx' => $covid_docx,
                    'covid_pdf' => $covid_pdf,
                    'covid_jpeg' => $covid_jpeg
            ], 200);
            

    }

    public function requestfitfile(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $email = $request->email;
        $fits = Fit::where('email', $email)->first();
        $fit_docx = $fits->fit_fileDOCX;
        $fit_pdf = $fits->fit_filePDF;
        $fit_jpeg = $fits->fit_fileJPEG;
        return response()->json([
                'fit_docx' => $fit_docx,
                'fit_pdf' => $fit_pdf,
                'fit_jpeg' => $fit_jpeg
        ], 200);
        
}

public function requesttor8file(Request $request) {
    $request->validate([
        'email' => 'required|string|email',
    ]);
    $email = $request->email;
    $tors = Files::where('email', $email)->first();
    $tor8_docx = $tors->tor8_fileDOCX;
    $tor8_pdf = $tors->tor8_filePDF;
    $tor8_jpeg = $tors->tor8_fileJPEG;
    return response()->json([
            'tor8_docx' => $tor8_docx,
            'tor8_pdf' => $tor8_pdf,
            'tor8_jpeg' => $tor8_jpeg
    ], 200);
    
}


public function tor8generate(Request $request) 
{
    $request->validate([
        'email' => 'required|string',
        'D1' => 'required|string',
        'D2' => 'required|string',
        'D3' => 'required|string',
        'D4' =>'required|string',
        'D5' => 'required|string',
        'departure1' => 'required|string',
        'arrival1' => 'required|string',
        'seat1' => 'required|string',
        'date1' => 'required|string',
        'name1' => 'required|string',
        'nationality1' => 'required|string',
        'age1' => 'required|string',
        'D6' => 'required|string',
        'D7' => 'required|string',
        'D8' => 'required|string',
        'D9' => 'required|string',
        'passport1' => 'required|string',
        'others1' => 'required|string',
        'accom1' => 'required|string',
        'list1' => 'required|string',
        'F1' => 'required|string',
        'F2' => 'required|string',
        'F3' => 'required|string',
        'F4' => 'required|string',
        'F5' => 'required|string',
        'F6' => 'required|string',
        'F7' => 'required|string',
        'F8' => 'required|string',
        'F9' => 'required|string',
        'G1' => 'required|string',
        'others2' => 'required|string',
        'passenger1' => 'required|string',
        'officer1' => 'required|string'

    ]);
    $original_name = "TOR8.docx";
    $filename = time().$original_name;
    $farray = explode('.',$filename);
    $pdfextension = ".pdf";
    $filenamePDF = $farray[0].$pdfextension;
    $docxextension = ".docx";
    $filenameDOCX = $farray[0].$docxextension;
    $JPEGextension = ".jpeg";
    $filenameJPEG = $farray[0].$JPEGextension;
    $email = $request->email;
    $D1 = $request->D1;
    $D2 = $request->D2;
    $D3 = $request->D3;
    $D4 = $request->D4;
    $D5 = $request->D5;
    $departure1 = $request->departure1;
    $arrival1 = $request->arrival1;
    $seat1 = $request->seat1;
    $date1 = $request->date1;
    $name1 = $request->name1;
    $nationality1 = $request->nationality1;
    $age1 = $request->age1;
    $D6 = $request->D6;
    $D7 = $request->D7;
    $D8 = $request->D8;
    $D9 = $request->D9;
    $passport1 = $request->passport1;
    $others1 = $request->others1;
    $accom1 = $request->accom1;
    $list1 = $request->list1;
    $F1 = $request->F1;
    $F2 = $request->F2;
    $F3 = $request->F3;
    $F4 = $request->F4;
    $F5 = $request->F5;
    $F6 = $request->F6;
    $F7 = $request->F7;
    $F8 = $request->F8;
    $F9 = $request->F9;
    $G1 = $request->G1;
    $others2 = $request->others2;
    $passenger1 = $request->passenger1;
    $officer1 = $request->officer1;



    $file_tor8 = public_path('/storage/TOR8.docx');
    $phpWord = new \PhpOffice\PhpWord\TemplateProcessor($file_tor8);
    $phpWord->setValue('D1', $D1);
    $phpWord->setValue('D2', $D2);
    $phpWord->setValue('D3', $D3);
    $phpWord->setValue('D4', $D4);
    $phpWord->setValue('D5', $D5);
    $phpWord->setValue('D6', $D6);
    $phpWord->setValue('D7', $D7);
    $phpWord->setValue('D8', $D8);
    $phpWord->setValue('D9', $D9);
    $phpWord->setValue('departure1', $departure1);
    $phpWord->setValue('arrival1', $arrival1);
    $phpWord->setValue('seat1', $seat1);
    $phpWord->setValue('date1', $date1);
    $phpWord->setValue('name1', $name1);
    $phpWord->setValue('nationality1', $nationality1);
    $phpWord->setValue('age1', $age1);
    $phpWord->setValue('others1', $others1);
    $phpWord->setValue('accom1', $accom1);
    $phpWord->setValue('list1', $list1);
    $phpWord->setValue('F1', $F1);
    $phpWord->setValue('F2', $F2);
    $phpWord->setValue('F3', $F3);
    $phpWord->setValue('F4', $F4);
    $phpWord->setValue('F5', $F5);
    $phpWord->setValue('F6', $F6);
    $phpWord->setValue('F7', $F7);
    $phpWord->setValue('F8', $F8);
    $phpWord->setValue('F9', $F9);
    $phpWord->setValue('G1', $G1);
    $phpWord->setValue('others2', $others2);
    $phpWord->setValue('passenger1', $passenger1);
    $phpWord->setValue('officer1', $officer1);

    $phpWord->saveAs($filename);

    
    
    $FilePath = '/public'.$filename;
    $FilePathPdf = "/storage/app/public/fit/".$filenamePDF;
    $phpWordM = \PhpOffice\PhpWord\IOFactory::load($filename);
    $domPdfPath = base_path( 'vendor/dompdf/dompdf');
    \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
    \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
    $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWordM, 'PDF' );
    $pdfWriter->save($filenamePDF);
    

    $base_html = 'link?http://localhost:8000/public/';
    $my_array = explode('?',$base_html);
    $tor8_fileDOCX = $my_array[1].$filename;
    $tor8_filePDF = $my_array[1].$filenamePDF;
    $tor8_fileJPEG = $my_array[1].$filenameJPEG;


    $tor8 = new Files ([
        'email' => $request->email,
        'D1' => $request->D1,
        'D2' => $request->D2,
        'D3' => $request->D3,
        'D4' => $request->D4,
        'D5' => $request->D5,
        'departure1' => $request->departure1,
        'arrival1' => $request->arrival1,
        'seat1' => $request->seat1,
        'date1' => $request->date1,
        'name1' => $request->name1,
        'nationality1' => $request->nationality1,
        'age1' => $request->age1,
        'D6' => $request->D6,
        'D7' => $request->D7,
        'D8' => $request->D8,
        'D9' => $request->D9,
        'passport1' => $request->passport1,
        'others1' => $request->others1,
        'accom1' => $request->accom1,
        'list1' => $request->list1,
        'F1' => $request->F1,
        'F2' => $request->F2,
        'F3' => $request->F3,
        'F4' => $request->F4,
        'F5'=> $request->F5,
        'F6' => $request->F6,
        'F7' => $request->F7,
        'F8' => $request->F8,
        'F9' => $request->F9,
        'G1' => $request->G1,
        'others2' => $request->others2,
        'passenger1' => $request->passenger1,
        'officer1' => $request->officer1,
        'tor8_filePDF' => $tor8_filePDF,
        'tor8_fileDOCX' => $tor8_fileDOCX,
        'tor8_fileJPEG' => $tor8_fileJPEG
        
    

    ]);
    $tor8->save();

    return response()->json([
        'message' => 'Successfully generated TOR8Template!',
        'tor8_file' => $my_array[1].$filenamePDF,
    ], 200);

}
}
