<?php

require_once('../../config.php');
require_once('lib.php');
require_login();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/demo/mail.php');
$PAGE->set_title(get_string('usermailinfo', 'local_demo'));
$PAGE->set_heading(get_string('usermailinfo', 'local_demo'));
$renderer = $PAGE->get_renderer('local_demo');
echo $OUTPUT->header();
echo $renderer->backbutton();
echo $renderer->sentmailusers();
echo $OUTPUT->footer();
