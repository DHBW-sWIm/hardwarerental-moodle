<?php

require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/locallib.php');

include(__DIR__ . '/view_init.php');

global $SESSION;

echo $OUTPUT->heading('Year to Year Deviation');

// Implement form for user
// v_demandreg.php gets fdemandreg class which extends moodleform

require_once(__DIR__ . '/forms/f_deviation.php');

$mform = new fdeviation();

$mform->render();

// init html table for output
$table_all_records = new html_table();
$table_all_records->head = array('Company', 'Year', 'Total Students', 'Year', 'Total Students', 'Delta');

// table name for sql requests
$tablename = 'studentregistration_demand';

// get planning year
$year = new stdClass();
$year = $DB->get_record_sql('SELECT MAX(YEAR) as year FROM mdl_studentregistration_demand');

// get all companies (used as keys)
$companies = array();
$companies = $DB->get_fieldset_sql('SELECT DISTINCT(company) FROM mdl_studentregistration_demand WHERE year >= :prev',
    array('prev'=>($year->year)-1));

// get all records for planning year
$planned = new stdClass();
$planned = $DB->get_records_select($tablename, "year = ?", array($year->year));

// get all records for previous year
$actual = new stdClass();
$actual = $DB->get_records_select($tablename, "year = ?", array(($year->year)-1));

// calculate delta
foreach ($companies as $company) {
    $minuend = 0;
    $subtrahend = 0;

    //check for values for key in planning table
    foreach ($planned as $struct) {
        if ($struct->company == $company) {
            $minuend = $struct->wi_se + $struct->wi_sc + $struct->wi_am + $struct->wi_ds + $struct->wi_eg + $struct->wi_eh +
                $struct->wi_imbit;
            break;
        }
    }

    //check for values for key in actual table
    foreach ($actual as $struct) {
        if ($struct->company == $company) {
            $subtrahend = $struct->wi_se + $struct->wi_sc + $struct->wi_am + $struct->wi_ds + $struct->wi_eg + $struct->wi_eh +
                $struct->wi_imbit;
            break;
        }
    }

    //calculate result
    $result = $minuend - $subtrahend;

    //add row to html table
    $table_all_records->data[] = array($company, ($year->year)-1, $subtrahend, $year->year, $minuend, $result);
}

// output html table
echo html_writer::table($table_all_records);


//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
    //Handle form successful operation, if button is present on form
    $returnurl = new moodle_url('/mod/studentregistration/view.php', array('id' => $cm->id));
    redirect($returnurl);
} else {
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.

    // Set default data (if any)
    // Required for module not to crash as a course id is always needed
    $formdata = array('id' => $id);
    $mform->set_data($formdata);
    //displays the form
    $mform->display();
}

// navigate back landing page (view.php)
echo $OUTPUT->single_button(new moodle_url('/mod/studentregistration/view.php', array('id' => $cm->id)),
    'Back To Landing Page', $attributes = null);

// Finish the page.
echo $OUTPUT->footer();
