<?php
    class ContributorMaps extends Page {
        //
    }
    class ContributorMaps_Data extends DataObject {
        private static $db = array(
            'Name' => 'Text',
            'Surname' => 'Text',
            'Email' => 'Varchar',
            'Location' => 'Varchar',
            'Skills_Base' => 'Boolean',
            'Skills_Calc' => 'Boolean',
            'Skills_Design' => 'Boolean',
            'Skills_Dev' => 'Boolean',
            'Skills_Doc'=> 'Boolean',
            'Skills_Draw' => 'Boolean',
            'Skills_Impress' => 'Boolean',
            'Skills_Infra' => 'Boolean',
            'Skills_l10n' => 'Boolean',
            'Skills_Marketing' => 'Boolean',
            'Skills_Math' => 'Boolean',
            'Skills_QA' => 'Boolean',
            'Skills_Writer' => 'Boolean',
            'Unique_Key' => 'Varchar',
            'Confirmed' => 'Boolean',
            'Expiry' => 'Date',
            'EditToken' => 'Varchar',
            'EditTokenExpires' => 'Date'
        );
    }
    class ContributorMaps_Controller extends Page_controller {
        private static $allowed_actions = array(
            'RegistrationForm',
            'DataOutput',
            'AccountConfirmation',
            'ExtendEntry',
            'Feedback',
            'Registered',
            'RequestEditForm',
            'processEditForm'
        );
        public function RegistrationForm() {
            if($this->request->getVar('key') && $this->request->getVar('token')) {
                $key = $this->request->getVar('key');
                $token = $this->request->getVar('token');
            }else if($this->request->isPOST()) {
                $key = $this->request->requestVar('Key');
                $token = $this->request->requestVar('Token');
            }
            if($key && $token) {
                $memory = ContributorMaps_Data::get()->filter(array(
                    'Unique_Key' => $key,
                    'EditToken' => $token,
                    'EditTokenExpires:GreaterThanOrEqual' => date('Y-m-d')
                ))->First();
                if(!$memory) {
                    return $this->redirect($this->Link("?action=edit&status=2"));
                }
            }else if(Session::get("FormInfo.RegistrationForm_Edit.data")) {
                $memory = Session::get("FormInfo.RegistrationForm_Edit.data");
            }
            $fields = new FieldList(
                new TextField('Name', 'Name*'),
                new TextField('Surname', 'Surname*'),
                new EmailField('Email', 'Email*'),
                new TextField('Location', 'Location* (e.g. Berlin, Deutschland)'),
                new HeaderField('Skills'),
                new CheckboxField('Skills_Design', 'Design'),
                new CheckboxField('Skills_Dev', 'Development'),
                new CheckboxField('Skills_Doc', 'Documentation'),
                new CheckboxField('Skills_Infra', 'Infrastructure'),
                new CheckboxField('Skills_l10n', 'Localisation'),
                new CheckboxField('Skills_Marketing', 'Marketing'),
                new CheckboxField('Skills_QA', 'Quality Assurance'),
                new CheckboxField('Skills_Base', 'Support - Base'),
                new CheckboxField('Skills_Calc', 'Support - Calc'),
                new CheckboxField('Skills_Draw', 'Support - Draw'),
                new CheckboxField('Skills_Impress', 'Support - Impress'),
                new CheckboxField('Skills_Math', 'Support - Math'),
                new CheckboxField('Skills_Writer', 'Support - Writer')
            );
            if($memory) {
                $fields->push(new HiddenField('Key','',$key));
                $fields->push(new HiddenField('Token','',$token));
                $actions = new FieldList(
                    new FormAction('processEditForm', 'Update')
                );
            }else{
                $recaptcha = new RecaptchaField('Captcha');
                $recaptcha->jsOptions = array('theme' => 'white');
                $fields->push($recaptcha);
                $actions = new FieldList(
                    new FormAction('processForm', 'Register')
                );
            }
            $validator = new RequiredFields('Name', 'Surname', 'Email', 'Location');
            $form = new Form($this, 'RegistrationForm', $fields, $actions, $validator);
            //Load previously submitted data
            if($memory) {
                $form->loadDataFrom($memory);
            }else{
                $form->loadDataFrom(Session::get("FormInfo.{$form->FormName()}.data"));
                Session::clear("FormInfo.{$form->FormName()}.data");
            }
            return $form;
        }
        public function processForm($data, $form) {
            $errors = 0;
            //Check if Email is used
            if(ContributorMaps_Data::get()->filter('Email', $data['Email'])->exists()) {
                $form->addErrorMessage('Email', 'Sorry, this email address is already being used. ', 'bad');
                $errors++;
            };
            if(!($data['Skills_Base'] || $data['Skills_Calc'] || $data['Skills_Dev']
                || $data['Skills_Doc'] || $data['Skills_Draw'] || $data['Skills_Impress'] || $data['Skills_Infra'] 
                || $data['Skills_l10n'] || $data['Skills_Marketing'] || $data['Skills_Math'] || $data['Skills_QA']
                || $data['Skills_Writer'])) {
                $form->addErrorMessage('Skills', 'Sorry, you must choose at least 1 skill. ', 'bad');
                $errors++;
            };
            if($errors > 0) {
                Session::set("FormInfo.{$form->FormName()}.data", $data);
                return $this->redirect($this->Link("?registered=2"));
            };
            $submission = new ContributorMaps_Data();
            $form->saveInto($submission);
            $key = sha1(microtime(true).mt_rand(10000,90000));
            $submission->Unique_Key = $key;
            $submission->Confirmed = 0;
            $submission->Expiry = strtotime('+1 year');
            $submission->EditToken = 0;
            $submission->EditTokenRequest = date('Y-m-d');
            $submission->write(); 
            //Email User Confirmation Link
            $subject = "Please confirm your submission to the LibreOffice Contributor Maps";
            $body = "Thanks for your submission to the LibreOffice Contributor Maps! "
                    ."However, in order to ensure that you made this submission, you"
                    ." are required to confirm this email address by going to http://"
                    ."www.libreoffice.org/new-contributor-maps/AccountConfirmation?key=$key"
                    .". Your listing will be published to the site for a period of 1 year. After"
                    ." this period we will contact you again so that you can review and extend "
                    ."your information. Please note that your email address will be shown in public"
                    ." on The LibreOffice Contributor Maps site. ";
            $email = new Email("hostmaster@documentfoundation.org", $data['Email'], $subject, $body);
            $email->send();
            return $this->redirect($this->Link("?registered=1"));
        }
        public function DataOutput() {
            $data = ContributorMaps_Data::get()->filter(array(
                'Confirmed' => '1',
                'Expiry:GreaterThan' => date('Y-m-d')
            ));
            return $data;
        }
        public function AccountConfirmation($request) {
            $key = $request->getVar('key');
            $submission = ContributorMaps_Data::get()->filter(array(
                'Unique_Key' => $key
            ))->First();
            if($submission) {
                $submission->Confirmed = 1;
                $submission->write();
                return $this->redirect($this->Link("?action=confirm&status=1"));
            }else{
                return $this->redirect($this->Link("?action=confirm&status=0"));
            }
        }
        public function ExtendEntry($request) {
            $key = $request->getVar('key');
            $entry = ContributorMaps_Data::get()->filter(array(
                'Unique_Key' => $key
            ))->First();
            if($entry) {
                $new = date('Y-m-d', strtotime('+1 year', strtotime($entry->Expiry)));
                $entry->Expiry = $new;
                $entry->write();
                return $this->redirect($this->Link("?action=extend&status=1"));
            }else{
                return $this->redirect($this->Link("?action=extend&status=0"));
            }
        }
        public function Feedback() {
            if($this->request->getVar('action') === "extend") {
                if($this->request->getVar('status') == 1) {
                    return new ArrayData(array(
                        'Status' => 'success',
                        'Message' => 'Your listing has been successfully extended for another year. '
                    ));
                }else{
                    return new ArrayData(array(
                        'Status' => 'error',
                        'Message' => 'There was an error while extending your listing. Please try again later. If this continues to happen, please contact us at hostmaster@documentfoundation.org and mention Libreoffice Contributor Maps and we will try to reply as soon as possible. '
                    ));
                }
            }else if($this->request->getVar('action') === "confirm") {
                if($this->request->getVar('status') == 1) {
                    return new ArrayData(array(
                        'Status' => 'success',
                        'Message' => 'Your listing has been successfully confirmed. This listing will remain active for a period of 1 year. When this period expires, we will give you the possibility to extend the listing. '
                    ));
                }else{
                    return new ArrayData(array(
                        'Status' => 'error',
                        'Message' => 'There was an error while confirming your account. Please try again later. If this continues to happen, please contact us at hostmaster@documentfoundation.org and mention Libreoffice Contributor Maps and we will try to reply as soon as possible. '
                    ));
                }
            }else if($this->request->getVar('action') === "edit") {
                if($this->request->getVar('status') == 1) {
                    return new ArrayData(array(
                        'Status' => 'success',
                        'Message' => 'You have successfully requested to edit your listing. We have sent you an email containing more information to the address associated with this listing. '
                    ));
                }else if($this->request->getVar('status') == 2) {
                    return new ArrayData(array(
                        'Status' => 'error',
                        'Message' => 'Unfortunately, you cannot edit your listing. Most likely this is happening because your token has expired. Please re-request to edit your listing. '
                    ));
                }else if($this->request->getVar('status') == 3) {
                    return new ArrayData(array(
                        'Status' => 'error',
                        'Message' => 'Unfortunately, we encountered an error while trying to edit your listing. If this continues to happen, please contact us at hostmaster@documentfoundation.org and mention Libreoffice Contributor Maps and we will try to reply as soon as possible. '
                    ));
                }else if($this->request->getVar('status') == 4) {
                    return new ArrayData(array(
                        'Status' => 'success',
                        'Message' => 'Your listing has been successfully updated. If you have changed your email address, your listing will not be shown anymore until you re-confirm your listing through the link we have sent you on your new email address. '
                    ));
                }else{
                    return new ArrayData(array(
                        'Status' => 'error',
                        'Message' => 'Your request to edit your listing has not submitted due to an error. Please try again later. '
                    ));
                }
            };
        }
        public function Registered() {
            if($this->request->getVar('registered') == 1) {
                return 1;
            }else if($this->request->getVar('registered') == 2) {
                return 2;
            }else if($this->request->getVar('key') && $this->request->getVar('token')) {
                return 3;
            }else if($this->request->getVar('registered') == 3) {
                return 4;
            }else{
                return false;
            }
        }
        public function RequestEditForm() {
            return new Form(
                $this,
                'RequestEditForm',
                new FieldList(
                    new EmailField('Email', 'Email*')
                ),
                new FieldList(
                    new FormAction('processEditRequestForm', 'Request Edit')
                ),
                new RequiredFields(
                    'Email'
                )
            );
        }
        public function processEditRequestForm($data, $form) {
            $email = $data['Email'];
            $listing = ContributorMaps_Data::get()->filter(array(
                'Email' => $email
            ))->First();
            if($listing) {
                $email = $listing->Email;
                $key = $listing->Unique_Key;
                $token = md5(uniqid(mt_rand(), true));
                $listing->EditToken = $token;
                $listing->EditTokenExpires = date('Y-m-d', strtotime('+1 day'));
                $listing->write();
                $subject = "LibreOffice Contributor Maps - Listing Editing";
                $body = "We have received a request from you to edit your listing. "
                        ."If you have made this request, please proceed by going to"
                        ." http://vm-1.liongold.kd.io/silverstripe/SilverStripe-cms-"
                        ."v-3.1.5/new-contributor-maps/?key=".$key."&token=".$token
                        ." If you did not make this request, you can ignore this email."
                        ." Please note that this link will expire after 24 hours from "
                        ."the time of the request. ";
                $email = new Email("hostmaster@documentfoundation.org", $email, $subject, $body);
                $email->send();
                return $this->redirect($this->Link("?action=edit&status=1"));
            }else{
                return $this->redirect($this->Link("?action=edit&status=0"));
            }
        }
        public function processEditForm($data, $form) {
            $entry = ContributorMaps_Data::get()->filter(array(
                'Unique_Key' => $data['Key'],
                'EditToken' => $data['Token']
            ))->First();
            if($entry) {
                $errors = 0;
                if($entry->Email !== $data['Email']) {
                    //Email Confirmation Email Again
                    //Check if Email is used
                    if(ContributorMaps_Data::get()->filter('Email', $data['Email'])->exists()) {
                        $form->addErrorMessage('Email', 'Sorry, this email address is already being used. ', 'bad');
                        $errors++;
                    }else{
                        $entry->Email = $data['Email'];
                        //Email User Confirmation Link
                        $subject = "Please confirm your submission to the LibreOffice Contributor Maps";
                        $body = "Thanks for your submission to the LibreOffice Contributor Maps! "
                                ."However, in order to ensure that you made this submission, you"
                                ." are required to confirm this email address by going to http://"
                                ."www.libreoffice.org/new-contributor-maps/AccountConfirmation?key=$key"
                                .". Your listing will be published to the site for a period of 1 year. After"
                                ." this period we will contact you again so that you can review and extend "
                                ."your information. Please note that your email address will be shown in public"
                                ." on The LibreOffice Contributor Maps site. ";
                        $email = new Email("hostmaster@documentfoundation.org", $data['Email'], $subject, $body);
                        $email->send();
                        $entry->Confirmed = 0;
                    }
                }
                if(!($data['Skills_Base'] || $data['Skills_Calc'] || $data['Skills_Dev']
                    || $data['Skills_Doc'] || $data['Skills_Draw'] || $data['Skills_Impress'] || $data['Skills_Infra'] 
                    || $data['Skills_l10n'] || $data['Skills_Marketing'] || $data['Skills_Math'] || $data['Skills_QA']
                    || $data['Skills_Writer'])) {
                    $form->addErrorMessage('Skills', 'Sorry, you must choose at least 1 skill. ', 'bad');
                    $errors++;
                };
                $entry->Name = $data['Name'];
                $entry->Surname = $data['Surname'];
                $entry->Location = $data['Location'];
                $entry->Skills_Design = $data['Skills_Design'];
                $entry->Skills_Dev = $data['Skills_Dev'];
                $entry->Skills_Doc = $data['Skills_Doc'];
                $entry->Skills_Infra = $data['Skills_Infra'];
                $entry->Skills_l10n = $data['Skills_l10n'];
                $entry->Skills_Marketing = $data['Skills_Marketing'];
                $entry->Skills_QA = $data['Skills_QA'];
                $entry->Skills_Base = $data['Skills_Base'];
                $entry->Skills_Calc = $data['Skills_Calc'];
                $entry->Skills_Draw = $data['Skills_Draw'];
                $entry->Skills_Impress = $data['Skills_Impress'];
                $entry->Skills_Math = $data['Skills_Math'];
                $entry->Skills_Writer = $data['Skills_Writer'];
                if($errors > 0) {
                    Session::set("FormInfo.{$form->FormName()}_Edit.data", $data);
                    return $this->redirect($this->Link("?registered=3"));
                }else{
                    $entry->write();
                    return $this->redirect($this->Link("?action=edit&status=4"));
                }
            }else{
                return $this->redirect($this->Link("?action=edit&status=3"));
            }
        }
    }
// u
?>

