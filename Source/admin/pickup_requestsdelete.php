<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "pickup_requestsinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$pickup_requests_delete = NULL; // Initialize page object first

class cpickup_requests_delete extends cpickup_requests {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'pickup_requests';

	// Page object name
	var $PageObjName = 'pickup_requests_delete';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME]);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (pickup_requests)
		if (!isset($GLOBALS["pickup_requests"]) || get_class($GLOBALS["pickup_requests"]) == "cpickup_requests") {
			$GLOBALS["pickup_requests"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["pickup_requests"];
		}

		// Table object (administrator)
		if (!isset($GLOBALS['administrator'])) $GLOBALS['administrator'] = new cadministrator();

		// User table object (administrator)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cadministrator();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pickup_requests', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn, $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $pickup_requests;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($pickup_requests);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("pickup_requestslist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in pickup_requests class, pickup_requestsinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "D"; // Delete record directly
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // Delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Load List page SQL
		$sSql = $this->SelectSQL();

		// Load recordset
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
		$conn->raiseErrorFn = '';

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->id->setDbValue($rs->fields('id'));
		$this->account_id->setDbValue($rs->fields('account_id'));
		if (array_key_exists('EV__account_id', $rs->fields)) {
			$this->account_id->VirtualValue = $rs->fields('EV__account_id'); // Set up virtual field value
		} else {
			$this->account_id->VirtualValue = ""; // Clear value
		}
		$this->from_time->setDbValue($rs->fields('from_time'));
		$this->to_time->setDbValue($rs->fields('to_time'));
		$this->contact_name->setDbValue($rs->fields('contact_name'));
		$this->account_type->setDbValue($rs->fields('account_type'));
		$this->account_number->setDbValue($rs->fields('account_number'));
		$this->company->setDbValue($rs->fields('company'));
		$this->contact_phone->setDbValue($rs->fields('contact_phone'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->content->setDbValue($rs->fields('content'));
		$this->weight->setDbValue($rs->fields('weight'));
		$this->source_pickup_address->setDbValue($rs->fields('source_pickup_address'));
		$this->source_pickup_city->setDbValue($rs->fields('source_pickup_city'));
		$this->source_governorate->setDbValue($rs->fields('source_governorate'));
		$this->destination_pickup_address->setDbValue($rs->fields('destination_pickup_address'));
		$this->destination_pickup_city->setDbValue($rs->fields('destination_pickup_city'));
		$this->destination_governorate->setDbValue($rs->fields('destination_governorate'));
		$this->no_of_pieces->setDbValue($rs->fields('no_of_pieces'));
		$this->pickup_date->setDbValue($rs->fields('pickup_date'));
		$this->product_type->setDbValue($rs->fields('product_type'));
		$this->status->setDbValue($rs->fields('status'));
		$this->created->setDbValue($rs->fields('created'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->account_id->DbValue = $row['account_id'];
		$this->from_time->DbValue = $row['from_time'];
		$this->to_time->DbValue = $row['to_time'];
		$this->contact_name->DbValue = $row['contact_name'];
		$this->account_type->DbValue = $row['account_type'];
		$this->account_number->DbValue = $row['account_number'];
		$this->company->DbValue = $row['company'];
		$this->contact_phone->DbValue = $row['contact_phone'];
		$this->_email->DbValue = $row['email'];
		$this->content->DbValue = $row['content'];
		$this->weight->DbValue = $row['weight'];
		$this->source_pickup_address->DbValue = $row['source_pickup_address'];
		$this->source_pickup_city->DbValue = $row['source_pickup_city'];
		$this->source_governorate->DbValue = $row['source_governorate'];
		$this->destination_pickup_address->DbValue = $row['destination_pickup_address'];
		$this->destination_pickup_city->DbValue = $row['destination_pickup_city'];
		$this->destination_governorate->DbValue = $row['destination_governorate'];
		$this->no_of_pieces->DbValue = $row['no_of_pieces'];
		$this->pickup_date->DbValue = $row['pickup_date'];
		$this->product_type->DbValue = $row['product_type'];
		$this->status->DbValue = $row['status'];
		$this->created->DbValue = $row['created'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// account_id
		// from_time
		// to_time
		// contact_name
		// account_type
		// account_number
		// company
		// contact_phone
		// email
		// content
		// weight
		// source_pickup_address
		// source_pickup_city
		// source_governorate
		// destination_pickup_address
		// destination_pickup_city
		// destination_governorate
		// no_of_pieces
		// pickup_date
		// product_type
		// status
		// created

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// account_id
			if ($this->account_id->VirtualValue <> "") {
				$this->account_id->ViewValue = $this->account_id->VirtualValue;
			} else {
			if (strval($this->account_id->CurrentValue) <> "") {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->account_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT DISTINCT `id`, `first_name` AS `DispFld`, `last_name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `accounts`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->account_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->account_id->ViewValue = $rswrk->fields('DispFld');
					$this->account_id->ViewValue .= ew_ValueSeparator(1,$this->account_id) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->account_id->ViewValue = $this->account_id->CurrentValue;
				}
			} else {
				$this->account_id->ViewValue = NULL;
			}
			}
			$this->account_id->ViewCustomAttributes = "";

			// from_time
			$this->from_time->ViewValue = $this->from_time->CurrentValue;
			$this->from_time->ViewCustomAttributes = "";

			// to_time
			$this->to_time->ViewValue = $this->to_time->CurrentValue;
			$this->to_time->ViewCustomAttributes = "";

			// contact_name
			$this->contact_name->ViewValue = $this->contact_name->CurrentValue;
			$this->contact_name->ViewCustomAttributes = "";

			// account_type
			if (strval($this->account_type->CurrentValue) <> "") {
				$sFilterWrk = "`account_type`" . ew_SearchString("=", $this->account_type->CurrentValue, EW_DATATYPE_STRING);
			$sSqlWrk = "SELECT DISTINCT `account_type`, `account_type` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `pickup_requests`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->account_type, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->account_type->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->account_type->ViewValue = $this->account_type->CurrentValue;
				}
			} else {
				$this->account_type->ViewValue = NULL;
			}
			$this->account_type->ViewCustomAttributes = "";

			// account_number
			$this->account_number->ViewValue = $this->account_number->CurrentValue;
			$this->account_number->ViewCustomAttributes = "";

			// company
			$this->company->ViewValue = $this->company->CurrentValue;
			$this->company->ViewCustomAttributes = "";

			// contact_phone
			$this->contact_phone->ViewValue = $this->contact_phone->CurrentValue;
			$this->contact_phone->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// content
			$this->content->ViewValue = $this->content->CurrentValue;
			$this->content->ViewCustomAttributes = "";

			// weight
			$this->weight->ViewValue = $this->weight->CurrentValue;
			$this->weight->ViewCustomAttributes = "";

			// source_pickup_address
			$this->source_pickup_address->ViewValue = $this->source_pickup_address->CurrentValue;
			$this->source_pickup_address->ViewCustomAttributes = "";

			// source_pickup_city
			$this->source_pickup_city->ViewValue = $this->source_pickup_city->CurrentValue;
			$this->source_pickup_city->ViewCustomAttributes = "";

			// source_governorate
			$this->source_governorate->ViewValue = $this->source_governorate->CurrentValue;
			$this->source_governorate->ViewCustomAttributes = "";

			// destination_pickup_city
			$this->destination_pickup_city->ViewValue = $this->destination_pickup_city->CurrentValue;
			$this->destination_pickup_city->ViewCustomAttributes = "";

			// destination_governorate
			$this->destination_governorate->ViewValue = $this->destination_governorate->CurrentValue;
			$this->destination_governorate->ViewCustomAttributes = "";

			// no_of_pieces
			$this->no_of_pieces->ViewValue = $this->no_of_pieces->CurrentValue;
			$this->no_of_pieces->ViewCustomAttributes = "";

			// pickup_date
			$this->pickup_date->ViewValue = $this->pickup_date->CurrentValue;
			$this->pickup_date->ViewValue = ew_FormatDateTime($this->pickup_date->ViewValue, 7);
			$this->pickup_date->ViewCustomAttributes = "";

			// product_type
			$this->product_type->ViewValue = $this->product_type->CurrentValue;
			$this->product_type->ViewCustomAttributes = "";

			// status
			if (strval($this->status->CurrentValue) <> "") {
				switch ($this->status->CurrentValue) {
					case $this->status->FldTagValue(1):
						$this->status->ViewValue = $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : $this->status->CurrentValue;
						break;
					case $this->status->FldTagValue(2):
						$this->status->ViewValue = $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : $this->status->CurrentValue;
						break;
					case $this->status->FldTagValue(3):
						$this->status->ViewValue = $this->status->FldTagCaption(3) <> "" ? $this->status->FldTagCaption(3) : $this->status->CurrentValue;
						break;
					default:
						$this->status->ViewValue = $this->status->CurrentValue;
				}
			} else {
				$this->status->ViewValue = NULL;
			}
			$this->status->ViewCustomAttributes = "";

			// created
			$this->created->ViewValue = $this->created->CurrentValue;
			$this->created->ViewValue = ew_FormatDateTime($this->created->ViewValue, 7);
			$this->created->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// account_id
			$this->account_id->LinkCustomAttributes = "";
			$this->account_id->HrefValue = "";
			$this->account_id->TooltipValue = "";

			// from_time
			$this->from_time->LinkCustomAttributes = "";
			$this->from_time->HrefValue = "";
			$this->from_time->TooltipValue = "";

			// to_time
			$this->to_time->LinkCustomAttributes = "";
			$this->to_time->HrefValue = "";
			$this->to_time->TooltipValue = "";

			// contact_name
			$this->contact_name->LinkCustomAttributes = "";
			$this->contact_name->HrefValue = "";
			$this->contact_name->TooltipValue = "";

			// account_type
			$this->account_type->LinkCustomAttributes = "";
			$this->account_type->HrefValue = "";
			$this->account_type->TooltipValue = "";

			// account_number
			$this->account_number->LinkCustomAttributes = "";
			$this->account_number->HrefValue = "";
			$this->account_number->TooltipValue = "";

			// contact_phone
			$this->contact_phone->LinkCustomAttributes = "";
			$this->contact_phone->HrefValue = "";
			$this->contact_phone->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// pickup_date
			$this->pickup_date->LinkCustomAttributes = "";
			$this->pickup_date->HrefValue = "";
			$this->pickup_date->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// created
			$this->created->LinkCustomAttributes = "";
			$this->created->HrefValue = "";
			$this->created->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;

		//} else {
		//	$this->LoadRowValues($rs); // Load row values

		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
				$this->LoadDbValues($row);
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "pickup_requestslist.php", "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, ew_CurrentUrl());
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pickup_requests_delete)) $pickup_requests_delete = new cpickup_requests_delete();

// Page init
$pickup_requests_delete->Page_Init();

// Page main
$pickup_requests_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pickup_requests_delete->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<script type="text/javascript">

// Page object
var pickup_requests_delete = new ew_Page("pickup_requests_delete");
pickup_requests_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = pickup_requests_delete.PageID; // For backward compatibility

// Form object
var fpickup_requestsdelete = new ew_Form("fpickup_requestsdelete");

// Form_CustomValidate event
fpickup_requestsdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpickup_requestsdelete.ValidateRequired = true;
<?php } else { ?>
fpickup_requestsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpickup_requestsdelete.Lists["x_account_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_first_name","x_last_name","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpickup_requestsdelete.Lists["x_account_type"] = {"LinkField":"x_account_type","Ajax":null,"AutoFill":false,"DisplayFields":["x_account_type","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($pickup_requests_delete->Recordset = $pickup_requests_delete->LoadRecordset())
	$pickup_requests_deleteTotalRecs = $pickup_requests_delete->Recordset->RecordCount(); // Get record count
if ($pickup_requests_deleteTotalRecs <= 0) { // No record found, exit
	if ($pickup_requests_delete->Recordset)
		$pickup_requests_delete->Recordset->Close();
	$pickup_requests_delete->Page_Terminate("pickup_requestslist.php"); // Return to list
}
?>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $pickup_requests_delete->ShowPageHeader(); ?>
<?php
$pickup_requests_delete->ShowMessage();
?>
<form name="fpickup_requestsdelete" id="fpickup_requestsdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pickup_requests_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pickup_requests_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pickup_requests">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($pickup_requests_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table class="table ewTable">
<?php echo $pickup_requests->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
<?php if ($pickup_requests->id->Visible) { // id ?>
		<th><span id="elh_pickup_requests_id" class="pickup_requests_id"><?php echo $pickup_requests->id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pickup_requests->account_id->Visible) { // account_id ?>
		<th><span id="elh_pickup_requests_account_id" class="pickup_requests_account_id"><?php echo $pickup_requests->account_id->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pickup_requests->from_time->Visible) { // from_time ?>
		<th><span id="elh_pickup_requests_from_time" class="pickup_requests_from_time"><?php echo $pickup_requests->from_time->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pickup_requests->to_time->Visible) { // to_time ?>
		<th><span id="elh_pickup_requests_to_time" class="pickup_requests_to_time"><?php echo $pickup_requests->to_time->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pickup_requests->contact_name->Visible) { // contact_name ?>
		<th><span id="elh_pickup_requests_contact_name" class="pickup_requests_contact_name"><?php echo $pickup_requests->contact_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pickup_requests->account_type->Visible) { // account_type ?>
		<th><span id="elh_pickup_requests_account_type" class="pickup_requests_account_type"><?php echo $pickup_requests->account_type->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pickup_requests->account_number->Visible) { // account_number ?>
		<th><span id="elh_pickup_requests_account_number" class="pickup_requests_account_number"><?php echo $pickup_requests->account_number->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pickup_requests->contact_phone->Visible) { // contact_phone ?>
		<th><span id="elh_pickup_requests_contact_phone" class="pickup_requests_contact_phone"><?php echo $pickup_requests->contact_phone->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pickup_requests->_email->Visible) { // email ?>
		<th><span id="elh_pickup_requests__email" class="pickup_requests__email"><?php echo $pickup_requests->_email->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pickup_requests->pickup_date->Visible) { // pickup_date ?>
		<th><span id="elh_pickup_requests_pickup_date" class="pickup_requests_pickup_date"><?php echo $pickup_requests->pickup_date->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pickup_requests->status->Visible) { // status ?>
		<th><span id="elh_pickup_requests_status" class="pickup_requests_status"><?php echo $pickup_requests->status->FldCaption() ?></span></th>
<?php } ?>
<?php if ($pickup_requests->created->Visible) { // created ?>
		<th><span id="elh_pickup_requests_created" class="pickup_requests_created"><?php echo $pickup_requests->created->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$pickup_requests_delete->RecCnt = 0;
$i = 0;
while (!$pickup_requests_delete->Recordset->EOF) {
	$pickup_requests_delete->RecCnt++;
	$pickup_requests_delete->RowCnt++;

	// Set row properties
	$pickup_requests->ResetAttrs();
	$pickup_requests->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$pickup_requests_delete->LoadRowValues($pickup_requests_delete->Recordset);

	// Render row
	$pickup_requests_delete->RenderRow();
?>
	<tr<?php echo $pickup_requests->RowAttributes() ?>>
<?php if ($pickup_requests->id->Visible) { // id ?>
		<td<?php echo $pickup_requests->id->CellAttributes() ?>>
<span id="el<?php echo $pickup_requests_delete->RowCnt ?>_pickup_requests_id" class="form-group pickup_requests_id">
<span<?php echo $pickup_requests->id->ViewAttributes() ?>>
<?php echo $pickup_requests->id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pickup_requests->account_id->Visible) { // account_id ?>
		<td<?php echo $pickup_requests->account_id->CellAttributes() ?>>
<span id="el<?php echo $pickup_requests_delete->RowCnt ?>_pickup_requests_account_id" class="form-group pickup_requests_account_id">
<span<?php echo $pickup_requests->account_id->ViewAttributes() ?>>
<?php echo $pickup_requests->account_id->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pickup_requests->from_time->Visible) { // from_time ?>
		<td<?php echo $pickup_requests->from_time->CellAttributes() ?>>
<span id="el<?php echo $pickup_requests_delete->RowCnt ?>_pickup_requests_from_time" class="form-group pickup_requests_from_time">
<span<?php echo $pickup_requests->from_time->ViewAttributes() ?>>
<?php echo $pickup_requests->from_time->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pickup_requests->to_time->Visible) { // to_time ?>
		<td<?php echo $pickup_requests->to_time->CellAttributes() ?>>
<span id="el<?php echo $pickup_requests_delete->RowCnt ?>_pickup_requests_to_time" class="form-group pickup_requests_to_time">
<span<?php echo $pickup_requests->to_time->ViewAttributes() ?>>
<?php echo $pickup_requests->to_time->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pickup_requests->contact_name->Visible) { // contact_name ?>
		<td<?php echo $pickup_requests->contact_name->CellAttributes() ?>>
<span id="el<?php echo $pickup_requests_delete->RowCnt ?>_pickup_requests_contact_name" class="form-group pickup_requests_contact_name">
<span<?php echo $pickup_requests->contact_name->ViewAttributes() ?>>
<?php echo $pickup_requests->contact_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pickup_requests->account_type->Visible) { // account_type ?>
		<td<?php echo $pickup_requests->account_type->CellAttributes() ?>>
<span id="el<?php echo $pickup_requests_delete->RowCnt ?>_pickup_requests_account_type" class="form-group pickup_requests_account_type">
<span<?php echo $pickup_requests->account_type->ViewAttributes() ?>>
<?php echo $pickup_requests->account_type->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pickup_requests->account_number->Visible) { // account_number ?>
		<td<?php echo $pickup_requests->account_number->CellAttributes() ?>>
<span id="el<?php echo $pickup_requests_delete->RowCnt ?>_pickup_requests_account_number" class="form-group pickup_requests_account_number">
<span<?php echo $pickup_requests->account_number->ViewAttributes() ?>>
<?php echo $pickup_requests->account_number->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pickup_requests->contact_phone->Visible) { // contact_phone ?>
		<td<?php echo $pickup_requests->contact_phone->CellAttributes() ?>>
<span id="el<?php echo $pickup_requests_delete->RowCnt ?>_pickup_requests_contact_phone" class="form-group pickup_requests_contact_phone">
<span<?php echo $pickup_requests->contact_phone->ViewAttributes() ?>>
<?php echo $pickup_requests->contact_phone->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pickup_requests->_email->Visible) { // email ?>
		<td<?php echo $pickup_requests->_email->CellAttributes() ?>>
<span id="el<?php echo $pickup_requests_delete->RowCnt ?>_pickup_requests__email" class="form-group pickup_requests__email">
<span<?php echo $pickup_requests->_email->ViewAttributes() ?>>
<?php echo $pickup_requests->_email->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pickup_requests->pickup_date->Visible) { // pickup_date ?>
		<td<?php echo $pickup_requests->pickup_date->CellAttributes() ?>>
<span id="el<?php echo $pickup_requests_delete->RowCnt ?>_pickup_requests_pickup_date" class="form-group pickup_requests_pickup_date">
<span<?php echo $pickup_requests->pickup_date->ViewAttributes() ?>>
<?php echo $pickup_requests->pickup_date->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pickup_requests->status->Visible) { // status ?>
		<td<?php echo $pickup_requests->status->CellAttributes() ?>>
<span id="el<?php echo $pickup_requests_delete->RowCnt ?>_pickup_requests_status" class="form-group pickup_requests_status">
<span<?php echo $pickup_requests->status->ViewAttributes() ?>>
<?php echo $pickup_requests->status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($pickup_requests->created->Visible) { // created ?>
		<td<?php echo $pickup_requests->created->CellAttributes() ?>>
<span id="el<?php echo $pickup_requests_delete->RowCnt ?>_pickup_requests_created" class="form-group pickup_requests_created">
<span<?php echo $pickup_requests->created->ViewAttributes() ?>>
<?php echo $pickup_requests->created->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$pickup_requests_delete->Recordset->MoveNext();
}
$pickup_requests_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div class="btn-group ewButtonGroup">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fpickup_requestsdelete.Init();
</script>
<?php
$pickup_requests_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$pickup_requests_delete->Page_Terminate();
?>
