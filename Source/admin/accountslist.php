<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "accountsinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$accounts_list = NULL; // Initialize page object first

class caccounts_list extends caccounts {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'accounts';

	// Page object name
	var $PageObjName = 'accounts_list';

	// Grid form hidden field names
	var $FormName = 'faccountslist';
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

		// Table object (accounts)
		if (!isset($GLOBALS["accounts"]) || get_class($GLOBALS["accounts"]) == "caccounts") {
			$GLOBALS["accounts"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["accounts"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "accountsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "accountsdelete.php";
		$this->MultiUpdateUrl = "accountsupdate.php";

		// Table object (administrator)
		if (!isset($GLOBALS['administrator'])) $GLOBALS['administrator'] = new cadministrator();

		// User table object (administrator)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cadministrator();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'accounts', TRUE);

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
		global $EW_EXPORT, $accounts;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($accounts);
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
		$this->BuildSearchSql($sWhere, $this->avatar, $Default, FALSE); // avatar
		$this->BuildSearchSql($sWhere, $this->first_name, $Default, FALSE); // first_name
		$this->BuildSearchSql($sWhere, $this->last_name, $Default, FALSE); // last_name
		$this->BuildSearchSql($sWhere, $this->_email, $Default, FALSE); // email
		$this->BuildSearchSql($sWhere, $this->integra_account_id, $Default, FALSE); // integra_account_id
		$this->BuildSearchSql($sWhere, $this->company, $Default, FALSE); // company
		$this->BuildSearchSql($sWhere, $this->job_title, $Default, FALSE); // job_title
		$this->BuildSearchSql($sWhere, $this->phone, $Default, FALSE); // phone
		$this->BuildSearchSql($sWhere, $this->address, $Default, FALSE); // address
		$this->BuildSearchSql($sWhere, $this->ship_monthly, $Default, FALSE); // ship_monthly
		$this->BuildSearchSql($sWhere, $this->commercial_register, $Default, FALSE); // commercial_register
		$this->BuildSearchSql($sWhere, $this->password, $Default, FALSE); // password
		$this->BuildSearchSql($sWhere, $this->status, $Default, FALSE); // status
		$this->BuildSearchSql($sWhere, $this->created, $Default, FALSE); // created

		// Set up search parm
		if (!$Default && $sWhere <> "") {
			$this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->id->AdvancedSearch->Save(); // id
			$this->avatar->AdvancedSearch->Save(); // avatar
			$this->first_name->AdvancedSearch->Save(); // first_name
			$this->last_name->AdvancedSearch->Save(); // last_name
			$this->_email->AdvancedSearch->Save(); // email
			$this->integra_account_id->AdvancedSearch->Save(); // integra_account_id
			$this->company->AdvancedSearch->Save(); // company
			$this->job_title->AdvancedSearch->Save(); // job_title
			$this->phone->AdvancedSearch->Save(); // phone
			$this->address->AdvancedSearch->Save(); // address
			$this->ship_monthly->AdvancedSearch->Save(); // ship_monthly
			$this->commercial_register->AdvancedSearch->Save(); // commercial_register
			$this->password->AdvancedSearch->Save(); // password
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
		$this->BuildBasicSearchSQL($sWhere, $this->avatar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->first_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->last_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->integra_account_id, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->company, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->job_title, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->ship_monthly, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->commercial_register, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->password, $arKeywords, $type);
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
		if ($this->avatar->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->first_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->last_name->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->_email->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->integra_account_id->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->company->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->job_title->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->phone->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->address->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->ship_monthly->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->commercial_register->AdvancedSearch->IssetSession())
			return TRUE;
		if ($this->password->AdvancedSearch->IssetSession())
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
		$this->avatar->AdvancedSearch->UnsetSession();
		$this->first_name->AdvancedSearch->UnsetSession();
		$this->last_name->AdvancedSearch->UnsetSession();
		$this->_email->AdvancedSearch->UnsetSession();
		$this->integra_account_id->AdvancedSearch->UnsetSession();
		$this->company->AdvancedSearch->UnsetSession();
		$this->job_title->AdvancedSearch->UnsetSession();
		$this->phone->AdvancedSearch->UnsetSession();
		$this->address->AdvancedSearch->UnsetSession();
		$this->ship_monthly->AdvancedSearch->UnsetSession();
		$this->commercial_register->AdvancedSearch->UnsetSession();
		$this->password->AdvancedSearch->UnsetSession();
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
		$this->avatar->AdvancedSearch->Load();
		$this->first_name->AdvancedSearch->Load();
		$this->last_name->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->integra_account_id->AdvancedSearch->Load();
		$this->company->AdvancedSearch->Load();
		$this->job_title->AdvancedSearch->Load();
		$this->phone->AdvancedSearch->Load();
		$this->address->AdvancedSearch->Load();
		$this->ship_monthly->AdvancedSearch->Load();
		$this->commercial_register->AdvancedSearch->Load();
		$this->password->AdvancedSearch->Load();
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
			$this->UpdateSort($this->avatar); // avatar
			$this->UpdateSort($this->first_name); // first_name
			$this->UpdateSort($this->last_name); // last_name
			$this->UpdateSort($this->_email); // email
			$this->UpdateSort($this->integra_account_id); // integra_account_id
			$this->UpdateSort($this->company); // company
			$this->UpdateSort($this->job_title); // job_title
			$this->UpdateSort($this->phone); // phone
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
				$this->id->setSort("");
				$this->avatar->setSort("");
				$this->first_name->setSort("");
				$this->last_name->setSort("");
				$this->_email->setSort("");
				$this->integra_account_id->setSort("");
				$this->company->setSort("");
				$this->job_title->setSort("");
				$this->phone->setSort("");
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
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitSelected(document.faccountslist, '" . $this->MultiDeleteUrl . "', ewLanguage.Phrase('DeleteMultiConfirmMsg'));return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.faccountslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"faccountslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

		// avatar
		$this->avatar->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_avatar"]);
		if ($this->avatar->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->avatar->AdvancedSearch->SearchOperator = @$_GET["z_avatar"];

		// first_name
		$this->first_name->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_first_name"]);
		if ($this->first_name->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->first_name->AdvancedSearch->SearchOperator = @$_GET["z_first_name"];

		// last_name
		$this->last_name->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_last_name"]);
		if ($this->last_name->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->last_name->AdvancedSearch->SearchOperator = @$_GET["z_last_name"];

		// email
		$this->_email->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x__email"]);
		if ($this->_email->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->_email->AdvancedSearch->SearchOperator = @$_GET["z__email"];

		// integra_account_id
		$this->integra_account_id->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_integra_account_id"]);
		if ($this->integra_account_id->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->integra_account_id->AdvancedSearch->SearchOperator = @$_GET["z_integra_account_id"];

		// company
		$this->company->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_company"]);
		if ($this->company->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->company->AdvancedSearch->SearchOperator = @$_GET["z_company"];

		// job_title
		$this->job_title->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_job_title"]);
		if ($this->job_title->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->job_title->AdvancedSearch->SearchOperator = @$_GET["z_job_title"];

		// phone
		$this->phone->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_phone"]);
		if ($this->phone->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->phone->AdvancedSearch->SearchOperator = @$_GET["z_phone"];

		// address
		$this->address->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_address"]);
		if ($this->address->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->address->AdvancedSearch->SearchOperator = @$_GET["z_address"];

		// ship_monthly
		$this->ship_monthly->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_ship_monthly"]);
		if ($this->ship_monthly->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->ship_monthly->AdvancedSearch->SearchOperator = @$_GET["z_ship_monthly"];

		// commercial_register
		$this->commercial_register->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_commercial_register"]);
		if ($this->commercial_register->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->commercial_register->AdvancedSearch->SearchOperator = @$_GET["z_commercial_register"];

		// password
		$this->password->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_password"]);
		if ($this->password->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->password->AdvancedSearch->SearchOperator = @$_GET["z_password"];

		// status
		$this->status->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_status"]);
		if ($this->status->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->status->AdvancedSearch->SearchOperator = @$_GET["z_status"];

		// created
		$this->created->AdvancedSearch->SearchValue = ew_StripSlashes(@$_GET["x_created"]);
		if ($this->created->AdvancedSearch->SearchValue <> "") $this->Command = "search";
		$this->created->AdvancedSearch->SearchOperator = @$_GET["z_created"];
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
		$this->avatar->Upload->DbValue = $rs->fields('avatar');
		$this->avatar->CurrentValue = $this->avatar->Upload->DbValue;
		$this->first_name->setDbValue($rs->fields('first_name'));
		$this->last_name->setDbValue($rs->fields('last_name'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->integra_account_id->setDbValue($rs->fields('integra_account_id'));
		$this->company->setDbValue($rs->fields('company'));
		$this->job_title->setDbValue($rs->fields('job_title'));
		$this->phone->setDbValue($rs->fields('phone'));
		$this->address->setDbValue($rs->fields('address'));
		$this->ship_monthly->setDbValue($rs->fields('ship_monthly'));
		$this->commercial_register->Upload->DbValue = $rs->fields('commercial_register');
		$this->commercial_register->CurrentValue = $this->commercial_register->Upload->DbValue;
		$this->password->setDbValue($rs->fields('password'));
		$this->status->setDbValue($rs->fields('status'));
		$this->created->setDbValue($rs->fields('created'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->avatar->Upload->DbValue = $row['avatar'];
		$this->first_name->DbValue = $row['first_name'];
		$this->last_name->DbValue = $row['last_name'];
		$this->_email->DbValue = $row['email'];
		$this->integra_account_id->DbValue = $row['integra_account_id'];
		$this->company->DbValue = $row['company'];
		$this->job_title->DbValue = $row['job_title'];
		$this->phone->DbValue = $row['phone'];
		$this->address->DbValue = $row['address'];
		$this->ship_monthly->DbValue = $row['ship_monthly'];
		$this->commercial_register->Upload->DbValue = $row['commercial_register'];
		$this->password->DbValue = $row['password'];
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
		// avatar
		// first_name
		// last_name
		// email
		// integra_account_id
		// company
		// job_title
		// phone
		// address
		// ship_monthly
		// commercial_register
		// password
		// status
		// created
		// Accumulate aggregate value

		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT && $this->RowType <> EW_ROWTYPE_AGGREGATE) {
			$this->id->Count++; // Increment count
		}
		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// avatar
			$this->avatar->UploadPath = '../webroot/uploads/accounts/';
			if (!ew_Empty($this->avatar->Upload->DbValue)) {
				$this->avatar->ImageWidth = 100;
				$this->avatar->ImageHeight = 0;
				$this->avatar->ImageAlt = $this->avatar->FldAlt();
				$this->avatar->ViewValue = ew_UploadPathEx(FALSE, $this->avatar->UploadPath) . $this->avatar->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->avatar->ViewValue = ew_UploadPathEx(TRUE, $this->avatar->UploadPath) . $this->avatar->Upload->DbValue;
				}
			} else {
				$this->avatar->ViewValue = "";
			}
			$this->avatar->ViewCustomAttributes = "";

			// first_name
			$this->first_name->ViewValue = $this->first_name->CurrentValue;
			$this->first_name->ViewCustomAttributes = "";

			// last_name
			$this->last_name->ViewValue = $this->last_name->CurrentValue;
			$this->last_name->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// integra_account_id
			$this->integra_account_id->ViewValue = $this->integra_account_id->CurrentValue;
			$this->integra_account_id->ViewCustomAttributes = "";

			// company
			$this->company->ViewValue = $this->company->CurrentValue;
			$this->company->ViewCustomAttributes = "";

			// job_title
			$this->job_title->ViewValue = $this->job_title->CurrentValue;
			$this->job_title->ViewCustomAttributes = "";

			// phone
			$this->phone->ViewValue = $this->phone->CurrentValue;
			$this->phone->ViewCustomAttributes = "";

			// address
			$this->address->ViewValue = $this->address->CurrentValue;
			$this->address->ViewCustomAttributes = "";

			// ship_monthly
			$this->ship_monthly->ViewValue = $this->ship_monthly->CurrentValue;
			$this->ship_monthly->ViewCustomAttributes = "";

			// commercial_register
			$this->commercial_register->UploadPath = '../webroot/uploads/accounts/';
			if (!ew_Empty($this->commercial_register->Upload->DbValue)) {
				$this->commercial_register->ViewValue = $this->commercial_register->Upload->DbValue;
			} else {
				$this->commercial_register->ViewValue = "";
			}
			$this->commercial_register->ViewCustomAttributes = "";

			// password
			$this->password->ViewValue = $this->password->CurrentValue;
			$this->password->ViewCustomAttributes = "";

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

			// avatar
			$this->avatar->LinkCustomAttributes = "";
			$this->avatar->UploadPath = '../webroot/uploads/accounts/';
			if (!ew_Empty($this->avatar->Upload->DbValue)) {
				$this->avatar->HrefValue = ew_UploadPathEx(FALSE, $this->avatar->UploadPath) . $this->avatar->Upload->DbValue; // Add prefix/suffix
				$this->avatar->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->avatar->HrefValue = ew_ConvertFullUrl($this->avatar->HrefValue);
			} else {
				$this->avatar->HrefValue = "";
			}
			$this->avatar->HrefValue2 = $this->avatar->UploadPath . $this->avatar->Upload->DbValue;
			$this->avatar->TooltipValue = "";
			if ($this->avatar->UseColorbox) {
				$this->avatar->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->avatar->LinkAttrs["data-rel"] = "accounts_x" . $this->RowCnt . "_avatar";
				$this->avatar->LinkAttrs["class"] = "ewLightbox";
			}

			// first_name
			$this->first_name->LinkCustomAttributes = "";
			$this->first_name->HrefValue = "";
			$this->first_name->TooltipValue = "";

			// last_name
			$this->last_name->LinkCustomAttributes = "";
			$this->last_name->HrefValue = "";
			$this->last_name->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// integra_account_id
			$this->integra_account_id->LinkCustomAttributes = "";
			$this->integra_account_id->HrefValue = "";
			$this->integra_account_id->TooltipValue = "";

			// company
			$this->company->LinkCustomAttributes = "";
			$this->company->HrefValue = "";
			$this->company->TooltipValue = "";

			// job_title
			$this->job_title->LinkCustomAttributes = "";
			$this->job_title->HrefValue = "";
			$this->job_title->TooltipValue = "";

			// phone
			$this->phone->LinkCustomAttributes = "";
			$this->phone->HrefValue = "";
			$this->phone->TooltipValue = "";

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

			// avatar
			$this->avatar->EditAttrs["class"] = "form-control";
			$this->avatar->EditCustomAttributes = "";
			$this->avatar->EditValue = ew_HtmlEncode($this->avatar->AdvancedSearch->SearchValue);
			$this->avatar->PlaceHolder = ew_RemoveHtml($this->avatar->FldCaption());

			// first_name
			$this->first_name->EditAttrs["class"] = "form-control";
			$this->first_name->EditCustomAttributes = "";
			$this->first_name->EditValue = ew_HtmlEncode($this->first_name->AdvancedSearch->SearchValue);
			$this->first_name->PlaceHolder = ew_RemoveHtml($this->first_name->FldCaption());

			// last_name
			$this->last_name->EditAttrs["class"] = "form-control";
			$this->last_name->EditCustomAttributes = "";
			$this->last_name->EditValue = ew_HtmlEncode($this->last_name->AdvancedSearch->SearchValue);
			$this->last_name->PlaceHolder = ew_RemoveHtml($this->last_name->FldCaption());

			// email
			$this->_email->EditAttrs["class"] = "form-control";
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->AdvancedSearch->SearchValue);
			$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

			// integra_account_id
			$this->integra_account_id->EditAttrs["class"] = "form-control";
			$this->integra_account_id->EditCustomAttributes = "";
			$this->integra_account_id->EditValue = ew_HtmlEncode($this->integra_account_id->AdvancedSearch->SearchValue);
			$this->integra_account_id->PlaceHolder = ew_RemoveHtml($this->integra_account_id->FldCaption());

			// company
			$this->company->EditAttrs["class"] = "form-control";
			$this->company->EditCustomAttributes = "";
			$this->company->EditValue = ew_HtmlEncode($this->company->AdvancedSearch->SearchValue);
			$this->company->PlaceHolder = ew_RemoveHtml($this->company->FldCaption());

			// job_title
			$this->job_title->EditAttrs["class"] = "form-control";
			$this->job_title->EditCustomAttributes = "";
			$this->job_title->EditValue = ew_HtmlEncode($this->job_title->AdvancedSearch->SearchValue);
			$this->job_title->PlaceHolder = ew_RemoveHtml($this->job_title->FldCaption());

			// phone
			$this->phone->EditAttrs["class"] = "form-control";
			$this->phone->EditCustomAttributes = "";
			$this->phone->EditValue = ew_HtmlEncode($this->phone->AdvancedSearch->SearchValue);
			$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

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
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATEINIT) { // Initialize aggregate row
			$this->id->Count = 0; // Initialize count
		} elseif ($this->RowType == EW_ROWTYPE_AGGREGATE) { // Aggregate row
			$this->id->CurrentValue = $this->id->Count;
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";
			$this->id->HrefValue = ""; // Clear href value
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
		$this->avatar->AdvancedSearch->Load();
		$this->first_name->AdvancedSearch->Load();
		$this->last_name->AdvancedSearch->Load();
		$this->_email->AdvancedSearch->Load();
		$this->integra_account_id->AdvancedSearch->Load();
		$this->company->AdvancedSearch->Load();
		$this->job_title->AdvancedSearch->Load();
		$this->phone->AdvancedSearch->Load();
		$this->address->AdvancedSearch->Load();
		$this->ship_monthly->AdvancedSearch->Load();
		$this->commercial_register->AdvancedSearch->Load();
		$this->password->AdvancedSearch->Load();
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
		$item->Body = "<button id=\"emf_accounts\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_accounts',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.faccountslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($accounts_list)) $accounts_list = new caccounts_list();

// Page init
$accounts_list->Page_Init();

// Page main
$accounts_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$accounts_list->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<?php if ($accounts->Export == "") { ?>
<script type="text/javascript">

// Page object
var accounts_list = new ew_Page("accounts_list");
accounts_list.PageID = "list"; // Page ID
var EW_PAGE_ID = accounts_list.PageID; // For backward compatibility

// Form object
var faccountslist = new ew_Form("faccountslist");
faccountslist.FormKeyCountName = '<?php echo $accounts_list->FormKeyCountName ?>';

// Form_CustomValidate event
faccountslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
faccountslist.ValidateRequired = true;
<?php } else { ?>
faccountslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var faccountslistsrch = new ew_Form("faccountslistsrch");

// Validate function for search
faccountslistsrch.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();
	var infix = "";

	// Set up row object
	ew_ElementsToRow(fobj);

	// Fire Form_CustomValidate event
	if (!this.Form_CustomValidate(fobj))
		return false;
	return true;
}

// Form_CustomValidate event
faccountslistsrch.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
faccountslistsrch.ValidateRequired = true; // Use JavaScript validation
<?php } else { ?>
faccountslistsrch.ValidateRequired = false; // No JavaScript validation
<?php } ?>

// Dynamic selection lists
// Init search panel as collapsed

if (faccountslistsrch) faccountslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($accounts->Export == "") { ?>
<div class="ewToolbar">
<?php if ($accounts->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($accounts_list->TotalRecs > 0 && $accounts_list->ExportOptions->Visible()) { ?>
<?php $accounts_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($accounts_list->SearchOptions->Visible()) { ?>
<?php $accounts_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($accounts->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$accounts_list->TotalRecs = $accounts->SelectRecordCount();
	} else {
		if ($accounts_list->Recordset = $accounts_list->LoadRecordset())
			$accounts_list->TotalRecs = $accounts_list->Recordset->RecordCount();
	}
	$accounts_list->StartRec = 1;
	if ($accounts_list->DisplayRecs <= 0 || ($accounts->Export <> "" && $accounts->ExportAll)) // Display all records
		$accounts_list->DisplayRecs = $accounts_list->TotalRecs;
	if (!($accounts->Export <> "" && $accounts->ExportAll))
		$accounts_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$accounts_list->Recordset = $accounts_list->LoadRecordset($accounts_list->StartRec-1, $accounts_list->DisplayRecs);

	// Set no record found message
	if ($accounts->CurrentAction == "" && $accounts_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$accounts_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($accounts_list->SearchWhere == "0=101")
			$accounts_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$accounts_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$accounts_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($accounts->Export == "" && $accounts->CurrentAction == "") { ?>
<form name="faccountslistsrch" id="faccountslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($accounts_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="faccountslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="accounts">
	<div class="ewBasicSearch">
<?php
if ($gsSearchError == "")
	$accounts_list->LoadAdvancedSearch(); // Load advanced search

// Render for search
$accounts->RowType = EW_ROWTYPE_SEARCH;

// Render row
$accounts->ResetAttrs();
$accounts_list->RenderRow();
?>
<div id="xsr_1" class="ewRow">
<?php if ($accounts->_email->Visible) { // email ?>
	<div id="xsc__email" class="ewCell form-group">
		<label for="x__email" class="ewSearchCaption ewLabel"><?php echo $accounts->_email->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z__email" id="z__email" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x__email" name="x__email" id="x__email" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($accounts->_email->PlaceHolder) ?>" value="<?php echo $accounts->_email->EditValue ?>"<?php echo $accounts->_email->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_2" class="ewRow">
<?php if ($accounts->integra_account_id->Visible) { // integra_account_id ?>
	<div id="xsc_integra_account_id" class="ewCell form-group">
		<label for="x_integra_account_id" class="ewSearchCaption ewLabel"><?php echo $accounts->integra_account_id->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("LIKE") ?><input type="hidden" name="z_integra_account_id" id="z_integra_account_id" value="LIKE"></span>
		<span class="ewSearchField">
<input type="text" data-field="x_integra_account_id" name="x_integra_account_id" id="x_integra_account_id" size="70" maxlength="255" placeholder="<?php echo ew_HtmlEncode($accounts->integra_account_id->PlaceHolder) ?>" value="<?php echo $accounts->integra_account_id->EditValue ?>"<?php echo $accounts->integra_account_id->EditAttributes() ?>>
</span>
	</div>
<?php } ?>
</div>
<div id="xsr_3" class="ewRow">
<?php if ($accounts->status->Visible) { // status ?>
	<div id="xsc_status" class="ewCell form-group">
		<label for="x_status" class="ewSearchCaption ewLabel"><?php echo $accounts->status->FldCaption() ?></label>
		<span class="ewSearchOperator"><?php echo $Language->Phrase("=") ?><input type="hidden" name="z_status" id="z_status" value="="></span>
		<span class="ewSearchField">
<select data-field="x_status" id="x_status" name="x_status"<?php echo $accounts->status->EditAttributes() ?>>
<?php
if (is_array($accounts->status->EditValue)) {
	$arwrk = $accounts->status->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($accounts->status->AdvancedSearch->SearchValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<div id="xsr_4" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($accounts_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($accounts_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $accounts_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($accounts_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($accounts_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($accounts_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($accounts_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $accounts_list->ShowPageHeader(); ?>
<?php
$accounts_list->ShowMessage();
?>
<?php if ($accounts_list->TotalRecs > 0 || $accounts->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($accounts->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($accounts->CurrentAction <> "gridadd" && $accounts->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($accounts_list->Pager)) $accounts_list->Pager = new cNumericPager($accounts_list->StartRec, $accounts_list->DisplayRecs, $accounts_list->TotalRecs, $accounts_list->RecRange) ?>
<?php if ($accounts_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($accounts_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $accounts_list->PageUrl() ?>start=<?php echo $accounts_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($accounts_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $accounts_list->PageUrl() ?>start=<?php echo $accounts_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($accounts_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $accounts_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($accounts_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $accounts_list->PageUrl() ?>start=<?php echo $accounts_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($accounts_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $accounts_list->PageUrl() ?>start=<?php echo $accounts_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $accounts_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $accounts_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $accounts_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($accounts_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="accounts">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="10"<?php if ($accounts_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="25"<?php if ($accounts_list->DisplayRecs == 25) { ?> selected="selected"<?php } ?>>25</option>
<option value="50"<?php if ($accounts_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($accounts_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($accounts->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($accounts_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="faccountslist" id="faccountslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($accounts_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $accounts_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="accounts">
<div id="gmp_accounts" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($accounts_list->TotalRecs > 0) { ?>
<table id="tbl_accountslist" class="table ewTable">
<?php echo $accounts->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$accounts_list->RenderListOptions();

// Render list options (header, left)
$accounts_list->ListOptions->Render("header", "left");
?>
<?php if ($accounts->id->Visible) { // id ?>
	<?php if ($accounts->SortUrl($accounts->id) == "") { ?>
		<th data-name="id"><div id="elh_accounts_id" class="accounts_id"><div class="ewTableHeaderCaption"><?php echo $accounts->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $accounts->SortUrl($accounts->id) ?>',1);"><div id="elh_accounts_id" class="accounts_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $accounts->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($accounts->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($accounts->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($accounts->avatar->Visible) { // avatar ?>
	<?php if ($accounts->SortUrl($accounts->avatar) == "") { ?>
		<th data-name="avatar"><div id="elh_accounts_avatar" class="accounts_avatar"><div class="ewTableHeaderCaption"><?php echo $accounts->avatar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="avatar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $accounts->SortUrl($accounts->avatar) ?>',1);"><div id="elh_accounts_avatar" class="accounts_avatar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $accounts->avatar->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($accounts->avatar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($accounts->avatar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($accounts->first_name->Visible) { // first_name ?>
	<?php if ($accounts->SortUrl($accounts->first_name) == "") { ?>
		<th data-name="first_name"><div id="elh_accounts_first_name" class="accounts_first_name"><div class="ewTableHeaderCaption"><?php echo $accounts->first_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="first_name"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $accounts->SortUrl($accounts->first_name) ?>',1);"><div id="elh_accounts_first_name" class="accounts_first_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $accounts->first_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($accounts->first_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($accounts->first_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($accounts->last_name->Visible) { // last_name ?>
	<?php if ($accounts->SortUrl($accounts->last_name) == "") { ?>
		<th data-name="last_name"><div id="elh_accounts_last_name" class="accounts_last_name"><div class="ewTableHeaderCaption"><?php echo $accounts->last_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="last_name"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $accounts->SortUrl($accounts->last_name) ?>',1);"><div id="elh_accounts_last_name" class="accounts_last_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $accounts->last_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($accounts->last_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($accounts->last_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($accounts->_email->Visible) { // email ?>
	<?php if ($accounts->SortUrl($accounts->_email) == "") { ?>
		<th data-name="_email"><div id="elh_accounts__email" class="accounts__email"><div class="ewTableHeaderCaption"><?php echo $accounts->_email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_email"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $accounts->SortUrl($accounts->_email) ?>',1);"><div id="elh_accounts__email" class="accounts__email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $accounts->_email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($accounts->_email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($accounts->_email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($accounts->integra_account_id->Visible) { // integra_account_id ?>
	<?php if ($accounts->SortUrl($accounts->integra_account_id) == "") { ?>
		<th data-name="integra_account_id"><div id="elh_accounts_integra_account_id" class="accounts_integra_account_id"><div class="ewTableHeaderCaption"><?php echo $accounts->integra_account_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="integra_account_id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $accounts->SortUrl($accounts->integra_account_id) ?>',1);"><div id="elh_accounts_integra_account_id" class="accounts_integra_account_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $accounts->integra_account_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($accounts->integra_account_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($accounts->integra_account_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($accounts->company->Visible) { // company ?>
	<?php if ($accounts->SortUrl($accounts->company) == "") { ?>
		<th data-name="company"><div id="elh_accounts_company" class="accounts_company"><div class="ewTableHeaderCaption"><?php echo $accounts->company->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="company"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $accounts->SortUrl($accounts->company) ?>',1);"><div id="elh_accounts_company" class="accounts_company">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $accounts->company->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($accounts->company->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($accounts->company->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($accounts->job_title->Visible) { // job_title ?>
	<?php if ($accounts->SortUrl($accounts->job_title) == "") { ?>
		<th data-name="job_title"><div id="elh_accounts_job_title" class="accounts_job_title"><div class="ewTableHeaderCaption"><?php echo $accounts->job_title->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="job_title"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $accounts->SortUrl($accounts->job_title) ?>',1);"><div id="elh_accounts_job_title" class="accounts_job_title">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $accounts->job_title->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($accounts->job_title->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($accounts->job_title->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($accounts->phone->Visible) { // phone ?>
	<?php if ($accounts->SortUrl($accounts->phone) == "") { ?>
		<th data-name="phone"><div id="elh_accounts_phone" class="accounts_phone"><div class="ewTableHeaderCaption"><?php echo $accounts->phone->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="phone"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $accounts->SortUrl($accounts->phone) ?>',1);"><div id="elh_accounts_phone" class="accounts_phone">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $accounts->phone->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($accounts->phone->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($accounts->phone->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($accounts->status->Visible) { // status ?>
	<?php if ($accounts->SortUrl($accounts->status) == "") { ?>
		<th data-name="status"><div id="elh_accounts_status" class="accounts_status"><div class="ewTableHeaderCaption"><?php echo $accounts->status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="status"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $accounts->SortUrl($accounts->status) ?>',1);"><div id="elh_accounts_status" class="accounts_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $accounts->status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($accounts->status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($accounts->status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($accounts->created->Visible) { // created ?>
	<?php if ($accounts->SortUrl($accounts->created) == "") { ?>
		<th data-name="created"><div id="elh_accounts_created" class="accounts_created"><div class="ewTableHeaderCaption"><?php echo $accounts->created->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="created"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $accounts->SortUrl($accounts->created) ?>',1);"><div id="elh_accounts_created" class="accounts_created">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $accounts->created->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($accounts->created->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($accounts->created->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$accounts_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($accounts->ExportAll && $accounts->Export <> "") {
	$accounts_list->StopRec = $accounts_list->TotalRecs;
} else {

	// Set the last record to display
	if ($accounts_list->TotalRecs > $accounts_list->StartRec + $accounts_list->DisplayRecs - 1)
		$accounts_list->StopRec = $accounts_list->StartRec + $accounts_list->DisplayRecs - 1;
	else
		$accounts_list->StopRec = $accounts_list->TotalRecs;
}
$accounts_list->RecCnt = $accounts_list->StartRec - 1;
if ($accounts_list->Recordset && !$accounts_list->Recordset->EOF) {
	$accounts_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $accounts_list->StartRec > 1)
		$accounts_list->Recordset->Move($accounts_list->StartRec - 1);
} elseif (!$accounts->AllowAddDeleteRow && $accounts_list->StopRec == 0) {
	$accounts_list->StopRec = $accounts->GridAddRowCount;
}

// Initialize aggregate
$accounts->RowType = EW_ROWTYPE_AGGREGATEINIT;
$accounts->ResetAttrs();
$accounts_list->RenderRow();
while ($accounts_list->RecCnt < $accounts_list->StopRec) {
	$accounts_list->RecCnt++;
	if (intval($accounts_list->RecCnt) >= intval($accounts_list->StartRec)) {
		$accounts_list->RowCnt++;

		// Set up key count
		$accounts_list->KeyCount = $accounts_list->RowIndex;

		// Init row class and style
		$accounts->ResetAttrs();
		$accounts->CssClass = "";
		if ($accounts->CurrentAction == "gridadd") {
		} else {
			$accounts_list->LoadRowValues($accounts_list->Recordset); // Load row values
		}
		$accounts->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$accounts->RowAttrs = array_merge($accounts->RowAttrs, array('data-rowindex'=>$accounts_list->RowCnt, 'id'=>'r' . $accounts_list->RowCnt . '_accounts', 'data-rowtype'=>$accounts->RowType));

		// Render row
		$accounts_list->RenderRow();

		// Render list options
		$accounts_list->RenderListOptions();
?>
	<tr<?php echo $accounts->RowAttributes() ?>>
<?php

// Render list options (body, left)
$accounts_list->ListOptions->Render("body", "left", $accounts_list->RowCnt);
?>
	<?php if ($accounts->id->Visible) { // id ?>
		<td data-name="id"<?php echo $accounts->id->CellAttributes() ?>>
<span<?php echo $accounts->id->ViewAttributes() ?>>
<?php echo $accounts->id->ListViewValue() ?></span>
<a id="<?php echo $accounts_list->PageObjName . "_row_" . $accounts_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($accounts->avatar->Visible) { // avatar ?>
		<td data-name="avatar"<?php echo $accounts->avatar->CellAttributes() ?>>
<span>
<?php echo ew_GetFileViewTag($accounts->avatar, $accounts->avatar->ListViewValue()) ?>
</span>
</td>
	<?php } ?>
	<?php if ($accounts->first_name->Visible) { // first_name ?>
		<td data-name="first_name"<?php echo $accounts->first_name->CellAttributes() ?>>
<span<?php echo $accounts->first_name->ViewAttributes() ?>>
<?php echo $accounts->first_name->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($accounts->last_name->Visible) { // last_name ?>
		<td data-name="last_name"<?php echo $accounts->last_name->CellAttributes() ?>>
<span<?php echo $accounts->last_name->ViewAttributes() ?>>
<?php echo $accounts->last_name->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($accounts->_email->Visible) { // email ?>
		<td data-name="_email"<?php echo $accounts->_email->CellAttributes() ?>>
<span<?php echo $accounts->_email->ViewAttributes() ?>>
<?php echo $accounts->_email->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($accounts->integra_account_id->Visible) { // integra_account_id ?>
		<td data-name="integra_account_id"<?php echo $accounts->integra_account_id->CellAttributes() ?>>
<span<?php echo $accounts->integra_account_id->ViewAttributes() ?>>
<?php echo $accounts->integra_account_id->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($accounts->company->Visible) { // company ?>
		<td data-name="company"<?php echo $accounts->company->CellAttributes() ?>>
<span<?php echo $accounts->company->ViewAttributes() ?>>
<?php echo $accounts->company->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($accounts->job_title->Visible) { // job_title ?>
		<td data-name="job_title"<?php echo $accounts->job_title->CellAttributes() ?>>
<span<?php echo $accounts->job_title->ViewAttributes() ?>>
<?php echo $accounts->job_title->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($accounts->phone->Visible) { // phone ?>
		<td data-name="phone"<?php echo $accounts->phone->CellAttributes() ?>>
<span<?php echo $accounts->phone->ViewAttributes() ?>>
<?php echo $accounts->phone->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($accounts->status->Visible) { // status ?>
		<td data-name="status"<?php echo $accounts->status->CellAttributes() ?>>
<span<?php echo $accounts->status->ViewAttributes() ?>>
<?php echo $accounts->status->ListViewValue() ?></span>
</td>
	<?php } ?>
	<?php if ($accounts->created->Visible) { // created ?>
		<td data-name="created"<?php echo $accounts->created->CellAttributes() ?>>
<span<?php echo $accounts->created->ViewAttributes() ?>>
<?php echo $accounts->created->ListViewValue() ?></span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$accounts_list->ListOptions->Render("body", "right", $accounts_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($accounts->CurrentAction <> "gridadd")
		$accounts_list->Recordset->MoveNext();
}
?>
</tbody>
<?php

// Render aggregate row
$accounts->RowType = EW_ROWTYPE_AGGREGATE;
$accounts->ResetAttrs();
$accounts_list->RenderRow();
?>
<?php if ($accounts_list->TotalRecs > 0 && ($accounts->CurrentAction <> "gridadd" && $accounts->CurrentAction <> "gridedit")) { ?>
<tfoot><!-- Table footer -->
	<tr class="ewTableFooter">
<?php

// Render list options
$accounts_list->RenderListOptions();

// Render list options (footer, left)
$accounts_list->ListOptions->Render("footer", "left");
?>
	<?php if ($accounts->id->Visible) { // id ?>
		<td data-name="id"><span id="elf_accounts_id" class="accounts_id">
<span class="ewAggregate"><?php echo $Language->Phrase("COUNT") ?></span>
<?php echo $accounts->id->ViewValue ?>
		</span></td>
	<?php } ?>
	<?php if ($accounts->avatar->Visible) { // avatar ?>
		<td data-name="avatar"><span id="elf_accounts_avatar" class="accounts_avatar">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($accounts->first_name->Visible) { // first_name ?>
		<td data-name="first_name"><span id="elf_accounts_first_name" class="accounts_first_name">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($accounts->last_name->Visible) { // last_name ?>
		<td data-name="last_name"><span id="elf_accounts_last_name" class="accounts_last_name">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($accounts->_email->Visible) { // email ?>
		<td data-name="_email"><span id="elf_accounts__email" class="accounts__email">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($accounts->integra_account_id->Visible) { // integra_account_id ?>
		<td data-name="integra_account_id"><span id="elf_accounts_integra_account_id" class="accounts_integra_account_id">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($accounts->company->Visible) { // company ?>
		<td data-name="company"><span id="elf_accounts_company" class="accounts_company">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($accounts->job_title->Visible) { // job_title ?>
		<td data-name="job_title"><span id="elf_accounts_job_title" class="accounts_job_title">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($accounts->phone->Visible) { // phone ?>
		<td data-name="phone"><span id="elf_accounts_phone" class="accounts_phone">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($accounts->status->Visible) { // status ?>
		<td data-name="status"><span id="elf_accounts_status" class="accounts_status">
		&nbsp;
		</span></td>
	<?php } ?>
	<?php if ($accounts->created->Visible) { // created ?>
		<td data-name="created"><span id="elf_accounts_created" class="accounts_created">
		&nbsp;
		</span></td>
	<?php } ?>
<?php

// Render list options (footer, right)
$accounts_list->ListOptions->Render("footer", "right");
?>
	</tr>
</tfoot>	
<?php } ?>
</table>
<?php } ?>
<?php if ($accounts->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($accounts_list->Recordset)
	$accounts_list->Recordset->Close();
?>
<?php if ($accounts->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($accounts->CurrentAction <> "gridadd" && $accounts->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($accounts_list->Pager)) $accounts_list->Pager = new cNumericPager($accounts_list->StartRec, $accounts_list->DisplayRecs, $accounts_list->TotalRecs, $accounts_list->RecRange) ?>
<?php if ($accounts_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($accounts_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $accounts_list->PageUrl() ?>start=<?php echo $accounts_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($accounts_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $accounts_list->PageUrl() ?>start=<?php echo $accounts_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($accounts_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $accounts_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($accounts_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $accounts_list->PageUrl() ?>start=<?php echo $accounts_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($accounts_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $accounts_list->PageUrl() ?>start=<?php echo $accounts_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $accounts_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $accounts_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $accounts_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($accounts_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="accounts">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="10"<?php if ($accounts_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="25"<?php if ($accounts_list->DisplayRecs == 25) { ?> selected="selected"<?php } ?>>25</option>
<option value="50"<?php if ($accounts_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($accounts_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($accounts->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($accounts_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($accounts_list->TotalRecs == 0 && $accounts->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($accounts_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($accounts->Export == "") { ?>
<script type="text/javascript">
faccountslistsrch.Init();
faccountslist.Init();
</script>
<?php } ?>
<?php
$accounts_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($accounts->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$accounts_list->Page_Terminate();
?>
