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

$pickup_requests_list = NULL; // Initialize page object first

class cpickup_requests_list extends cpickup_requests {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'pickup_requests';

	// Page object name
	var $PageObjName = 'pickup_requests_list';

	// Grid form hidden field names
	var $FormName = 'fpickup_requestslist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "pickup_requestsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "pickup_requestsdelete.php";
		$this->MultiUpdateUrl = "pickup_requestsupdate.php";

		// Table object (administrator)
		if (!isset($GLOBALS['administrator'])) $GLOBALS['administrator'] = new cadministrator();

		// User table object (administrator)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cadministrator();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'pickup_requests', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";
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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();

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

		// Setup other options
		$this->SetupOtherOptions();

		// Set "checkbox" visible
		if (count($this->CustomActions) > 0)
			$this->ListOptions->Items["checkbox"]->Visible = TRUE;
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $DisplayRecs = 25;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process custom action first
			$this->ProcessCustomAction();

			// Set up records per page
			$this->SetUpDisplayRecs();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide export options
			if ($this->Export <> "" || $this->CurrentAction <> "")
				$this->ExportOptions->HideAllOptions();

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));
			ew_AddFilter($this->DefaultSearchWhere, $this->AdvancedSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Get and validate search values for advanced search
			$this->LoadSearchValues(); // Get search values
			if (!$this->ValidateSearch())
				$this->setFailureMessage($gsSearchError);

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Get search criteria for advanced search
			if ($gsSearchError == "")
				$sSrchAdvanced = $this->AdvancedSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 25; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();

			// Load advanced search from default
			if ($this->LoadAdvancedSearchDefault()) {
				$sSrchAdvanced = $this->AdvancedSearchWhere();
			}
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";

		// Export data only
		if ($this->CustomExport == "" && in_array($this->Export, array("html","word","excel","xml","csv","email","pdf"))) {
			$this->ExportData();
			$this->Page_Terminate(); // Terminate response
			exit();
		}

		// Load record count first
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount();
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Set up number of records displayed per page
	function SetUpDisplayRecs() {
		$sWrk = @$_GET[EW_TABLE_REC_PER_PAGE];
		if ($sWrk <> "") {
			if (is_numeric($sWrk)) {
				$this->DisplayRecs = intval($sWrk);
			} else {
				if (strtolower($sWrk) == "all") { // Display all records
					$this->DisplayRecs = -1;
				} else {
					$this->DisplayRecs = 25; // Non-numeric, load default
				}
			}
			$this->setRecordsPerPage($this->DisplayRecs); // Save to Session

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->id->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Advanced search WHERE clause based on QueryString
	function AdvancedSearchWhere($Default = FALSE) {
		global $Security;
		$sWhere = "";
		if (!$Security->CanSearch()) return "";
		$this->BuildSearchSql($sWhere, $this->id, $Default, FALSE); // id
		$this->BuildSearchSql($sWhere, $this->account_id, $Default, FALSE); // account_id
		$this->BuildSearchSql($sWhere, $this->from_time, $Default, FALSE); // from_time
		$this->BuildSearchSql($sWhere, $this->to_time, $Default, FALSE); // to_time
		$this->BuildSearchSql($sWhere, $this->contact_name, $Default, FALSE); // contact_name
		$this->BuildSearchSql($sWhere, $this->account_type, $Default, FALSE); // account_type
		$this->BuildSearchSql($sWhere, $this->account_number, $Default, FALSE); // account_number
		$this->BuildSearchSql($sWhere, $this->company, $Default, FALSE); // company
		$this->BuildSearchSql($sWhere, $this->contact_phone, $Default, FALSE); // contact_phone
		$this->BuildSearchSql($sWhere, $this->_email, $Default, FALSE); // email
		$this->BuildSearchSql($sWhere, $this->content, $Default, FALSE); // content
		$this->BuildSearchSql($sWhere, $this->weight, $Default, FALSE); // weight
		$this->BuildSearchSql($sWhere, $this->source_pickup_address, $Default, FALSE); // source_pickup_address
		$this->BuildSearchSql($sWhere, $this->source_pickup_city, $Default, FALSE); // source_pickup_city
		$this->BuildSearchSql($sWhere, $this->source_governorate, $Default, FALSE); // source_governorate
		$this->BuildSearchSql($sWhere, $this->destination_pickup_address, $Default, FALSE); // destination_pickup_address
		$this->BuildSearchSql($sWhere, $this->destination_pickup_city, $Default, FALSE); // destination_pickup_city
		$this->BuildSearchSql($sWhere, $this->destination_governorate, $Default, FALSE); // destination_governorate
		$this->BuildSearchSql($sWhere, $this->no_of_pieces, $Default, FALSE); // no_of_pieces
		$this->BuildSearchSql($sWhere, $this->pickup_date, $Default, FALSE); // pickup_date
		$this->BuildSearchSql($sWhere, $this->product_type, $Default, FALSE); // product_type
		$this->BuildSearchSql($sWhere, $this->status, $Default, FALSE); // status
		$this->BuildSearchSql($sWhere, $this->created, $Default, FALSE); // created

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->account_id->AdvancedSearch->Save(); // account_id
			$this->from_time->AdvancedSearch->Save(); // from_time
			$this->to_time->AdvancedSearch->Save(); // to_time
			$this->contact_name->AdvancedSearch->Save(); // contact_name
			$this->account_type->AdvancedSearch->Save(); // account_type
			$this->account_number->AdvancedSearch->Save(); // account_number
			$this->company->AdvancedSearch->Save(); // company
			$this->contact_phone->AdvancedSearch->Save(); // contact_phone
			$this->_email->AdvancedSearch->Save(); // email
			$this->content->AdvancedSearch->Save(); // content
			$this->weight->AdvancedSearch->Save(); // weight
			$this->source_pickup_address->AdvancedSearch->Save(); // source_pickup_address
			$this->source_pickup_city->AdvancedSearch->Save(); // source_pickup_city
			$this->source_governorate->AdvancedSearch->Save(); // source_governorate
			$this->destination_pickup_address->AdvancedSearch->Save(); // destination_pickup_address
			$this->destination_pickup_city->AdvancedSearch->Save(); // destination_pickup_city
			$this->destination_governorate->AdvancedSearch->Save(); // destination_governorate
			$this->no_of_pieces->AdvancedSearch->Save(); // no_of_pieces
			$this->pickup_date->AdvancedSearch->Save(); // pickup_date
			$this->product_type->AdvancedSearch->Save(); // product_type
			$this->status->AdvancedSearch->Save(); // status
			$this->created->AdvancedSearch->Save(); // created
		}
		return $sWhere;
	}

	// Build search SQL
	function BuildSearchSql(&$Where, &$Fld, $Default, $MultiValue) {
		$FldParm = substr($Fld->FldVar, 2);
		$FldVal = ($Default) ? $Fld->AdvancedSearch->SearchValueDefault : $Fld->AdvancedSearch->SearchValue; // @$_GET["x_$FldParm"]
		$FldOpr = ($Default) ? $Fld->AdvancedSearch->SearchOperatorDefault : $Fld->AdvancedSearch->SearchOperator; // @$_GET["z_$FldParm"]
		$FldCond = ($Default) ? $Fld->AdvancedSearch->SearchConditionDefault : $Fld->AdvancedSearch->SearchCondition; // @$_GET["v_$FldParm"]
		$FldVal2 = ($Default) ? $Fld->AdvancedSearch->SearchValue2Default : $Fld->AdvancedSearch->SearchValue2; // @$_GET["y_$FldParm"]
		$FldOpr2 = ($Default) ? $Fld->AdvancedSearch->SearchOperator2Default : $Fld->AdvancedSearch->SearchOperator2; // @$_GET["w_$FldParm"]
		$sWrk = "";

		//$FldVal = ew_StripSlashes($FldVal);
		if (is_array($FldVal)) $FldVal = implode(",", $FldVal);

		//$FldVal2 = ew_StripSlashes($FldVal2);
		if (is_array($FldVal2)) $FldVal2 = implode(",", $FldVal2);
		$FldOpr = strtoupper(trim($FldOpr));
		if ($FldOpr == "") $FldOpr = "=";
		$FldOpr2 = strtoupper(trim($FldOpr2));
		if ($FldOpr2 == "") $FldOpr2 = "=";
		if (EW_SEARCH_MULTI_VALUE_OPTION == 1 || $FldOpr <> "LIKE" ||
			($FldOpr2 <> "LIKE" && $FldVal2 <> ""))
			$MultiValue = FALSE;
		if ($MultiValue) {
			$sWrk1 = ($FldVal <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr, $FldVal) : ""; // Field value 1
			$sWrk2 = ($FldVal2 <> "") ? ew_GetMultiSearchSql($Fld, $FldOpr2, $FldVal2) : ""; // Field value 2
			$sWrk = $sWrk1; // Build final SQL
			if ($sWrk2 <> "")
				$sWrk = ($sWrk <> "") ? "($sWrk) $FldCond ($sWrk2)" : $sWrk2;
		} else {
			$FldVal = $this->ConvertSearchValue($Fld, $FldVal);
			$FldVal2 = $this->ConvertSearchValue($Fld, $FldVal2);
			$sWrk = ew_GetSearchSql($Fld, $FldVal, $FldOpr, $FldCond, $FldVal2, $FldOpr2);
		}
		ew_AddFilter($Where, $sWrk);
	}

	// Convert search value
	function ConvertSearchValue(&$Fld, $FldVal) {
		if ($FldVal == EW_NULL_VALUE || $FldVal == EW_NOT_NULL_VALUE)
			return $FldVal;
		$Value = $FldVal;
		if ($Fld->FldDataType == EW_DATATYPE_BOOLEAN) {
			if ($FldVal <> "") $Value = ($FldVal == "1" || strtolower(strval($FldVal)) == "y" || strtolower(strval($FldVal)) == "t") ? $Fld->TrueValue : $Fld->FalseValue;
		} elseif ($Fld->FldDataType == EW_DATATYPE_DATE) {
			if ($FldVal <> "") $Value = ew_UnFormatDateTime($FldVal, $Fld->FldDateTimeFormat);
		}
		return $Value;
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->from_time, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->to_time, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->contact_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->account_type, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->account_number, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->company, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->contact_phone, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->content, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->source_pickup_address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->source_pickup_city, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->source_governorate, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->destination_pickup_address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->destination_pickup_city, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->destination_governorate, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->product_type, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $arKeywords, $type) {
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$sCond = $sDefCond;
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if (EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace(EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
						$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .=  "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				$ar = array();

				// Match quoted keywords (i.e.: "...")
				if (preg_match_all('/"([^"]*)"/i', $sSearch, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$p = strpos($sSearch, $match[0]);
						$str = substr($sSearch, 0, $p);
						$sSearch = substr($sSearch, $p + strlen($match[0]));
						if (strlen(trim($str)) > 0)
							$ar = array_merge($ar, explode(" ", trim($str)));
						$ar[] = $match[1]; // Save quoted keyword
					}
				}

				// Match individual keywords
				if (strlen(trim($sSearch)) > 0)
					$ar = array_merge($ar, explode(" ", trim($sSearch)));
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			} else {
				$sSearchStr = $this->BasicSearchSQL(array($sSearch), $sSearchType);
			}
			if (!$Default) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		if ($this->id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->account_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->from_time->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->to_time->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->contact_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->account_type->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->account_number->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->company->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->contact_phone->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_email->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->content->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->weight->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->source_pickup_address->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->source_pickup_city->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->source_governorate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->destination_pickup_address->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->destination_pickup_city->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->destination_governorate->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->no_of_pieces->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->pickup_date->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->product_type->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->status->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->created->AdvancedSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();

		// Clear advanced search parameters
		$this->ResetAdvancedSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Clear all advanced search parameters
	function ResetAdvancedSearchParms() {
		$this->id->AdvancedSearch->UnsetSession();
		$this->account_id->AdvancedSearch->UnsetSession();
		$this->from_time->AdvancedSearch->UnsetSession();
		$this->to_time->AdvancedSearch->UnsetSession();
		$this->contact_name->AdvancedSearch->UnsetSession();
		$this->account_type->AdvancedSearch->UnsetSession();
		$this->account_number->AdvancedSearch->UnsetSession();
		$this->company->AdvancedSearch->UnsetSession();
		$this->contact_phone->AdvancedSearch->UnsetSession();
		$this->_email->AdvancedSearch->UnsetSession();
		$this->content->AdvancedSearch->UnsetSession();
		$this->weight->AdvancedSearch->UnsetSession();
		$this->source_pickup_address->AdvancedSearch->UnsetSession();
		$this->source_pickup_city->AdvancedSearch->UnsetSession();
		$this->source_governorate->AdvancedSearch->UnsetSession();
		$this->destination_pickup_address->AdvancedSearch->UnsetSession();
		$this->destination_pickup_city->AdvancedSearch->UnsetSession();
		$this->destination_governorate->AdvancedSearch->UnsetSession();
		$this->no_of_pieces->AdvancedSearch->UnsetSession();
		$this->pickup_date->AdvancedSearch->UnsetSession();
		$this->product_type->AdvancedSearch->UnsetSession();
		$this->status->AdvancedSearch->UnsetSession();
		$this->created->AdvancedSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();

		// Restore advanced search values
		$this->id->AdvancedSearch->Load();
		$this->account_id->AdvancedSearch->Load();
		$this->from_time->AdvancedSearch->Load();
		$this->to_time->AdvancedSearch->Load();
		$this->contact_name->AdvancedSearch->Load();
		$this->account_type->AdvancedSearch->Load();
		$this->account_number->AdvancedSearch->Load();
		$this->company->AdvancedSearch->Load();
		$this->contact_phone->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->content->AdvancedSearch->Load();
		$this->weight->AdvancedSearch->Load();
		$this->source_pickup_address->AdvancedSearch->Load();
		$this->source_pickup_city->AdvancedSearch->Load();
		$this->source_governorate->AdvancedSearch->Load();
		$this->destination_pickup_address->AdvancedSearch->Load();
		$this->destination_pickup_city->AdvancedSearch->Load();
		$this->destination_governorate->AdvancedSearch->Load();
		$this->no_of_pieces->AdvancedSearch->Load();
		$this->pickup_date->AdvancedSearch->Load();
		$this->product_type->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->created->AdvancedSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->account_id); // account_id
			$this->UpdateSort($this->from_time); // from_time
			$this->UpdateSort($this->to_time); // to_time
			$this->UpdateSort($this->contact_name); // contact_name
			$this->UpdateSort($this->account_type); // account_type
			$this->UpdateSort($this->account_number); // account_number
			$this->UpdateSort($this->contact_phone); // contact_phone
			$this->UpdateSort($this->_email); // email
			$this->UpdateSort($this->pickup_date); // pickup_date
			$this->UpdateSort($this->status); // status
			$this->UpdateSort($this->created); // created
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->setSessionOrderByList($sOrderBy);
				$this->id->setSort("");
				$this->account_id->setSort("");
				$this->from_time->setSort("");
				$this->to_time->setSort("");
				$this->contact_name->setSort("");
				$this->account_type->setSort("");
				$this->account_number->setSort("");
				$this->contact_phone->setSort("");
				$this->_email->setSort("");
				$this->pickup_date->setSort("");
				$this->status->setSort("");
				$this->created->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = TRUE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = TRUE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = TRUE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-space: nowrap;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = TRUE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = TRUE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->MoveTo(0);
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("ViewLink")) . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		else
			$oListOpt->Body = "";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("CopyLink")) . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->id->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddLink")) . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitSelected(document.fpickup_requestslist, '" . $this->MultiDeleteUrl . "', ewLanguage.Phrase('DeleteMultiConfirmMsg'));return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
		$item->Visible = ($Security->CanDelete());

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fpickup_requestslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
			}

			// Hide grid edit, multi-delete and multi-update
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$item = &$option->GetItem("multidelete");
				if ($item) $item->Visible = FALSE;
				$item = &$option->GetItem("multiupdate");
				if ($item) $item->Visible = FALSE;
			}
	}

	// Process custom action
	function ProcessCustomAction() {
		global $conn, $Language, $Security;
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$rsuser = ($rs) ? $rs->GetRows() : array();
			if ($rs)
				$rs->Close();

			// Call row custom action event
			if (count($rsuser) > 0) {
				$conn->BeginTrans();
				foreach ($rsuser as $row) {
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $UserAction, $Language->Phrase("CustomActionCancelled")));
					}
				}
			}
		}
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : "";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fpickup_requestslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere);

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch())
			$this->SearchOptions->HideAllOptions();
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	//  Load search values for validation
	function LoadSearchValues() {
		global $objForm;

		// Load search values
		// id

		$this->id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_id"]);
		if ($this->id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->id->AdvancedSearch->SearchOperator = @$_GET["z_id"];

		// account_id
		$this->account_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_account_id"]);
		if ($this->account_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->account_id->AdvancedSearch->SearchOperator = @$_GET["z_account_id"];

		// from_time
		$this->from_time->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_from_time"]);
		if ($this->from_time->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->from_time->AdvancedSearch->SearchOperator = @$_GET["z_from_time"];

		// to_time
		$this->to_time->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_to_time"]);
		if ($this->to_time->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->to_time->AdvancedSearch->SearchOperator = @$_GET["z_to_time"];

		// contact_name
		$this->contact_name->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_contact_name"]);
		if ($this->contact_name->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->contact_name->AdvancedSearch->SearchOperator = @$_GET["z_contact_name"];

		// account_type
		$this->account_type->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_account_type"]);
		if ($this->account_type->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->account_type->AdvancedSearch->SearchOperator = @$_GET["z_account_type"];

		// account_number
		$this->account_number->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_account_number"]);
		if ($this->account_number->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->account_number->AdvancedSearch->SearchOperator = @$_GET["z_account_number"];

		// company
		$this->company->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_company"]);
		if ($this->company->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->company->AdvancedSearch->SearchOperator = @$_GET["z_company"];

		// contact_phone
		$this->contact_phone->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_contact_phone"]);
		if ($this->contact_phone->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->contact_phone->AdvancedSearch->SearchOperator = @$_GET["z_contact_phone"];

		// email
		$this->_email->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__email"]);
		if ($this->_email->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_email->AdvancedSearch->SearchOperator = @$_GET["z__email"];

		// content
		$this->content->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_content"]);
		if ($this->content->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->content->AdvancedSearch->SearchOperator = @$_GET["z_content"];

		// weight
		$this->weight->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_weight"]);
		if ($this->weight->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->weight->AdvancedSearch->SearchOperator = @$_GET["z_weight"];

		// source_pickup_address
		$this->source_pickup_address->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_source_pickup_address"]);
		if ($this->source_pickup_address->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->source_pickup_address->AdvancedSearch->SearchOperator = @$_GET["z_source_pickup_address"];

		// source_pickup_city
		$this->source_pickup_city->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_source_pickup_city"]);
		if ($this->source_pickup_city->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->source_pickup_city->AdvancedSearch->SearchOperator = @$_GET["z_source_pickup_city"];

		// source_governorate
		$this->source_governorate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_source_governorate"]);
		if ($this->source_governorate->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->source_governorate->AdvancedSearch->SearchOperator = @$_GET["z_source_governorate"];

		// destination_pickup_address
		$this->destination_pickup_address->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_destination_pickup_address"]);
		if ($this->destination_pickup_address->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->destination_pickup_address->AdvancedSearch->SearchOperator = @$_GET["z_destination_pickup_address"];

		// destination_pickup_city
		$this->destination_pickup_city->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_destination_pickup_city"]);
		if ($this->destination_pickup_city->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->destination_pickup_city->AdvancedSearch->SearchOperator = @$_GET["z_destination_pickup_city"];

		// destination_governorate
		$this->destination_governorate->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_destination_governorate"]);
		if ($this->destination_governorate->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->destination_governorate->AdvancedSearch->SearchOperator = @$_GET["z_destination_governorate"];

		// no_of_pieces
		$this->no_of_pieces->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_no_of_pieces"]);
		if ($this->no_of_pieces->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->no_of_pieces->AdvancedSearch->SearchOperator = @$_GET["z_no_of_pieces"];

		// pickup_date
		$this->pickup_date->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_pickup_date"]);
		if ($this->pickup_date->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->pickup_date->AdvancedSearch->SearchOperator = @$_GET["z_pickup_date"];

		// product_type
		$this->product_type->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_product_type"]);
		if ($this->product_type->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->product_type->AdvancedSearch->SearchOperator = @$_GET["z_product_type"];

		// status
		$this->status->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_status"]);
		if ($this->status->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->status->AdvancedSearch->SearchOperator = @$_GET["z_status"];

		// created
		$this->created->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_created"]);
		if ($this->created->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->created->AdvancedSearch->SearchOperator = @$_GET["z_created"];
		$this->created->AdvancedSearch->SearchCondition = @$_GET["v_created"];
		$this->created->AdvancedSearch->SearchValue2 = ew_StripSlashes(@$_GET["y_created"]);
		if ($this->created->AdvancedSearch->SearchValue2 <> "") $this->Command = "search";
		$this->created->AdvancedSearch->SearchOperator2 = @$_GET["w_created"];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
		} elseif ($this->RowType == EW_ROWTYPE_SEARCH) { // Search row

			// id
			$this->id->EditAttrs["class"] = "form-control";
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->AdvancedSearch->SearchValue);
			$this->id->PlaceHolder = ew_RemoveHtml($this->id->FldCaption());

			// account_id
			$this->account_id->EditAttrs["class"] = "form-control";
			$this->account_id->EditCustomAttributes = "";
			if (trim(strval($this->account_id->AdvancedSearch->SearchValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->account_id->AdvancedSearch->SearchValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT DISTINCT `id`, `first_name` AS `DispFld`, `last_name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `accounts`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->account_id, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->account_id->EditValue = $arwrk;

			// from_time
			$this->from_time->EditAttrs["class"] = "form-control";
			$this->from_time->EditCustomAttributes = "";
			$this->from_time->EditValue = ew_HtmlEncode($this->from_time->AdvancedSearch->SearchValue);
			$this->from_time->PlaceHolder = ew_RemoveHtml($this->from_time->FldCaption());

			// to_time
			$this->to_time->EditAttrs["class"] = "form-control";
			$this->to_time->EditCustomAttributes = "";
			$this->to_time->EditValue = ew_HtmlEncode($this->to_time->AdvancedSearch->SearchValue);
			$this->to_time->PlaceHolder = ew_RemoveHtml($this->to_time->FldCaption());

			// contact_name
			$this->contact_name->EditAttrs["class"] = "form-control";
			$this->contact_name->EditCustomAttributes = "";
			$this->contact_name->EditValue = ew_HtmlEncode($this->contact_name->AdvancedSearch->SearchValue);
			$this->contact_name->PlaceHolder = ew_RemoveHtml($this->contact_name->FldCaption());

			// account_type
			$this->account_type->EditAttrs["class"] = "form-control";
			$this->account_type->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT DISTINCT `account_type`, `account_type` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `pickup_requests`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}

			// Call Lookup selecting
			$this->Lookup_Selecting($this->account_type, $sWhereWrk);
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->account_type->EditValue = $arwrk;

			// account_number
			$this->account_number->EditAttrs["class"] = "form-control";
			$this->account_number->EditCustomAttributes = "";
			$this->account_number->EditValue = ew_HtmlEncode($this->account_number->AdvancedSearch->SearchValue);
			$this->account_number->PlaceHolder = ew_RemoveHtml($this->account_number->FldCaption());

			// contact_phone
			$this->contact_phone->EditAttrs["class"] = "form-control";
			$this->contact_phone->EditCustomAttributes = "";
			$this->contact_phone->EditValue = ew_HtmlEncode($this->contact_phone->AdvancedSearch->SearchValue);
			$this->contact_phone->PlaceHolder = ew_RemoveHtml($this->contact_phone->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->AdvancedSearch->SearchValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// pickup_date
			$this->pickup_date->EditAttrs["class"] = "form-control";
			$this->pickup_date->EditCustomAttributes = "";
			$this->pickup_date->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->pickup_date->AdvancedSearch->SearchValue, 7), 7));
			$this->pickup_date->PlaceHolder = ew_RemoveHtml($this->pickup_date->FldCaption());

			// status
			$this->status->EditAttrs["class"] = "form-control";
			$this->status->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->status->FldTagValue(1), $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : $this->status->FldTagValue(1));
			$arwrk[] = array($this->status->FldTagValue(2), $this->status->FldTagCaption(2) <> "" ? $this->status->FldTagCaption(2) : $this->status->FldTagValue(2));
			$arwrk[] = array($this->status->FldTagValue(3), $this->status->FldTagCaption(3) <> "" ? $this->status->FldTagCaption(3) : $this->status->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->status->EditValue = $arwrk;

			// created
			$this->created->EditAttrs["class"] = "form-control";
			$this->created->EditCustomAttributes = "";
			$this->created->EditValue = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->created->AdvancedSearch->SearchValue, 7), 7));
			$this->created->PlaceHolder = ew_RemoveHtml($this->created->FldCaption());
			$this->created->EditAttrs["class"] = "form-control";
			$this->created->EditCustomAttributes = "";
			$this->created->EditValue2 = ew_HtmlEncode(ew_FormatDateTime(ew_UnFormatDateTime($this->created->AdvancedSearch->SearchValue2, 7), 7));
			$this->created->PlaceHolder = ew_RemoveHtml($this->created->FldCaption());
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate search
	function ValidateSearch() {
		global $gsSearchError;

		// Initialize
		$gsSearchError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return TRUE;
		if (!ew_CheckEuroDate($this->created->AdvancedSearch->SearchValue)) {
			ew_AddMessage($gsSearchError, $this->created->FldErrMsg());
		}
		if (!ew_CheckEuroDate($this->created->AdvancedSearch->SearchValue2)) {
			ew_AddMessage($gsSearchError, $this->created->FldErrMsg());
		}

		// Return validate result
		$ValidateSearch = ($gsSearchError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateSearch = $ValidateSearch && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsSearchError, $sFormCustomError);
		}
		return $ValidateSearch;
	}

	// Load advanced search
	function LoadAdvancedSearch() {
		$this->id->AdvancedSearch->Load();
		$this->account_id->AdvancedSearch->Load();
		$this->from_time->AdvancedSearch->Load();
		$this->to_time->AdvancedSearch->Load();
		$this->contact_name->AdvancedSearch->Load();
		$this->account_type->AdvancedSearch->Load();
		$this->account_number->AdvancedSearch->Load();
		$this->company->AdvancedSearch->Load();
		$this->contact_phone->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->content->AdvancedSearch->Load();
		$this->weight->AdvancedSearch->Load();
		$this->source_pickup_address->AdvancedSearch->Load();
		$this->source_pickup_city->AdvancedSearch->Load();
		$this->source_governorate->AdvancedSearch->Load();
		$this->destination_pickup_address->AdvancedSearch->Load();
		$this->destination_pickup_city->AdvancedSearch->Load();
		$this->destination_governorate->AdvancedSearch->Load();
		$this->no_of_pieces->AdvancedSearch->Load();
		$this->pickup_date->AdvancedSearch->Load();
		$this->product_type->AdvancedSearch->Load();
		$this->status->AdvancedSearch->Load();
		$this->created->AdvancedSearch->Load();
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
		$item->Visible = FALSE;

		// Export to Email
		$item = &$this->ExportOptions->Add("email");
		$url = "";
		$item->Body = "<button id=\"emf_pickup_requests\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_pickup_requests',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fpickup_requestslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
	}

	// Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
	function ExportData() {
		$utf8 = (strtolower(EW_CHARSET) == "utf-8");
		$bSelectLimit = EW_SELECT_LIMIT;

		// Load recordset
		if ($bSelectLimit) {
			$this->TotalRecs = $this->SelectRecordCount();
		} else {
			if ($rs = $this->LoadRecordset())
				$this->TotalRecs = $rs->RecordCount();
		}
		$this->StartRec = 1;

		// Export all
		if ($this->ExportAll) {
			set_time_limit(EW_EXPORT_ALL_TIME_LIMIT);
			$this->DisplayRecs = $this->TotalRecs;
			$this->StopRec = $this->TotalRecs;
		} else { // Export one page only
			$this->SetUpStartRec(); // Set up start record position

			// Set the last record to display
			if ($this->DisplayRecs <= 0) {
				$this->StopRec = $this->TotalRecs;
			} else {
				$this->StopRec = $this->StartRec + $this->DisplayRecs - 1;
			}
		}
		if ($bSelectLimit)
			$rs = $this->LoadRecordset($this->StartRec-1, $this->DisplayRecs <= 0 ? $this->TotalRecs : $this->DisplayRecs);
		if (!$rs) {
			header("Content-Type:"); // Remove header
			header("Content-Disposition:");
			$this->ShowMessage();
			return;
		}
		$this->ExportDoc = ew_ExportDocument($this, "h");
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
		$this->ExportDocument($Doc, $rs, $this->StartRec, $this->StopRec, "");
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
		$url = ew_CurrentUrl();
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
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
if (!isset($pickup_requests_list)) $pickup_requests_list = new cpickup_requests_list();

// Page init
$pickup_requests_list->Page_Init();

// Page main
$pickup_requests_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pickup_requests_list->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<?php if ($pickup_requests->Export == "") { ?>
<script type="text/javascript">

// Page object
var pickup_requests_list = new ew_Page("pickup_requests_list");
pickup_requests_list.PageID = "list"; // Page ID
var EW_PAGE_ID = pickup_requests_list.PageID; // For backward compatibility

// Form object
var fpickup_requestslist = new ew_Form("fpickup_requestslist");
fpickup_requestslist.FormKeyCountName = '<?php echo $pickup_requests_list->FormKeyCountName ?>';

// Form_CustomValidate event
fpickup_requestslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpickup_requestslist.ValidateRequired = true;
<?php } else { ?>
fpickup_requestslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpickup_requestslist.Lists["x_account_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_first_name","x_last_name","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpickup_requestslist.Lists["x_account_type"] = {"LinkField":"x_account_type","Ajax":null,"AutoFill":false,"DisplayFields":["x_account_type","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
var fpickup_requestslistsrch = new ew_Form("fpickup_requestslistsrch");

// Validate function for search
fpickup_requestslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";
	elm = this.GetElements("x" + infix + "_created");
	if (elm && !ew_CheckEuroDate(elm.value))
		return this.OnError(elm, "<?php echo ew_JsEncode2($pickup_requests->created->FldErrMsg()) ?>");

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
fpickup_requestslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpickup_requestslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
fpickup_requestslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
fpickup_requestslistsrch.Lists["x_account_id"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_first_name","x_last_name","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
fpickup_requestslistsrch.Lists["x_account_type"] = {"LinkField":"x_account_type","Ajax":null,"AutoFill":false,"DisplayFields":["x_account_type","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Init search panel as collapsed
if (fpickup_requestslistsrch) fpickup_requestslistsrch.InitSearchPanel = true;
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
<?php if ($pickup_requests_list->TotalRecs > 0 && $pickup_requests_list->ExportOptions->Visible()) { ?>
<?php $pickup_requests_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($pickup_requests_list->SearchOptions->Visible()) { ?>
<?php $pickup_requests_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($pickup_requests->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$pickup_requests_list->TotalRecs = $pickup_requests->SelectRecordCount();
	} else {
		if ($pickup_requests_list->Recordset = $pickup_requests_list->LoadRecordset())
			$pickup_requests_list->TotalRecs = $pickup_requests_list->Recordset->RecordCount();
	}
	$pickup_requests_list->StartRec = 1;
	if ($pickup_requests_list->DisplayRecs <= 0 || ($pickup_requests->Export <> "" && $pickup_requests->ExportAll)) // Display all records
		$pickup_requests_list->DisplayRecs = $pickup_requests_list->TotalRecs;
	if (!($pickup_requests->Export <> "" && $pickup_requests->ExportAll))
		$pickup_requests_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$pickup_requests_list->Recordset = $pickup_requests_list->LoadRecordset($pickup_requests_list->StartRec-1, $pickup_requests_list->DisplayRecs);

	// Set no record found message
	if ($pickup_requests->CurrentAction == "" && $pickup_requests_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$pickup_requests_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($pickup_requests_list->SearchWhere == "0=101")
			$pickup_requests_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$pickup_requests_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$pickup_requests_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($pickup_requests->Export == "" && $pickup_requests->CurrentAction == "") { ?>
<form name="fpickup_requestslistsrch" id="fpickup_requestslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($pickup_requests_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fpickup_requestslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="pickup_requests">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$pickup_requests_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$pickup_requests->RowType = EW_ROWTYPE_SEARCH;

// Render row
$pickup_requests->ResetAttrs();
$pickup_requests_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($pickup_requests->account_id->Visible) { // account_id ?>
	<div id="xsc_account_id" class="ewCell form-group">
		<label for="x_account_id" class="ewSearchCaption ewLabel"><?php echo $pickup_requests->account_id->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_account_id" id="z_account_id" value="="></span>
		<span class="ewSearchField">
<select data-field="x_account_id" id="x_account_id" name="x_account_id"<?php echo $pickup_requests->account_id->EditAttributes() ?>>
<?php
if (is_array($pickup_requests->account_id->EditValue)) {
	$arwrk = $pickup_requests->account_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pickup_requests->account_id->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$pickup_requests->account_id) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<?php
$sSqlWrk = "SELECT DISTINCT `id`, `first_name` AS `DispFld`, `last_name` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `accounts`";
$sWhereWrk = "";

// Call Lookup selecting
$pickup_requests->Lookup_Selecting($pickup_requests->account_id, $sWhereWrk);
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_account_id" id="s_x_account_id" value="s=<?php echo ew_Encrypt($sSqlWrk) ?>&amp;f0=<?php echo ew_Encrypt("`id` = {filter_value}"); ?>&amp;t0=3">
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($pickup_requests->from_time->Visible) { // from_time ?>
	<div id="xsc_from_time" class="ewCell form-group">
		<label for="x_from_time" class="ewSearchCaption ewLabel"><?php echo $pickup_requests->from_time->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_from_time" id="z_from_time" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_from_time" name="x_from_time" id="x_from_time" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pickup_requests->from_time->PlaceHolder) ?>" value="<?php echo $pickup_requests->from_time->EditValue ?>"<?php echo $pickup_requests->from_time->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($pickup_requests->to_time->Visible) { // to_time ?>
	<div id="xsc_to_time" class="ewCell form-group">
		<label for="x_to_time" class="ewSearchCaption ewLabel"><?php echo $pickup_requests->to_time->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_to_time" id="z_to_time" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_to_time" name="x_to_time" id="x_to_time" size="30" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pickup_requests->to_time->PlaceHolder) ?>" value="<?php echo $pickup_requests->to_time->EditValue ?>"<?php echo $pickup_requests->to_time->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_4" class="ewRow">
<?php if ($pickup_requests->account_type->Visible) { // account_type ?>
	<div id="xsc_account_type" class="ewCell form-group">
		<label for="x_account_type" class="ewSearchCaption ewLabel"><?php echo $pickup_requests->account_type->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_account_type" id="z_account_type" value="LIKE"></span>
		<span class="ewSearchField">
<select data-field="x_account_type" id="x_account_type" name="x_account_type"<?php echo $pickup_requests->account_type->EditAttributes() ?>>
<?php
if (is_array($pickup_requests->account_type->EditValue)) {
	$arwrk = $pickup_requests->account_type->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pickup_requests->account_type->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fpickup_requestslistsrch.Lists["x_account_type"].Options = <?php echo (is_array($pickup_requests->account_type->EditValue)) ? ew_ArrayToJson($pickup_requests->account_type->EditValue, 1) : "[]" ?>;
</script>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_5" class="ewRow">
<?php if ($pickup_requests->account_number->Visible) { // account_number ?>
	<div id="xsc_account_number" class="ewCell form-group">
		<label for="x_account_number" class="ewSearchCaption ewLabel"><?php echo $pickup_requests->account_number->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_account_number" id="z_account_number" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_account_number" name="x_account_number" id="x_account_number" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($pickup_requests->account_number->PlaceHolder) ?>" value="<?php echo $pickup_requests->account_number->EditValue ?>"<?php echo $pickup_requests->account_number->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_6" class="ewRow">
<?php if ($pickup_requests->status->Visible) { // status ?>
	<div id="xsc_status" class="ewCell form-group">
		<label for="x_status" class="ewSearchCaption ewLabel"><?php echo $pickup_requests->status->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></span>
		<span class="ewSearchField">
<select data-field="x_status" id="x_status" name="x_status"<?php echo $pickup_requests->status->EditAttributes() ?>>
<?php
if (is_array($pickup_requests->status->EditValue)) {
	$arwrk = $pickup_requests->status->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($pickup_requests->status->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_7" class="ewRow">
<?php if ($pickup_requests->created->Visible) { // created ?>
	<div id="xsc_created" class="ewCell form-group">
		<label for="x_created" class="ewSearchCaption ewLabel"><?php echo $pickup_requests->created->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("BETWEEN") ?><input type="hidden" name="z_created" id="z_created" value="BETWEEN"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_created" name="x_created" id="x_created" placeholder="<?php echo ew_HtmlEncode($pickup_requests->created->PlaceHolder) ?>" value="<?php echo $pickup_requests->created->EditValue ?>"<?php echo $pickup_requests->created->EditAttributes() ?>>
</span>
		<span class="ewSearchCond btw1_created">&nbsp;<?php echo $Language->Phrase("AND") ?>&nbsp;</span>
		<span class="ewSearchField btw1_created">
<input type="text" data-field="x_created" name="y_created" id="y_created" placeholder="<?php echo ew_HtmlEncode($pickup_requests->created->PlaceHolder) ?>" value="<?php echo $pickup_requests->created->EditValue2 ?>"<?php echo $pickup_requests->created->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_8" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($pickup_requests_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($pickup_requests_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $pickup_requests_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($pickup_requests_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($pickup_requests_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($pickup_requests_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($pickup_requests_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("QuickSearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $pickup_requests_list->ShowPageHeader(); ?>
<?php
$pickup_requests_list->ShowMessage();
?>
<?php if ($pickup_requests_list->TotalRecs > 0 || $pickup_requests->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($pickup_requests->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($pickup_requests->CurrentAction <> "gridadd" && $pickup_requests->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pickup_requests_list->Pager)) $pickup_requests_list->Pager = new cNumericPager($pickup_requests_list->StartRec, $pickup_requests_list->DisplayRecs, $pickup_requests_list->TotalRecs, $pickup_requests_list->RecRange) ?>
<?php if ($pickup_requests_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($pickup_requests_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_list->PageUrl() ?>start=<?php echo $pickup_requests_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($pickup_requests_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_list->PageUrl() ?>start=<?php echo $pickup_requests_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($pickup_requests_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $pickup_requests_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($pickup_requests_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_list->PageUrl() ?>start=<?php echo $pickup_requests_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($pickup_requests_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_list->PageUrl() ?>start=<?php echo $pickup_requests_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $pickup_requests_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $pickup_requests_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $pickup_requests_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($pickup_requests_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="pickup_requests">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="10"<?php if ($pickup_requests_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="25"<?php if ($pickup_requests_list->DisplayRecs == 25) { ?> selected="selected"<?php } ?>>25</option>
<option value="50"<?php if ($pickup_requests_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($pickup_requests_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($pickup_requests->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pickup_requests_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fpickup_requestslist" id="fpickup_requestslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($pickup_requests_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $pickup_requests_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="pickup_requests">
<div id="gmp_pickup_requests" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($pickup_requests_list->TotalRecs > 0) { ?>
<table id="tbl_pickup_requestslist" class="table ewTable">
<?php echo $pickup_requests->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$pickup_requests_list->RenderListOptions();

// Render list options (header, left)
$pickup_requests_list->ListOptions->Render("header", "left");
?>
<?php if ($pickup_requests->id->Visible) { // id ?>
	<?php if ($pickup_requests->SortUrl($pickup_requests->id) == "") { ?>
		<th data-name="id"><div id="elh_pickup_requests_id" class="pickup_requests_id"><div class="ewTableHeaderCaption"><?php echo $pickup_requests->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pickup_requests->SortUrl($pickup_requests->id) ?>',1);"><div id="elh_pickup_requests_id" class="pickup_requests_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pickup_requests->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pickup_requests->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pickup_requests->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pickup_requests->account_id->Visible) { // account_id ?>
	<?php if ($pickup_requests->SortUrl($pickup_requests->account_id) == "") { ?>
		<th data-name="account_id"><div id="elh_pickup_requests_account_id" class="pickup_requests_account_id"><div class="ewTableHeaderCaption"><?php echo $pickup_requests->account_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="account_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pickup_requests->SortUrl($pickup_requests->account_id) ?>',1);"><div id="elh_pickup_requests_account_id" class="pickup_requests_account_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pickup_requests->account_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pickup_requests->account_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pickup_requests->account_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pickup_requests->from_time->Visible) { // from_time ?>
	<?php if ($pickup_requests->SortUrl($pickup_requests->from_time) == "") { ?>
		<th data-name="from_time"><div id="elh_pickup_requests_from_time" class="pickup_requests_from_time"><div class="ewTableHeaderCaption"><?php echo $pickup_requests->from_time->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="from_time"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pickup_requests->SortUrl($pickup_requests->from_time) ?>',1);"><div id="elh_pickup_requests_from_time" class="pickup_requests_from_time">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pickup_requests->from_time->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pickup_requests->from_time->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pickup_requests->from_time->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pickup_requests->to_time->Visible) { // to_time ?>
	<?php if ($pickup_requests->SortUrl($pickup_requests->to_time) == "") { ?>
		<th data-name="to_time"><div id="elh_pickup_requests_to_time" class="pickup_requests_to_time"><div class="ewTableHeaderCaption"><?php echo $pickup_requests->to_time->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="to_time"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pickup_requests->SortUrl($pickup_requests->to_time) ?>',1);"><div id="elh_pickup_requests_to_time" class="pickup_requests_to_time">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pickup_requests->to_time->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pickup_requests->to_time->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pickup_requests->to_time->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pickup_requests->contact_name->Visible) { // contact_name ?>
	<?php if ($pickup_requests->SortUrl($pickup_requests->contact_name) == "") { ?>
		<th data-name="contact_name"><div id="elh_pickup_requests_contact_name" class="pickup_requests_contact_name"><div class="ewTableHeaderCaption"><?php echo $pickup_requests->contact_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="contact_name"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pickup_requests->SortUrl($pickup_requests->contact_name) ?>',1);"><div id="elh_pickup_requests_contact_name" class="pickup_requests_contact_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pickup_requests->contact_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pickup_requests->contact_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pickup_requests->contact_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pickup_requests->account_type->Visible) { // account_type ?>
	<?php if ($pickup_requests->SortUrl($pickup_requests->account_type) == "") { ?>
		<th data-name="account_type"><div id="elh_pickup_requests_account_type" class="pickup_requests_account_type"><div class="ewTableHeaderCaption"><?php echo $pickup_requests->account_type->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="account_type"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pickup_requests->SortUrl($pickup_requests->account_type) ?>',1);"><div id="elh_pickup_requests_account_type" class="pickup_requests_account_type">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pickup_requests->account_type->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pickup_requests->account_type->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pickup_requests->account_type->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pickup_requests->account_number->Visible) { // account_number ?>
	<?php if ($pickup_requests->SortUrl($pickup_requests->account_number) == "") { ?>
		<th data-name="account_number"><div id="elh_pickup_requests_account_number" class="pickup_requests_account_number"><div class="ewTableHeaderCaption"><?php echo $pickup_requests->account_number->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="account_number"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pickup_requests->SortUrl($pickup_requests->account_number) ?>',1);"><div id="elh_pickup_requests_account_number" class="pickup_requests_account_number">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pickup_requests->account_number->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pickup_requests->account_number->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pickup_requests->account_number->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pickup_requests->contact_phone->Visible) { // contact_phone ?>
	<?php if ($pickup_requests->SortUrl($pickup_requests->contact_phone) == "") { ?>
		<th data-name="contact_phone"><div id="elh_pickup_requests_contact_phone" class="pickup_requests_contact_phone"><div class="ewTableHeaderCaption"><?php echo $pickup_requests->contact_phone->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="contact_phone"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pickup_requests->SortUrl($pickup_requests->contact_phone) ?>',1);"><div id="elh_pickup_requests_contact_phone" class="pickup_requests_contact_phone">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pickup_requests->contact_phone->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pickup_requests->contact_phone->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pickup_requests->contact_phone->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pickup_requests->_email->Visible) { // email ?>
	<?php if ($pickup_requests->SortUrl($pickup_requests->_email) == "") { ?>
		<th data-name="_email"><div id="elh_pickup_requests__email" class="pickup_requests__email"><div class="ewTableHeaderCaption"><?php echo $pickup_requests->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pickup_requests->SortUrl($pickup_requests->_email) ?>',1);"><div id="elh_pickup_requests__email" class="pickup_requests__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pickup_requests->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($pickup_requests->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pickup_requests->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pickup_requests->pickup_date->Visible) { // pickup_date ?>
	<?php if ($pickup_requests->SortUrl($pickup_requests->pickup_date) == "") { ?>
		<th data-name="pickup_date"><div id="elh_pickup_requests_pickup_date" class="pickup_requests_pickup_date"><div class="ewTableHeaderCaption"><?php echo $pickup_requests->pickup_date->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="pickup_date"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pickup_requests->SortUrl($pickup_requests->pickup_date) ?>',1);"><div id="elh_pickup_requests_pickup_date" class="pickup_requests_pickup_date">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pickup_requests->pickup_date->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pickup_requests->pickup_date->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pickup_requests->pickup_date->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pickup_requests->status->Visible) { // status ?>
	<?php if ($pickup_requests->SortUrl($pickup_requests->status) == "") { ?>
		<th data-name="status"><div id="elh_pickup_requests_status" class="pickup_requests_status"><div class="ewTableHeaderCaption"><?php echo $pickup_requests->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pickup_requests->SortUrl($pickup_requests->status) ?>',1);"><div id="elh_pickup_requests_status" class="pickup_requests_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pickup_requests->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pickup_requests->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pickup_requests->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($pickup_requests->created->Visible) { // created ?>
	<?php if ($pickup_requests->SortUrl($pickup_requests->created) == "") { ?>
		<th data-name="created"><div id="elh_pickup_requests_created" class="pickup_requests_created"><div class="ewTableHeaderCaption"><?php echo $pickup_requests->created->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="created"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $pickup_requests->SortUrl($pickup_requests->created) ?>',1);"><div id="elh_pickup_requests_created" class="pickup_requests_created">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pickup_requests->created->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pickup_requests->created->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pickup_requests->created->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$pickup_requests_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($pickup_requests->ExportAll && $pickup_requests->Export <> "") {
	$pickup_requests_list->StopRec = $pickup_requests_list->TotalRecs;
} else {

	// Set the last record to display
	if ($pickup_requests_list->TotalRecs > $pickup_requests_list->StartRec + $pickup_requests_list->DisplayRecs - 1)
		$pickup_requests_list->StopRec = $pickup_requests_list->StartRec + $pickup_requests_list->DisplayRecs - 1;
	else
		$pickup_requests_list->StopRec = $pickup_requests_list->TotalRecs;
}
$pickup_requests_list->RecCnt = $pickup_requests_list->StartRec - 1;
if ($pickup_requests_list->Recordset && !$pickup_requests_list->Recordset->EOF) {
	$pickup_requests_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $pickup_requests_list->StartRec > 1)
		$pickup_requests_list->Recordset->Move($pickup_requests_list->StartRec - 1);
} elseif (!$pickup_requests->AllowAddDeleteRow && $pickup_requests_list->StopRec == 0) {
	$pickup_requests_list->StopRec = $pickup_requests->GridAddRowCount;
}

// Initialize aggregate
$pickup_requests->RowType = EW_ROWTYPE_AGGREGATEINIT;
$pickup_requests->ResetAttrs();
$pickup_requests_list->RenderRow();
while ($pickup_requests_list->RecCnt < $pickup_requests_list->StopRec) {
	$pickup_requests_list->RecCnt++;
	if (intval($pickup_requests_list->RecCnt) >= intval($pickup_requests_list->StartRec)) {
		$pickup_requests_list->RowCnt++;

		// Set up key count
		$pickup_requests_list->KeyCount = $pickup_requests_list->RowIndex;

		// Init row class and style
		$pickup_requests->ResetAttrs();
		$pickup_requests->CssClass = "";
		if ($pickup_requests->CurrentAction == "gridadd") {
		} else {
			$pickup_requests_list->LoadRowValues($pickup_requests_list->Recordset); // Load row values
		}
		$pickup_requests->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$pickup_requests->RowAttrs = array_merge($pickup_requests->RowAttrs, array('data-rowindex'=>$pickup_requests_list->RowCnt, 'id'=>'r' . $pickup_requests_list->RowCnt . '_pickup_requests', 'data-rowtype'=>$pickup_requests->RowType));

		// Render row
		$pickup_requests_list->RenderRow();

		// Render list options
		$pickup_requests_list->RenderListOptions();
?>
	<tr<?php echo $pickup_requests->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pickup_requests_list->ListOptions->Render("body", "left", $pickup_requests_list->RowCnt);
?>
	<?php if ($pickup_requests->id->Visible) { // id ?>
		<td data-name="id"<?php echo $pickup_requests->id->CellAttributes() ?>>
<span<?php echo $pickup_requests->id->ViewAttributes() ?>>
<?php echo $pickup_requests->id->ListViewValue() ?></span>
<a id="<?php echo $pickup_requests_list->PageObjName . "_row_" . $pickup_requests_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($pickup_requests->account_id->Visible) { // account_id ?>
		<td data-name="account_id"<?php echo $pickup_requests->account_id->CellAttributes() ?>>
<span<?php echo $pickup_requests->account_id->ViewAttributes() ?>>
<?php echo $pickup_requests->account_id->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($pickup_requests->from_time->Visible) { // from_time ?>
		<td data-name="from_time"<?php echo $pickup_requests->from_time->CellAttributes() ?>>
<span<?php echo $pickup_requests->from_time->ViewAttributes() ?>>
<?php echo $pickup_requests->from_time->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($pickup_requests->to_time->Visible) { // to_time ?>
		<td data-name="to_time"<?php echo $pickup_requests->to_time->CellAttributes() ?>>
<span<?php echo $pickup_requests->to_time->ViewAttributes() ?>>
<?php echo $pickup_requests->to_time->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($pickup_requests->contact_name->Visible) { // contact_name ?>
		<td data-name="contact_name"<?php echo $pickup_requests->contact_name->CellAttributes() ?>>
<span<?php echo $pickup_requests->contact_name->ViewAttributes() ?>>
<?php echo $pickup_requests->contact_name->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($pickup_requests->account_type->Visible) { // account_type ?>
		<td data-name="account_type"<?php echo $pickup_requests->account_type->CellAttributes() ?>>
<span<?php echo $pickup_requests->account_type->ViewAttributes() ?>>
<?php echo $pickup_requests->account_type->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($pickup_requests->account_number->Visible) { // account_number ?>
		<td data-name="account_number"<?php echo $pickup_requests->account_number->CellAttributes() ?>>
<span<?php echo $pickup_requests->account_number->ViewAttributes() ?>>
<?php echo $pickup_requests->account_number->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($pickup_requests->contact_phone->Visible) { // contact_phone ?>
		<td data-name="contact_phone"<?php echo $pickup_requests->contact_phone->CellAttributes() ?>>
<span<?php echo $pickup_requests->contact_phone->ViewAttributes() ?>>
<?php echo $pickup_requests->contact_phone->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($pickup_requests->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $pickup_requests->_email->CellAttributes() ?>>
<span<?php echo $pickup_requests->_email->ViewAttributes() ?>>
<?php echo $pickup_requests->_email->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($pickup_requests->pickup_date->Visible) { // pickup_date ?>
		<td data-name="pickup_date"<?php echo $pickup_requests->pickup_date->CellAttributes() ?>>
<span<?php echo $pickup_requests->pickup_date->ViewAttributes() ?>>
<?php echo $pickup_requests->pickup_date->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($pickup_requests->status->Visible) { // status ?>
		<td data-name="status"<?php echo $pickup_requests->status->CellAttributes() ?>>
<span<?php echo $pickup_requests->status->ViewAttributes() ?>>
<?php echo $pickup_requests->status->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($pickup_requests->created->Visible) { // created ?>
		<td data-name="created"<?php echo $pickup_requests->created->CellAttributes() ?>>
<span<?php echo $pickup_requests->created->ViewAttributes() ?>>
<?php echo $pickup_requests->created->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pickup_requests_list->ListOptions->Render("body", "right", $pickup_requests_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($pickup_requests->CurrentAction <> "gridadd")
		$pickup_requests_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($pickup_requests->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($pickup_requests_list->Recordset)
	$pickup_requests_list->Recordset->Close();
?>
<?php if ($pickup_requests->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($pickup_requests->CurrentAction <> "gridadd" && $pickup_requests->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($pickup_requests_list->Pager)) $pickup_requests_list->Pager = new cNumericPager($pickup_requests_list->StartRec, $pickup_requests_list->DisplayRecs, $pickup_requests_list->TotalRecs, $pickup_requests_list->RecRange) ?>
<?php if ($pickup_requests_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($pickup_requests_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_list->PageUrl() ?>start=<?php echo $pickup_requests_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($pickup_requests_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_list->PageUrl() ?>start=<?php echo $pickup_requests_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($pickup_requests_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $pickup_requests_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($pickup_requests_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_list->PageUrl() ?>start=<?php echo $pickup_requests_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($pickup_requests_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $pickup_requests_list->PageUrl() ?>start=<?php echo $pickup_requests_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $pickup_requests_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $pickup_requests_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $pickup_requests_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($pickup_requests_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="pickup_requests">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="10"<?php if ($pickup_requests_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="25"<?php if ($pickup_requests_list->DisplayRecs == 25) { ?> selected="selected"<?php } ?>>25</option>
<option value="50"<?php if ($pickup_requests_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($pickup_requests_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($pickup_requests->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pickup_requests_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($pickup_requests_list->TotalRecs == 0 && $pickup_requests->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pickup_requests_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($pickup_requests->Export == "") { ?>
<script type="text/javascript">
fpickup_requestslistsrch.Init();
fpickup_requestslist.Init();
</script>
<?php } ?>
<?php
$pickup_requests_list->ShowPageFooter();
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
$pickup_requests_list->Page_Terminate();
?>
