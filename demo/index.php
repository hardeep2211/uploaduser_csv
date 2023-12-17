<?php

require_once('../../config.php');
require_once('uploaduserform.php');
require_once($CFG->dirroot . '/admin/tool/uploaduser/user_form.php');
require_once('lib.php');

require_login();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/demo/index.php');
$PAGE->set_title(get_string('userform', 'local_demo'));
$PAGE->set_heading(get_string('csvuser', 'local_demo'));
$PAGE->requires->js_call_amd('local_demo/script', 'user');
$sitecontext = context_system::instance();
$iid = optional_param('iid', '', PARAM_INT);
$previewrows = optional_param('previewrows', 10, PARAM_INT);
$returnurl = new moodle_url('/local/demo/index.php');
$mform = new local_uploaduserform();
$renderer = $PAGE->get_renderer('local_demo');
if ($hassiteconfig = has_capability('moodle/cohort:assign', $sitecontext) || is_siteadmin()) {

    if (empty($iid)) {
        if ($fromform = $mform->get_data()) {
            $mform1 = new local_uploaduserform();
            $iid = csv_import_reader::get_new_iid('uploaduserform');
            $cir = new csv_import_reader($iid, 'uploaduserform');

            $content = $mform1->get_file_content('userfile');
            $returnurl = new moodle_url('/local/demo/index.php');
            $readcount = $cir->load_csv_content($content, $fromform->encoding, $fromform->delimiter_name);
            $csvloaderror = $cir->get_error();
            unset($content);

            if (!is_null($csvloaderror)) {
                print_error('csvloaderror', '', $returnurl, $csvloaderror);
            }
        } else {
            echo $OUTPUT->header();
            echo $renderer->displaybuttons();
            echo $OUTPUT->heading_with_help(get_string('uploadusers', 'tool_uploaduser'), 'uploadusers', 'tool_uploaduser');
            $mform->set_data($fromform);
            $mform->display();
            echo $OUTPUT->footer();
            die;
        }
    }

    $cir = new csv_import_reader($iid, 'uploaduserform');
    $process = new \tool_uploaduser\process($cir);
    $filecolumns = $process->get_file_columns();

    $mform2 = new admin_uploaduser_form2(
        null,
        ['columns' => $filecolumns, 'data' => ['iid' => $iid, 'previewrows' => $previewrows]]
    );

    // If a file has been uploaded, then process it.
    if ($formdata = $mform2->is_cancelled()) {
        $cir->cleanup(true);
        redirect($returnurl);
    } else if ($formdata = $mform2->get_data()) {
        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('uploadusersresult', 'tool_uploaduser'));

        $process->set_form_data($formdata);
        $process->process();

        echo $OUTPUT->box_start('boxwidthnarrow boxaligncenter generalbox', 'uploadresults');
        echo html_writer::tag('p', join('<br />', $process->get_stats()));
        echo $OUTPUT->box_end();

        if ($process->get_bulk()) {
            echo $OUTPUT->continue_button($bulknurl);
        } else {
            echo $OUTPUT->continue_button($returnurl);
        }
        echo $OUTPUT->footer();
        die;
    }
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('uploaduserspreview', 'tool_uploaduser'));
    $table = new \tool_uploaduser\preview($cir, $filecolumns, $previewrows);
    echo html_writer::tag('div', html_writer::table($table), ['class' => 'flexible-wrap']);
    if ($table->get_no_error()) {
        $mform2->display();
    }
    echo $OUTPUT->footer();
    die;
} else {
    echo $OUTPUT->header();
    echo  html_writer::tag('div', get_string('noaccess', 'local_demo'), array('class' => 'alert alert-info'));
    echo $OUTPUT->footer();
}
