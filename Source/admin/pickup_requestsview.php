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

$pickup_requests_view = NULL; // Initialize page object first

class cpickup_requests_view extends cpickup_requests {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'pickup_requests';

	// Page object name
	var $PageObjName = 'pickup_requests_view';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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
		$KeyUrl = "";
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&amp;id=" . urlencode($this->RecKey["id"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (administrator)
		if (!isset($GLOBALS['administrator'])) $GLOBALS['administrator'] = new cadministrator();

		// User table object (administrator)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cadministrator();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pickup_requests', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
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

		// Get export parameters
		$custom = "";
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
			$custom = @$_GET["custom"];
		} elseif (@$_POST["export"] <> "") {
			$this->Export = $_POST["export"];
			$custom = @$_POST["custom"];
		} elseif (ew_IsHttpPost()) {
			if (@$_POST["exporttype"] <> "")
				$this->Export = $_POST["exporttype"];
			$custom = @$_POST["custom"];
		} else {
			$this->setExportReturnUrl(ew_CurrentUrl());
		}
		$gsExportFile = $this->TableVar; // Get export file, used in header
		if (@$_GET["id"] <> "") {
			if ($gsExportFile <> "") $gsExportFile .= "_";
			$gsExportFile .= ew_StripSlashes($_GET["id"]);
		}

		// Get custom export parameters
		if ($this->Export <> "" && $custom <> "") {
			$this->CustomExport = $this->Export;
			$this->Export = "print";
		}
		$gsCustomExport = $this->CustomExport;
		$gsExport = $this->Export; // Get export parameter, used in header

		// Update Export URLs
		if (defined("EW_USE_PHPEXCEL"))
			$this->ExportExcelCustom = FALSE;
		if ($this->ExportExcelCustom)
			$this->ExportExcelUrl .= "&amp;custom=1";
		if (defined("EW_USE_PHPWORD"))
			$this->ExportWordCustom = FALSE;
		if ($this->ExportWordCustom)
			$this->ExportWordUrl .= "&amp;custom=1";
		if ($this->ExportPdfCustom)
			$this->ExportPdfUrl .= "&amp;custom=1";
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Setup export options
		$this->SetupExportOptions();
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
	var $ExportOptions; // Export options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 1;
	var $DbMasterFilter;
	var $DbDetailFilter;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;

		// Set up Breadcrumb
		if ($this->Export == "")
			$this->SetupBreadcrumb();
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					$this->StartRec = 1; // Initialize start position
					if ($this->Recordset = $this->LoadRecordset()) // Load records
						$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
					if ($this->TotalRecs <= 0) { // No record found
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$this->Page_Terminate("pickup_requestslist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->id->CurrentValue) == strval($this->Recordset->fields('id'))) {
								$this->setStartRecordNumber($this->StartRec); // Save record position
								$bMatchRecord = TRUE;
								break;
							} else {
								$this->StartRec++;
								$this->Recordset->MoveNext();
							}
						}
					}
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "pickup_requestslist.php"; // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}

			// Export data only
			if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
				$this->ExportData();
				$this->Page_Terminate(); // Terminate response
				exit();
			}
		} else {
			$sReturnUrl = "pickup_requestslist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = &$options["action"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAction ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageAddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("ViewPageAddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());

		// Edit
		$item = &$option->Add("edit");
		$item->Body = "<a class=\"ewAction ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageEditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("ViewPageEditLink") . "</a>";
		$item->Visible = ($this->EditUrl <> "" && $Security->CanEdit());

		// Copy
		$item = &$option->Add("copy");
		$item->Body = "<a class=\"ewAction ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageCopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("ViewPageCopyLink") . "</a>";
		$item->Visible = ($this->CopyUrl <> "" && $Security->CanAdd());

		// Delete
		$item = &$option->Add("delete");
		$item->Body = "<a onclick=\"return ew_Confirm(ewLanguage.Phrase('DeleteConfirmMsg'));\" class=\"ewAction ewDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewPageDeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("ViewPageDeleteLink") . "</a>";
		$item->Visible = ($this->DeleteUrl <> "" && $Security->CanDelete());

		// Set up action default
		$option = &$options["action"];
		$option->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
		$option->UseImageAndText = TRUE;
		$option->UseDropDownButton = FALSE;
		$option->UseButtonGroup = TRUE;
		$item = &$option->Add($option->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();
		$this->SetupOtherOptions();

		// Convert decimal values if posted back
		if ($this->weight->FormValue == $this->weight->CurrentValue && is_numeric(ew_StrToFloat($this->weight->CurrentValue)))
			$this->weight->CurrentValue = ew_StrToFloat($this->weight->CurrentValue);

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

			// destination_pickup_address
			$this->destination_pickup_address->ViewValue = $this->destination_pickup_address->CurrentValue;
			$this->destination_pickup_address->ViewCustomAttributes = "";

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

			// company
			$this->company->LinkCustomAttributes = "";
			$this->company->HrefValue = "";
			$this->company->TooltipValue = "";

			// contact_phone
			$this->contact_phone->LinkCustomAttributes = "";
			$this->contact_phone->HrefValue = "";
			$this->contact_phone->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// content
			$this->content->LinkCustomAttributes = "";
			$this->content->HrefValue = "";
			$this->content->TooltipValue = "";

			// weight
			$this->weight->LinkCustomAttributes = "";
			$this->weight->HrefValue = "";
			$this->weight->TooltipValue = "";

			// source_pickup_address
			$this->source_pickup_address->LinkCustomAttributes = "";
			$this->source_pickup_address->HrefValue = "";
			$this->source_pickup_address->TooltipValue = "";

			// source_pickup_city
			$this->source_pickup_city->LinkCustomAttributes = "";
			$this->source_pickup_city->HrefValue = "";
			$this->source_pickup_city->TooltipValue = "";

			// source_governorate
			$this->source_governorate->LinkCustomAttributes = "";
			$this->source_governorate->HrefValue = "";
			$this->source_governorate->TooltipValue = "";

			// destination_pickup_address
			$this->destination_pickup_address->LinkCustomAttributes = "";
			$this->destination_pickup_address->HrefValue = "";
			$this->destination_pickup_address->TooltipValue = "";

			// destination_pickup_city
			$this->destination_pickup_city->LinkCustomAttributes = "";
			$this->destination_pickup_city->HrefValue = "";
			$this->destination_pickup_city->TooltipValue = "";

			// destination_governorate
			$this->destination_governorate->LinkCustomAttributes = "";
			$this->destination_governorate->HrefValue = "";
			$this->destination_governorate->TooltipValue = "";

			// no_of_pieces
			$this->no_of_pieces->LinkCustomAttributes = "";
			$this->no_of_pieces->HrefValue = "";
			$this->no_of_pieces->TooltipValue = "";

			// pickup_date
			$this->pickup_date->LinkCustomAttributes = "";
			$this->pickup_date->HrefValue = "";
			$this->pickup_date->TooltipValue = "";

			// product_type
			$this->product_type->LinkCustomAttributes = "";
			$this->product_type->HrefValue = "";
			$this->product_type->TooltipValue = "";

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

	// Set up export options
	function SetupExportOptions() {
		global $Language;

		// Printer friendly
		$item = &$this->ExportOptions->Add("print");
		$item->Body = "<a href=\"" . $this->ExportPrintUrl . "\" class=\"ewExportLink ewPrint\" title=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("PrinterFriendlyText")) . "\">" . $Language->Phrase("PrinterFriendly") . "</a>";
		$item->Visible = TRUE;

		// Export to Excel
		$item = &$this->ExportOptions->Add("excel");
		$item->Body = "<a href=\"" . $this->ExportExcelUrl . "\" class=\"ewExportLink ewExcel\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToExcelText")) . "\">" . $Language->Phrase("ExportToExcel") . "</a>";
		$item->Visible = TRUE;

		// Export to Word
		$item = &$this->ExportOptions->Add("word");
		$item->Body = "<a href=\"" . $this->ExportWordUrl . "\" class=\"ewExportLink ewWord\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToWordText")) . "\">" . $Language->Phrase("ExportToWord") . "</a>";
		$item->Visible = FALSE;

		// Export to Html
		$item = &$this->ExportOptions->Add("html");
		$item->Body = "<a href=\"" . $this->ExportHtmlUrl . "\" class=\"ewExportLink ewHtml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToHtmlText")) . "\">" . $Language->Phrase("ExportToHtml") . "</a>";
		$item->Visible = FALSE;

		// Export to Xml
		$item = &$this->ExportOptions->Add("xml");
		$item->Body = "<a href=\"" . $this->ExportXmlUrl . "\" class=\"ewExportLink ewXml\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToXmlText")) . "\">" . $Language->Phrase("ExportToXml") . "</a>";
		$item->Visible = FALSE;

		// Export to Csv
		$item = &$this->ExportOptions->Add("csv");
		$item->Body = "<a href=\"" . $this->ExportCsvUrl . "\" class=\"ewExportLink ewCsv\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToCsvText")) . "\">" . $Language->Phrase("ExportToCsv") . "</a>";
		$item->Visible = TRUE;

		// Export to Pdf
		$item = &$this->ExportOptions->Add("pdf");
		$item->Body = "<a href=\"" . $this->ExportPdfUrl . "\" class=\"ewExportLink ewPdf\" title=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\" data-caption=\"" . ew_HtmlEncode($Language->Phrase("ExportToPDFText")) . "\">" . $Language->Phrase("ExportToPDF") . "</a>";
		$item->Visible = TRUE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_pickup_requests\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_pickup_requests',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fpickup_requestsview,key:" . ew_ArrayToJsonAttr($this->RecKey) . ",sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
		$item->Visible = FALSE;

		// Drop down button for export
		$this->ExportOptions->UseButtonGroup = TRUE;
		$this->ExportOptions->UseImageAndText = TRUE;
		$this->ExportOptions->UseDropDownButton = FALSE;
		if ($this->ExportOptions->UseButtonGroup && ew_IsMobile())
			$this->ExportOptions->UseDropDownButton = TRUE;
		$this->ExportOptions->DropDownButtonPhrase = $Language->Phrase("ButtonExport");

		// Add group option item
		$item = &$this->ExportOptions->Add($this->ExportOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide options for export
		if ($this->Export <> "")
			$this->ExportOptions->HideAllOptions();
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = FALSE;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;
		$this->SetUpStartRec(); // Set up start record position

		// Set the last record to display
		if ($this->DisplayRecs <= 0) {
			$this->StopRec = $this->TotalRecs;
		} else {
			$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
		}
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "v");
		$Doc = &$this->ExportDoc;
		if ($bSelectLimit) {
			$this->StartRec = 1;
			$this->StopRec = $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs;
		} else {

			//$this->StartRec = $this->StartRec;
			//$this->StopRec = $this->StopRec;

		}

		// Call Page Exporting server event
		$this->ExportDoc->ExportCustom = !$this->Page_Exporting();
		$ParentTable = "";
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		$Doc->Text .= $sHeader;
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "view");
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		$Doc->Text .= $sFooter;

		// Close recordset
		$rs->Close();

		// Export header and footer
		$Doc->ExportHeaderAndFooter();

		// Call Page Exported server event
		$this->Page_Exported();

		// Clean output buffer
		if (!EW_DEBUG_ENABLED && ob_get_length())
			ob_end_clean();

		// Write debug message if enabled
		if (EW_DEBUG_ENABLED)
			echo ew_DebugMsg();

		// Output data
		$Doc->Export();
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("list", $this->TableVar, "pickup_requestslist.php", "", $this->TableVar, TRUE);
		$PageId = "view";
		$Breadcrumb->Add("view", $PageId, ew_CurrentUrl());
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

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

	    //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($pickup_requests_view)) $pickup_requests_view = new cpickup_requests_view();

// Page init
$pickup_requests_view->Page_Init();

// Page main
$pickup_requests_view->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pickup_requests_view->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<?php if ($pickup_requests->Export == "") { ?>
<script type="text/javascript">

// Page object
var pickup_requests_view = new ew_Page("pickup_requests_view");
pickup_requests_view.PageID = "view"; // Page ID
var EW_PAGE_ID = pickup_requests_view.PageID; // For backward compatibility

// Form object
var fpickup_requestsview = new ew_Form("fpickup_requestsview");

// Form_CustomValidate event
fpickup_requestsview.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpickup_requestsview.ValidateRequired = true;
<?php } else { ?>
fpickup_requestsview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpickup_requestsview.Lists["x_account_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_first_name","x_last_name","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpickup_requestsview.Lists["x_account_type"] = {"LinkField":"x_account_type","Ajax":null,"AutoFill":false,"DisplayFields":["x_account_type","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($pickup_requests->Export == "") { ?>
<div class="ewToolbar">
<?php if ($pickup_requests->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php $pickup_requests_view->ExportOptions->Render("body") ?>
<?php
	foreach ($pickup_requests_view->OtherOptions as &$option)
		$option->Render("body");
?>
<?php if ($pickup_requests->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $pickup_requests_view->ShowPageHeader(); ?>
<?php
$pickup_requests_view->ShowMessage();
?>
<?php if ($pickup_requests->Export == "") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pickup_requests_view->Pager)) $pickup_requests_view->Pager = new cNumericPager($pickup_requests_view->StartRec, $pickup_requests_view->DisplayRecs, $pickup_requests_view->TotalRecs, $pickup_requests_view->RecRange) ?>
<?php if ($pickup_requests_view->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($pickup_requests_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_view->PageUrl() ?>start=<?php echo $pickup_requests_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($pickup_requests_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_view->PageUrl() ?>start=<?php echo $pickup_requests_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($pickup_requests_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $pickup_requests_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($pickup_requests_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_view->PageUrl() ?>start=<?php echo $pickup_requests_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($pickup_requests_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_view->PageUrl() ?>start=<?php echo $pickup_requests_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
</form>
<?php } ?>
<form name="fpickup_requestsview" id="fpickup_requestsview" class="form-inline ewForm ewViewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pickup_requests_view->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pickup_requests_view->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pickup_requests">
<table class="table table-bordered table-striped ewViewTable">
<?php if ($pickup_requests->id->Visible) { // id ?>
	<tr id="r_id">
		<td><span id="elh_pickup_requests_id"><?php echo $pickup_requests->id->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->id->CellAttributes() ?>>
<span id="el_pickup_requests_id" class="form-group">
<span<?php echo $pickup_requests->id->ViewAttributes() ?>>
<?php echo $pickup_requests->id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->account_id->Visible) { // account_id ?>
	<tr id="r_account_id">
		<td><span id="elh_pickup_requests_account_id"><?php echo $pickup_requests->account_id->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->account_id->CellAttributes() ?>>
<span id="el_pickup_requests_account_id" class="form-group">
<span<?php echo $pickup_requests->account_id->ViewAttributes() ?>>
<?php echo $pickup_requests->account_id->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->from_time->Visible) { // from_time ?>
	<tr id="r_from_time">
		<td><span id="elh_pickup_requests_from_time"><?php echo $pickup_requests->from_time->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->from_time->CellAttributes() ?>>
<span id="el_pickup_requests_from_time" class="form-group">
<span<?php echo $pickup_requests->from_time->ViewAttributes() ?>>
<?php echo $pickup_requests->from_time->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->to_time->Visible) { // to_time ?>
	<tr id="r_to_time">
		<td><span id="elh_pickup_requests_to_time"><?php echo $pickup_requests->to_time->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->to_time->CellAttributes() ?>>
<span id="el_pickup_requests_to_time" class="form-group">
<span<?php echo $pickup_requests->to_time->ViewAttributes() ?>>
<?php echo $pickup_requests->to_time->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->contact_name->Visible) { // contact_name ?>
	<tr id="r_contact_name">
		<td><span id="elh_pickup_requests_contact_name"><?php echo $pickup_requests->contact_name->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->contact_name->CellAttributes() ?>>
<span id="el_pickup_requests_contact_name" class="form-group">
<span<?php echo $pickup_requests->contact_name->ViewAttributes() ?>>
<?php echo $pickup_requests->contact_name->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->account_type->Visible) { // account_type ?>
	<tr id="r_account_type">
		<td><span id="elh_pickup_requests_account_type"><?php echo $pickup_requests->account_type->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->account_type->CellAttributes() ?>>
<span id="el_pickup_requests_account_type" class="form-group">
<span<?php echo $pickup_requests->account_type->ViewAttributes() ?>>
<?php echo $pickup_requests->account_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->account_number->Visible) { // account_number ?>
	<tr id="r_account_number">
		<td><span id="elh_pickup_requests_account_number"><?php echo $pickup_requests->account_number->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->account_number->CellAttributes() ?>>
<span id="el_pickup_requests_account_number" class="form-group">
<span<?php echo $pickup_requests->account_number->ViewAttributes() ?>>
<?php echo $pickup_requests->account_number->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->company->Visible) { // company ?>
	<tr id="r_company">
		<td><span id="elh_pickup_requests_company"><?php echo $pickup_requests->company->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->company->CellAttributes() ?>>
<span id="el_pickup_requests_company" class="form-group">
<span<?php echo $pickup_requests->company->ViewAttributes() ?>>
<?php echo $pickup_requests->company->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->contact_phone->Visible) { // contact_phone ?>
	<tr id="r_contact_phone">
		<td><span id="elh_pickup_requests_contact_phone"><?php echo $pickup_requests->contact_phone->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->contact_phone->CellAttributes() ?>>
<span id="el_pickup_requests_contact_phone" class="form-group">
<span<?php echo $pickup_requests->contact_phone->ViewAttributes() ?>>
<?php echo $pickup_requests->contact_phone->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->_email->Visible) { // email ?>
	<tr id="r__email">
		<td><span id="elh_pickup_requests__email"><?php echo $pickup_requests->_email->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->_email->CellAttributes() ?>>
<span id="el_pickup_requests__email" class="form-group">
<span<?php echo $pickup_requests->_email->ViewAttributes() ?>>
<?php echo $pickup_requests->_email->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->content->Visible) { // content ?>
	<tr id="r_content">
		<td><span id="elh_pickup_requests_content"><?php echo $pickup_requests->content->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->content->CellAttributes() ?>>
<span id="el_pickup_requests_content" class="form-group">
<span<?php echo $pickup_requests->content->ViewAttributes() ?>>
<?php echo $pickup_requests->content->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->weight->Visible) { // weight ?>
	<tr id="r_weight">
		<td><span id="elh_pickup_requests_weight"><?php echo $pickup_requests->weight->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->weight->CellAttributes() ?>>
<span id="el_pickup_requests_weight" class="form-group">
<span<?php echo $pickup_requests->weight->ViewAttributes() ?>>
<?php echo $pickup_requests->weight->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->source_pickup_address->Visible) { // source_pickup_address ?>
	<tr id="r_source_pickup_address">
		<td><span id="elh_pickup_requests_source_pickup_address"><?php echo $pickup_requests->source_pickup_address->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->source_pickup_address->CellAttributes() ?>>
<span id="el_pickup_requests_source_pickup_address" class="form-group">
<span<?php echo $pickup_requests->source_pickup_address->ViewAttributes() ?>>
<?php echo $pickup_requests->source_pickup_address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->source_pickup_city->Visible) { // source_pickup_city ?>
	<tr id="r_source_pickup_city">
		<td><span id="elh_pickup_requests_source_pickup_city"><?php echo $pickup_requests->source_pickup_city->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->source_pickup_city->CellAttributes() ?>>
<span id="el_pickup_requests_source_pickup_city" class="form-group">
<span<?php echo $pickup_requests->source_pickup_city->ViewAttributes() ?>>
<?php echo $pickup_requests->source_pickup_city->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->source_governorate->Visible) { // source_governorate ?>
	<tr id="r_source_governorate">
		<td><span id="elh_pickup_requests_source_governorate"><?php echo $pickup_requests->source_governorate->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->source_governorate->CellAttributes() ?>>
<span id="el_pickup_requests_source_governorate" class="form-group">
<span<?php echo $pickup_requests->source_governorate->ViewAttributes() ?>>
<?php echo $pickup_requests->source_governorate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->destination_pickup_address->Visible) { // destination_pickup_address ?>
	<tr id="r_destination_pickup_address">
		<td><span id="elh_pickup_requests_destination_pickup_address"><?php echo $pickup_requests->destination_pickup_address->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->destination_pickup_address->CellAttributes() ?>>
<span id="el_pickup_requests_destination_pickup_address" class="form-group">
<span<?php echo $pickup_requests->destination_pickup_address->ViewAttributes() ?>>
<?php echo $pickup_requests->destination_pickup_address->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->destination_pickup_city->Visible) { // destination_pickup_city ?>
	<tr id="r_destination_pickup_city">
		<td><span id="elh_pickup_requests_destination_pickup_city"><?php echo $pickup_requests->destination_pickup_city->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->destination_pickup_city->CellAttributes() ?>>
<span id="el_pickup_requests_destination_pickup_city" class="form-group">
<span<?php echo $pickup_requests->destination_pickup_city->ViewAttributes() ?>>
<?php echo $pickup_requests->destination_pickup_city->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->destination_governorate->Visible) { // destination_governorate ?>
	<tr id="r_destination_governorate">
		<td><span id="elh_pickup_requests_destination_governorate"><?php echo $pickup_requests->destination_governorate->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->destination_governorate->CellAttributes() ?>>
<span id="el_pickup_requests_destination_governorate" class="form-group">
<span<?php echo $pickup_requests->destination_governorate->ViewAttributes() ?>>
<?php echo $pickup_requests->destination_governorate->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->no_of_pieces->Visible) { // no_of_pieces ?>
	<tr id="r_no_of_pieces">
		<td><span id="elh_pickup_requests_no_of_pieces"><?php echo $pickup_requests->no_of_pieces->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->no_of_pieces->CellAttributes() ?>>
<span id="el_pickup_requests_no_of_pieces" class="form-group">
<span<?php echo $pickup_requests->no_of_pieces->ViewAttributes() ?>>
<?php echo $pickup_requests->no_of_pieces->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->pickup_date->Visible) { // pickup_date ?>
	<tr id="r_pickup_date">
		<td><span id="elh_pickup_requests_pickup_date"><?php echo $pickup_requests->pickup_date->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->pickup_date->CellAttributes() ?>>
<span id="el_pickup_requests_pickup_date" class="form-group">
<span<?php echo $pickup_requests->pickup_date->ViewAttributes() ?>>
<?php echo $pickup_requests->pickup_date->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->product_type->Visible) { // product_type ?>
	<tr id="r_product_type">
		<td><span id="elh_pickup_requests_product_type"><?php echo $pickup_requests->product_type->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->product_type->CellAttributes() ?>>
<span id="el_pickup_requests_product_type" class="form-group">
<span<?php echo $pickup_requests->product_type->ViewAttributes() ?>>
<?php echo $pickup_requests->product_type->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->status->Visible) { // status ?>
	<tr id="r_status">
		<td><span id="elh_pickup_requests_status"><?php echo $pickup_requests->status->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->status->CellAttributes() ?>>
<span id="el_pickup_requests_status" class="form-group">
<span<?php echo $pickup_requests->status->ViewAttributes() ?>>
<?php echo $pickup_requests->status->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
<?php if ($pickup_requests->created->Visible) { // created ?>
	<tr id="r_created">
		<td><span id="elh_pickup_requests_created"><?php echo $pickup_requests->created->FldCaption() ?></span></td>
		<td<?php echo $pickup_requests->created->CellAttributes() ?>>
<span id="el_pickup_requests_created" class="form-group">
<span<?php echo $pickup_requests->created->ViewAttributes() ?>>
<?php echo $pickup_requests->created->ViewValue ?></span>
</span>
</td>
	</tr>
<?php } ?>
</table>
<?php if ($pickup_requests->Export == "") { ?>
<?php if (!isset($pickup_requests_view->Pager)) $pickup_requests_view->Pager = new cNumericPager($pickup_requests_view->StartRec, $pickup_requests_view->DisplayRecs, $pickup_requests_view->TotalRecs, $pickup_requests_view->RecRange) ?>
<?php if ($pickup_requests_view->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($pickup_requests_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_view->PageUrl() ?>start=<?php echo $pickup_requests_view->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($pickup_requests_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_view->PageUrl() ?>start=<?php echo $pickup_requests_view->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($pickup_requests_view->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $pickup_requests_view->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($pickup_requests_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_view->PageUrl() ?>start=<?php echo $pickup_requests_view->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($pickup_requests_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_view->PageUrl() ?>start=<?php echo $pickup_requests_view->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<?php } ?>
<div class="clearfix"></div>
<?php } ?>
</form>
<script type="text/javascript">
fpickup_requestsview.Init();
</script>
<?php
$pickup_requests_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($pickup_requests->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$pickup_requests_view->Page_Terminate();
?>
