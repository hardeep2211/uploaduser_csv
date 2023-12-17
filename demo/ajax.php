<?php
require_once('../../config.php');
require_once('lib.php');
require_login();
global $DB, $PAGE;
$value = optional_param('value', '', PARAM_RAW);
$context = context_system::instance();
$PAGE->set_context($context);

$userrecords = get_showusers();
foreach ($userrecords as $user) {

    $emailtouser = new stdClass();
    $emailtouser->id = -99;
    $emailtouser->email = $user->email;
    $emailtouser->firstname = $user->firstname;
    $emailtouser->lastname = $user->lastname;
    $emailtouser->firstnamephonetic = '';
    $emailtouser->alternatename = '';
    $emailtouser->middlename = '';
    $emailtouser->lastnamephonetic = '';
    $emailtouser->username = $user->username;

    $emailfromuser = new stdClass();
    $emailfromuser->id = -99;
    $emailfromuser->firstname = 'Ballisticlearning';
    $emailfromuser->lastname = '';
    $emailfromuser->firstnamephonetic = '';
    $emailfromuser->alternatename = '';
    $emailfromuser->middlename = '';
    $emailfromuser->username = '';
    $emailfromuser->lastnamephonetic = '';
    $emailfromuser->email = 'vijay@lingellearning.com';
    $subject = 'New account created';
    $messagetext = 'Hi,
    Your account has been created successsfully!';
    $mail =  email_to_user($emailtouser, $emailfromuser, $subject, $messagetext);
    $record = new stdClass();
    $record->firstname = $user->firstname;
    $record->lastname = $user->lastname;
    $record->timecreated = time();
    if ($mail) {
        $record->mailstatus = 1;
    } else {
        $record->mailstatus = 0;
    }
    $insert = $DB->insert_record('local_usermail', $record);
}
