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
    $DateNow = date("Y-m-d H:i:s");
    $employeeDetail = $this->maintenance_model->getCreatorDetails();
    if($this->uri->segment(3) == 1) // add borrower
    {
      $time = strtotime($_POST['DOB']);
      $newformat = date('Y-m-d', $time);

      if(htmlentities($_POST['BranchId'], ENT_QUOTES) == null)
      {
        $assignedBranch = htmlentities($_POST['BranchId'], ENT_QUOTES);
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
          $auditquery = $this->borrower_model->getBorrowerDetails($BorrowerId['BorrowerId']);
          $auditDetail = 'Added '.$auditquery['Name'].' in borrower list.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $EmployeeNumber,
            'DateCreated' => $DateNow
          );
          $this->maintenance_model->insertAdminLog($insertData);

        // borrower audits
          $auditBorrower = 'Added in Borrowers list.';
          $insertAuditBorrower = array(
            'Description' => $auditBorrower,
            'BorrowerId' => $BorrowerId['BorrowerId'],
            'CreatedBy' => $EmployeeNumber,
            'DateCreated' => $DateNow
          );
          $auditTable = 'borrower_has_notifications';
          $this->maintenance_model->insertFunction($insertAuditBorrower, $auditTable);

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
      $data = array(
        'Name'                        => htmlentities($_POST['Name'], ENT_QUOTES)
        , 'Address'                   => htmlentities($_POST['Address'], ENT_QUOTES)
        , 'ContactNumber'             => htmlentities($_POST['ContactNumber'], ENT_QUOTES)
        , 'BorrowerId'                => htmlentities($this->uri->segment(4), ENT_QUOTES)
      );
      $query = $this->borrower_model->countPersonalReference($data);
      if($query == 0) // not existing
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
        // admin audits
          $auditDetail = 'Added '.htmlentities($_POST['Name'], ENT_QUOTES).' in personal reference list.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $EmployeeNumber,
            'DateCreated' => $DateNow
          );
          $this->maintenance_model->insertAdminLog($insertData);

        // borrower audits
          $auditBorrower = $auditDetail;
          $insertAuditBorrower = array(
            'Description' => $auditBorrower,
            'BorrowerId' => htmlentities($this->uri->segment(4), ENT_QUOTES),
            'CreatedBy' => $EmployeeNumber,
            'DateCreated' => $DateNow
          );
          $auditTable = 'borrower_has_notifications';
          $this->maintenance_model->insertFunction($insertAuditBorrower, $auditTable);
        // notification
          $this->session->set_flashdata('alertTitle','Success!'); 
          $this->session->set_flashdata('alertText','Reference successfully added!'); 
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
        // admin audits
          $auditDetail = 'Added '.htmlentities($_POST['Name'], ENT_QUOTES).' as co-maker.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $EmployeeNumber,
            'DateCreated' => $DateNow
          );
          $this->maintenance_model->insertAdminLog($insertData);

        // borrower audits
          $auditBorrower = $auditDetail;
          $insertAuditBorrower = array(
            'Description' => $auditBorrower,
            'BorrowerId' => htmlentities($this->uri->segment(4), ENT_QUOTES),
            'CreatedBy' => $EmployeeNumber,
            'DateCreated' => $DateNow
          );
          $auditTable = 'borrower_has_notifications';
          $this->maintenance_model->insertFunction($insertAuditBorrower, $auditTable);
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
          $rowNumber = $this->db->query("SELECT LPAD(".$NewId['BorrowerIdentificationId'].", 6, 0) as number")->row_array();

          $auditMainAndManagerLog = 'Added supporting document #SD-' . $rowNumber['number'].' for borrower #'. $borrowerDetail['BorrowerNumber'];
          $auditBorrowerLog = 'Added new supporting document #SD-' . $rowNumber['number'];

          $insertAuditBorrower = array(
            'Description'       => $auditBorrowerLog
            , 'BorrowerId'      => $this->uri->segment(4)
            , 'CreatedBy'       => $EmployeeNumber
          );
          $insertMainLog = array(
            'Description'       => $auditMainAndManagerLog
            , 'CreatedBy'       => $EmployeeNumber
          );
          $insertManagerAudit = array(
            'Description'         => $auditMainAndManagerLog
            , 'ManagerBranchId'   => $employeeDetail['ManagerBranchId']
            , 'CreatedBy'         => $EmployeeNumber
          );

          $auditTable2 = 'R_Logs';
          $this->maintenance_model->insertFunction($insertMainLog, $auditTable2);
          $auditTable3 = 'borrower_has_notifications';
          $this->maintenance_model->insertFunction($insertAuditBorrower, $auditTable3);
          $auditTable4 = 'manager_has_notifications';
          $this->maintenance_model->insertFunction($insertManagerAudit, $auditTable4);
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
          , 'DateOfBirth'                 => htmlentities($newformat, ENT_QUOTES)
        );
        $query = $this->borrower_model->countBorrower($data);
        if($query == 0) // not existing
        {
          // first name
            if($borrowerDetail['FirstName'] != htmlentities($_POST['FirstName'], ENT_QUOTES))
            {
              // insert into borrower notification
                $auditBorrower = 'Updated first name from '.$borrowerDetail['FirstName'].' to '.htmlentities($_POST['FirstName'], ENT_QUOTES);
                $insertEmployeeAudit = array(
                  'Description' => $auditBorrower,
                  'CreatedBy'   => $EmployeeNumber,
                  'BorrowerId'  => $this->uri->segment(4)
                );
                $auditEmployeeTable = 'borrower_has_notifications';
                $this->maintenance_model->insertFunction($insertEmployeeAudit, $auditEmployeeTable);
              // insert into manager and employee notification
                $auditStaff = 'Updated first name from '.$borrowerDetail['FirstName'].' to '.htmlentities($_POST['FirstName'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'];
                // insert into
                  $insertManagerAudit = array(
                    'Description'       => $auditStaff,
                    'CreatedBy'         => $EmployeeNumber,
                    'ManagerBranchId'   => $employeeDetail['ManagerBranchId']
                  );
                  $auditManagerTable = 'manager_has_notifications';
                  $this->maintenance_model->insertFunction($insertManagerAudit, $auditManagerTable);

                  $insertAudit = array(
                    'Description'       => $auditStaff,
                    'CreatedBy'         => $EmployeeNumber,
                    'EmployeeNumber'    => $EmployeeNumber
                  );
                  $auditTable = 'employee_has_notifications';
                  $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
              // insert into borrower notification
                $auditBorrower = 'Updated middle name from '.$borrowerDetail['MiddleName'].' to '.htmlentities($_POST['MiddleName'], ENT_QUOTES);
                $insertEmployeeAudit = array(
                  'Description' => $auditBorrower,
                  'CreatedBy'   => $EmployeeNumber,
                  'BorrowerId'  => $this->uri->segment(4)
                );
                $auditEmployeeTable = 'borrower_has_notifications';
                $this->maintenance_model->insertFunction($insertEmployeeAudit, $auditEmployeeTable);
              // insert into manager and employee notification
                $auditStaff = 'Updated middle name from '.$borrowerDetail['MiddleName'].' to '.htmlentities($_POST['MiddleName'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'];
                // insert into
                  $insertManagerAudit = array(
                    'Description'       => $auditStaff,
                    'CreatedBy'         => $EmployeeNumber,
                    'ManagerBranchId'   => $employeeDetail['ManagerBranchId']
                  );
                  $auditManagerTable = 'manager_has_notifications';
                  $this->maintenance_model->insertFunction($insertManagerAudit, $auditManagerTable);

                  $insertAudit = array(
                    'Description'       => $auditStaff,
                    'CreatedBy'         => $EmployeeNumber,
                    'EmployeeNumber'    => $EmployeeNumber
                  );
                  $auditTable = 'employee_has_notifications';
                  $this->maintenance_model->insertFunction($insertAudit, $auditTable);
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
              $auditBorrower = 'Updated last name from '.$borrowerDetail['LastName'].' to '.htmlentities($_POST['LastName'], ENT_QUOTES);
              $auditStaff = 'Updated last name from '.$borrowerDetail['LastName'].' to '.htmlentities($_POST['LastName'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'];
              $this->auditBorrower($auditBorrower, $auditStaff, $this->uri->segment(4));
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
              $auditBorrower = 'Updated extension name from '.$borrowerDetail['ExtName'].' to '.htmlentities($_POST['ExtName'], ENT_QUOTES);
              $auditStaff = 'Updated extension name from '.$borrowerDetail['ExtName'].' to '.htmlentities($_POST['ExtName'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'];
              $this->auditBorrower($auditBorrower, $auditStaff, $this->uri->segment(4));
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
              $auditBorrower = 'Updated birth date from '.$oldData.' to '.$newData.'.';
              $auditStaff = 'Updated birth date from '.$oldData.' to '.$newData.' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
              $this->auditBorrower($auditBorrower, $auditStaff, $this->uri->segment(4));

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
              // insert borrower city address
                $insertCityAddress = array(
                  'AddressId'                         => $borrowerCityAddress['AddressId']
                  , 'BorrowerId'                      => $this->uri->segment(4)
                  , 'SpouseId'                        => $SpouseId['SpouseId']
                  , 'CreatedBy'                       => $EmployeeNumber
                  , 'UpdatedBy'                       => $EmployeeNumber
                );
                $insertCityAddressTable = 'borroweraddresshistory';
                $this->maintenance_model->insertFunction($insertCityAddress, $insertCityAddressTable);
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
          // audit
            $auditquery = $this->borrower_model->getSpouseDetails($SpouseId['SpouseId']);
            $auditBorrower = 'Added '.$auditquery['Name'].' in spouse list.';
            $this->auditBorrower($auditBorrower, $auditBorrower, $this->uri->segment(4));
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
          $auditBorrower = 'Added employment record #ER-'.$transactionNumber.' in employment list.';
          $staffDesc = 'Added employment record #ER-'.$transactionNumber.' in employment record of borrower #'.$borrowerDetail['BorrowerNumber'].'.';
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
        , 'SchoolName'                   => htmlentities($_POST['SchoolName'], ENT_QUOTES)
        , 'YearGraduated'                   => htmlentities($_POST['EducationYear'], ENT_QUOTES)
        , 'BorrowerId'                => htmlentities($this->uri->segment(4), ENT_QUOTES)
      );
      $query = $this->borrower_model->countEducation($data);
      if($query == 0) // not existing
      {
        // insert details
          $insertBorrower = array(
            'Level'                        => htmlentities($_POST['EducationLevel'], ENT_QUOTES)
            , 'SchoolName'                   => htmlentities($_POST['SchoolName'], ENT_QUOTES)
            , 'YearGraduated'                   => htmlentities($_POST['EducationYear'], ENT_QUOTES)
            , 'BorrowerId'                => htmlentities($this->uri->segment(4), ENT_QUOTES)
            , 'CreatedBy'                 => $EmployeeNumber
            , 'UpdatedBy'                 => $EmployeeNumber
          );
          $insertBorrowerTable = 'borrower_has_Education';
          $this->maintenance_model->insertFunction($insertBorrower, $insertBorrowerTable);
        // admin audits
          $auditDetail = 'Added '.htmlentities($_POST['SchoolName'], ENT_QUOTES).' in Education Background list.';
          $insertData = array(
            'Description' => $auditDetail,
            'CreatedBy' => $EmployeeNumber,
            'DateCreated' => $DateNow
          );
          $this->maintenance_model->insertAdminLog($insertData);

        // borrower audits
          $auditBorrower = $auditDetail;
          $insertAuditBorrower = array(
            'Description' => $auditBorrower,
            'BorrowerId' => htmlentities($this->uri->segment(4), ENT_QUOTES),
            'CreatedBy' => $EmployeeNumber,
            'DateCreated' => $DateNow
          );
          $auditTable = 'borrower_has_notifications';
          $this->maintenance_model->insertFunction($insertAuditBorrower, $auditTable);
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
        // insert into borrower notification
          $auditBorrower = 'Updated salutation from '.$oldDetail['Name'].' to '.$newDetail['Name'].'.';
          $auditStaff = 'Updated salutation from '.$oldDetail['Name'].' to '.$newDetail['Name'].' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $this->auditBorrower($auditBorrower, $auditStaff, $this->uri->segment(4));
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
        // insert into borrower notification
          $auditBorrower = 'Updated gender from '.$oldDetail['Name'].' to '.$newDetail['Name'].'.';
          $auditStaff = 'Updated gender from '.$oldDetail['Name'].' to '.$newDetail['Name'].' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $this->auditBorrower($auditBorrower, $auditStaff, $this->uri->segment(4));
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
        // insert into borrower notification
          $auditBorrower = 'Updated nationality from '.$oldDetail['Name'].' to '.$newDetail['Name'].'.';
          $auditStaff = 'Updated nationality from '.$oldDetail['Name'].' to '.$newDetail['Name'].' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $this->auditBorrower($auditBorrower, $auditStaff, $this->uri->segment(4));
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
        // insert into borrower notification
          $auditBorrower = 'Updated civil status from '.$oldDetail['Name'].' to '.$newDetail['Name'].'.';
          $auditStaff = 'Updated civil status from '.$oldDetail['Name'].' to '.$newDetail['Name'].' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $this->auditBorrower($auditBorrower, $auditStaff, $this->uri->segment(4));
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
        // insert into borrower notification
          $auditBorrower = 'Updated status from '.$oldDetail['Name'].' to '.$newDetail['Name'].'.';
          $auditStaff = 'Updated status from '.$oldDetail['Name'].' to '.$newDetail['Name'].' of borrower #'. $borrowerDetail['BorrowerNumber'].'.';
          $this->auditBorrower($auditBorrower, $auditStaff, $this->uri->segment(4));
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
        // insert into borrower notification
          $auditBorrower = 'Updated number of dependents from '.$borrowerDetail['Dependents'].' to '.htmlentities($_POST['NoDependents'], ENT_QUOTES);
          $auditStaff = 'Updated number of dependents from '.$borrowerDetail['Dependents'].' to '.htmlentities($_POST['NoDependents'], ENT_QUOTES).' of borrower #'. $borrowerDetail['BorrowerNumber'];
          $this->auditBorrower($auditBorrower, $auditStaff, $this->uri->segment(4));
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

  function updateEmail()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $input = array( 
      'Id' => htmlentities($this->input->post('Id'), ENT_QUOTES)
      , 'updateType' => htmlentities($this->input->post('updateType'), ENT_QUOTES)
      , 'tableType' => htmlentities($this->input->post('tableType'), ENT_QUOTES)
    );

    $query = $this->borrower_model->updateEmail($input);
  }

  function IDCategory()
  {
    echo $this->maintenance_model->IDCategory();
  }

  function AddContact()
  {
    $EmployeeNumber = $this->session->userdata('EmployeeNumber');
    $DateNow = date("Y-m-d H:i:s");
    if($_POST['FormType'] == 1) // add contact
    {
      if($_POST['ContactType'] == 'Mobile') //Mobile Number
      {
        // pass mo yung data to be used
        $data = array(
          'Type'                 => $_POST['']
          , 'Number'              => $_POST['ContactNumber']
          , 'BorrowerId'              => $_POST['']
        );
        $result = $this->borrower_model->countBorrower($data);
        if($result == 0)
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
        else
        {
          // notification
            $this->session->set_flashdata('alertTitle','Warning!'); 
            $this->session->set_flashdata('alertText','Contact number already existing!'); 
            $this->session->set_flashdata('alertType','warning'); 
            redirect('home/borrowers');
        }
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


  function getBorrowerDetails()
  {
    $output = $this->borrower_model->getBorrowerDetails($this->input->post('Id'));
    $this->output->set_output(print(json_encode($output)));
    exit();
  }

  function getSpouseDetails()
  {
    $output = $this->borrower_model->getSpouseDetails($this->input->post('Id'));
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
    return 'hellsssaaa';
  }

}
