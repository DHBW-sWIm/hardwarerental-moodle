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
require_once(dirname(__FILE__) . '/lib.php');
require_once(dirname(__FILE__) . '/locallib.php');
require_once(dirname(__FILE__)."/view_init.php");
require_once(dirname(__FILE__)."/classes/pdf_class.php");

if(!isset($usergroup[AUTH_LABORATORY_ENGINEER])) die("403 Unauthorized");


$ini = parse_ini_file(__DIR__ . '/.ini');
$camunda_url = $ini['camunda_url'];
$camunda_task_api = $ini['camunda_task_api'];
$camunda_task_variables_api = $ini['camunda_task_variables_api'];
$camunda_task_complete_api = $ini['camunda_task_complete_api'];

//Get url parameter taskid
$taskid = optional_param('taskid', 0, PARAM_NOTAGS); // Task ID

do_header(substr(__FILE__, strpos(__FILE__,'/mod')));


$strName = "Antragsdetails";
echo $OUTPUT->heading($strName);


echo '<br>';

// Implement form for user
require_once(__DIR__ . '/forms/lab_applicationDetailForm.php');
$mform = new labApplicationDetailForm();
$mform->render();

$data = array();

if ($taskid) {
    //Create a client
    $client = new GuzzleHttp\Client();
    // Send a request
    $response = $client->get($camunda_url . $camunda_task_api . '/' . $taskid . $camunda_task_variables_api);

    $body = $response->getBody();
    $variables = json_decode($body, true);
    $return = date("Y-m-d", $variables['applic_to']['value']);

    //Set Data from variables
    $data = array(
        'id' => $cm->id,
        'taskid' => $taskid,
        "name" => $variables['stdnt_firstname']['value'],
        "matr" => $variables['stdnt_id']['value'],
        "email" => $variables['stdnt_email']['value'],
        "date" => $return,
        "resource" => $variables['resource_name']['value'],
        "reason" => $variables['stdnt_reason']['value'],
        "comment" => $variables['stdnt_comment']['value'],
    );
}


//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect(new moodle_url('../ausleihverwaltung/lab_applicationlist_view.php', array('id' => $cm->id)));
} else if ($fromform = $mform->get_data()) {
    //Handle form successful operation, if button is present on form
    //redirect(new moodle_url('../ausleihverwaltung/lab_applicationlist_view.php', array('id' => $cm->id)));

    $url = $camunda_url . $camunda_task_api . '/' . $taskid . $camunda_task_complete_api;

    //Create a client
    $client = new GuzzleHttp\Client();
    // Send HTTP POST and use class camunda_var
    //TODO: make easy to use standard & document
    $response = $client->post($url,
        [GuzzleHttp\RequestOptions::JSON =>
            ['variables' => [
                'DHBW_approval' => new camunda_variable(true, 'boolean'),
            ]
            ]
        ]
    );
    $code = $response->getStatusCode();

    $pdf = new PDF();
    $pdf->BasicInfo($variables['stdnt_firstname']['value'], $variables['stdnt_lastname']['value'], $variables['stdnt_address']['value'], "", $variables['stdnt_city']['value'], $variables['stdnt_phone']['value'], $variables['stdnt_username']['value'], $variables['stdnt_course']['value'], $variables['stdnt_email']['value']);
    $pdf->Signatures(date("Y-m-d"));

    $pdfString = $pdf->Output("", "S");

    $SESSION->pdf = $pdfString;

    // Redirect to the course result page.
    $returnurl = new moodle_url('../ausleihverwaltung/pdf_test.php', array('id' => $cm->id));
    //$returnurl = new moodle_url('../ausleihverwaltung/lab_applicationlist_view.php', array('id' => $cm->id));
    //redirect($returnurl);
    redirect($returnurl);
} else {
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.
    // Set default data (if any)
    // Required for module not to crash as a course id is always needed

    $formdata = $data;
    $mform->set_data($formdata);
    //displays the form
    $mform->display();
}

echo $OUTPUT->single_button(new moodle_url('../ausleihverwaltung/lab_edit_application_view.php', array('id' => $cm->id)), 'Antrag bearbeiten');
echo $OUTPUT->single_button(new moodle_url('../ausleihverwaltung/main_lab_view.php', array('id' => $cm->id)), 'Home');

echo $OUTPUT->footer();
