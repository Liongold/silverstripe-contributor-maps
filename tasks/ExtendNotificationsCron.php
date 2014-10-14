<?php
    class ExtendNotificationsCron extends WeeklyTask {
        function process() {
            self::notifyFifteenDays();
            self::notifyWeek();
        }
        private static function notifyWeek() {
            echo "Till: ".date('Y-m-d', strtotime('+7 days'));
            $data = ContributorMaps_Data::get()->filter(array(
                'Confirmed' => 1,
                'Expiry:GreaterThan' => date('Y-m-d'),
                'Expiry:LessThanOrEqual' => date('Y-m-d', strtotime('+7 days'))
            ));
            foreach($data as $submission) {
                echo "<p>$submission->Name $submission->Surname, $submission->Email</p>";
                $subject = "Your LibreOffice Contributor Maps is expiring in 7 days";
                $body = "You are currently listed in the LibreOffice Contributor Maps. "
                        ."These submission are valid for a year. For this reason, we"
                        ." are sending you this final email to notify you that your submission"
                        ." is expiring on ".$submission->Expiry.". If you want"
                        ."to extend your submission for another year, please visit: http://www."
                        ."libreoffice.org/new-contributor-maps/ExtendEntry?key=".$submission->Unique_Key
                        ." If you don't wand to extend this submission, you can ignore this email. ";
                $email = new Email("hostmaster@documentfoundation.org", $submission->Email, $subject, $body);
                $email->send();
            } 
        }
        private static function notifyFifteenDays() {
            echo "Till: ".date('Y-m-d', strtotime('+14 days'));
            $data = ContributorMaps_Data::get()->filter(array(
                'Confirmed' => 1,
                'Expiry:GreaterThan' => date('Y-m-d', strtotime('+7 days')),
                'Expiry:LessThanOrEqual' => date('Y-m-d', strtotime('+14 days'))
            ));
            foreach($data as $submission) {
                echo "<p>$submission->Name $submission->Surname, $submission->Email</p>";
                $subject = "Your LibreOffice Contributor Maps is expiring in 7 days";
                $body = "You are currently listed in the LibreOffice Contributor Maps. "
                        ."These submission are valid for a year. For this reason, we"
                        ." are sending you this friendly email to notify you that your submission"
                        ." is expiring on ".$submission->Expiry.". If you want"
                        ."to extend your submission for another year, please visit: http://www."
                        ."libreoffice.org/new-contributor-maps/ExtendEntry?key=".$submission->Unique_Key
                        ." If you don't wand to extend this submission, you can ignore this email. ";
                $email = new Email("hostmaster@documentfoundation.org", $submission->Email, $subject, $body);
                $email->send();
            }
        }
    }
?>