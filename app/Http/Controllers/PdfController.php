<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\People;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {

        $mpdf = new \Mpdf\Mpdf([
            'utf-8', 'A4-C'
        ]);

        $mpdf->useFixedNormalLineHeight = false;
        $mpdf->useFixedTextBaseline = false;
        $mpdf->autoPageBreak = true;



        $mpdf->SetHTMLHeader($request->header, 'E');
        $mpdf->SetHTMLHeader($request->header, 'O');
        $mpdf->setHTMLFooter($request->footer, 'E');
        $mpdf->setHTMLFooter($request->footer, 'O');
        $content = ['ds', 'ds', 'ds', 'ds', 'ds'];

        $ids = $request->get('ids');
        if ($ids) {
            $ids = $this->convertObjectId($ids);
        }
        $peoples = People::raw(function ($collection) use ($ids) {
            return  $collection->find(["_id" => ['$in' => $ids]]);
        });

        $htmlContent = \View::make('invite_pdf', array('content' => $peoples, 'name' => $request->get('name'), 'invfor'=>$request->get('invfor')))->render();
        // exit;
        $mpdf->WriteHTML($htmlContent);

        // $mpdf->WriteHTML('<p>Hello World</p>', \Mpdf\HTMLParserMode::HTML_BODY);


        $mpdf->Output('MyPDF.pdf');

        $file = public_path()."/MyPDF.pdf";
        $headers = array('Content-Type: application/pdf',);
        return \Response::download($file, 'MyPDF.pdf',$headers);
    }

    public function convertObjectId($ids)
    {

        // $ids=explode(',',$ids);
        $idArr = [];
        foreach ($ids as $id) {
            $idArr[] = new ObjectId($id);
        }
        return $idArr;
    }
}
