<?php

require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/locallib.php');

include(__DIR__ . '/view_init.php');

global $SESSION;

echo $OUTPUT->heading('Capacity Planning Dashboard');

//Example of using .ini
$ini = parse_ini_file(__DIR__ . '/.ini');
$camunda_url = $ini['camunda_url'];

$client = new GuzzleHttp\Client();

// Implement form for user
require_once(__DIR__ . '/forms/start_form.php');

$plannednumbers = 'studentregistration_demand';
$plannedrecords = $DB->get_records_select($plannednumbers,  $params=null);

// bind data to the variables of the charts
foreach ($plannedrecords as $precord) {
    $wi_am_plan = $precord->wi_am;
    $wi_ds_plan   = $precord->wi_ds;
    $wi_eg_plan   = $precord->wi_eg;
    $wi_eh_plan     = $precord->wi_eh;
    $imbit_plan     = $precord->wi_imbit;
    $wi_sc_plan    = $precord->wi_sc;
    $wi_se_plan    = $precord->wi_se;

}
/*
//Variables
$wi_am_plan = 40;
$wi_ds_plan = 85;
$wi_eg_plan = 30;
$wi_eh_plan = 53;
$imbit_plan = 115;
$wi_sc_plan = 130;
$wi_se_plan = 54;
*/
$wi_am_reg = 16;
$wi_ds_reg = 28;
$wi_eg_reg = 12;
$wi_eh_reg = 31;
$imbit_reg = 77;
$wi_sc_reg = 82;
$wi_se_reg = 39;


//Stacked bar chart Planned vs Actual registration
$chart11 = new core\chart_bar();
$chart11->set_stacked(true);
$chart11->set_title('Submitted demand vs actual registrations');
$series_actual = new \core\chart_series('Students registered in the current year',
    [$wi_am_reg, $wi_ds_reg, $wi_eg_reg, $wi_eh_reg, $imbit_reg, $wi_sc_reg, $wi_se_reg]);
//adjusting stacked bar chart to percentual (registered as % of planned)
$wi_am_plan_chart = $wi_am_plan-$wi_am_reg;
$wi_ds_plan_chart = $wi_ds_plan-$wi_ds_reg;
$wi_eg_plan_chart = $wi_eg_plan-$wi_eg_reg;
$wi_eh_plan_chart = $wi_eh_plan-$wi_eh_reg;
$imbit_plan_chart = $imbit_plan-$imbit_reg;
$wi_sc_plan_chart = $wi_sc_plan-$wi_sc_reg;
$wi_se_plan_chart = $wi_se_plan-$wi_se_reg;
$series_planned = new \core\chart_series('Difference between planned and actual',
    [$wi_am_plan_chart, $wi_ds_plan_chart, $wi_eg_plan_chart, $wi_eh_plan_chart, $imbit_plan_chart, $wi_sc_plan_chart, $wi_se_plan_chart]);
$chart11->add_series($series_actual);
$chart11->add_series($series_planned);
$chart11->set_labels(['WI-AM', 'WI-DS', 'WI-EG', 'WI-EH', 'IMBIT', 'WI-SC', 'WI-SE']);
   $CFG->chart_colorset = ['#E2001A', '#505C64'];

//Felix - YoY bar chart
$chart12 = new \core\chart_bar();
$chart12->set_title('Year-over-year comparison of actual registrations');
$past = new \core\chart_series('2018 registrations', [2, 14, 18, 32, 40, 47, 78, 145, 233, 280, 330, 364]);
$actual = new \core\chart_series('2019 registrations', [0, 4, 7, 14, 25, 38, 81, 169, 240, 281, 325, 372]);
$chart12->add_series($past);
$chart12->add_series($actual);
$chart12->set_labels([date('F', strtotime('-11 month')), date('F', strtotime('-10 month')), date('F', strtotime('-9 month')), date('F', strtotime('-8 month')), date('F', strtotime('-7 month')), date('F', strtotime('-6 month')), date('F', strtotime('-5 month')), date('F', strtotime('-4 month')), date('F', strtotime('-3 month')), date('F', strtotime('-2 month')), date('F', strtotime('-1 month')), date('F') ]);
$CFG->chart_colorset = ['#E2001A', '#505C64'];

//Variables for pie charts
//$wiam_curr_reg = 25;
$wiam_max_cap = 30;

//$wids_curr_reg = 24;
$wids_max_cap = 70;

//$wieg_curr_reg = 27;
$wieg_max_cap = 25;

//$wieh_curr_reg = 21;
$wieh_max_cap = 40;

//$imbit_curr_reg = 28;
$imbit_max_cap = 95;

//$wisc_curr_reg = 29;
$wisc_max_cap = 110;

//$wise_curr_reg = 21;
$wise_max_cap = 50;

//doughnut chart WI-AM
$chart5 = new \core\chart_pie();
$chart5->set_doughnut(true);
$chart5->set_title('WI-AM fill');
$series_doughnut5 = new core\chart_series("WI-AM fill",[$wi_am_reg, $wiam_max_cap]);
$chart5->add_series($series_doughnut5);
$chart5->set_labels(['Currently registered', 'Maximum capacity']);

//doughnut chart WI-DS
$chart7 = new \core\chart_pie();
$chart7->set_doughnut(true);
$chart7->set_title('WI-DS fill');
$series_doughnut7 = new core\chart_series("WI-DS fill" , [$wi_ds_reg, $wids_max_cap]);
$chart7->add_series($series_doughnut7);
$chart7->set_labels(['Currently registered', 'Maximum capacity']);

//doughnut chart WI-EG
$chart8 = new \core\chart_pie();
$chart8->set_doughnut(true);
$chart8->set_title('WI-EG fill');
$series_doughnut8 = new core\chart_series("WI-EG fill",[$wi_eg_reg, $wieg_max_cap]);
$chart8->add_series($series_doughnut8);
$chart8->set_labels(['Currently registered', 'Maximum capacity']);

//doughnut chart WI-EH
$chart9 = new \core\chart_pie();
$chart9->set_doughnut(true);
$chart9->set_title('WI-EH fill');
$series_doughnut9 = new core\chart_series("WI-EH fill",[$wi_eh_reg, $wieh_max_cap]);
$chart9->add_series($series_doughnut9);
$chart9->set_labels(['Currently registered', 'Maximum capacity']);

//doughnut chart IMBIT
$chart10 = new \core\chart_pie();
$chart10->set_doughnut(true);
$chart10->set_title('IMBIT fill');
$series_doughnut10 = new core\chart_series("IMBIT fill",[$imbit_reg, $imbit_max_cap]);
$chart10->add_series($series_doughnut10);
$chart10->set_labels(['Currently registered', 'Maximum capacity']);

//doughnut chart WI-SC
$chart2 = new \core\chart_pie();
$chart2->set_doughnut(true);
$chart2->set_title('WI-SC fill');
$series_doughnut2 = new core\chart_series("WI-SC fill",[$wi_sc_reg, $wisc_max_cap]);
$chart2->add_series($series_doughnut2);
$chart2->set_labels(['Currently registered', 'Maximum capacity']);

//doughnut chart WI-SE
$chart4 = new \core\chart_pie();
$chart4->set_doughnut(true);
$chart4->set_title('WI-SE fill');
$series_doughnut4 = new core\chart_series("WI-SE fill",[$wi_se_reg, $wise_max_cap]);
$chart4->add_series($series_doughnut4);
$chart4->set_labels(['Currently registered', 'Maximum capacity']);


// echo "<style>.grid-container-title{display:grid; grid-template-columns: 100%; background-color: #FFFFFF; padding:10px;} </style><div class='grid-container-title'><div class='header'><h1>Capacity Planning Dashboard</h1></div></div>";
echo "<style>.grid-container{display:grid; grid-template-columns: 50% 50%; background-color: #FFFFFF; padding:10px;} </style><div class='grid-container'> <div>".$OUTPUT->render($chart11)."</div><div>".$OUTPUT->render($chart12)."</div></div>";
echo "<style>.grid-container-heading{display:grid; grid-template-columns: 100%; background-color: #DCE2E6; padding:10px;} </style><div class='grid-container-heading'><div class='header'><h2>Fill rates by course specialisation</h2></div></div>";
echo "<style>.grid-container2{display:grid; grid-template-columns: 25% 25% 25% 25%; background-color: #DCE2E6; padding:10px;} </style><div class='grid-container2'> <div>".$OUTPUT->render($chart5)."</div><div>".$OUTPUT->render($chart7)."</div><div>".$OUTPUT->render($chart8)."</div><div>".$OUTPUT->render($chart9)."</div></div>";
echo "<style>.grid-container3{display:grid; grid-template-columns: 25% 25% 25% 25%; background-color: #DCE2E6; padding:10px;} </style><div class='grid-container3'> <div>".$OUTPUT->render($chart10)."</div><div>".$OUTPUT->render($chart2)."</div><div>".$OUTPUT->render($chart4)."</div></div>";

// navigate back landing page (view.php)
echo $OUTPUT->single_button(new moodle_url('/mod/studentregistration/view.php', array('id' => $cm->id)),
    'Back To Landing Page', $attributes = null);

// Finish the page.
echo $OUTPUT->footer();
