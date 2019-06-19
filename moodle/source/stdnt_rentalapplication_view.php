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

global $SESSION;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... ausleihantrag instance ID - it should be named as the first character of the module.
$resourceid = optional_param('resourceid', 0, PARAM_INT);

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

$PAGE->set_url('/mod/ausleihverwaltung/stdnt_available_resource_detail_view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($ausleihverwaltung->name));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('ausleihantrag-'.$somevar);
 */

// Output starts here.
echo $OUTPUT->header();

$strName = "Personal Information:";
echo $OUTPUT->heading($strName);

echo '<br>';

require_once(__DIR__ . '/forms/stdnt_antragForm.php');
$mform = new stdntAntragForm();
$mform->render();
//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect(new moodle_url('../ausleihverwaltung/stdnt_available_resourcedetail_view.php', array('id' => $cm->id)));
} else if ($fromform = $mform->get_data()) {
    //Handle form successful operation, if button is present on form
    $resource = new stdClass();
    foreach($SESSION->resourceList as $item) {
        if ($fromform->resourceid == $item->id) {
            $resource = $item;
            break;
        }
    }
    $appId = rand(1,10000);
    $responsibles = array('Prof. Martin', 'Prof. Koslowski', 'Gzuz', 'Tichy');
    $responsible = $responsibles[$fromform->assignee];
    $SESSION->applicationList[] = (object) array('id' => $appId, 'ausleiher' => $fromform->ausleiher, 'matrikel' => $fromform->matrikel, 'mail' => $fromform->mail, 'resource' => $resource->name, 'resourceid' => $resource->id, 'amount' => $resource->quantity, 'grund' => $fromform->grund, 'returndate' => $fromform->returnDate, 'anmerkung' => $fromform->anmerkung, 'assignee' => $responsible, 'applicationtype' => 'Rental Application', 'status' => 'Requested');

    // redirect(new moodle_url('../ausleihverwaltung/stdnt_applicationlist_view.php', array('id' => $cm->id)));

    // Get camunda base url from .ini
    $ini = parse_ini_file(__DIR__ . '/.ini');
    $camunda_url = $ini['camunda_url'];
    $start_process_url = $ini['start_process_url'];
    $process_key = $ini['process_key'];
    $start_process_url_appendix = $ini['start_process_url_appendix'];

    $url = $camunda_url . $start_process_url . $process_key . $start_process_url_appendix;

    //Create a client
    $client = new GuzzleHttp\Client();
    // Send HTTP POST and use class camunda_var
    //TODO: make easy to use standard & document
    include("camunda_var.php");
    $response = $client->post($url,
        [ GuzzleHttp\RequestOptions::JSON =>
            [ 'variables' => [
                'stdnt_name' => new camunda_var($fromform->ausleiher, 'string'),
                'stdnt_matr' => new camunda_var($fromform->matrikel, 'string'),
                'stdnt_length' => new camunda_var($fromform->returnDate, 'string'),
                'stdnt_resource' => new camunda_var($resource->name, 'string'),
                'stdnt_quantity' => new camunda_var($resource->quantity, 'long'),
                'stdnt_mail' => new camunda_var($fromform->mail, 'string'),
            ]
            ]
        ]
    );

    $code = $response->getStatusCode();

    $returnurl = new moodle_url('../ausleihverwaltung/stdnt_applicationlist_view.php', array('id' => $cm->id));
    redirect($returnurl);

} else {
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.
    // Set default data (if any)
    // Required for module not to crash as a course id is always needed
    $formdata = array('id' => $id, 'resourceid' => $resourceid);
    $mform->set_data($formdata);
    //displays the form
    $mform->display();
}

echo $OUTPUT->single_button(new moodle_url('../ausleihverwaltung/main_student_view.php', array('id' => $cm->id)), 'Home');

echo $OUTPUT->footer();