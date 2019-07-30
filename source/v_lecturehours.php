<?php

require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/locallib.php');

include(__DIR__ . '/view_init.php');

global $SESSION;

echo $OUTPUT->heading('Record Planned Lecture Hours');

// Implement form for user
require_once(__DIR__ . '/forms/f_lecturehours.php');

$mform = new flecturehours();
$mform->render();

$tablename = 'studentregistration_hours';
$records = $DB->get_records_select($tablename,  $params=null);
$table_all_records = new html_table();
$table_all_records->head = array('First Name', 'Surname', 'Company', 'No. of lecture hours', 'No. of exam supervisions', 'No. of academic papers');

// bind data to html table
foreach ($records as $record) {
    $firstname = $record->firstname;
    $surname   = $record->surname;
    $company   = $record->company;
    $lhours     = $record->lhours;
    $exams     = $record->exams;
    $papers    = $record->papers;
    $table_all_records->data[] = array($firstname, $surname, $company, $lhours, $exams, $papers);
}

// print table
echo html_writer::table($table_all_records);

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
    //Handle form successful operation, if button is present on form
    $SESSION->formdata = $fromform;
    $record = new stdClass();
    $record->firstname = $fromform->firstname;
    $record->surname = $fromform->surname;
    $record->company = $fromform->company;
    $record->lhours = $fromform->lhours;
    $record->exams = $fromform->exams;
    $record->papers = $fromform->papers;


    $lastinsertid = $DB->insert_record($tablename, $record, false);    // redirect user
    $returnurl = new moodle_url('/mod/studentregistration/v_lecturehours.php', array('id' => $cm->id));
    redirect($returnurl);
} else {
    $formdata = array('id' => $id);
    $mform->set_data($formdata);
    //displays the form
    $mform->display();
}

// navigate back landing page (view.php)
echo $OUTPUT->single_button(new moodle_url('/mod/studentregistration/view.php', array('id' => $cm->id)),
    'Back To Landing Page', $attributes = null);


echo $OUTPUT->footer();
