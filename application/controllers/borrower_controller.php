<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class borrower_controller extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();		
		$this->load->model('maintenance_model');
		$this->load->model('access');
		$this->load->model('employee_model');
		$this->load->model('admin_model');
    $this->load->model('borrower_model');
    $this->load->model('loanapplication_model');
    $this->load->library('Pdf');
    $this->load->library('session');
    $this->load->library('excel');
    $this->load->helper('url');
    date_default_timezone_set('Asia/Manila');

   	if(empty($this->session->userdata("EmployeeNumber")) || $this->session->userdata("logged_in") == 0)
   	{
      $DateNow = date("Y-m-d H:i:s");
     	$this->session->set_flashdata('logout','Account successfully logged out.'); 
      $data = array(
      	'Description' => 'Session timed out.'
      	, 'DateCreated' => $DateNow
      	, 'CreatedBy' => $this->session->userdata('EmployeeNumber')
      );
      $this->access->audit($data);
      $loginSession = array(
        'logged_in' => 0,
      );
   		redirect(site_url());
   	}
	}

  public function getBorrowers()
  {
    $json = [];
    if(!empty($this->input->get("q")))
    {
      $keyword = $this->input->get("q");
      $json = $this->admin_model->getBorrowers($keyword);
    }
    echo json_encode($json);
  }

  function BorrowerProcessing()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $BranchId = $this->session->userdata('BranchId');
    $DateNow = date("Y-m-d H:i:s");
    $employeeDetail = $this->maintenance_model->getCreatorDetails();
    if($this->uri->segment(4) != null)
    {
      $borrowerDetail = $this->borrower_model->getBorrowerDetails($this->uri->segment(4));
    }
    if($this->uri->segment(3) == 1) // add borrower
    {
      $time = strtotime($_POST['DOB']);
      $newformat = date('Y-m-d', $time);

      if(htmlentities($BranchId, ENT_QUOTES) == null)
      {
        $assignedBranch = htmlentities($BranchId, ENT_QUOTES);
      }
      else
      {
        $assignedBranch = $employeeDetail['BranchId'];
      }

      $data = array(
        'FirstName'                     => htmlentities($_POST['FirstName'], ENT_QUOTES)
        , 'MiddleName'                  => htmlentities($_POST['MiddleName'], ENT_QUOTES)
        , 'LastName'                    => htmlentities($_POST['LastName'], ENT_QUOTES)
        , 'ExtName'                     => htmlentities($_POST['ExtName'], ENT_QUOTES)
        , 'MotherName'                  => htmlentities($_POST['MotherName'], ENT_QUOTES)
        , 'DOB'                         => htmlentities($newformat, ENT_QUOTES)
      );
      $query = $this->borrower_model->countBorrower($data);
      if($query == 0) // not existing
      {
        // insert borrower details
          $insertBorrower = array(
            'Salutation'                    => htmlentities($_POST['SalutationId'], ENT_QUOTES)
            , 'BranchId'                    => $assignedBranch
            , 'FirstName'                   => htmlentities($_POST['FirstName'], ENT_QUOTES)
            , 'MiddleName'                  => htmlentities($_POST['MiddleName'], ENT_QUOTES)
            , 'LastName'                    => htmlentities($_POST['LastName'], ENT_QUOTES)
            , 'ExtName'                     => htmlentities($_POST['ExtName'], ENT_QUOTES)
            , 'Sex'                         => htmlentities($_POST['SexId'], ENT_QUOTES)
            , 'Nationality'                 => htmlentities($_POST['NationalityId'], ENT_QUOTES)
            , 'CivilStatus'                 => htmlentities($_POST['CivilStatusId'], ENT_QUOTES)
            , 'Dependents'                  => htmlentities($_POST['NoDependents'], ENT_QUOTES)
            , 'MotherName'                  => htmlentities($_POST['MotherName'], ENT_QUOTES)
            , 'DateOfBirth'                 => htmlentities($newformat, ENT_QUOTES)
            , 'BirthPlace'                  => htmlentities($_POST['BirthPlace'], ENT_QUOTES)
            , 'StatusId'                    => 1
            , 'CreatedBy'                   => $EmployeeNumber
            , 'UpdatedBy'                   => $EmployeeNumber
          );
          $insertBorrowerTable = 'R_Borrowers';
          $this->maintenance_model->insertFunction($insertBorrower, $insertBorrowerTable);
        // get borrower generated id
          $auditData1 = array(
            'table'                 => 'R_Borrowers'
            , 'column'              => 'BorrowerId'
          );
          $BorrowerId = $this->maintenance_model->getGeneratedId($auditData1);

        // reference number
          $generatedBorrowerNumber = $employeeDetail['Code'] . '-'.str_pad($BorrowerId['BorrowerId'], 6, '0', STR_PAD_LEFT);

          $set = array( 
            'BorrowerNumber' => $generatedBorrowerNumber
          );

          $condition = array( 
            'BorrowerId' => $BorrowerId['BorrowerId']
          );
          $table = 'R_Borrowers';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
          
        // insert mobile number
          // insert into contact numbers
            $insertContact1 = array(
              'PhoneType'                     => 'Mobile'
              , 'Number'                      => htmlentities($_POST['ContactNumber'], ENT_QUOTES)
              , 'CreatedBy'                      => $EmployeeNumber
            );
            $insertContactTable1 = 'r_contactnumbers';
            $this->maintenance_model->insertFunction($insertContact1, $insertContactTable1);
          // get mobile number id
            $generatedIdData1 = array(
              'table'                 => 'r_contactnumbers'
              , 'column'              => 'ContactNumberId'
            );
            $mobileNumberId = $this->maintenance_model->getGeneratedId($generatedIdData1);
          // insert into borrower contact numbers
            $insertContact2 = array(
              'BorrowerId'                      => $BorrowerId['BorrowerId']
              , 'ContactNumberId'               => $mobileNumberId['ContactNumberId']
              , 'isPrimary'                     => 1
              , 'CreatedBy'                     => $EmployeeNumber
              , 'UpdatedBy'                     => $EmployeeNumber
            );
            $insertContactTable2 = 'borrower_has_contactnumbers';
            $this->maintenance_model->insertFunction($insertContact2, $insertContactTable2);

        // insert telephone number
          if(htmlentities($_POST['TelephoneNumber'], ENT_QUOTES) != '')
          {
            // insert into telephone numbers
              $insertTelephone1 = array(
                'PhoneType'                     => 'Telephone'
                , 'Number'                      => htmlentities($_POST['TelephoneNumber'], ENT_QUOTES)
                , 'CreatedBy'                   => $EmployeeNumber
              );
              $insertTelephoneTable1 = 'r_contactnumbers';
              $this->maintenance_model->insertFunction($insertTelephone1, $insertTelephoneTable1);
            // get mobile number id
              $generatedIdData2 = array(
                'table'                 => 'r_contactnumbers'
                , 'column'              => 'ContactNumberId'
              );
              $TelephoneNumberId = $this->maintenance_model->getGeneratedId($generatedIdData2);
            // insert into borrower contact numbers
              $insertTelephone2 = array(
                'BorrowerId'                     => $BorrowerId['BorrowerId']
                , 'ContactNumberId'              => $TelephoneNumberId['ContactNumberId']
                , 'CreatedBy'                   => $EmployeeNumber
                , 'UpdatedBy'                   => $EmployeeNumber
              );
              $insertTelephoneTable2 = 'borrower_has_contactnumbers';
              $this->maintenance_model->insertFunction($insertTelephone2, $insertTelephoneTable2);
          }

        // insert email address
          // insert into email addresses
            $insertDataEmail = array(
              'EmailAddress'                  => htmlentities($_POST['EmailAddress'], ENT_QUOTES)
              , 'CreatedBy'                   => $EmployeeNumber
            );
            $insertTableEmail = 'r_emails';
            $this->maintenance_model->insertFunction($insertDataEmail, $insertTableEmail);
          // get mobile number id
            $generatedIdData3 = array(
              'table'                 => 'r_emails'
              , 'column'              => 'EmailId'
            );
            $EmailId = $this->maintenance_model->getGeneratedId($generatedIdData3);
          // insert into borrower contact numbers
            $insertDataEmail2 = array(
              'BorrowerId'                      => $BorrowerId['BorrowerId']
              , 'EmailId'                       => $EmailId['EmailId']
              , 'isPrimary'                     => 1
              , 'CreatedBy'                     => $EmployeeNumber
              , 'UpdatedBy'                     => $EmployeeNumber
            );
            $insertTableEmail2 = 'borrower_has_emails';
            $this->maintenance_model->insertFunction($insertDataEmail2, $insertTableEmail2);

        // insert city address
          // insert into addresses
            $insertDataAddress = array(
              'HouseNo'                           => htmlentities($_POST['HouseNo'], ENT_QUOTES)
              , 'Telephone'                       => htmlentities($_POST['TelephoneCityAddress'], ENT_QUOTES)
              , 'ContactNumber'                   => htmlentities($_POST['CellphoneCityAdd'], ENT_QUOTES)
              , 'AddressType'                     => 'City Address'
              , 'BarangayId'                      => htmlentities($_POST['BarangayId'], ENT_QUOTES)
              , 'CreatedBy'                       => $EmployeeNumber
            );
            $insertTableAddress = 'r_address';
            $this->maintenance_model->insertFunction($insertDataAddress, $insertTableAddress);
          // get address id
            $generatedIdData4 = array(
              'table'                 => 'r_address'
              , 'column'              => 'AddressId'
            );
            $AddressId = $this->maintenance_model->getGeneratedId($generatedIdData4);
          // insert into borrower addresses
            if(htmlentities($_POST['optionsRadios'], ENT_QUOTES) == 'Rented')
            {
              $insertDataAddress2 = array(
                'BorrowerId'                          => $BorrowerId['BorrowerId']
                , 'AddressId'                         => $AddressId['AddressId']
                , 'isPrimary'                         => 1
                , 'YearsStayed'                       => htmlentities($_POST['YearsStayed'], ENT_QUOTES)
                , 'MonthsStayed'                      => htmlentities($_POST['MonthsStayed'], ENT_QUOTES)
                , 'AddressType'                       => htmlentities($_POST['optionsRadios'], ENT_QUOTES)
                , 'NameOfLandlord'                    => htmlentities($_POST['LandLord'], ENT_QUOTES)
                , 'ContactNumber'                     => htmlentities($_POST['LandLordNumber'], ENT_QUOTES)
                , 'CreatedBy'                         => $EmployeeNumber
                , 'UpdatedBy'                         => $EmployeeNumber
              );
            }
            else if(htmlentities($_POST['optionsRadios'], ENT_QUOTES) == 'Living with relatives')
            {
              $insertDataAddress2 = array(
                'BorrowerId'                          => $BorrowerId['BorrowerId']
                , 'AddressId'                         => $AddressId['AddressId']
                , 'isPrimary'                         => 1
                , 'YearsStayed'                       => htmlentities($_POST['YearsStayed'], ENT_QUOTES)
                , 'MonthsStayed'                      => htmlentities($_POST['MonthsStayed'], ENT_QUOTES)
                , 'AddressType'                       => htmlentities($_POST['optionsRadios'], ENT_QUOTES)
                , 'NameOfLandlord'                    => htmlentities($_POST['RelativeName'], ENT_QUOTES)
                , 'CreatedBy'                         => $EmployeeNumber
                , 'UpdatedBy'                         => $EmployeeNumber
              );
            }
            else
            {
              $insertDataAddress2 = array(
                'BorrowerId'                          => $BorrowerId['BorrowerId']
                , 'AddressId'                         => $AddressId['AddressId']
                , 'isPrimary'                         => 1
                , 'YearsStayed'                       => htmlentities($_POST['YearsStayed'], ENT_QUOTES)
                , 'MonthsStayed'                      => htmlentities($_POST['MonthsStayed'], ENT_QUOTES)
                , 'AddressType'                       => htmlentities($_POST['optionsRadios'], ENT_QUOTES)
                , 'CreatedBy'                         => $EmployeeNumber
                , 'UpdatedBy'                         => $EmployeeNumber
              );
            }
            $insertTableAddress2 = 'borroweraddresshistory';
            $this->maintenance_model->insertFunction($insertDataAddress2, $insertTableAddress2);

        // insert province address
          if(htmlentities($_POST['IsSameAddress'], ENT_QUOTES) == 1)
          {
            // insert into addresses
              $insertDataAddress = array(
                'HouseNo'                           => htmlentities($_POST['HouseNo'], ENT_QUOTES)
                , 'AddressType'                     => 'Province Address'
                , 'BarangayId'                      => htmlentities($_POST['BarangayId'], ENT_QUOTES)
                , 'CreatedBy'                       => $EmployeeNumber
              );
              $insertTableAddress = 'r_address';
              $this->maintenance_model->insertFunction($insertDataAddress, $insertTableAddress);
            // get address id
              $generatedIdData4 = array(
                'table'                 => 'r_address'
                , 'column'              => 'AddressId'
              );
              $AddressId = $this->maintenance_model->getGeneratedId($generatedIdData4);
            // insert into borrower addresses
              $insertDataAddress2 = array(
                'BorrowerId'                        => $BorrowerId['BorrowerId']
                , 'AddressId'                       => $AddressId['AddressId']
                , 'CreatedBy'                       => $EmployeeNumber
                , 'UpdatedBy'                       => $EmployeeNumber
              );
              $insertTableAddress2 = 'borroweraddresshistory';
              $this->maintenance_model->insertFunction($insertDataAddress2, $insertTableAddress2);
          }
          else
          {
            // insert into addresses
              $insertDataAddress = array(
                'HouseNo'                           => htmlentities($_POST['HouseNo2'], ENT_QUOTES)
                , 'AddressType'                     => 'Province Address'
                , 'BarangayId'                      => htmlentities($_POST['BarangayId2'], ENT_QUOTES)
                , 'CreatedBy'                       => $EmployeeNumber
              );
              $insertTableAddress = 'r_address';
              $this->maintenance_model->insertFunction($insertDataAddress, $insertTableAddress);
            // get address id
              $generatedIdData4 = array(
                'table'                 => 'r_address'
                , 'column'              => 'AddressId'
              );
              $AddressId = $this->maintenance_model->getGeneratedId($generatedIdData4);
            // insert into borrower addresses
                $insertDataAddress2 = array(
                  'BorrowerId'                        => $BorrowerId['BorrowerId']
                  , 'AddressId'                       => $AddressId['AddressId']
                  , 'CreatedBy'                       => $EmployeeNumber
                  , 'UpdatedBy'                       => $EmployeeNumber
                );
              $insertTableAddress2 = 'borroweraddresshistory';
              $this->maintenance_model->insertFunction($insertDataAddress2, $insertTableAddress2);
          }

        // admin audits
          $auditLogsManager = 'Added new borrower #'.$generatedBorrowerNumber.' in borrower module.';
          $auditAffectedEmployee = 'Added new borrower #'.$generatedBorrowerNumber.' in borrower module.';
          $auditAffectedTable = 'Added in borrower list.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $BorrowerId['BorrowerId'], 'borrower_has_notifications', 'BorrowerId');

        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Borrower successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/borrowerDetails/' . $BorrowerId['BorrowerId']);
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Borrower already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/borrowers');
      }
    }
    else if($this->uri->segment(3) == 2) // add reference
    {
      // insert details
        $insertBorrower = array(
          'Name'                        => htmlentities($_POST['Name'], ENT_QUOTES)
          , 'Address'                   => htmlentities($_POST['Address'], ENT_QUOTES)
          , 'ContactNumber'             => htmlentities($_POST['ContactNumber'], ENT_QUOTES)
          , 'BorrowerId'                => htmlentities($this->uri->segment(4), ENT_QUOTES)
          , 'CreatedBy'                 => $EmployeeNumber
          , 'UpdatedBy'                 => $EmployeeNumber
        );
        $insertBorrowerTable = 'borrower_has_reference';
        $this->maintenance_model->insertFunction($insertBorrower, $insertBorrowerTable);
      // get generated id
        $generatedIdData = array(
          'table'                     => 'borrower_has_reference'
          , 'column'                  => 'ReferenceId'
          , 'CreatedBy'               => $EmployeeNumber
        );
        $NewId = $this->maintenance_model->getGeneratedId2($generatedIdData);
      // admin audits
        $employeeDetail = $this->employee_model->getEmployeeProfile($EmployeeNumber);
        $TransactionNumber = 'RF-'.sprintf('%05d', $NewId['ReferenceId']);
        $borrowerNumber = $this->maintenance_model->selectSpecific('R_Borrowers', 'BorrowerId', $this->uri->segment(4));
        $auditLogsManager = 'Added personal reference #'.$TransactionNumber.' in personal reference tab to borrower #'.$borrowerNumber['BorrowerNumber'].'.';
        $auditBorrowerDetails = 'Added personal reference #'.$TransactionNumber.' in personal reference tab.';
        $this->auditBorrowerDetails($auditLogsManager, $auditLogsManager, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditBorrowerDetails, $this->uri->segment(4));
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Reference successfully added!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/BorrowerDetails/' . htmlentities($this->uri->segment(4), ENT_QUOTES));
    }
    else if($this->uri->segment(3) == 3) // add co maker
    {
      $time = strtotime($_POST['DOB']);
      $newformat = date('Y-m-d', $time);
      $data = array(
        'Name'                        => htmlentities($_POST['Name'], ENT_QUOTES)
        , 'PositionId'                => htmlentities($_POST['PositionId'], ENT_QUOTES)
        , 'BorrowerId'                => htmlentities($this->uri->segment(4), ENT_QUOTES)
        , 'Employer'                  => htmlentities($_POST['Employer'], ENT_QUOTES)
        , 'DOB'                       => htmlentities($newformat, ENT_QUOTES)
      );
      $query = $this->borrower_model->countCoMaker($data);
      if($query == 0) // not existing
      {
        // insert details
          $insertBorrower = array(
            'Name'                        => htmlentities($_POST['Name'], ENT_QUOTES)
            , 'Birthdate'                 => htmlentities($newformat, ENT_QUOTES)
            , 'PositionId'                => htmlentities($_POST['PositionId'], ENT_QUOTES)
            , 'Employer'                  => htmlentities($_POST['Employer'], ENT_QUOTES)
            , 'TenureYear'                => htmlentities($_POST['TenureYear'], ENT_QUOTES)
            , 'TenureMonth'               => htmlentities($_POST['TenureMonth'], ENT_QUOTES)
            , 'TelephoneNo'               => htmlentities($_POST['TelephoneNo'], ENT_QUOTES)
            , 'BusinessNo'                => htmlentities($_POST['BusinessNo'], ENT_QUOTES)
            , 'MobileNo'                  => htmlentities($_POST['CellphoneNo'], ENT_QUOTES)
            , 'MonthlyIncome'             => htmlentities($_POST['MonthlyIncome'], ENT_QUOTES)
            , 'BusinessAddress'           => htmlentities($_POST['BusinessAddress'], ENT_QUOTES)
            , 'BorrowerId'                => htmlentities($this->uri->segment(4), ENT_QUOTES)
            , 'CreatedBy'                 => $EmployeeNumber
            , 'UpdatedBy'                 => $EmployeeNumber
          );
          $insertBorrowerTable = 'borrower_has_comaker';
          $this->maintenance_model->insertFunction($insertBorrower, $insertBorrowerTable);
        // admin audits finalss
          $getNewId = $this->maintenance_model->selectSpecific('borroweraddresshistory', 'BorrowerId', $this->uri->segment(4));
          $generatedIdData1 = array(
            'table'                 => 'borrower_has_comaker'
            , 'column'              => 'BorrowerCoMakerId'
          );
          $newId = $this->maintenance_model->getGeneratedId($generatedIdData1);

          $TransactionNumber = 'CM-'.sprintf('%06d', $newId['BorrowerCoMakerId']);
          $auditLogsManager = 'Added new co-maker #'.$TransactionNumber.' in co-maker tab for borrower #'.$borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedEmployee = 'Added new co-maker #'.$TransactionNumber.' in co-maker tab for borrower #'.$borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedTable = 'Added new co-maker #'.$TransactionNumber.' in co-maker tab.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Co-maker successfully added!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/BorrowerDetails/' . htmlentities($this->uri->segment(4), ENT_QUOTES));
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Reference already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/BorrowerDetails/' . htmlentities($this->uri->segment(4), ENT_QUOTES));
      }
    }
    else if($this->uri->segment(3) == 4) // add supporting documents
    {
      $path = './borrowerarchive';
      $config = array
      (
      'upload_path' => $path,
      'allowed_types' => 'png|jpg|jpeg|pdf',
      'overwrite' => 1
      );
      
      $this->load->library('upload', $config);

      $files = $_FILES['Attachment'];
      $fileName = "";
      $images = array();
      $isApproved = 0;
      
      foreach ($files['name'] as $key => $image) 
      {
        $file_ext = pathinfo($image, PATHINFO_EXTENSION);

        $_FILES['Attachment[]']['name']= $files['name'][$key];
        $_FILES['Attachment[]']['type']= $files['type'][$key];
        $_FILES['Attachment[]']['tmp_name']= $files['tmp_name'][$key];
        $_FILES['Attachment[]']['error']= $files['error'][$key];
        $_FILES['Attachment[]']['size']= $files['size'][$key];
        $uniq_id = uniqid();
        $fileName = $uniq_id.'.'.$file_ext;
        $fileName = str_replace(" ","_",$fileName);
        $images[] = $fileName;

        $config['file_name'] = $fileName;
        $Title = $_FILES['Attachment[]']['name'];

        $this->upload->initialize($config);
        $attachment = $fileName;

        print_r($fileName);
        if($attachment != "")
        {
          if($this->upload->do_upload('Attachment[]')) 
          {
            $this->upload->data();
          } 
          else
          {
            $fileName = "";
            $isApproved = 0;
          }
        }
      }

      if($attachment != "")
      {
        // insert into address table
          $insertData = array(
            'Id'                                => htmlentities($_POST['ReqTypeId'], ENT_QUOTES)
            , 'Attachment'                      => htmlentities($Title, ENT_QUOTES)
            , 'Description'                     => htmlentities($_POST['Description'], ENT_QUOTES)
            , 'FileName'                        => htmlentities($attachment, ENT_QUOTES)
            , 'CreatedBy'                       => $EmployeeNumber
          );
          $insertTable = 'r_identificationcards';
          $this->maintenance_model->insertFunction($insertData, $insertTable);
        // get address id
          $generatedIdData = array(
            'table'                     => 'r_identificationcards'
            , 'column'                  => 'IdentificationId'
            , 'CreatedBy'               => $EmployeeNumber
          );
          $NewId = $this->maintenance_model->getGeneratedId2($generatedIdData);
        // insert into employee address      
          $insertData2 = array(
            'BorrowerId'                        => $this->uri->segment(4)
            , 'IdentificationId'                => $NewId['IdentificationId']
            , 'RequirementId'                   => $_POST['ReqTypeId']
            , 'CreatedBy'                       => $EmployeeNumber
            , 'UpdatedBy'                       => $EmployeeNumber
          );
          $insertTable2 = 'borrower_has_supportdocuments';
          $this->maintenance_model->insertFunction($insertData2, $insertTable2);
        // audits
          $borrowerDetail = $this->borrower_model->getBorrowerDetails($this->uri->segment(4));
          $getNewId = array(
            'table'                             => 'borrower_has_supportdocuments'
            , 'column'                          => 'BorrowerIdentificationId'
            , 'CreatedBy'                       => $EmployeeNumber
          );
          $NewId = $this->maintenance_model->getGeneratedId2($getNewId);
          $TransactionNumber = 'SD-'.sprintf('%06d', $NewId['BorrowerIdentificationId']);
          $auditLogsManager = 'Added supporting document #' . $TransactionNumber.' for borrower #'.$borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedEmployee = 'Added supporting document #' . $TransactionNumber.' for borrower #'.$borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedTable = 'Added supporting document ##' . $TransactionNumber.' in supporting documents tab.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');

        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Successfully uploaded supporting documents!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/BorrowerDetails/'. $this->uri->segment(4));
      }
      else
      {
        // Notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','File uploaded was unsuccessful! Please try again!'); 
          $this->session->set_flashdata('alertType','warning');
          redirect('home/BorrowerDetails/'. $this->uri->segment(4));
      }
    }
    else if($this->uri->segment(3) == 5) // edit borrower details
    {
      if($_POST['formType'] == 1) // update borrower profile
      {
        $borrowerDetail = $this->borrower_model->getBorrowerDetails($this->uri->segment(4));
        $time = strtotime($_POST['DateOfBirth']);
        $newformat = date('Y-m-d', $time);
        $data = array(
          'FirstName'                     => htmlentities($_POST['FirstName'], ENT_QUOTES)
          , 'MiddleName'                  => htmlentities($_POST['MiddleName'], ENT_QUOTES)
          , 'LastName'                    => htmlentities($_POST['LastName'], ENT_QUOTES)
          , 'ExtName'                     => htmlentities($_POST['ExtName'], ENT_QUOTES)
          , 'MotherName'                  => htmlentities($_POST['MotherName'], ENT_QUOTES)
          , 'DateOfBirth'                 => htmlentities($newformat, ENT_QUOTES)
        );
        $query = $this->borrower_model->countBorrower($data);
        if($query == 0) // not existing
        {
          // first name
            if($borrowerDetail['FirstName'] != htmlentities($_POST['FirstName'], ENT_QUOTES))
            {
              // admin audits finalss
                $auditLogsManager = 'Updated first name from '.$borrowerDetail['FirstName'].' to '.htmlentities($_POST['FirstName'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
                $auditAffectedEmployee = 'Updated first name from '.$borrowerDetail['FirstName'].' to '.htmlentities($_POST['FirstName'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
                $auditAffectedTable = 'Updated first name from '.$borrowerDetail['FirstName'].' to '.htmlentities($_POST['FirstName'], ENT_QUOTES).'.';
                $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
              // update detail
                $set = array( 
                  'FirstName' => htmlentities($_POST['FirstName'], ENT_QUOTES)
                );

                $condition = array( 
                  'BorrowerId' => $this->uri->segment(4)
                );
                $table = 'R_Borrowers';
                $this->maintenance_model->updateFunction1($set, $condition, $table);
            }
          // middle name
            if($borrowerDetail['MiddleName'] != htmlentities($_POST['MiddleName'], ENT_QUOTES))
            {
              // admin audits finalss
                $auditLogsManager = 'Updated middle name from '.$borrowerDetail['MiddleName'].' to '.htmlentities($_POST['MiddleName'], ENT_QUOTES).'.';
                $auditAffectedEmployee = 'Updated middle name from '.$borrowerDetail['MiddleName'].' to '.htmlentities($_POST['MiddleName'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
                $auditAffectedTable = 'Updated middle name from '.$borrowerDetail['MiddleName'].' to '.htmlentities($_POST['MiddleName'], ENT_QUOTES).'.';
                $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
              // update detail
                $set = array( 
                  'MiddleName' => htmlentities($_POST['MiddleName'], ENT_QUOTES)
                );

                $condition = array( 
                  'BorrowerId' => $this->uri->segment(4)
                );
                $table = 'R_Borrowers';
                $this->maintenance_model->updateFunction1($set, $condition, $table);
            }
          // last name
            if($borrowerDetail['LastName'] != htmlentities($_POST['LastName'], ENT_QUOTES))
            {
              // admin audits finalss
                $auditLogsManager = 'Updated last name from '.$borrowerDetail['LastName'].' to '.htmlentities($_POST['LastName'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
                $auditAffectedEmployee = 'Updated last name from '.$borrowerDetail['LastName'].' to '.htmlentities($_POST['LastName'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
                $auditAffectedTable = 'Updated last name from '.$borrowerDetail['LastName'].' to '.htmlentities($_POST['LastName'], ENT_QUOTES).'.';
                $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
              // update detail
                $set = array( 
                  'LastName' => htmlentities($_POST['LastName'], ENT_QUOTES)
                );

                $condition = array( 
                  'BorrowerId' => $this->uri->segment(4)
                );
                $table = 'R_Borrowers';
                $this->maintenance_model->updateFunction1($set, $condition, $table);
            }
          // ext name
            if($borrowerDetail['ExtName'] != htmlentities($_POST['ExtName'], ENT_QUOTES))
            {
              // admin audits finalss
                $auditLogsManager = 'Updated extension name from '.$borrowerDetail['ExtName'].' to '.htmlentities($_POST['ExtName'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
                $auditAffectedEmployee = 'Updated extension name from '.$borrowerDetail['ExtName'].' to '.htmlentities($_POST['ExtName'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
                $auditAffectedTable = 'Updated extension name from '.$borrowerDetail['ExtName'].' to '.htmlentities($_POST['ExtName'], ENT_QUOTES).'.';
                $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
              // update detail
                $set = array( 
                  'ExtName' => htmlentities($_POST['ExtName'], ENT_QUOTES)
                );

                $condition = array( 
                  'BorrowerId' => $this->uri->segment(4)
                );
                $table = 'R_Borrowers';
                $this->maintenance_model->updateFunction1($set, $condition, $table);
            }
          // birthdate
            $convertOldPost = strtotime($borrowerDetail['RawDateOfBirth']);
            $convertNewPost = strtotime($_POST['DateOfBirth']);
            $oldData = date('d M Y', $convertOldPost);
            $newData = date('d M Y', $convertNewPost);
            $newUpdateData = date('Y-m-d', $convertNewPost);
            if($oldData != $newData)
            {
              // admin audits finalss
                $auditLogsManager = 'Updated birth date from '.$oldData.' to '.$newData.' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
                $auditAffectedEmployee = 'Updated birth date from '.$oldData.' to '.$newData.' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
                $auditAffectedTable = 'Updated birth date from '.$oldData.' to '.$newData.'.';
                $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
              // update detail
                $set = array( 
                  'DateOfBirth' => $newUpdateData
                );

                $condition = array( 
                  'BorrowerId' => $this->uri->segment(4)
                );
                $table = 'R_Borrowers';
                $this->maintenance_model->updateFunction1($set, $condition, $table);
            }
          $this->supportingInfoUpdate();
          // notification
            $this->session->set_flashdata('alertTitle','Success!'); 
            $this->session->set_flashdata('alertText','Personal information successfully updated!'); 
            $this->session->set_flashdata('alertType','success'); 
            redirect('home/BorrowerDetails/'. $this->uri->segment(4));
        }
        else
        {
          $this->supportingInfoUpdate();
          // notification
            $this->session->set_flashdata('alertTitle','Success!'); 
            $this->session->set_flashdata('alertText','Personal information successfully updated!'); 
            $this->session->set_flashdata('alertType','success'); 
            redirect('home/BorrowerDetails/'. $this->uri->segment(4));
        }
      }
      else // add spouse details
      {
        $time = strtotime($_POST['DateOfBirth']);
        $newformat = date('Y-m-d', $time);

        $data = array(
          'FirstName'                             => htmlentities($_POST['FirstName'], ENT_QUOTES)
          , 'MiddleName'                          => htmlentities($_POST['MiddleName'], ENT_QUOTES)
          , 'LastName'                            => htmlentities($_POST['LastName'], ENT_QUOTES)
          , 'ExtName'                             => htmlentities($_POST['ExtName'], ENT_QUOTES)
          , 'DateOfBirth'                         => htmlentities($newformat, ENT_QUOTES)
          , 'BorrowerId'                          => $this->uri->segment(4)
        );
        $query = $this->borrower_model->countSpouse($data);
        if($query == 0) // not existing
        {
          // update spouse
            $set = array( 
              'StatusId' => 0
            );

            $condition = array( 
              'BorrowerId' => $this->uri->segment(4),
              'StatusId' => 1
            );
            $table = 'borrower_has_spouse';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
          // insert borrower details
            $insertBorrower = array(
              'Salutation'                    => htmlentities($_POST['SalutationId'], ENT_QUOTES)
              , 'BranchId'                    => $employeeDetail['BranchId']
              , 'FirstName'                   => htmlentities($_POST['FirstName'], ENT_QUOTES)
              , 'MiddleName'                  => htmlentities($_POST['MiddleName'], ENT_QUOTES)
              , 'LastName'                    => htmlentities($_POST['LastName'], ENT_QUOTES)
              , 'ExtName'                     => htmlentities($_POST['ExtName'], ENT_QUOTES)
              , 'Sex'                         => htmlentities($_POST['SexId'], ENT_QUOTES)
              , 'Nationality'                 => htmlentities($_POST['NationalityId'], ENT_QUOTES)
              , 'CivilStatus'                 => htmlentities($_POST['CivilStatusId'], ENT_QUOTES)
              , 'Dependents'                  => htmlentities($_POST['NoDependents'], ENT_QUOTES)
              , 'EmailAddress'                => htmlentities($_POST['EmailAddress'], ENT_QUOTES)
              , 'BirthPlace'                  => htmlentities($_POST['BirthPlace'], ENT_QUOTES)
              , 'DateOfBirth'                 => htmlentities($newformat, ENT_QUOTES)
              , 'StatusId'                    => 1
              , 'CreatedBy'                   => $EmployeeNumber
              , 'UpdatedBy'                   => $EmployeeNumber
            );
            $insertBorrowerTable = 'R_Spouse';
            $this->maintenance_model->insertFunction($insertBorrower, $insertBorrowerTable);
          // get borrower generated id
            $auditData1 = array(
              'table'                 => 'R_Spouse'
              , 'column'              => 'SpouseId'
            );
            $SpouseId = $this->maintenance_model->getGeneratedId($auditData1);
          // insert mobile number
            // insert into contact numbers
              $insertContact1 = array(
                'PhoneType'                     => 'Mobile'
                , 'Number'                      => htmlentities($_POST['ContactNumber'], ENT_QUOTES)
                , 'CreatedBy'                      => $EmployeeNumber
              );
              $insertContactTable1 = 'r_contactnumbers';
              $this->maintenance_model->insertFunction($insertContact1, $insertContactTable1);
            // get mobile number id
              $generatedIdData1 = array(
                'table'                 => 'r_contactnumbers'
                , 'column'              => 'ContactNumberId'
              );
              $mobileNumberId = $this->maintenance_model->getGeneratedId($generatedIdData1);
            // insert into borrower contact numbers
              $insertContact2 = array(
                'SpouseId'                        => $SpouseId['SpouseId']
                , 'ContactNumberId'               => $mobileNumberId['ContactNumberId']
                , 'isPrimary'                     => 1
                , 'CreatedBy'                     => $EmployeeNumber
                , 'UpdatedBy'                     => $EmployeeNumber
              );
              $insertContactTable2 = 'borrower_has_contactnumbers';
              $this->maintenance_model->insertFunction($insertContact2, $insertContactTable2);
          // insert telephone number
            if(htmlentities($_POST['TelephoneNumber'], ENT_QUOTES) != '')
            {
              // insert into telephone numbers
                $insertTelephone1 = array(
                  'PhoneType'                     => 'Telephone'
                  , 'Number'                      => htmlentities($_POST['TelephoneNumber'], ENT_QUOTES)
                  , 'CreatedBy'                   => $EmployeeNumber
                );
                $insertTelephoneTable1 = 'r_contactnumbers';
                $this->maintenance_model->insertFunction($insertTelephone1, $insertTelephoneTable1);
              // get mobile number id
                $generatedIdData2 = array(
                  'table'                 => 'r_contactnumbers'
                  , 'column'              => 'ContactNumberId'
                );
                $TelephoneNumberId = $this->maintenance_model->getGeneratedId($generatedIdData2);
              // insert into borrower contact numbers
                $insertTelephone2 = array(
                  'SpouseId'                     => $SpouseId['SpouseId']
                  , 'ContactNumberId'              => $TelephoneNumberId['ContactNumberId']
                  , 'CreatedBy'                   => $EmployeeNumber
                  , 'UpdatedBy'                   => $EmployeeNumber
                );
                $insertTelephoneTable2 = 'borrower_has_contactnumbers';
                $this->maintenance_model->insertFunction($insertTelephone2, $insertTelephoneTable2);
            }
          // insert email address
            // insert into email addresses
              $insertDataEmail = array(
                'EmailAddress'                  => htmlentities($_POST['EmailAddress'], ENT_QUOTES)
                , 'CreatedBy'                   => $EmployeeNumber
              );
              $insertTableEmail = 'r_emails';
              $this->maintenance_model->insertFunction($insertDataEmail, $insertTableEmail);
            // get mobile number id
              $generatedIdData3 = array(
                'table'                 => 'r_emails'
                , 'column'              => 'EmailId'
              );
              $EmailId = $this->maintenance_model->getGeneratedId($generatedIdData3);
            // insert into borrower contact numbers
              $insertDataEmail2 = array(
                'SpouseId'                      => $SpouseId['SpouseId']
                , 'EmailId'                       => $EmailId['EmailId']
                , 'isPrimary'                     => 1
                , 'CreatedBy'                     => $EmployeeNumber
                , 'UpdatedBy'                     => $EmployeeNumber
              );
              $insertTableEmail2 = 'borrower_has_emails';
              $this->maintenance_model->insertFunction($insertDataEmail2, $insertTableEmail2);
          // addresses
            if(htmlentities($_POST['IsSameAddress'], ENT_QUOTES) == 3)
            {
              $borrowerCityAddress = $this->borrower_model->getCityAddress($this->uri->segment(4));
              $borrowerProvince = $this->borrower_model->getProvinceAddress($this->uri->segment(4));
              // insert borrower city address
                if($borrowerCityAddress['AddressId'] != null)
                {
                  $insertCityAddress = array(
                    'AddressId'                         => $borrowerCityAddress['AddressId']
                    , 'YearsStayed'                     => $borrowerCityAddress['YearsStayed']
                    , 'MonthsStayed'                    => $borrowerCityAddress['MonthsStayed']
                    , 'NameOfLandlord'                  => $borrowerCityAddress['NameOfLandlord']
                    , 'isPrimary'                       => $borrowerCityAddress['IsPrimary']
                    , 'AddressType'                     => $borrowerCityAddress['AddressType']
                    , 'BorrowerId'                      => $this->uri->segment(4)
                    , 'SpouseId'                        => $SpouseId['SpouseId']
                    , 'CreatedBy'                       => $EmployeeNumber
                    , 'UpdatedBy'                       => $EmployeeNumber
                  );
                  $insertCityAddressTable = 'borroweraddresshistory';
                  $this->maintenance_model->insertFunction($insertCityAddress, $insertCityAddressTable);  
                }
                if($borrowerProvince['AddressId'] != null)
                {
                  // insert borrower city address
                    $insertCityAddress2 = array(
                      'AddressId'                         => $borrowerProvince['AddressId']
                      , 'BorrowerId'                      => $this->uri->segment(4)
                      , 'SpouseId'                        => $SpouseId['SpouseId']
                      , 'CreatedBy'                       => $EmployeeNumber
                      , 'UpdatedBy'                       => $EmployeeNumber
                    );
                    $insertCityAddressTable2 = 'borroweraddresshistory';
                    $this->maintenance_model->insertFunction($insertCityAddress2, $insertCityAddressTable2);
                }
            }
            else
            {
              // insert city address
                // insert into addresses
                  $insertDataAddress = array(
                    'HouseNo'                           => htmlentities($_POST['HouseNo'], ENT_QUOTES)
                    , 'Telephone'                       => htmlentities($_POST['TelephoneCityAddress'], ENT_QUOTES)
                    , 'ContactNumber'                   => htmlentities($_POST['CellphoneCityAdd'], ENT_QUOTES)
                    , 'AddressType'                     => 'City Address'
                    , 'BarangayId'                      => htmlentities($_POST['BarangayId'], ENT_QUOTES)
                    , 'CreatedBy'                       => $EmployeeNumber
                  );
                  $insertTableAddress = 'r_address';
                  $this->maintenance_model->insertFunction($insertDataAddress, $insertTableAddress);
                // get address id
                  $generatedIdData4 = array(
                    'table'                 => 'r_address'
                    , 'column'              => 'AddressId'
                  );
                  $AddressId = $this->maintenance_model->getGeneratedId($generatedIdData4);
                // insert into borrower addresses
                  $insertDataAddress2 = array(
                    'SpouseId'                          => $SpouseId['SpouseId']
                    , 'AddressId'                         => $AddressId['AddressId']
                    , 'AddressType'                       => 'City Address'
                    , 'YearsStayed'                       => htmlentities($_POST['YearsStayed'], ENT_QUOTES)
                    , 'MonthsStayed'                      => htmlentities($_POST['MonthsStayed'], ENT_QUOTES)
                    , 'CreatedBy'                         => $EmployeeNumber
                    , 'UpdatedBy'                         => $EmployeeNumber
                  );
                  $insertTableAddress2 = 'borroweraddresshistory';
                  $this->maintenance_model->insertFunction($insertDataAddress2, $insertTableAddress2);
              // insert province address
                if(htmlentities($_POST['IsSameAddress'], ENT_QUOTES) == 1)
                {
                  // insert into addresses
                    $insertDataAddress = array(
                      'HouseNo'                           => htmlentities($_POST['HouseNo'], ENT_QUOTES)
                      , 'AddressType'                     => 'Province Address'
                      , 'BarangayId'                      => htmlentities($_POST['BarangayId'], ENT_QUOTES)
                      , 'CreatedBy'                       => $EmployeeNumber
                    );
                    $insertTableAddress = 'r_address';
                    $this->maintenance_model->insertFunction($insertDataAddress, $insertTableAddress);
                  // get address id
                    $generatedIdData4 = array(
                      'table'                 => 'r_address'
                      , 'column'              => 'AddressId'
                    );
                    $AddressId = $this->maintenance_model->getGeneratedId($generatedIdData4);
                  // insert into borrower addresses
                    $insertDataAddress2 = array(
                      'SpouseId'                          => $SpouseId['SpouseId']
                      , 'AddressId'                       => $AddressId['AddressId']
                      , 'CreatedBy'                       => $EmployeeNumber
                      , 'UpdatedBy'                       => $EmployeeNumber
                    );
                    $insertTableAddress2 = 'borroweraddresshistory';
                    $this->maintenance_model->insertFunction($insertDataAddress2, $insertTableAddress2);
                }
                else
                {
                  // insert into addresses
                    $insertDataAddress = array(
                      'HouseNo'                           => htmlentities($_POST['HouseNo2'], ENT_QUOTES)
                      , 'AddressType'                     => 'Province Address'
                      , 'BarangayId'                      => htmlentities($_POST['BarangayId2'], ENT_QUOTES)
                      , 'CreatedBy'                       => $EmployeeNumber
                    );
                    $insertTableAddress = 'r_address';
                    $this->maintenance_model->insertFunction($insertDataAddress, $insertTableAddress);
                  // get address id
                    $generatedIdData4 = array(
                      'table'                 => 'r_address'
                      , 'column'              => 'AddressId'
                    );
                    $AddressId = $this->maintenance_model->getGeneratedId($generatedIdData4);
                  // insert into borrower addresses
                      $insertDataAddress2 = array(
                        'SpouseId'                          => $SpouseId['SpouseId']
                        , 'AddressId'                       => $AddressId['AddressId']
                        , 'CreatedBy'                       => $EmployeeNumber
                        , 'UpdatedBy'                       => $EmployeeNumber
                      );
                    $insertTableAddress2 = 'borroweraddresshistory';
                    $this->maintenance_model->insertFunction($insertDataAddress2, $insertTableAddress2);
                }
            }
          // insert employer details
            $time2 = strtotime($_POST['SpouseDateHired']);
            $newformat2 = date('Y-m-d', $time2);
            $insertData3 = array(
              'SpouseId'             => $SpouseId['SpouseId']
              , 'EmployerName'       => htmlentities($_POST['SpouseEmployer'], ENT_QUOTES)
              , 'SpousePosition'     => htmlentities($_POST['PositionTitle'], ENT_QUOTES)
              , 'TelephoneNumber'    => htmlentities($_POST['TelephoneNumber'], ENT_QUOTES)
              , 'TenureYear'         => htmlentities($_POST['TenureYear'], ENT_QUOTES)
              , 'TenureMonth'        => htmlentities($_POST['TenureMonth'], ENT_QUOTES)
              , 'BusinessAddress'    => htmlentities($_POST['BusinessAddress'], ENT_QUOTES)
              , 'TelephoneNumber'    => htmlentities($_POST['TelephoneNumber'], ENT_QUOTES)
              , 'ContactNumber'      => htmlentities($_POST['ContactNumber'], ENT_QUOTES)
              , 'DateHired'         => htmlentities($newformat2, ENT_QUOTES)
              , 'CreatedBy'          => $EmployeeNumber
              , 'UpdatedBy'          => $EmployeeNumber
            );
            $insertTable3 = 'borrower_has_employer';
            $this->maintenance_model->insertFunction($insertData3, $insertTable3);
          // insert borrower spouse
            $insertSpouse = array(
              'BorrowerId'                        => $this->uri->segment(4)
              , 'SpouseId'                        => $SpouseId['SpouseId']
              , 'CreatedBy'                       => $EmployeeNumber
            );
            $insertSpouseTable = 'borrower_has_spouse';
            $this->maintenance_model->insertFunction($insertSpouse, $insertSpouseTable);
          // get borrower generated id
            $auditData1 = array(
              'table'                 => 'borrower_has_employer'
              , 'column'              => 'EmployerId'
            );
            $employerId = $this->maintenance_model->getGeneratedId($auditData1);
          // admin audits finalss
            $TransactionNumber = 'SR-'.sprintf('%06d', $SpouseId['SpouseId']);
            $auditLogsManager = 'Added spouse #'.$TransactionNumber.' in spouse tab for borrower #'.$borrowerDetail['BorrowerNumber'].'.';
            $auditAffectedEmployee = 'Added spouse #'.$TransactionNumber.' in spouse tab for borrower #'.$borrowerDetail['BorrowerNumber'].'.';
            $auditAffectedTable = 'Added spouse #'.$TransactionNumber.' in spouse tab.';
            $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
          // notification
            $this->session->set_flashdata('alertTitle','Success!'); 
            $this->session->set_flashdata('alertText','Spouse successfully recorded!'); 
            $this->session->set_flashdata('alertType','success'); 
            redirect('home/borrowerDetails/' . $this->uri->segment(4));
        }
        else
        {
          // notification
            $this->session->set_flashdata('alertTitle','Warning!'); 
            $this->session->set_flashdata('alertText','Spouse already existing!'); 
            $this->session->set_flashdata('alertType','warning'); 
            redirect('home/borrowerDetails/' . $this->uri->segment(4));
        }
      }
    }
    else if($this->uri->segment(3) == 6) // add employment
    {
      $borrowerDetail = $this->borrower_model->getBorrowerDetails($this->uri->segment(4));
      $time = strtotime($_POST['DateHired']);
      $newformat = date('Y-m-d', $time);
      $data = array(
        'EmployerName'         => htmlentities($_POST['BorrowerEmployer'], ENT_QUOTES)
        , 'PositionId'         => htmlentities($_POST['PositionId'], ENT_QUOTES)
        , 'DateHired'          => htmlentities($newformat, ENT_QUOTES)
        , 'TelephoneNumber'    => htmlentities($_POST['TelephoneNumber'], ENT_QUOTES)
        , 'TenureYear'         => htmlentities($_POST['TenureYear'], ENT_QUOTES)
        , 'TenureMonth'        => htmlentities($_POST['TenureMonth'], ENT_QUOTES)
        , 'BusinessAddress'    => htmlentities($_POST['BusinessAddress'], ENT_QUOTES)
        , 'IndustryId'         => htmlentities($_POST['EmploymentIndustry'], ENT_QUOTES)
        , 'EmployerStatus'     => htmlentities($_POST['EmploymentType'], ENT_QUOTES)
      );
      $query = $this->borrower_model->countEmploymentRecord($data);
      if($query == 0) // not existing
      {
        if(htmlentities($_POST['EmploymentIndustry'], ENT_QUOTES) == 1) // present employer, deactivate active present employer
        {
          // update detail
            $set = array( 
              'EmployerStatus' => 2
            );

            $condition = array( 
              'BorrowerId'      => $this->uri->segment(4),
              'StatusId'        => 1,
              'EmployerStatus'  => 1
            );
            $table = 'borrower_has_employer';
            $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
        // insert employer details
          $insertData = array(
            'BorrowerId'           => $this->uri->segment(4)
            , 'EmployerName'       => htmlentities($_POST['BorrowerEmployer'], ENT_QUOTES)
            , 'PositionId'         => htmlentities($_POST['PositionId'], ENT_QUOTES)
            , 'DateHired'          => htmlentities($newformat, ENT_QUOTES)
            , 'TelephoneNumber'    => htmlentities($_POST['TelephoneNumber'], ENT_QUOTES)
            , 'TenureYear'         => htmlentities($_POST['TenureYear'], ENT_QUOTES)
            , 'TenureMonth'        => htmlentities($_POST['TenureMonth'], ENT_QUOTES)
            , 'BusinessAddress'    => htmlentities($_POST['BusinessAddress'], ENT_QUOTES)
            , 'IndustryId'         => htmlentities($_POST['EmploymentIndustry'], ENT_QUOTES)
            , 'EmployerStatus'     => htmlentities($_POST['EmploymentType'], ENT_QUOTES)
            , 'CreatedBy'          => $EmployeeNumber
            , 'UpdatedBy'          => $EmployeeNumber
          );
          $insertTable = 'borrower_has_employer';
          $this->maintenance_model->insertFunction($insertData, $insertTable);
        // get borrower generated id
          $auditData1 = array(
            'table'                 => 'borrower_has_employer'
            , 'column'              => 'EmployerId'
          );
          $employerId = $this->maintenance_model->getGeneratedId($auditData1);
        // audit
          $transactionNumber = sprintf('%06d', $employerId['EmployerId']);
          $auditBorrower = 'Added employment #ER-'.$transactionNumber;
          $staffDesc = 'Added employment #ER-'.$transactionNumber.' in employment record of borrower #'.$borrowerDetail['BorrowerNumber'].'.';
          $this->auditBorrower($auditBorrower, $staffDesc, $this->uri->segment(4));
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Employment details successfully recorded!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/borrowerDetails/' . $this->uri->segment(4));
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Employment details already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/borrowerDetails/' . $this->uri->segment(4));
      }
    }
    else if($this->uri->segment(3) == 7) // add contact number
    {
      $isPrimary = 0;
      if(isset($_POST['isPrimary']))
      {
        $isPrimary = 1;
        $set = array( 
          'IsPrimary' => 0
        );

        $condition = array( 
          'BorrowerId' => $this->uri->segment(4)
        );
        $table = 'borrower_has_contactnumbers';
        $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
      else
      {
        $isPrimary = 0;
      }
      $data = array(
        'PhoneType'                     => htmlentities($_POST['ContactType'], ENT_QUOTES)
        , 'Number'                      => htmlentities($_POST['FieldNumber'], ENT_QUOTES)
        , 'BorrowerId'                  => $this->uri->segment(4)
      );
      $query = $this->employee_model->countContactNumber($data);
      if($query == 0)
      {
        if($_POST['ContactType'] == 'Mobile')
        {
          // insert into contact numbers
            $insertContact1 = array(
              'PhoneType'                     => 'Mobile'
              , 'Number'                      => htmlentities($_POST['FieldNumber'], ENT_QUOTES)
              , 'CreatedBy'                   => $EmployeeNumber
            );
            $insertContactTable1 = 'r_contactnumbers';
            $this->maintenance_model->insertFunction($insertContact1, $insertContactTable1);
          // get mobile number id
            $generatedIdData1 = array(
              'table'                         => 'r_contactnumbers'
              , 'column'                      => 'ContactNumberId'
            );
            $mobileNumberId = $this->maintenance_model->getGeneratedId($generatedIdData1);
          // insert into employee contact numbers
            $insertContact2 = array(
              'BorrowerId'                => $this->uri->segment(4)
              , 'ContactNumberId'             => $mobileNumberId['ContactNumberId']
              , 'IsPrimary'                   => $isPrimary
              , 'CreatedBy'                   => $EmployeeNumber
              , 'UpdatedBy'                   => $EmployeeNumber
            );
            $insertContactTable2 = 'borrower_has_contactnumbers';
            $this->maintenance_model->insertFunction($insertContact2, $insertContactTable2);
        }
        else // telephone number
        {
          // insert into telephone numbers
            $insertTelephone1 = array(
              'PhoneType'                     => 'Telephone'
              , 'Number'                      => htmlentities($_POST['FieldNumber'], ENT_QUOTES)
              , 'CreatedBy'                   => $EmployeeNumber
            );
            $insertTelephoneTable1 = 'r_contactnumbers';
            $this->maintenance_model->insertFunction($insertTelephone1, $insertTelephoneTable1);
          // get mobile number id
            $generatedIdData2 = array(
              'table'                 => 'r_contactnumbers'
              , 'column'              => 'ContactNumberId'
            );
            $TelephoneNumberId = $this->maintenance_model->getGeneratedId($generatedIdData2);
          // insert into client contact numbers
            $insertTelephone2 = array(
              'BorrowerId'                    => $this->uri->segment(4)
              , 'ContactNumberId'             => $TelephoneNumberId['ContactNumberId']
              , 'IsPrimary'                   => $isPrimary
              , 'CreatedBy'                   => $EmployeeNumber
              , 'UpdatedBy'                   => $EmployeeNumber
            );
            $insertTelephoneTable2 = 'borrower_has_contactnumbers';
            $this->maintenance_model->insertFunction($insertTelephone2, $insertTelephoneTable2);
        }
        // insert into notifications
          $borrowerDetail = $this->borrower_model->getBorrowerDetails($_POST['BorrowerId']);
          $getNewId = array(
            'table'                             => 'borrower_has_contactnumbers'
            , 'column'                          => 'BorrowerContactId'
            , 'CreatedBy'                       => $EmployeeNumber
          );
          $NewId = $this->maintenance_model->getGeneratedId2($getNewId);
          $rowNumber = $this->db->query("SELECT LPAD(".$NewId['BorrowerContactId'].", 6, 0) as number")->row_array();
          $auditMainAndManagerLog = 'Added new contact record #CN-' . $rowNumber['number'].' for borrower #'. $borrowerDetail['BorrowerNumber'];
          $auditEmpDetail = 'Added new contact record #CN-' . $rowNumber['number'];


          $auditBorrower = 'Added new contact record #CN-' . $rowNumber['number'].'.';
          $auditStaff = 'Added new contact record #CN-'.$rowNumber['number'].' for borrower #'. $borrowerDetail['BorrowerNumber'];
          $this->auditBorrower($auditBorrower, $auditStaff, $this->uri->segment(4));
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Contact number successfully added!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/borrowerDetails/' . $this->uri->segment(4));
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Contact number already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/borrowerDetails/' . $this->uri->segment(4));
      }
    }
    else if($this->uri->segment(3) == 8) // add Education
    {
      $data = array(
        'Level'                        => htmlentities($_POST['EducationLevel'], ENT_QUOTES)
        , 'SchoolName'                 => htmlentities($_POST['SchoolName'], ENT_QUOTES)
        , 'YearGraduated'              => htmlentities($_POST['EducationYear'], ENT_QUOTES)
        , 'BorrowerId'                 => $this->uri->segment(4)
      );
      $query = $this->borrower_model->countEducation($data);
      if($query == 0) // not existing
      {
        // insert details
          $insertBorrower = array(
            'EducationId'                  => htmlentities($_POST['EducationLevel'], ENT_QUOTES)
            , 'SchoolName'                 => htmlentities($_POST['SchoolName'], ENT_QUOTES)
            , 'YearGraduated'              => htmlentities($_POST['EducationYear'], ENT_QUOTES)
            , 'BorrowerId'                 => $this->uri->segment(4)
            , 'CreatedBy'                  => $EmployeeNumber
            , 'UpdatedBy'                  => $EmployeeNumber
          );
          $insertBorrowerTable = 'borrower_has_Education';
          $this->maintenance_model->insertFunction($insertBorrower, $insertBorrowerTable);
        // admin audits finalss
          $getNewId = array(
            'table'                             => 'borrower_has_Education'
            , 'column'                          => 'BorrowerEducationId'
            , 'CreatedBy'                       => $EmployeeNumber
          );
          $NewId = $this->maintenance_model->getGeneratedId2($getNewId);
          $borrowerDetail = $this->borrower_model->getBorrowerDetails($_POST['BorrowerId']);
          $rowNumber = $this->db->query("SELECT LPAD(".$NewId['BorrowerEducationId'].", 6, 0) as number")->row_array();
          $auditLogsManager = 'Added new education background #EDU-' . $rowNumber['number'].' for borrower #'.$borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedEmployee = 'Added new education background #EDU-' . $rowNumber['number'].' for borrower #'.$borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedTable = 'Added new education background #EDU-' . $rowNumber['number'].' in education tab.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Education successfully added!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/BorrowerDetails/' . htmlentities($this->uri->segment(4), ENT_QUOTES));
      }
      else
      {
        // notification
          $this->session->set_flashdata('alertTitle','Warning!'); 
          $this->session->set_flashdata('alertText','Education already existing!'); 
          $this->session->set_flashdata('alertType','warning'); 
          redirect('home/BorrowerDetails/' . htmlentities($this->uri->segment(4), ENT_QUOTES));
      }
    }
    else if($this->uri->segment(3) == 9) // add address
    {
      $EmployeeNumber = $this->session->userdata('EmployeeNumber');
      // insert into address table
        $insertDataAddress = array(
          'HouseNo'                           => htmlentities($_POST['HouseNo'], ENT_QUOTES)
          , 'AddressType'                     => htmlentities($_POST['AddressType'], ENT_QUOTES)
          , 'BarangayId'                      => htmlentities($_POST['BarangayId'], ENT_QUOTES)
          , 'Telephone'                       => $_POST['TelephoneCityAddress']
          , 'ContactNumber'                   => $_POST['CellphoneCityAdd']
          , 'CreatedBy'                       => $EmployeeNumber
        );
        $insertTableAddress = 'r_address';
        $this->maintenance_model->insertFunction($insertDataAddress, $insertTableAddress);
      // get address id
        $generatedIdData = array(
          'table'                 => 'r_address'
          , 'column'              => 'AddressId'
        );
        $AddressId = $this->maintenance_model->getGeneratedId($generatedIdData);
      // Update existing primary address
        if($_POST['isPrimary'] == 1)
        {
          $set = array( 
            'isPrimary' => 0
          );

          $condition = array( 
            'BorrowerId' => $this->uri->segment(4)
            , 'isPrimary' => 1
          );
          $table = 'borroweraddresshistory';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
        }
      // insert into employee address      
        $insertDataAddress2 = array(
          'BorrowerId'                        => $this->uri->segment(4)
          , 'AddressId'                       => $AddressId['AddressId']
          , 'IsPrimary'                       => htmlentities($_POST['isPrimary'], ENT_QUOTES)
          , 'YearsStayed'                     => $_POST['YearsStayed']
          , 'MonthsStayed'                    => $_POST['MonthsStayed']
          , 'NameOfLandlord'                  => $_POST['NameOfLandlord']
          , 'AddressType'                     => htmlentities($_POST['optionsRadios'], ENT_QUOTES) // owned and whatnot
          , 'ContactNumber'                   => $_POST['LandLordNumber']
          , 'CreatedBy'                       => $EmployeeNumber
          , 'UpdatedBy'                       => $EmployeeNumber
        );
        $insertTableAddress2 = 'borroweraddresshistory';
        $this->maintenance_model->insertFunction($insertDataAddress2, $insertTableAddress2);

      // admin audits
        $getNewId = $this->maintenance_model->selectSpecific('borroweraddresshistory', 'BorrowerId', $this->uri->segment(4));
        $generatedIdData1 = array(
          'table'                 => 'borroweraddresshistory'
          , 'column'              => 'BorrowerAddressHistoryId'
        );
        $newId = $this->maintenance_model->getGeneratedId($generatedIdData1);

        $TransactionNumber = 'ADD-'.sprintf('%06d', $newId['BorrowerAddressHistoryId']);
        $auditLogsManager = 'Added new address #'.$TransactionNumber.' for borrower #'.$borrowerDetail['BorrowerNumber'].'.';
        $auditAffectedEmployee = 'Added new address #'.$TransactionNumber.' for borrower #'.$borrowerDetail['BorrowerNumber'].'.';
        $auditAffectedTable = 'Added new address #'.$TransactionNumber.'.';
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Borrower address successfully recorded!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/BorrowerDetails/'. $this->uri->segment(4));
    }
    else if($this->uri->segment(3) == 10) // add email address
    {
      $isPrimary = 0;
      if(isset($_POST['isPrimary']))
      {
        $isPrimary = 1;
      }
      else
      {
        $isPrimary = 0;
      }
      $set = array( 
        'IsPrimary' => 0
      );

      $condition = array( 
        'BorrowerId' => $this->uri->segment(4)
      );
      $table = 'borrower_has_emails';
      $this->maintenance_model->updateFunction1($set, $condition, $table);

      $data = array(
        'EmailAddress'              => htmlentities($_POST['EmailAddress'], ENT_QUOTES)
        , 'BorrowerId'              => $this->uri->segment(4)
      );
      // insert into email
        $insertEmail1 = array(
          'EmailAddress'                      => htmlentities($_POST['EmailAddress'], ENT_QUOTES)
          , 'CreatedBy'                       => $EmployeeNumber
        );
        $insertEmailTable1 = 'r_emails';
        $this->maintenance_model->insertFunction($insertEmail1, $insertEmailTable1);
      // get email id
        $generatedIdData1 = array(
          'table'                         => 'r_emails'
          , 'column'                      => 'EmailId'
        );
        $generatedIdNumber = $this->maintenance_model->getGeneratedId($generatedIdData1);
      // insert into employee email
        $insertEmail2 = array(
          'BorrowerId'                    => $this->uri->segment(4)
          , 'EmailId'                     => $generatedIdNumber['EmailId']
          , 'IsPrimary'                   => $isPrimary
          , 'CreatedBy'                   => $EmployeeNumber
          , 'UpdatedBy'                   => $EmployeeNumber
        );
        $insertEmailTable2 = 'borrower_has_emails';
        $this->maintenance_model->insertFunction($insertEmail2, $insertEmailTable2);
      // admin audits finals
        $generatedIdData1 = array(
          'table'                 => 'borrower_has_emails'
          , 'column'              => 'BorrowerEmailId'
        );
        $newId = $this->maintenance_model->getGeneratedId($generatedIdData1);

        $TransactionNumber = 'EA-'.sprintf('%06d', $newId['BorrowerEmailId']);
        $auditLogsManager = 'Added new email #'.$TransactionNumber.' for borrower #'.$borrowerDetail['BorrowerNumber'].'.';
        $auditAffectedEmployee = 'Added new email #'.$TransactionNumber.' for borrower #'.$borrowerDetail['BorrowerNumber'].'.';
        $auditAffectedTable = 'Added new email #'.$TransactionNumber.' in emails tab.';
        $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Email address successfully added!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/BorrowerDetails/'. $this->uri->segment(4));
    }
  }

  function auditBorrowerDetails($auditLogsManager, $auditAffectedEmployee, $ManagerId, $AffectedEmployeeNumber, $auditBorrowerDets ,$borrowerId)
  {
    $CreatedBy = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    $insertMainLog = array(
      'Description'       => $auditLogsManager
      , 'CreatedBy'       => $CreatedBy
    );
    $auditTable1 = 'R_Logs';
    $this->maintenance_model->insertFunction($insertMainLog, $auditTable1);
    $insertManagerAudit = array(
      'Description'         => $auditLogsManager
      , 'ManagerBranchId'   => $ManagerId
      , 'CreatedBy'         => $CreatedBy
    );
    $auditTable3 = 'manager_has_notifications';
    $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable3);
    $insertEmpLog = array(
      'Description'       => $auditAffectedEmployee
      , 'EmployeeNumber'  => $AffectedEmployeeNumber
      , 'CreatedBy'       => $CreatedBy
    );
    $auditTable2 = 'employee_has_notifications';
    $this->maintenance_model->insertFunction($insertEmpLog, $auditTable2);
    $insertApplicationLog = array(
      'Description'       => $auditBorrowerDets
      , 'BorrowerId'      => $borrowerId
      , 'CreatedBy'       => $CreatedBy
    );
    $auditLoanApplicationTable = 'borrower_has_notifications';
    $this->maintenance_model->insertFunction($insertApplicationLog, $auditLoanApplicationTable);
  }

  function auditBorrower($borrowerDesc, $staffDesc, $BorrowerId)
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    $employeeDetail = $this->maintenance_model->getCreatorDetails();
    // insert into borrower notification
      $insertEmployeeAudit = array(
        'Description' => $borrowerDesc,
        'CreatedBy'   => $EmployeeNumber,
        'BorrowerId'  => $BorrowerId
      );
      $auditEmployeeTable = 'borrower_has_notifications';
      $this->maintenance_model->insertFunction($insertEmployeeAudit, $auditEmployeeTable);
    // insert into manager and employee notification
      $insertManagerAudit = array(
        'Description'       => $staffDesc,
        'CreatedBy'         => $EmployeeNumber,
        'ManagerBranchId'   => $employeeDetail['ManagerBranchId']
      );
      $auditManagerTable = 'manager_has_notifications';
      $this->maintenance_model->insertFunction($insertManagerAudit, $auditManagerTable);

      $insertAudit = array(
        'Description'       => $staffDesc,
        'CreatedBy'         => $EmployeeNumber,
        'EmployeeNumber'    => $EmployeeNumber
      );
      $auditTable = 'employee_has_notifications';
      $this->maintenance_model->insertFunction($insertAudit, $auditTable);
  }

  function supportingInfoUpdate()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $employeeDetail = $this->maintenance_model->getCreatorDetails();
    $borrowerDetail = $this->borrower_model->getBorrowerDetails($this->uri->segment(4));
    // salutation
      if($borrowerDetail['SalutationId'] != htmlentities($_POST['SalutationId'], ENT_QUOTES))
      {
        $data1 = array(
          'column'                      => 'Name'
          , 'table'                     => 'R_Salutation'
          , 'query'                     => 'WHERE SalutationId = '. htmlentities($borrowerDetail['SalutationId'], ENT_QUOTES)
        );
        $data2 = array(
          'column'                      => 'Name'
          , 'table'                     => 'R_Salutation'
          , 'query'                     => 'WHERE SalutationId = '. htmlentities($_POST['SalutationId'], ENT_QUOTES)
        );
        $oldDetail = $this->employee_model->getNameOfCategory($data1);
        $newDetail = $this->employee_model->getNameOfCategory($data2);
        // admin audits finalss
          $auditLogsManager = 'Updated salutation from '.$oldDetail['Name'].' to '.$newDetail['Name'].' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedEmployee = 'Updated salutation from '.$oldDetail['Name'].' to '.$newDetail['Name'].' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedTable = 'Updated salutation from '.$oldDetail['Name'].' to '.$newDetail['Name'].'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
        // update detail
          $set = array( 
            'Salutation' => htmlentities($_POST['SalutationId'], ENT_QUOTES)
          );

          $condition = array( 
            'BorrowerId' => $this->uri->segment(4)
          );
          $table = 'R_Borrowers';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // sex
      if($borrowerDetail['SexId'] != htmlentities($_POST['SexId'], ENT_QUOTES))
      {
        $data1 = array(
          'column'                      => 'Name'
          , 'table'                     => 'R_Sex'
          , 'query'                     => 'WHERE SexId = '. htmlentities($borrowerDetail['SexId'], ENT_QUOTES)
        );

        $data2 = array(
          'column'                      => 'Name'
          , 'table'                     => 'R_Sex'
          , 'query'                     => 'WHERE SexId = '. htmlentities($_POST['SexId'], ENT_QUOTES)
        );
        $oldDetail = $this->employee_model->getNameOfCategory($data1);
        $newDetail = $this->employee_model->getNameOfCategory($data2);
        // admin audits finalss
          $auditLogsManager = 'Updated gender from '.$oldDetail['Name'].' to '.$newDetail['Name'].' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedEmployee = 'Updated gender from '.$oldDetail['Name'].' to '.$newDetail['Name'].' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedTable = 'Updated gender from '.$oldDetail['Name'].' to '.$newDetail['Name'].'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
        // update detail
          $set = array( 
            'Sex' => htmlentities($_POST['SexId'], ENT_QUOTES)
          );

          $condition = array( 
            'BorrowerId' => $this->uri->segment(4)
          );
          $table = 'R_Borrowers';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // nationality
      if($borrowerDetail['NationalityId'] != htmlentities($_POST['NationalityId'], ENT_QUOTES))
      {
        $data1 = array(
          'column'                      => 'Description'
          , 'table'                     => 'R_Nationality'
          , 'query'                     => 'WHERE NationalityId = '. htmlentities($borrowerDetail['NationalityId'], ENT_QUOTES)
        );

        $data2 = array(
          'column'                      => 'Description'
          , 'table'                     => 'R_Nationality'
          , 'query'                     => 'WHERE NationalityId = '. htmlentities($_POST['NationalityId'], ENT_QUOTES)
        );
        $oldDetail = $this->employee_model->getNameOfCategory($data1);
        $newDetail = $this->employee_model->getNameOfCategory($data2);
        // admin audits finalss
          $auditLogsManager = 'Updated nationality from '.$oldDetail['Description'].' to '.$newDetail['Description'].' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedEmployee = 'Updated nationality from '.$oldDetail['Description'].' to '.$newDetail['Description'].' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedTable = 'Updated nationality from '.$oldDetail['Description'].' to '.$newDetail['Description'].'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
        // update detail
          $set = array( 
            'Nationality' => htmlentities($_POST['NationalityId'], ENT_QUOTES)
          );

          $condition = array( 
            'BorrowerId' => $this->uri->segment(4)
          );
          $table = 'R_Borrowers';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // civil status
      if($borrowerDetail['CivilStatusId'] != htmlentities($_POST['CivilStatusId'], ENT_QUOTES))
      {
        $data1 = array(
          'column'                      => 'Name'
          , 'table'                     => 'r_civilstatus'
          , 'query'                     => 'WHERE CivilStatusId = '. htmlentities($borrowerDetail['CivilStatusId'], ENT_QUOTES)
        );

        $data2 = array(
          'column'                      => 'Name'
          , 'table'                     => 'r_civilstatus'
          , 'query'                     => 'WHERE CivilStatusId = '. htmlentities($_POST['CivilStatusId'], ENT_QUOTES)
        );
        $oldDetail = $this->employee_model->getNameOfCategory($data1);
        $newDetail = $this->employee_model->getNameOfCategory($data2);
        // admin audits finalss
          $auditLogsManager = 'Updated civil status from '.$oldDetail['Name'].' to '.$newDetail['Name'].' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedEmployee = 'Updated civil status from '.$oldDetail['Name'].' to '.$newDetail['Name'].' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedTable = 'Updated civil status from '.$oldDetail['Name'].' to '.$newDetail['Name'].'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
        // update detail
          $set = array( 
            'CivilStatus' => htmlentities($_POST['CivilStatusId'], ENT_QUOTES)
          );

          $condition = array( 
            'BorrowerId' => $this->uri->segment(4)
          );
          $table = 'R_Borrowers';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // status
      if($borrowerDetail['StatusId'] != htmlentities($_POST['StatusId'], ENT_QUOTES))
      {
        $data1 = array(
          'column'                      => 'Name'
          , 'table'                     => 'r_borrowerstatus'
          , 'query'                     => 'WHERE BorrowerStatusId = '. htmlentities($borrowerDetail['StatusId'], ENT_QUOTES)
        );

        $data2 = array(
          'column'                      => 'Name'
          , 'table'                     => 'r_borrowerstatus'
          , 'query'                     => 'WHERE BorrowerStatusId = '. htmlentities($_POST['StatusId'], ENT_QUOTES)
        );
        $oldDetail = $this->employee_model->getNameOfCategory($data1);
        $newDetail = $this->employee_model->getNameOfCategory($data2);
        // admin audits finalss
          $auditLogsManager = 'Updated status from '.$oldDetail['Name'].' to '.$newDetail['Name'].' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedEmployee = 'Updated status from '.$oldDetail['Name'].' to '.$newDetail['Name'].' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedTable = 'Updated status from '.$oldDetail['Name'].' to '.$newDetail['Name'].'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
        // update detail
          $set = array( 
            'StatusId' => htmlentities($_POST['StatusId'], ENT_QUOTES)
          );

          $condition = array( 
            'BorrowerId' => $this->uri->segment(4)
          );
          $table = 'R_Borrowers';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // dependents
      if($borrowerDetail['Dependents'] != htmlentities($_POST['NoDependents'], ENT_QUOTES))
      {
        // admin audits finalss
          $auditLogsManager = 'Updated number of dependents from '.$borrowerDetail['Dependents'].' to '.htmlentities($_POST['NoDependents'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'];
          $auditAffectedEmployee = 'Updated number of dependents from '.$borrowerDetail['Dependents'].' to '.htmlentities($_POST['NoDependents'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'];
          $auditAffectedTable = 'Updated number of dependents from '.$borrowerDetail['Dependents'].' to '.htmlentities($_POST['NoDependents'], ENT_QUOTES);
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
        // update detail
          $set = array( 
            'Dependents' => htmlentities($_POST['NoDependents'], ENT_QUOTES)
          );

          $condition = array( 
            'BorrowerId' => $this->uri->segment(4)
          );
          $table = 'R_Borrowers';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
    // birthplace
      if($borrowerDetail['Birthplace'] != htmlentities($_POST['BirthPlace'], ENT_QUOTES))
      {
        // admin audits finalss
          $auditLogsManager = 'Updated birthplace from '.$borrowerDetail['Birthplace'].' to '.htmlentities($_POST['BirthPlace'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedEmployee = 'Updated birthplace from '.$borrowerDetail['Birthplace'].' to '.htmlentities($_POST['BirthPlace'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $auditAffectedTable = 'Updated birthplace from '.$borrowerDetail['Birthplace'].' to '.htmlentities($_POST['BirthPlace'], ENT_QUOTES).'.';
          $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $EmployeeNumber, $auditAffectedTable, $this->uri->segment(4), 'borrower_has_notifications', 'BorrowerId');
        // update detail
          $set = array( 
            'Birthplace' => htmlentities($_POST['BirthPlace'], ENT_QUOTES)
          );

          $condition = array( 
            'BorrowerId' => $this->uri->segment(4)
          );
          $table = 'R_Borrowers';
          $this->maintenance_model->updateFunction1($set, $condition, $table);
      }
  }

  function updateEmail()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $input = array( 
      'Id' => htmlentities($this->input->post('Id'), ENT_QUOTES)
      , 'updateType' => htmlentities($this->input->post('updateType'), ENT_QUOTES)
      , 'tableType' => htmlentities($this->input->post('tableType'), ENT_QUOTES)
    );

    // $query = $this->borrower_model->updateEmail($input);
    $output = $this->borrower_model->updateEmail($input);
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function IDCategory()
  {
    echo $this->maintenance_model->IDCategory();
  }

  function IDCategory2()
  {
    echo $this->maintenance_model->IDCategory2();
  }

  function IDCategory3()
  {
    echo $this->maintenance_model->IDCategory3($this->input->post('Id'));
  }

  function AddContact()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if($_POST['FormType'] == 1) // add contact
    {
      if($_POST['ContactType'] == 'Mobile') //Mobile Number
      {
        // contact number
          $insertContact = array(
            'PhoneType'                     => 'Mobile'
            , 'Number'                      => htmlentities($_POST['ContactNumber'], ENT_QUOTES)
            , 'CreatedBy'                      => $EmployeeNumber
          );
          $insertContactTable = 'r_contactnumbers';
          $this->maintenance_model->insertFunction($insertContact, $insertContactTable);
        // get generated contactnumber id
          $generatedIdData = array(
            'table'                 => 'r_contactnumbers'
            , 'column'              => 'ContactNumberId'
          );
          $ContactNumberId = $this->maintenance_model->getGeneratedId($generatedIdData);
        // insert into borrower_has_contact
            $insertContact2 = array(
            'BorrowerId'                      => $_POST['ContactNumber']
              , 'ContactNumberId'             => $ContactNumberId['ContactNumberId']
              , 'CreatedBy'                   => $EmployeeNumber
              , 'UpdatedBy'                   => $EmployeeNumber
          );
          $insertContactTable2 = 'borrower_has_contactnumbers';
          $this->maintenance_model->insertFunction($insertContact2, $insertContactTable2);
        // notification
          $this->session->set_flashdata('alertTitle','Success!');
          $this->session->set_flashdata('alertText','Contact number successfully added!'); 
          $this->session->set_flashdata('alertType','success'); 
          redirect('home/borrowerDetails/' . $BorrowerId['BorrowerId']);
      }
      else // Telephone Number
      {
        $insertMobile = array(
          'PhoneType'                     => 'Telephone'
          , 'Number'                      => htmlentities($_POST['ContactNumber'], ENT_QUOTES)
          , 'CreatedBy'                      => $EmployeeNumber
        );
        $insertManagerTable = 'borrower_has_contactnumbers';
        $this->maintenance_model->insertFunction($insertManager, $insertManagerTable);
      }


    }
    else if($_POST['FormType'] == 2) // edit contact
    {
      // input here the update function

      // notification
        $this->session->set_flashdata('alertTitle','Success!'); 
        $this->session->set_flashdata('alertText','Contact number successfully updated!'); 
        $this->session->set_flashdata('alertType','success'); 
        redirect('home/borrowers');
    }
  }

  function getAllList()
  {
    $result = $this->borrower_model->getAllList();
    foreach($result as $key=>$row)
    {
      $result[$key]['TotalLoans'] = $this->borrower_model->getTotalLoans($row['BorrowerId']);
    }
    echo json_encode($result);
  }

  function filterBorrower()
  {
    $result = $this->borrower_model->filterBorrower($this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5), $this->uri->segment(6), $this->uri->segment(7));
    foreach($result as $key=>$row)
    {
      $result[$key]['TotalLoans'] = $this->borrower_model->getTotalLoans($row['BorrowerId']);
    }
    echo json_encode($result);
  }


  function getBorrowerDetails()
  {
    $output = $this->borrower_model->getBorrowerDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getBorrowerEmployment()
  {
    $output = $this->borrower_model->getBorrowerEmployment($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getBorrowerCityAddress()
  {
    $output = $this->borrower_model->getBorrowerCityAddress($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getSpouseDetails()
  {
    $output = $this->borrower_model->getSpouseDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getSpouseCityAddress()
  {
    $output = $this->borrower_model->getSpouseCityAddress($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getSpouseProvAddress()
  {
    $output = $this->borrower_model->getSpouseCityAddress($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getSpouseEmployer()
  {
    $output = $this->borrower_model->getSpouseEmployer($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getSpouseEmail()
  {
    $output = $this->borrower_model->getSpouseEmail($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getPersonalDetails()
  {
    $output = $this->borrower_model->getPersonalDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getComakerDetails()
  {
    $output = $this->borrower_model->getComakerDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function generateReport()
  {
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'M.C Biliber Lending Corporation', "Income Statement");
    // set margins
    $pdf->SetMargins('10', '20', '10');
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->SetFont('dejavusans', '', 10);

    // Affected Asset
      $pdf->AddPage('L');

      $html = '
        <style>
        table {
          border-collapse: collapse;
        }

        table, td, th {
          border: 1px solid black;
        }
        div.a{
          text-align: right;
        }
        div.b{
          text-align: left;
        }
        </style>
        <br>




        <table>
          <thead>
          <tr>
            <th><strong>Affected Asset</strong></th>
            <th><strong>Activity Undertake</strong></th>
            <th><strong>Status of Activity</strong></th>
          </tr>
          </thead>
          <tbody>';
            // foreach($details as $key => $current) {
            //   $html .= '<tr>
            //       <td>' . $current['AffectedStructure'] . '</td>
            //       <td>' . $current['ActivityUndertaken'] . '</td>
            //       <td>' . $current['ActivityStatus'] . '</td>
            //   </tr>';
            // }
          $html .= '
          </tbody>
        </table>
      ';
      $pdf->writeHTML($html, true, false, true, false, '');

    // Close and output PDF document
      $pdf->Output('Form3.pdf', 'I');
      // $pdf->Output('Form6.pdf', 'D');
  }

  function auditFunction($auditLogsManager, $auditAffectedEmployee, $ManagerId, $AffectedEmployeeNumber, $auditLoanDets, $ApplicationId, $independentTable, $independentColumn)
  {
    $CreatedBy = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    $insertMainLog = array(
      'Description'       => $auditLogsManager
      , 'CreatedBy'       => $CreatedBy
    );
    $auditTable1 = 'R_Logs';
    $this->maintenance_model->insertFunction($insertMainLog, $auditTable1);
    $insertManagerAudit = array(
      'Description'         => $auditLogsManager
      , 'ManagerBranchId'   => $ManagerId
      , 'CreatedBy'         => $CreatedBy
    );
    $auditTable3 = 'manager_has_notifications';
    $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable3);
    $insertEmpLog = array(
      'Description'       => $auditAffectedEmployee
      , 'EmployeeNumber'  => $AffectedEmployeeNumber
      , 'CreatedBy'       => $CreatedBy
    );
    $auditTable2 = 'employee_has_notifications';
    $this->maintenance_model->insertFunction($insertEmpLog, $auditTable2);
    $insertApplicationLog = array(
      'Description'       => $auditLoanDets
      , ''.$independentColumn.''   => $ApplicationId
      , 'CreatedBy'       => $CreatedBy
    );
    $auditLoanApplicationTable = $independentTable;
    $this->maintenance_model->insertFunction($insertApplicationLog, $auditLoanApplicationTable);
  }

  public function uploadForm3Excel()
  {
    if(isset($_FILES["form3UploadExcel"]["name"]))
    {
      $path = $_FILES["form3UploadExcel"]["tmp_name"];
      $obj = PHPExcel_IOFactory::load($path);

      $EmployeeData = array();
      $consolidatedDamagedItem = array();

      $dateCreated = date('Y-m-d H:i:s');
      $createdBy = $this->session->userdata('EmployeeNumber');

      $execution_time_limit = 300;
      set_time_limit($execution_time_limit);

      $highestRow = 0;

      foreach($obj->getWorksheetIterator() as $worksheet)
      {
        $sheetName = $worksheet->getTitle();
        $highestRow = $worksheet->getHighestDataRow();
        $highestCol = $worksheet->getHighestDataColumn();

        $title = $worksheet->getCellByColumnAndRow(0, 1)->getValue(); // CELL A1
        $rowCount = 0;

        if($sheetName == 'DATA')
        {
          for($row = 2; $row <= $highestRow; $row++)
          {
            $Salutation = $worksheet->getCellByColumnandRow(0, $row)->getValue();
            $LastName = $worksheet->getCellByColumnandRow(1, $row)->getValue();
            $FirstName = $worksheet->getCellByColumnandRow(2, $row)->getValue();
            $ExtName = $worksheet->getCellByColumnandRow(3, $row)->getValue();
            $MiddleName = $worksheet->getCellByColumnandRow(4, $row)->getValue();
            $MotherName = $worksheet->getCellByColumnandRow(5, $row)->getValue();
            $Gender = $worksheet->getCellByColumnandRow(6, $row)->getValue();
            $Nationality = $worksheet->getCellByColumnandRow(7, $row)->getValue();
            $CivilStatus = $worksheet->getCellByColumnandRow(8, $row)->getValue();
            $DOB = $worksheet->getCellByColumnandRow(9, $row)->getFormattedValue();
            $Dependents = $worksheet->getCellByColumnandRow(10, $row)->getValue();
            $Branch = str_replace(' ', '', strtolower($worksheet->getCellByColumnandRow(11, $row)->getValue()));

            if($LastName != '' && $FirstName != '' && $Salutation != '' && $Gender != '' && $Nationality != '' && $CivilStatus != '' && $DOB != '' && $Branch != '' && $MotherName != '' && $Dependents != '')
            {
              $borrowerName = str_replace(' ', '', strtolower($LastName. ', '. $FirstName. ' ' . $MiddleName. ' ' . $ExtName));

              $dbBorrowerName = $this->loanapplication_model->getBorrowerByName($borrowerName);
              $dbBranch = $this->loanapplication_model->getBranchByName($Branch);
              $SalutationId = $this->maintenance_model->getReferenceId('SalutationId', 'R_Salutation', $Salutation, 'Name');
              $GenderId = $this->maintenance_model->getReferenceId('SexId', 'r_sex', $Gender, 'Name');
              $CivilStatusId = $this->maintenance_model->getReferenceId('CivilStatusId', 'r_civilstatus', $CivilStatus, 'Name');
              $NationalityId = $this->maintenance_model->getReferenceId('NationalityId', 'r_nationality', $Nationality, 'Description');
              $BranchId = $this->maintenance_model->getReferenceId('BranchId', 'r_branches', $Branch, 'Name');
              $branchCode = $this->maintenance_model->selectSpecific('r_branches', 'BranchId', $BranchId['Id']);

              $time = strtotime($DOB);
              $DateOfB = date('Y-m-d', $time);

              if($dbBranch['Name'] != null && $SalutationId['Id'] != null && $GenderId['Id'] != null && $NationalityId['Id'] != null && $CivilStatusId['Id'] != null && $dbBorrowerName['Name'] == null)
              {
                // employee
                  $data = array(
                    'Salutation'    => $SalutationId['Id'],
                    'LastName'      => $LastName,
                    'FirstName'     => $FirstName,
                    'ExtName'       => $ExtName,
                    'MiddleName'    => $MiddleName,
                    'Sex'           => $GenderId['Id'],
                    'Nationality'   => $NationalityId['Id'],
                    'CivilStatus'   => $CivilStatusId['Id'],
                    'DateOfBirth'   => $DateOfB,
                    'StatusId'      => 1,
                    'DateCreated'   => $dateCreated,
                    'CreatedBy'     => $createdBy,
                    'BranchId'      => $BranchId['Id'],
                    'Dependents'    => $Dependents,
                    'MotherName'    => $MotherName
                  );
                  $table = 'R_Borrowers';
                  $this->maintenance_model->insertFunction($data, $table);
                // get employee generated id
                  $auditData1 = array(
                    'table'                 => 'R_Borrowers'
                    , 'column'              => 'BorrowerId'
                  );
                  $BorrowerId = $this->maintenance_model->getGeneratedId($auditData1);
                  $BorrowerNumber = $branchCode['Code'] . '-' . sprintf('%06d', $BorrowerId['BorrowerId']);
                // update employee numbers
                  $set = array( 
                    'BorrowerNumber' => $BorrowerNumber
                  );

                  $condition = array( 
                    'BorrowerId' => $BorrowerId['BorrowerId']
                  );
                  $table = 'R_Borrowers';
                  $this->maintenance_model->updateFunction1($set, $condition, $table);
                // admin audits finalss
                  $auditLogsManager = 'Uploaded borrower #'.$BorrowerNumber . ' to borrower list.';
                  $auditAffectedEmployee = 'Uploaded borrower #'.$BorrowerNumber . ' to borrower list.';
                  $auditAffectedTable = 'Uploaded to borrower list.';
                  $this->AuditFunction($auditLogsManager, $auditAffectedEmployee, $this->session->userdata('ManagerId'), $createdBy, $auditAffectedTable, $BorrowerId['BorrowerId'], 'borrower_has_notifications', 'BorrowerId');
                $rowCount = $rowCount + 1;
              }
            }
          } // END FOR LOOP

          echo "Borrower records successfully saved! " . $rowCount . " records inserted.";
        }

      } // END FOREACH
    }
    else
    {
      echo "File not set";
    }
  }

}
