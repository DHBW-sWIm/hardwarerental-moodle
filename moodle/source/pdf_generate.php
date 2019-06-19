<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints a particular instance of ausleihantrag
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_ausleihverwaltung
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace ausleihantrag with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once(dirname(__FILE__).'/resource_class.php');

global $SESSION;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... ausleihantrag instance ID - it should be named as the first character of the module.

if ($id) {
    $cm           = get_coursemodule_from_id('ausleihverwaltung', $id, 0, false, MUST_EXIST);
    $course       = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $ausleihverwaltung  = $DB->get_record('ausleihverwaltung', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $ausleihverwaltung  = $DB->get_record('ausleihverwaltung', array('id' => $n), '*', MUST_EXIST);
    $course       = $DB->get_record('course', array('id' => $ausleihverwaltung->course), '*', MUST_EXIST);
    $cm           = get_coursemodule_from_instance('ausleihverwaltung', $ausleihverwaltung->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
};

require_login($course, true, $cm);

$event = \mod_ausleihverwaltung\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $ausleihverwaltung);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/ausleihverwaltung/stdnt_resource_view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($ausleihverwaltung->name));
$PAGE->set_heading(format_string($course->fullname));

$SESSION->applicationList = array();

$SESSION->samplePDF = null;

$firstName = 'Nikas';
$lastName = 'Stemberg';

require(dirname(__FILE__).'/pdf/fpdf.php');

class PDF extends FPDF
{
// Page header
    function Header()
    {
        // Arial bold 15
        $this->SetFont('Arial','B',18);
        // Move to the right
        $this->Cell(70);
        // Title
        $this->Cell(30,10,'Leihschein','B',1,'C');
        // Line break
        $this->Ln(12);
    }

// Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Seite '.$this->PageNo().'/{nb}',0,0,'C');
    }

    function BasicInfo()
    {
        $this->Cell(30, 7, 'Vorname: ');
        $this->Cell(40, 7, 'Niklas', 'B');
        $this->Cell(10, 7, '');
        $this->Cell(30, 7, 'Nachname: ');
        $this->Cell(40, 7, 'Stemberg', 'B');
        $this->Ln();
        $this->Cell(30, 7, 'Strasse: ');
        $this->Cell(40, 7, 'Test Strasse 2', 'B');
        $this->Cell(10, 7, '');
        $this->Cell(30, 7, 'PLZ: ');
        $this->Cell(40, 7, '68169', 'B');
        $this->Ln();
        $this->Cell(30, 7, 'Wohnort: ');
        $this->Cell(40, 7, 'Mannheim', 'B');
        $this->Cell(10, 7, '');
        $this->Cell(30, 7, 'Telefon: ');
        $this->Cell(40, 7, '030534604', 'B', 1);
        $this->Cell(30, 7, 'Matrikelnr. : ');
        $this->Cell(40, 7, '534733', 'B');
        $this->Cell(10, 7, '');
        $this->Cell(30, 7, 'Kurs: ');
        $this->Cell(40, 7, 'WWI16SC', 'B');
        $this->Ln();
        $this->Cell(30, 7, 'Email: ');
        $this->Cell(40, 7, 'email@test.com', 'B');
        $this->Ln();
    }
    function Resources()
    {
        $this->Cell(10, 7, 'Pos.', 1);
        $this->Cell(20, 7, 'Menge', 1);
        $this->Cell(50, 7, 'Artikelbezeichnung', 1);
        $this->Cell(40, 7, 'Seriennummer', 1);
        $this->Cell(40, 7, 'Inventarnummer', 1);
        $this->Cell(30, 7, 'Anmerkungen', 1);
        $this->Ln();
        $this->Cell(10, 7, '1', 1);
        $this->Cell(20, 7, '', 1);
        $this->Cell(50, 7, '', 1);
        $this->Cell(40, 7, '', 1);
        $this->Cell(40, 7, '', 1);
        $this->Cell(30, 7, '', 1);
        $this->Ln();
        $this->Cell(10, 7, '2', 1);
        $this->Cell(20, 7, '', 1);
        $this->Cell(50, 7, '', 1);
        $this->Cell(40, 7, '', 1);
        $this->Cell(40, 7, '', 1);
        $this->Cell(30, 7, '', 1);
        $this->Ln();
        $this->Cell(10, 7, '3', 1);
        $this->Cell(20, 7, '', 1);
        $this->Cell(50, 7, '', 1);
        $this->Cell(40, 7, '', 1);
        $this->Cell(40, 7, '', 1);
        $this->Cell(30, 7, '', 1);
        $this->Ln();
    }

    function Conditions()
    {
        $this->Cell(5, 5, '1. ');
        $this->Cell(5, 5, '');
        $this->MultiCell(150, 5, 'Der Leihvertrag kommt zwischen der Dualen Hochschule Baden-Württemberg Mannheim, Coblitzalle 1-9, 68163 Mannheim und der ausleihenden Person zustande.');
        $this->Cell(5, 5, '2. ');
        $this->Cell(5, 5, '');
        $this->MultiCell(150, 5, 'Die AUsleihe und Verwendung der Artikel erfolgt ausschließlich zu Zwecken des Studiums.');
        $this->Cell(5, 5, '3. ');
        $this->Cell(5, 5, '');
        $this->MultiCell(150, 5, 'Die Weitergabe an Dritte ist untersagt.');
        $this->Cell(5, 5, '4. ');
        $this->Cell(5, 5, '');
        $this->MultiCell(150, 5, 'Die Artikel sind stets pfleglich zu behandeln. Beschädigungen, Verlust oder Diebstahl sind umgehend, spätestens bei der Rückgabe bei Herrn Stephan Kaldschmidt oder Herrn Johann Meister von der DHBW Mannheim anzugeben.');
        $this->Cell(5, 5, '5. ');
        $this->Cell(5, 5, '');
        $this->MultiCell(150, 5, 'Die ausleihende Person haftet bei Beschädigung, Verlust oder Diebstahl der betroffenen Artikel.');
        $this->Cell(5, 5, '6. ');
        $this->Cell(5, 5, '');
        $this->MultiCell(150, 5, 'Die Rückgabe hat zum vereinbarten Rückgabetermin zu erfolgen - die Duale Hochschule Baden-Württemberg Mannheim behält sich ansonsten die Versagung weiterer Ausleihen vor.');
        $this->Cell(5, 5, '7. ');
        $this->Cell(5, 5, '');
        $this->MultiCell(150, 5, 'Öffnungszeiten Rückgabe: Mo-Fr 9:00 bis 15:00 Uhr bei Herrn Stephan Kaldschmidt oder Herrn Johann Meister.');
        $this->Cell(5, 5, '8. ');
        $this->Cell(5, 5, '');
        $this->MultiCell(150, 5, 'Es ist strikt untersagt die ausgeliehenen Artikel an andere Personen weiterzuverleihen!');
    }

    function Signatures()
    {
        $this->Cell(40, 7, 'Mannheim den ');
        $this->Cell(40, 7, '17.06.2019', 'B');
        $this->Cell(10, 7, '');
        $this->Cell(90, 7, '', 'B', 1);
        $this->Cell(90, 7, '');
        $this->Cell(50, 7, 'Unterschrift Ausleiher', '', 1);
        $this->Ln();
        $this->Cell(40, 7, 'Rueckgabe am: ');
        $this->Cell(40, 7, '', 'B');
        $this->Cell(10, 7, '');
        $this->Cell(40, 7, 'Ruecknahme durch: ');
        $this->Cell(50, 7, '', 'B', 1);
        $this->Ln();
        $this->Cell(40, 7, 'Bemerkungen: ');
        $this->Cell(140, 7, '', 'B');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,10,'Daten der ausleihenden Person:', 'B', 1);
// Line break
$pdf->Ln(2);

$pdf->SetFont('Arial','',10);
$pdf->BasicInfo();
$pdf->Ln(2);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,10,'Ausgeliehene Artikel:', 'B', 1);
// Line break
$pdf->Ln(2);

$pdf->SetFont('Arial','',10);
$pdf->Resources();
$pdf->Ln(2);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,10,'Leihbedingungen:', 'B', 1);
$pdf->Ln(2);

$pdf->SetFont('Arial','',6);
$pdf->Conditions();
$pdf->Ln(2);

$pdf->SetFont('Arial','',10);
$pdf->Cell(150,10,'Mit meiner nachstehenden Unterschrift bestätige ich die Ausleihe und erkenne die Leihbedingungen an,', '', 1);
$pdf->Ln(4);

$pdf->SetFont('Arial','',12);
$pdf->Signatures();
$pdf->Ln(2);

$pdf->Output("OfficeForm.pdf", "I");
