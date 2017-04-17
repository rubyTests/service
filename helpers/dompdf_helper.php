<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function pdf_create($html, $filename, $stream=TRUE,$paperType) 
{
    require_once("dompdf/dompdf_config_helper.php");
    $date=date("m_d_Y");
    $filename = $filename.'_'.$date.'_'.rand();
    $dompdf = new DOMPDF();
    $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');   
    $dompdf->load_html($html , 'UTF-8');
    if($paperType=='portrait')
    {
       $dompdf->set_paper('A4','portrait'); 
    }
    else if($paperType=='landscape')
    {
        $dompdf->set_paper('A4','landscape');
    }else
    {
        $dompdf->set_paper('A4','portrait'); 
    }
    
     $dompdf->render();
    $canvas = $dompdf->get_canvas(); 
    $font = Font_Metrics::get_font("helvetica"); 
    //$canvas->page_text(490, 815, "Page No: {PAGE_NUM} of {PAGE_COUNT}",$font, 10, array(0,0,0)); 
    $dompdf->stream("$filename".".pdf", array("Attachment" => false));
}
function attachAndSendEmail($html,$filename,$receiptEmailId, $stream=TRUE) 
{
    
    $CI =& get_instance();
    require_once("dompdf/dompdf_config.inc.php");
    $date=date("m_d_Y");
    $filename = $filename.'_'.$date.'_'.rand();
    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->render();
    $output = $dompdf->output();
    $file_to_save = 'uploads/'."$filename".".pdf";
    file_put_contents($file_to_save, $output);
    $CI->load->library('SpineEmail');
    $subject="Sedar Spine Transaction Details";
    
    $body = "Dear Customer,
    Your requested file is attatched and please find the attachment below..
    Regards,
    2015 Sedar Spine. All rights reserved.";
    $body.=$html;
    $attachment=$file_to_save;
    return $CI->spineemail->EmailSend($receiptEmailId,$subject,$body,$attachment);
}




//function pdf_create($html, $filename='', $stream=TRUE) 
//{
//    require_once("dompdf/dompdf_config.inc.php");
//    $savein = 'uploads/';
//    $dompdf = new DOMPDF();
//    $dompdf->load_html($html);
//    $dompdf->render();
//    $canvas = $dompdf->get_canvas();
//    $font = Font_Metrics::get_font("arial", "normal","12px");
//
//    // the same call as in my previous example
//    $canvas->page_text(540, 773, "Page {PAGE_NUM} of {PAGE_COUNT}",
//                   $font, 6, array(0,0,0));
//
//    $pdf = $dompdf->output();      // gets the PDF as a string
//
//    file_put_contents($savein.str_replace("/","-",$filename), $pdf);    // save the pdf file on server
//    unset($html);
//    unset($dompdf); 
//
//}
?>