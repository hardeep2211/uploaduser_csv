<?php
require_once('../../config.php');
require_once('lib.php');
class local_demo_renderer extends plugin_renderer_base
{
    public function fetchuserlist()
    {
        $o = '';
        $results = get_showusers();
        if ($results) {
            $table = new html_table();
            $table->head = array(
                get_string('id', 'local_demo'),
                get_string('firstname', 'local_demo'),
                get_string('lastname', 'local_demo'),
                get_string('email', 'local_demo'),

            );
            foreach ($results as $result) {
                $table->data[] = array($result->id, $result->firstname, $result->lastname, $result->email);
            }
            $o .= html_writer::table($table);
        } else {
            $o .= html_writer::div('No record found', null, array('class' => 'alert alert-primary'));
        }
        return $o;
    }

    public function sentmailusers()
    {
        $o = '';
        $results = get_sentmailusers();
        if ($results) {
            $table = new html_table();
            $table->head = array(
                get_string('id', 'local_demo'),
                get_string('firstname', 'local_demo'),
                get_string('lastname', 'local_demo'),
                get_string('status', 'local_demo'),
                get_string('senttime', 'local_demo'),
            );
            foreach ($results as $result) {
                $mailseentdate = date("d/m/y", $result->timecreated);
                $table->data[] = array($result->id, $result->firstname, $result->lastname, $result->mailstatus, $mailseentdate);
            }
            $o .= html_writer::table($table);
        } else {
            $o .= html_writer::div('No record found', null, array('class' => 'alert alert-primary'));
        }
        return $o;
    }

    public function displaybuttons()
    {
        $button = '';
        $userlisturl = new moodle_url('/local/demo/user.php');
        $mailurl = new moodle_url('/local/demo/mail.php');
        $button .= html_writer::link('', get_string('sendmailtouser', 'local_demo'), array('id' => 'sendmail', 'class' => 'btn btn-warning float-right'));
        $button .= html_writer::link($userlisturl, get_string('listusers', 'local_demo'), array('id' => 'userinfo', 'class' => 'btn btn-primary  float-right  mr-2'));
        $button .= html_writer::link($mailurl, get_string('checkmailstatus', 'local_demo'), array('id' => 'checkmailstatus', 'class' => 'btn btn-secondary float-right  mr-2'));
        return $button;
    }

    public function backbutton()
    {
        $url = new moodle_url('/local/demo/index.php');
        $button = html_writer::link($url, get_string('backlink', 'local_demo'), array('id' => 'allbtn', 'class' => 'btn btn-info  float-right'));
        return $button;
    }
}
