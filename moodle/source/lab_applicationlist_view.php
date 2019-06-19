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

$PAGE->set_url('/mod/ausleihverwaltung/lab_applicationlist_view.php', array('id' => $cm->id));
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

$strName = "Vorhandene Anfragen:";
echo $OUTPUT->heading($strName);

echo '<br>';

//Get constants by using .ini
$ini = parse_ini_file(__DIR__ . '/.ini');
$camunda_url = $ini['camunda_url'];
$camunda_task_api = $ini['camunda_task_api'];
//$camunda_task_filter = $ini['camunda_task_filter'];

//Create a client
$client = new GuzzleHttp\Client();
// Send a request
$response = $client->get($camunda_url . $camunda_task_api . '?processDefinitionKey=Request_Approval_1&taskDefinitionKey=DHBW_Approval');

$body = $response->getBody();
$tasks = json_decode($body, true);

//Tabelle mit camunda
$table = new html_table();
$table->head = array('Task ID', 'Name', 'Created', '');
//Für jeden Datensatz
foreach ($tasks as $task) {
    $id = $task['id'];
    $name = $task['name'];
    $created = $task['created'];
    //Link zum löschen des Verantwortlichen in foreach-Schleife setzen
    $detailButton = $OUTPUT->single_button(new moodle_url('../ausleihverwaltung/lab_application_detail_view.php', array('id' => $cm->id, 'taskid' => $id)), 'Details', $attributes = null);
    //Daten zuweisen an HTML-Tabelle
    $table->data[] = array($id, $name, $created, $detailButton);
}
//Tabelle ausgeben
echo html_writer::table($table);


/*$table = new html_table();
$table->head = array('Typ', 'Name', 'Ressource', 'Status', 'Details');

$type = "Ausleihantrag";
$resource = "iPhone 8";
$name = "Henry Stoll";
$status = "Angefragt";
$htmlLink = html_writer::link(new moodle_url('../ausleihverwaltung/lab_application_detail_view.php', array('id' => $cm->id, 'resourceid' => $id)), 'Details', $attributes=null);

$table->data[] = array($type, $name, $resource, $status, $htmlLink);

echo html_writer::table($table);*/

echo $OUTPUT->single_button(new moodle_url('../ausleihverwaltung/main_lab_view.php', array('id' => $cm->id)), 'Home');

echo $OUTPUT->footer();