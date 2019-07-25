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

if(!isset($usergroup[AUTH_LABORATORY_ENGINEER])) die("403 Unauthorized");

do_header(substr(__FILE__, strpos(__FILE__,'/mod')));

$strName = "Open Requests:";
echo $OUTPUT->heading($strName);

echo '<br>';

//Get constants by using .ini
$ini = parse_ini_file(__DIR__ . '/.ini');
$camunda_url = $ini['camunda_url'];
$camunda_task_api = $ini['camunda_task_api'];
$camunda_task_variables_api = $ini['camunda_task_variables_api'];
//$camunda_task_filter = $ini['camunda_task_filter'];

//Create a client
$client = new GuzzleHttp\Client();
// Send a request
$response = $client->get($camunda_url . $camunda_task_api . '?processDefinitionKey=hardwarerental-request-approval&taskDefinitionKey=hardwarerental.DHBW_Approval');

$body = $response->getBody();
$tasks = json_decode($body, true);

//Tabelle mit camunda
$table = new html_table();
$table->head = array('Request', 'Student', 'Request Date', 'Resource', '');
//Für jeden Datensatz
foreach ($tasks as $task) {
    $taskId = $task['id'];
    $name = $task['name'];

    $client2 = new GuzzleHttp\Client();
    $response2 = $client2->get($camunda_url . $camunda_task_api . '/' . $taskId . $camunda_task_variables_api);
    $body = $response2->getBody();
    $variables = json_decode($body, true);

    $date = date("Y-m-d", $variables['req_date']['value']);

    //Link zum löschen des Verantwortlichen in foreach-Schleife setzen
    $detailButton = $OUTPUT->single_button(new moodle_url('../ausleihverwaltung/lab_application_detail_view.php', array('id' => $cm->id, 'taskid' => $taskId)), 'Details', $attributes = null);
    //Daten zuweisen an HTML-Tabelle
    $table->data[] = array($name, ($variables['stdnt_firstname']['value'] . ", " . $variables['stdnt_lastname']['value']), $date, $variables['resource_name']['value'], $detailButton);
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