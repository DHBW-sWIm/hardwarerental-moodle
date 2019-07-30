<?php

require_once(dirname(dirname(__DIR__)) . '/config.php');

require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/locallib.php');
include(__DIR__ . '/view_init.php');

global $SESSION;
global $USER;
// set db table
$tablename = 'studentregistration_demand';

// show all records for user's company
echo $OUTPUT->heading('All records:');

// get records
$records = $DB->get_records_select($tablename, "company = ?", array($USER->institution));

// init html table
$table_all_records = new html_table();
$table_all_records->head = array('Company', 'Year', 'SE', 'SC', 'AM', 'DS', 'EG', 'EH', 'IMBIT');

// bind data to html table
foreach ($records as $record) {
    $company  = $record->company;
    $year     = $record->year;
    $wi_se    = $record->wi_se;
    $wi_sc    = $record->wi_sc;
    $wi_am    = $record->wi_am;
    $wi_ds    = $record->wi_ds;
    $wi_eg    = $record->wi_eg;
    $wi_eh    = $record->wi_eh;
    $wi_imbit = $record->wi_imbit;
    $table_all_records->data[] = array($company, $year, $wi_se, $wi_sc, $wi_am, $wi_ds, $wi_eg, $wi_eh, $wi_imbit);
}

// print table
echo html_writer::table($table_all_records);

// navigate back to add records form (v_demandreg.php)
echo $OUTPUT->single_button(new moodle_url('/mod/studentregistration/v_demandreg.php', array('id' => $cm->id)),
    'Back', $attributes = null);
// Finish the page.
echo $OUTPUT->footer();
