<?php

namespace App\Http\Controllers;
//use PDF; 
use TCPDF;
use App\Models\Student; 
use App\Models\Course; 
use App\Models\Instructor;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Models\Certificate;

class CertificateController extends Controller
{
    //
    public function index()
    {
        $data = Certificate::with(['instructor', 'course'])->get();
        return view('backend.certificate.index', compact('data'));
    }

    public function create()
    {
        $userRoleId = auth()->user()->role_id;
        
        if ($userRoleId == 1){
            $instructor = Instructor::all();
            $course = Course::all();
        }
        elseif ($userRoleId == 3) {
            $instructorId = auth()->user()->instructor_id;
            $instructor = Instructor::where('id', $instructorId)->first();
            $course = Course::where('instructor_id', $instructorId)->get();
        }

        return view('backend.certificate.create', compact('instructor','course'));
       
    }

    public function store(Request $request)
    {
        try {
            // Check if a certificate already exists for the given course_id
            $existingCertificate = Certificate::where('course_id', $request->courseId)->first();

            if ($existingCertificate) {
                // If a certificate exists, return a message indicating it can be edited
                return redirect()->back()->with('error', 'A certificate has already been added for this course. You can edit it instead.');
            }

            // Create a new Certificate instance
            $certificate = new Certificate();
            $certificate->course_id = $request->courseId;

            // Get the instructor for the course
            $course = Course::find($request->courseId);
            $certificate->instructor_id = $course->instructor_id;
            $certificate->certificate_type = $request->certificateType;

            // Assign certificate_id based on certificateType
            switch ($request->certificateType) {
                case 'Default':
                    $certificate->certificate_id = 2;
                    break;
                case 'Nova':
                    $certificate->certificate_id = 3;
                    break;
                case 'Inspire':
                    $certificate->certificate_id = 4;
                    break;
                case 'Eclipse':
                    $certificate->certificate_id = 5;
                    break;
                case 'Kings Digi Hub':
                    $certificate->certificate_id = 1;
                    break;
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $imageName = rand(111, 999) . time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/signatures'), $imageName);
                $certificate->image = $imageName;
            }

            // Save the new certificate
            if ($certificate->save()) {
                return redirect()->route('certificates.index')->with('success', 'Data Saved');
            } else {
                return redirect()->back()->withInput()->with('error', 'Please try again');
            }
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Please try again');
        }
    }

    public function edit($id)
    {   
        $decryptedId = encryptor('decrypt', $id);
        $certificate = Certificate::with(['instructor', 'course'])
        ->where('id', $decryptedId)
        ->firstOrFail();
        
        return view('backend.certificate.edit', compact('certificate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    try {           
        // Find the student by ID
        $certificate = Certificate::findOrFail(encryptor('decrypt', $id));
        
        // Update student fields            
            $certificate->certificate_type = $request->certificateType;
            switch ($request->certificateType) {
                case 'Default':
                    $certificate->certificate_id = 2;
                    break;
                case 'Nova':
                    $certificate->certificate_id = 3;
                    break;
                case 'Inspire':
                    $certificate->certificate_id = 4;
                    break;
                case 'Eclipse':
                    $certificate->certificate_id = 5;
                    break;
                case 'Kings Digi Hub':
                    $certificate->certificate_id = 1;
                    break;
            } 
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $imageName = rand(111, 999) . time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/signatures'), $imageName);
            $certificate->image = $imageName;
        }

        // Save the student record
        if ($certificate->save()) {
            return redirect()->route('certificates.index')->with('success', 'Data Saved');
        } else {
            return redirect()->back()->withInput()->with('error', 'Please try again');
        }
    } catch (Exception $e) {
        // Debug the exception
        //dd($e);
        return redirect()->back()->withInput()->with('error', 'Please try again');
    }
}

    
    public function generateCertificate($recipientName, $courseName, $completionDate)
    {
        // Create new PDF document in landscape orientation
        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); // 'L' for landscape

        // Set document information        
        $pdf->SetAuthor('Kings Digital Literacy Hub');
        $pdf->SetTitle('Freelance Bootcamp Course Certificate');
        $pdf->SetSubject('PDF Subject');   

        // Set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins (left, top, right)
        $pdf->SetMargins(10, 20, 10); // 10 mm left and right, 20 mm top
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);

        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Add a page
        $pdf->AddPage();

        // Define the paths to the images
        $imagePath = public_path('images/certificate_back.png');
        $logoPath = public_path('images/kdh_logo_small.png');
        $kdhSignaturePath = public_path('images/Signature.png');
        $instrSignaturePath = public_path('images/Signature.png');


        // Get the page width and height considering the margins
        $pageWidth = $pdf->getPageWidth() - $pdf->getMargins()['left'] - $pdf->getMargins()['right']; // Width after margins
        $pageHeight = $pdf->getPageHeight() - $pdf->getMargins()['top'] - $pdf->getMargins()['bottom']; // Height after margins

        // Add a background image that covers the entire page
        $pdf->Image($imagePath, 10, 20, $pageWidth, $pageHeight, '', '', '', false, 300, '', false, false, 0, false, false, false);

        // Set the position to add content (above the background)
        $pdf->SetXY(10, 30); // Adjust to your needs        

        // Define the HTML content
        $html = <<<EOD
        <table width="100%" border="0" cellpadding="2" cellspacing="2">
            <tr>
                <td colspan="3"><span class="style2"><img src="$logoPath" width="50" height="66" /></span></td>
                <td width="216">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3"><span class="style2"></span></td>
                <td width="216">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" style="font-family: 'Arial', sans-serif; font-size: 32px; font-weight: bold;">CERTIFICATE</td>
                <td width="216">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" style="font-family: 'Georgia', serif; font-size: 14px;">OF COMPLETION</td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td colspan="3">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            
            <tr>
                <td colspan="3" style="font-family: 'Times New Roman', sans-serif; font-size: 28px; font-weight: bold; color:blue">$courseName</td>
                <td>&nbsp;</td>
            </tr>
            
            <tr>
                <td colspan="3">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

            <tr>
                <td colspan="3" style="font-family: 'Arial', serif; font-size: 14px;">This certificate is presented to:</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" style="font-family: 'Courier New', monospace; font-size: 30px; font-weight: bold;"><span class="style3">$recipientName</span></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" style="font-family: 'Arial', serif; font-size: 14px;">Having successfully completed the evaluation of the knowledge and skills gained during the supervised mentoring period,<br>which involved practical applications and guidance, the recipient is now recognized for their dedication and achievement.
                </td>
                <td>&nbsp;</td>
            </tr>            
            <tr>
                <td colspan="3">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><img src="$kdhSignaturePath" width="150" height="20"/></td>
                <td><img src="$instrSignaturePath" width="150" height="20"/></td>
                <td>$completionDate</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td width="226" align="left" style="font-family: 'Arial', sans-serif; font-size: 14px; font-weight: bold;">Name of kdh rep</td>
                <td width="205" align="left" style="font-family: 'Arial', sans-serif; font-size: 14px; font-weight: bold;">Name of Instructor</td>
                <td width="159" align="center" style="font-family: 'Arial', sans-serif; font-size: 14px; font-weight: bold;">certificate link</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td align="left" style="font-family: 'Arial', sans-serif; font-size: 12px;">Head Digital Literacy</td>
                <td align="left" style="font-family: 'Arial', sans-serif; font-size: 12px;">Lead Trainer</td>
                <td align="center" style="font-family: 'Arial', sans-serif; font-size: 12px;"></td>
                <td>&nbsp;</td>
            </tr>
            </table>
        EOD;

        // Write the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        // Close and output PDF document
        return $pdf->Output('certificate.pdf', 'I');
    }   

    public function showCertificate($id)
    {
        
        $recipientName = "Maxwell Akinyooye";
        $courseName = "Freelance Bootcamp"; // Fetch course name
        $completionDate = "24th October 2024"; // Fetch completion date

        return $this->generateCertificate($recipientName, $courseName, $completionDate);
    }

}
