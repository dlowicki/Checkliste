<?php

if(!isset($_POST['jSign']) && !isset($_POST['jData']) && !isset($_POST['jBearbeiter']))
{
   echo "PDF konnte nicht generiert werden <br>Fehler bei Datenübertragung";
   return;
}

$wplDatum = date("d.m.Y");
$wplBearbeiter = $_POST['jBearbeiter'];

//$wplHeader = '<img src="">';

$wplItems = json_decode($_POST['jData'], true);

$pdfName = 'Checkliste '.$wplBearbeiter.' ' . $wplDatum . '.pdf';
$pdfName = str_replace(' ','_',$pdfName);

/*-------------------------------------------------*/

$html = '
<h4 style="text-align: right; font-weight: 100;">Erstellt am '.$wplDatum.'</h4>
<div></div><div></div><div></div>
<h2 style="font-weight: 100;">Checkliste von <b>'.$wplBearbeiter.'</b></h2>
<div></div><div></div>
<h3 style="font-weight: 100; font-size: 11rem;">Hiermit bestätige ich den Erhalt der folgenden <b>Gegenstände</b> am <b>'.$wplDatum.'</b></h3>
<br>
<br>
<table cellpadding="5" cellspacing="0" style="width: 80%;" border="0">
 <tr style="background-color: #cccccc; padding:5px;">
 <td style="padding:5px;"><b>Anzahl</b></td>
 <td style="text-align: center;"><b>Objekt</b></td>
 <td style="text-align: center;"><b>Inventarnummer</b></td>
 </tr>';

 foreach ($wplItems as $item) {
    if($item[0] == true){
        $html .= '<tr>
                    <td>1</td>
                    <td style="text-align: center">'.$item[2].'</td>
                    <td style="text-align: center">'.$item[3].'</td>
                 </tr>';
    }
 }
 $html .= '</table>';


 $html .= '
<br>
<br>
<h3 style="font-weight: 100; font-size: 11rem;">Folgende Gegenstände wurden nicht bestätigt</h3>
<br>
<br>
<table cellpadding="5" cellspacing="0" style="width: 80%;"border="0">
 <tr style="background-color: #cccccc; padding:5px;">
 <td style="padding:5px;"><b>Anzahl</b></td>
 <td style="text-align: center;"><b>Objekt</b></td>
 <td style="text-align: center;"><b>Inventarnummer</b></td>
 </tr>';

 foreach ($wplItems as $item) {
    if($item[0] == false){
        $html .= '<tr>
                    <td>1</td>
                    <td style="text-align: center">'.$item[2].'</td>
                    <td style="text-align: center">'.$item[3].'</td>
                 </tr>';
    }
 }
 $html .= '</table>';

 $html .=   '<div></div><div></div><div></div>
            <h3 style="font-weight: 100; font-size: 11rem;">Unterschrieben am '.$wplDatum.'</h3>
            <img src="'.$_POST["jSign"].'" width="300" height="80">
            <h3 style="font-weight: 100; font-size: 11rem;">'.$wplBearbeiter.'</h3>
            ';
 

/*-------------------------------------------------*/

require_once('tcpdf/tcpdf.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
 
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($wplBearbeiter);
$pdf->SetTitle('Checkliste ' . $wplBearbeiter);
$pdf->SetSubject('Checkliste ' . $wplBearbeiter);
 
 
// Header und Footer Informationen
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
// Auswahl des Font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 
// Auswahl der MArgins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
 
// Automatisches Autobreak der Seiten
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
 
// Image Scale 
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
// Schriftart
$pdf->SetFont('dejavusans', '', 10);
 
// Neue Seite
$pdf->AddPage();
 
// Fügt den HTML Code in das PDF Dokument ein
$pdf->writeHTML($html, true, false, true, false, '');
 
//Ausgabe der PDF
 
//Variante 1: PDF direkt an den Benutzer senden:
//$pdf->Output($pdfName, 'I');
$pdf->Output(dirname(__FILE__).'/'.$pdfName, 'F');
//echo 'PDF herunterladen: <a href="'.$pdfName.'">'.$pdfName.'</a>';
 

?>