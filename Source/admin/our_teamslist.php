<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "our_teamsinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$our_teams_list = NULL; // Initialize page object first

class cour_teams_list extends cour_teams {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'our_teams';

	// Page object name
	var $PageObjName = 'our_teams_list';

	// Grid form hidden field names
	var $FormName = 'four_teamslist';
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

		// Table object (our_teams)
		if (!isset($GLOBALS["our_teams"]) || get_class($GLOBALS["our_teams"]) == "cour_teams") {
			$GLOBALS["our_teams"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["our_teams"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "our_teamsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "our_teamsdelete.php";
		$this->MultiUpdateUrl = "our_teamsupdate.php";

		// Table object (administrator)
		if (!isset($GLOBALS['administrator'])) $GLOBALS['administrator'] = new cadministrator();

		// User table object (administrator)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cadministrator();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'our_teams', TRUE);

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

		// Create form object
		$objForm = new cFormObj();

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
		global $EW_EXPORT, $our_teams;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($our_teams);
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

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to grid add mode
				if ($this->CurrentAction == "gridadd")
					$this->GridAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Grid Insert
					if ($this->CurrentAction == "gridinsert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "gridadd") {
						if ($this->ValidateGridForm()) {
							$bGridInsert = $this->GridInsert();
						} else {
							$bGridInsert = FALSE;
							$this->setFailureMessage($gsFormError);
						}
						if (!$bGridInsert) {
							$this->EventCancelled = TRUE;
							$this->CurrentAction = "gridadd"; // Stay in Grid Add mode
						}
					}
				}
			}

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

			// Show grid delete link for grid add / grid edit
			if ($this->AllowAddDeleteRow) {
				if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
					$item = $this->ListOptions->GetItem("griddelete");
					if ($item) $item->Visible = TRUE;
				}
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

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

	//  Exit inline mode
	function ClearInlineMode() {
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Grid Add mode
	function GridAddMode() {
		$_SESSION[EW_SESSION_INLINE_MODE] = "gridadd"; // Enabled grid add
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

	// Perform Grid Add
	function GridInsert() {
		global $conn, $Language, $objForm, $gsFormError;
		$rowindex = 1;
		$bGridInsert = FALSE;

		// Call Grid Inserting event
		if (!$this->Grid_Inserting()) {
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("GridAddCancelled")); // Set grid add cancelled message
			}
			return FALSE;
		}

		// Begin transaction
		$conn->BeginTrans();

		// Init key filter
		$sWrkFilter = "";
		$addcnt = 0;
		$sKey = "";

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Insert all rows
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "" && $rowaction <> "insert")
				continue; // Skip
			$this->LoadFormValues(); // Get form values
			if (!$this->EmptyRow()) {
				$addcnt++;
				$this->SendEmail = FALSE; // Do not send email on insert success

				// Validate form
				if (!$this->ValidateForm()) {
					$bGridInsert = FALSE; // Form error, reset action
					$this->setFailureMessage($gsFormError);
				} else {
					$bGridInsert = $this->AddRow($this->OldRecordset); // Insert this row
				}
				if ($bGridInsert) {
					if ($sKey <> "") $sKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
					$sKey .= $this->id->CurrentValue;

					// Add filter for this record
					$sFilter = $this->KeyFilter();
					if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
					$sWrkFilter .= $sFilter;
				} else {
					break;
				}
			}
		}
		if ($addcnt == 0) { // No record inserted
			$this->setFailureMessage($Language->Phrase("NoAddRecord"));
			$bGridInsert = FALSE;
		}
		if ($bGridInsert) {
			$conn->CommitTrans(); // Commit transaction

			// Get new recordset
			$this->CurrentFilter = $sWrkFilter;
			$sSql = $this->SQL();
			if ($rs = $conn->Execute($sSql)) {
				$rsnew = $rs->GetRows();
				$rs->Close();
			}

			// Call Grid_Inserted event
			$this->Grid_Inserted($rsnew);
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("InsertSuccess")); // Set up insert success message
			$this->ClearInlineMode(); // Clear grid add mode
		} else {
			$conn->RollbackTrans(); // Rollback transaction
			if ($this->getFailureMessage() == "") {
				$this->setFailureMessage($Language->Phrase("InsertFailed")); // Set insert failed message
			}
		}
		return $bGridInsert;
	}

	// Check if empty row
	function EmptyRow() {
		global $objForm;
		if (!ew_Empty($this->image->Upload->Value))
			return FALSE;
		if ($objForm->HasValue("x_name_en") && $objForm->HasValue("o_name_en") && $this->name_en->CurrentValue <> $this->name_en->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_name_ar") && $objForm->HasValue("o_name_ar") && $this->name_ar->CurrentValue <> $this->name_ar->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_title_en") && $objForm->HasValue("o_title_en") && $this->title_en->CurrentValue <> $this->title_en->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_title_ar") && $objForm->HasValue("o_title_ar") && $this->title_ar->CurrentValue <> $this->title_ar->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_bio_en") && $objForm->HasValue("o_bio_en") && $this->bio_en->CurrentValue <> $this->bio_en->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_bio_ar") && $objForm->HasValue("o_bio_ar") && $this->bio_ar->CurrentValue <> $this->bio_ar->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_facebook") && $objForm->HasValue("o_facebook") && $this->facebook->CurrentValue <> $this->facebook->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_twitter") && $objForm->HasValue("o_twitter") && $this->twitter->CurrentValue <> $this->twitter->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_linkedin") && $objForm->HasValue("o_linkedin") && $this->linkedin->CurrentValue <> $this->linkedin->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_last_modified") && $objForm->HasValue("o_last_modified") && $this->last_modified->CurrentValue <> $this->last_modified->OldValue)
			return FALSE;
		return TRUE;
	}

	// Validate grid form
	function ValidateGridForm() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;

		// Validate all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else if (!$this->ValidateForm()) {
					return FALSE;
				}
			}
		}
		return TRUE;
	}

	// Get all form values of the grid
	function GetGridFormValues() {
		global $objForm;

		// Get row count
		$objForm->Index = -1;
		$rowcnt = strval($objForm->GetValue($this->FormKeyCountName));
		if ($rowcnt == "" || !is_numeric($rowcnt))
			$rowcnt = 0;
		$rows = array();

		// Loop through all records
		for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {

			// Load current row values
			$objForm->Index = $rowindex;
			$rowaction = strval($objForm->GetValue($this->FormActionName));
			if ($rowaction <> "delete" && $rowaction <> "insertdelete") {
				$this->LoadFormValues(); // Get form values
				if ($rowaction == "insert" && $this->EmptyRow()) {

					// Ignore
				} else {
					$rows[] = $this->GetFieldValues("FormValue"); // Return row as array
				}
			}
		}
		return $rows; // Return as array of array
	}

	// Restore form values for current row
	function RestoreCurrentRowFormValues($idx) {
		global $objForm;

		// Get row based on current index
		$objForm->Index = $idx;
		$this->LoadFormValues(); // Load form values
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->image, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->name_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->name_ar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->title_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->title_ar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->bio_en, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->bio_ar, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->facebook, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->twitter, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->linkedin, $arKeywords, $type);
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
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->image); // image
			$this->UpdateSort($this->name_en); // name_en
			$this->UpdateSort($this->name_ar); // name_ar
			$this->UpdateSort($this->title_en); // title_en
			$this->UpdateSort($this->title_ar); // title_ar
			$this->UpdateSort($this->bio_en); // bio_en
			$this->UpdateSort($this->bio_ar); // bio_ar
			$this->UpdateSort($this->facebook); // facebook
			$this->UpdateSort($this->twitter); // twitter
			$this->UpdateSort($this->linkedin); // linkedin
			$this->UpdateSort($this->last_modified); // last_modified
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
				$this->image->setSort("");
				$this->name_en->setSort("");
				$this->name_ar->setSort("");
				$this->title_en->setSort("");
				$this->title_ar->setSort("");
				$this->bio_en->setSort("");
				$this->bio_ar->setSort("");
				$this->facebook->setSort("");
				$this->twitter->setSort("");
				$this->linkedin->setSort("");
				$this->last_modified->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "griddelete"
		if ($this->AllowAddDeleteRow) {
			$item = &$this->ListOptions->Add("griddelete");
			$item->CssStyle = "white-space: nowrap;";
			$item->OnLeft = TRUE;
			$item->Visible = FALSE; // Default hidden
		}

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

		// Set up row action and key
		if (is_numeric($this->RowIndex) && $this->CurrentMode <> "view") {
			$objForm->Index = $this->RowIndex;
			$ActionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
			$OldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormOldKeyName);
			$KeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormKeyName);
			$BlankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $ActionName . "\" id=\"" . $ActionName . "\" value=\"" . $this->RowAction . "\">";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue($this->FormKeyName);
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $BlankRowName . "\" id=\"" . $BlankRowName . "\" value=\"1\">";
		}

		// "delete"
		if ($this->AllowAddDeleteRow) {
			if ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$option = &$this->ListOptions;
				$option->UseButtonGroup = TRUE; // Use button group for grid delete button
				$option->UseImageAndText = TRUE; // Use image and text for grid delete button
				$oListOpt = &$option->Items["griddelete"];
				if (!$Security->CanDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
					$oListOpt->Body = "&nbsp;";
				} else {
					$oListOpt->Body = "<a class=\"ewGridLink ewGridDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"javascript:void(0);\" onclick=\"ew_DeleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->Phrase("DeleteLink") . "</a>";
				}
			}
		}

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
		$item = &$option->Add("gridadd");
		$item->Body = "<a class=\"ewAddEdit ewGridAdd\" title=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridAddLink")) . "\" href=\"" . ew_HtmlEncode($this->GridAddUrl) . "\">" . $Language->Phrase("GridAddLink") . "</a>";
		$item->Visible = ($this->GridAddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Add multi delete
		$item = &$option->Add("multidelete");
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitSelected(document.four_teamslist, '" . $this->MultiDeleteUrl . "', ewLanguage.Phrase('DeleteMultiConfirmMsg'));return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "gridedit") { // Not grid add/edit mode
			$option = &$options["action"];
			foreach ($this->CustomActions as $action => $name) {

				// Add custom action
				$item = &$option->Add("custom_" . $action);
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.four_teamslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		} else { // Grid add/edit mode

			// Hide all options first
			foreach ($options as &$option)
				$option->HideAllOptions();
			if ($this->CurrentAction == "gridadd") {
				if ($this->AllowAddDeleteRow) {

					// Add add blank row
					$option = &$options["addedit"];
					$option->UseDropDownButton = FALSE;
					$option->UseImageAndText = TRUE;
					$item = &$option->Add("addblankrow");
					$item->Body = "<a class=\"ewAddEdit ewAddBlankRow\" title=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("AddBlankRow")) . "\" href=\"javascript:void(0);\" onclick=\"ew_AddGridRow(this);\">" . $Language->Phrase("AddBlankRow") . "</a>";
					$item->Visible = $Security->CanAdd();
				}
				$option = &$options["action"];
				$option->UseDropDownButton = FALSE;
				$option->UseImageAndText = TRUE;

				// Add grid insert
				$item = &$option->Add("gridinsert");
				$item->Body = "<a class=\"ewAction ewGridInsert\" title=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridInsertLink")) . "\" href=\"\" onclick=\"return ewForms(this).Submit();\">" . $Language->Phrase("GridInsertLink") . "</a>";

				// Add grid cancel
				$item = &$option->Add("gridcancel");
				$item->Body = "<a class=\"ewAction ewGridCancel\" title=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("GridCancelLink")) . "\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("GridCancelLink") . "</a>";
			}
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"four_teamslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->image->Upload->Index = $objForm->Index;
		$this->image->Upload->UploadFile();
		$this->image->CurrentValue = $this->image->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->image->Upload->DbValue = NULL;
		$this->image->OldValue = $this->image->Upload->DbValue;
		$this->name_en->CurrentValue = NULL;
		$this->name_en->OldValue = $this->name_en->CurrentValue;
		$this->name_ar->CurrentValue = NULL;
		$this->name_ar->OldValue = $this->name_ar->CurrentValue;
		$this->title_en->CurrentValue = NULL;
		$this->title_en->OldValue = $this->title_en->CurrentValue;
		$this->title_ar->CurrentValue = NULL;
		$this->title_ar->OldValue = $this->title_ar->CurrentValue;
		$this->bio_en->CurrentValue = NULL;
		$this->bio_en->OldValue = $this->bio_en->CurrentValue;
		$this->bio_ar->CurrentValue = NULL;
		$this->bio_ar->OldValue = $this->bio_ar->CurrentValue;
		$this->facebook->CurrentValue = NULL;
		$this->facebook->OldValue = $this->facebook->CurrentValue;
		$this->twitter->CurrentValue = NULL;
		$this->twitter->OldValue = $this->twitter->CurrentValue;
		$this->linkedin->CurrentValue = NULL;
		$this->linkedin->OldValue = $this->linkedin->CurrentValue;
		$this->last_modified->CurrentValue = NULL;
		$this->last_modified->OldValue = $this->last_modified->CurrentValue;
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->id->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->name_en->FldIsDetailKey) {
			$this->name_en->setFormValue($objForm->GetValue("x_name_en"));
		}
		$this->name_en->setOldValue($objForm->GetValue("o_name_en"));
		if (!$this->name_ar->FldIsDetailKey) {
			$this->name_ar->setFormValue($objForm->GetValue("x_name_ar"));
		}
		$this->name_ar->setOldValue($objForm->GetValue("o_name_ar"));
		if (!$this->title_en->FldIsDetailKey) {
			$this->title_en->setFormValue($objForm->GetValue("x_title_en"));
		}
		$this->title_en->setOldValue($objForm->GetValue("o_title_en"));
		if (!$this->title_ar->FldIsDetailKey) {
			$this->title_ar->setFormValue($objForm->GetValue("x_title_ar"));
		}
		$this->title_ar->setOldValue($objForm->GetValue("o_title_ar"));
		if (!$this->bio_en->FldIsDetailKey) {
			$this->bio_en->setFormValue($objForm->GetValue("x_bio_en"));
		}
		$this->bio_en->setOldValue($objForm->GetValue("o_bio_en"));
		if (!$this->bio_ar->FldIsDetailKey) {
			$this->bio_ar->setFormValue($objForm->GetValue("x_bio_ar"));
		}
		$this->bio_ar->setOldValue($objForm->GetValue("o_bio_ar"));
		if (!$this->facebook->FldIsDetailKey) {
			$this->facebook->setFormValue($objForm->GetValue("x_facebook"));
		}
		$this->facebook->setOldValue($objForm->GetValue("o_facebook"));
		if (!$this->twitter->FldIsDetailKey) {
			$this->twitter->setFormValue($objForm->GetValue("x_twitter"));
		}
		$this->twitter->setOldValue($objForm->GetValue("o_twitter"));
		if (!$this->linkedin->FldIsDetailKey) {
			$this->linkedin->setFormValue($objForm->GetValue("x_linkedin"));
		}
		$this->linkedin->setOldValue($objForm->GetValue("o_linkedin"));
		if (!$this->last_modified->FldIsDetailKey) {
			$this->last_modified->setFormValue($objForm->GetValue("x_last_modified"));
			$this->last_modified->CurrentValue = ew_UnFormatDateTime($this->last_modified->CurrentValue, 7);
		}
		$this->last_modified->setOldValue($objForm->GetValue("o_last_modified"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->CurrentValue = $this->id->FormValue;
		$this->name_en->CurrentValue = $this->name_en->FormValue;
		$this->name_ar->CurrentValue = $this->name_ar->FormValue;
		$this->title_en->CurrentValue = $this->title_en->FormValue;
		$this->title_ar->CurrentValue = $this->title_ar->FormValue;
		$this->bio_en->CurrentValue = $this->bio_en->FormValue;
		$this->bio_ar->CurrentValue = $this->bio_ar->FormValue;
		$this->facebook->CurrentValue = $this->facebook->FormValue;
		$this->twitter->CurrentValue = $this->twitter->FormValue;
		$this->linkedin->CurrentValue = $this->linkedin->FormValue;
		$this->last_modified->CurrentValue = $this->last_modified->FormValue;
		$this->last_modified->CurrentValue = ew_UnFormatDateTime($this->last_modified->CurrentValue, 7);
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
		$this->image->Upload->DbValue = $rs->fields('image');
		$this->image->CurrentValue = $this->image->Upload->DbValue;
		$this->name_en->setDbValue($rs->fields('name_en'));
		$this->name_ar->setDbValue($rs->fields('name_ar'));
		$this->title_en->setDbValue($rs->fields('title_en'));
		$this->title_ar->setDbValue($rs->fields('title_ar'));
		$this->bio_en->setDbValue($rs->fields('bio_en'));
		$this->bio_ar->setDbValue($rs->fields('bio_ar'));
		$this->facebook->setDbValue($rs->fields('facebook'));
		$this->twitter->setDbValue($rs->fields('twitter'));
		$this->linkedin->setDbValue($rs->fields('linkedin'));
		$this->last_modified->setDbValue($rs->fields('last_modified'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->image->Upload->DbValue = $row['image'];
		$this->name_en->DbValue = $row['name_en'];
		$this->name_ar->DbValue = $row['name_ar'];
		$this->title_en->DbValue = $row['title_en'];
		$this->title_ar->DbValue = $row['title_ar'];
		$this->bio_en->DbValue = $row['bio_en'];
		$this->bio_ar->DbValue = $row['bio_ar'];
		$this->facebook->DbValue = $row['facebook'];
		$this->twitter->DbValue = $row['twitter'];
		$this->linkedin->DbValue = $row['linkedin'];
		$this->last_modified->DbValue = $row['last_modified'];
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
		// image
		// name_en
		// name_ar
		// title_en
		// title_ar
		// bio_en
		// bio_ar
		// facebook
		// twitter
		// linkedin
		// last_modified

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// image
			$this->image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->ImageWidth = 100;
				$this->image->ImageHeight = 0;
				$this->image->ImageAlt = $this->image->FldAlt();
				$this->image->ViewValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->image->ViewValue = ew_UploadPathEx(TRUE, $this->image->UploadPath) . $this->image->Upload->DbValue;
				}
			} else {
				$this->image->ViewValue = "";
			}
			$this->image->ViewCustomAttributes = "";

			// name_en
			$this->name_en->ViewValue = $this->name_en->CurrentValue;
			$this->name_en->ViewCustomAttributes = "";

			// name_ar
			$this->name_ar->ViewValue = $this->name_ar->CurrentValue;
			$this->name_ar->ViewCustomAttributes = "";

			// title_en
			$this->title_en->ViewValue = $this->title_en->CurrentValue;
			$this->title_en->ViewCustomAttributes = "";

			// title_ar
			$this->title_ar->ViewValue = $this->title_ar->CurrentValue;
			$this->title_ar->ViewCustomAttributes = "";

			// bio_en
			$this->bio_en->ViewValue = $this->bio_en->CurrentValue;
			$this->bio_en->ViewCustomAttributes = "";

			// bio_ar
			$this->bio_ar->ViewValue = $this->bio_ar->CurrentValue;
			$this->bio_ar->ViewCustomAttributes = "";

			// facebook
			$this->facebook->ViewValue = $this->facebook->CurrentValue;
			$this->facebook->ViewCustomAttributes = "";

			// twitter
			$this->twitter->ViewValue = $this->twitter->CurrentValue;
			$this->twitter->ViewCustomAttributes = "";

			// linkedin
			$this->linkedin->ViewValue = $this->linkedin->CurrentValue;
			$this->linkedin->ViewCustomAttributes = "";

			// last_modified
			$this->last_modified->ViewValue = $this->last_modified->CurrentValue;
			$this->last_modified->ViewValue = ew_FormatDateTime($this->last_modified->ViewValue, 7);
			$this->last_modified->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// image
			$this->image->LinkCustomAttributes = "";
			$this->image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue; // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_ConvertFullUrl($this->image->HrefValue);
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;
			$this->image->TooltipValue = "";
			if ($this->image->UseColorbox) {
				$this->image->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->image->LinkAttrs["data-rel"] = "our_teams_x" . $this->RowCnt . "_image";
				$this->image->LinkAttrs["class"] = "ewLightbox";
			}

			// name_en
			$this->name_en->LinkCustomAttributes = "";
			$this->name_en->HrefValue = "";
			$this->name_en->TooltipValue = "";

			// name_ar
			$this->name_ar->LinkCustomAttributes = "";
			$this->name_ar->HrefValue = "";
			$this->name_ar->TooltipValue = "";

			// title_en
			$this->title_en->LinkCustomAttributes = "";
			$this->title_en->HrefValue = "";
			$this->title_en->TooltipValue = "";

			// title_ar
			$this->title_ar->LinkCustomAttributes = "";
			$this->title_ar->HrefValue = "";
			$this->title_ar->TooltipValue = "";

			// bio_en
			$this->bio_en->LinkCustomAttributes = "";
			$this->bio_en->HrefValue = "";
			$this->bio_en->TooltipValue = "";

			// bio_ar
			$this->bio_ar->LinkCustomAttributes = "";
			$this->bio_ar->HrefValue = "";
			$this->bio_ar->TooltipValue = "";

			// facebook
			$this->facebook->LinkCustomAttributes = "";
			if (!ew_Empty($this->facebook->CurrentValue)) {
				$this->facebook->HrefValue = $this->facebook->CurrentValue; // Add prefix/suffix
				$this->facebook->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->facebook->HrefValue = ew_ConvertFullUrl($this->facebook->HrefValue);
			} else {
				$this->facebook->HrefValue = "";
			}
			$this->facebook->TooltipValue = "";

			// twitter
			$this->twitter->LinkCustomAttributes = "";
			if (!ew_Empty($this->twitter->CurrentValue)) {
				$this->twitter->HrefValue = $this->twitter->CurrentValue; // Add prefix/suffix
				$this->twitter->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->twitter->HrefValue = ew_ConvertFullUrl($this->twitter->HrefValue);
			} else {
				$this->twitter->HrefValue = "";
			}
			$this->twitter->TooltipValue = "";

			// linkedin
			$this->linkedin->LinkCustomAttributes = "";
			if (!ew_Empty($this->linkedin->CurrentValue)) {
				$this->linkedin->HrefValue = $this->linkedin->CurrentValue; // Add prefix/suffix
				$this->linkedin->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->linkedin->HrefValue = ew_ConvertFullUrl($this->linkedin->HrefValue);
			} else {
				$this->linkedin->HrefValue = "";
			}
			$this->linkedin->TooltipValue = "";

			// last_modified
			$this->last_modified->LinkCustomAttributes = "";
			$this->last_modified->HrefValue = "";
			$this->last_modified->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			// image

			$this->image->EditAttrs["class"] = "form-control";
			$this->image->EditCustomAttributes = "";
			$this->image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->ImageWidth = 100;
				$this->image->ImageHeight = 0;
				$this->image->ImageAlt = $this->image->FldAlt();
				$this->image->EditValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->image->EditValue = ew_UploadPathEx(TRUE, $this->image->UploadPath) . $this->image->Upload->DbValue;
				}
			} else {
				$this->image->EditValue = "";
			}
			if (!ew_Empty($this->image->CurrentValue))
				$this->image->Upload->FileName = $this->image->CurrentValue;
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->image, $this->RowIndex);

			// name_en
			$this->name_en->EditAttrs["class"] = "form-control";
			$this->name_en->EditCustomAttributes = "";
			$this->name_en->EditValue = ew_HtmlEncode($this->name_en->CurrentValue);
			$this->name_en->PlaceHolder = ew_RemoveHtml($this->name_en->FldCaption());

			// name_ar
			$this->name_ar->EditAttrs["class"] = "form-control";
			$this->name_ar->EditCustomAttributes = "";
			$this->name_ar->EditValue = ew_HtmlEncode($this->name_ar->CurrentValue);
			$this->name_ar->PlaceHolder = ew_RemoveHtml($this->name_ar->FldCaption());

			// title_en
			$this->title_en->EditAttrs["class"] = "form-control";
			$this->title_en->EditCustomAttributes = "";
			$this->title_en->EditValue = ew_HtmlEncode($this->title_en->CurrentValue);
			$this->title_en->PlaceHolder = ew_RemoveHtml($this->title_en->FldCaption());

			// title_ar
			$this->title_ar->EditAttrs["class"] = "form-control";
			$this->title_ar->EditCustomAttributes = "";
			$this->title_ar->EditValue = ew_HtmlEncode($this->title_ar->CurrentValue);
			$this->title_ar->PlaceHolder = ew_RemoveHtml($this->title_ar->FldCaption());

			// bio_en
			$this->bio_en->EditAttrs["class"] = "form-control";
			$this->bio_en->EditCustomAttributes = "";
			$this->bio_en->EditValue = ew_HtmlEncode($this->bio_en->CurrentValue);
			$this->bio_en->PlaceHolder = ew_RemoveHtml($this->bio_en->FldCaption());

			// bio_ar
			$this->bio_ar->EditAttrs["class"] = "form-control";
			$this->bio_ar->EditCustomAttributes = "";
			$this->bio_ar->EditValue = ew_HtmlEncode($this->bio_ar->CurrentValue);
			$this->bio_ar->PlaceHolder = ew_RemoveHtml($this->bio_ar->FldCaption());

			// facebook
			$this->facebook->EditAttrs["class"] = "form-control";
			$this->facebook->EditCustomAttributes = "";
			$this->facebook->EditValue = ew_HtmlEncode($this->facebook->CurrentValue);
			$this->facebook->PlaceHolder = ew_RemoveHtml($this->facebook->FldCaption());

			// twitter
			$this->twitter->EditAttrs["class"] = "form-control";
			$this->twitter->EditCustomAttributes = "";
			$this->twitter->EditValue = ew_HtmlEncode($this->twitter->CurrentValue);
			$this->twitter->PlaceHolder = ew_RemoveHtml($this->twitter->FldCaption());

			// linkedin
			$this->linkedin->EditAttrs["class"] = "form-control";
			$this->linkedin->EditCustomAttributes = "";
			$this->linkedin->EditValue = ew_HtmlEncode($this->linkedin->CurrentValue);
			$this->linkedin->PlaceHolder = ew_RemoveHtml($this->linkedin->FldCaption());

			// last_modified
			$this->last_modified->EditAttrs["class"] = "form-control";
			$this->last_modified->EditCustomAttributes = "";
			$this->last_modified->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->last_modified->CurrentValue, 7));
			$this->last_modified->PlaceHolder = ew_RemoveHtml($this->last_modified->FldCaption());

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// image
			$this->image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->image->Upload->DbValue)) {
				$this->image->HrefValue = ew_UploadPathEx(FALSE, $this->image->UploadPath) . $this->image->Upload->DbValue; // Add prefix/suffix
				$this->image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->image->HrefValue = ew_ConvertFullUrl($this->image->HrefValue);
			} else {
				$this->image->HrefValue = "";
			}
			$this->image->HrefValue2 = $this->image->UploadPath . $this->image->Upload->DbValue;

			// name_en
			$this->name_en->HrefValue = "";

			// name_ar
			$this->name_ar->HrefValue = "";

			// title_en
			$this->title_en->HrefValue = "";

			// title_ar
			$this->title_ar->HrefValue = "";

			// bio_en
			$this->bio_en->HrefValue = "";

			// bio_ar
			$this->bio_ar->HrefValue = "";

			// facebook
			if (!ew_Empty($this->facebook->CurrentValue)) {
				$this->facebook->HrefValue = $this->facebook->CurrentValue; // Add prefix/suffix
				$this->facebook->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->facebook->HrefValue = ew_ConvertFullUrl($this->facebook->HrefValue);
			} else {
				$this->facebook->HrefValue = "";
			}

			// twitter
			if (!ew_Empty($this->twitter->CurrentValue)) {
				$this->twitter->HrefValue = $this->twitter->CurrentValue; // Add prefix/suffix
				$this->twitter->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->twitter->HrefValue = ew_ConvertFullUrl($this->twitter->HrefValue);
			} else {
				$this->twitter->HrefValue = "";
			}

			// linkedin
			if (!ew_Empty($this->linkedin->CurrentValue)) {
				$this->linkedin->HrefValue = $this->linkedin->CurrentValue; // Add prefix/suffix
				$this->linkedin->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->linkedin->HrefValue = ew_ConvertFullUrl($this->linkedin->HrefValue);
			} else {
				$this->linkedin->HrefValue = "";
			}

			// last_modified
			$this->last_modified->HrefValue = "";
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

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if ($this->image->Upload->FileName == "" && !$this->image->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->image->FldCaption(), $this->image->ReqErrMsg));
		}
		if (!$this->name_en->FldIsDetailKey && !is_null($this->name_en->FormValue) && $this->name_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name_en->FldCaption(), $this->name_en->ReqErrMsg));
		}
		if (!$this->name_ar->FldIsDetailKey && !is_null($this->name_ar->FormValue) && $this->name_ar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->name_ar->FldCaption(), $this->name_ar->ReqErrMsg));
		}
		if (!$this->title_en->FldIsDetailKey && !is_null($this->title_en->FormValue) && $this->title_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title_en->FldCaption(), $this->title_en->ReqErrMsg));
		}
		if (!$this->title_ar->FldIsDetailKey && !is_null($this->title_ar->FormValue) && $this->title_ar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->title_ar->FldCaption(), $this->title_ar->ReqErrMsg));
		}
		if (!$this->bio_en->FldIsDetailKey && !is_null($this->bio_en->FormValue) && $this->bio_en->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bio_en->FldCaption(), $this->bio_en->ReqErrMsg));
		}
		if (!$this->bio_ar->FldIsDetailKey && !is_null($this->bio_ar->FormValue) && $this->bio_ar->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->bio_ar->FldCaption(), $this->bio_ar->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->last_modified->FormValue)) {
			ew_AddMessage($gsFormError, $this->last_modified->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
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
				$this->image->OldUploadPath = '../webroot/uploads/images/';
				@unlink(ew_UploadPathEx(TRUE, $this->image->OldUploadPath) . $row['image']);
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
		} else {
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
			$this->image->OldUploadPath = '../webroot/uploads/images/';
			$this->image->UploadPath = $this->image->OldUploadPath;
		}
		$rsnew = array();

		// image
		if (!$this->image->Upload->KeepFile) {
			$this->image->Upload->DbValue = ""; // No need to delete old file
			if ($this->image->Upload->FileName == "") {
				$rsnew['image'] = NULL;
			} else {
				$rsnew['image'] = $this->image->Upload->FileName;
			}
		}

		// name_en
		$this->name_en->SetDbValueDef($rsnew, $this->name_en->CurrentValue, "", FALSE);

		// name_ar
		$this->name_ar->SetDbValueDef($rsnew, $this->name_ar->CurrentValue, "", FALSE);

		// title_en
		$this->title_en->SetDbValueDef($rsnew, $this->title_en->CurrentValue, "", FALSE);

		// title_ar
		$this->title_ar->SetDbValueDef($rsnew, $this->title_ar->CurrentValue, "", FALSE);

		// bio_en
		$this->bio_en->SetDbValueDef($rsnew, $this->bio_en->CurrentValue, "", FALSE);

		// bio_ar
		$this->bio_ar->SetDbValueDef($rsnew, $this->bio_ar->CurrentValue, "", FALSE);

		// facebook
		$this->facebook->SetDbValueDef($rsnew, $this->facebook->CurrentValue, NULL, FALSE);

		// twitter
		$this->twitter->SetDbValueDef($rsnew, $this->twitter->CurrentValue, NULL, FALSE);

		// linkedin
		$this->linkedin->SetDbValueDef($rsnew, $this->linkedin->CurrentValue, NULL, FALSE);

		// last_modified
		$this->last_modified->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->last_modified->CurrentValue, 7), NULL, FALSE);
		if (!$this->image->Upload->KeepFile) {
			$this->image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->image->Upload->Value)) {
				if ($this->image->Upload->FileName == $this->image->Upload->DbValue) { // Overwrite if same file name
					$this->image->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['image'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->image->UploadPath), $rsnew['image']); // Get new file name
				}
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if (!$this->image->Upload->KeepFile) {
					if (!ew_Empty($this->image->Upload->Value)) {
						$this->image->Upload->SaveToFile($this->image->UploadPath, $rsnew['image'], TRUE);
					}
					if ($this->image->Upload->DbValue <> "")
						@unlink(ew_UploadPathEx(TRUE, $this->image->OldUploadPath) . $this->image->Upload->DbValue);
				}
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->id->setDbValue($conn->Insert_ID());
			$rsnew['id'] = $this->id->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// image
		ew_CleanUploadTempPath($this->image, $this->image->Upload->Index);
		return $AddRow;
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
		$item->Body = "<button id=\"emf_our_teams\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_our_teams',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.four_teamslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($our_teams_list)) $our_teams_list = new cour_teams_list();

// Page init
$our_teams_list->Page_Init();

// Page main
$our_teams_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$our_teams_list->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<?php if ($our_teams->Export == "") { ?>
<script type="text/javascript">

// Page object
var our_teams_list = new ew_Page("our_teams_list");
our_teams_list.PageID = "list"; // Page ID
var EW_PAGE_ID = our_teams_list.PageID; // For backward compatibility

// Form object
var four_teamslist = new ew_Form("four_teamslist");
four_teamslist.FormKeyCountName = '<?php echo $our_teams_list->FormKeyCountName ?>';

// Validate form
four_teamslist.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	this.PostAutoSuggest();
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
			felm = this.GetElements("x" + infix + "_image");
			elm = this.GetElements("fn_x" + infix + "_image");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $our_teams->image->FldCaption(), $our_teams->image->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_name_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $our_teams->name_en->FldCaption(), $our_teams->name_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_name_ar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $our_teams->name_ar->FldCaption(), $our_teams->name_ar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_title_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $our_teams->title_en->FldCaption(), $our_teams->title_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_title_ar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $our_teams->title_ar->FldCaption(), $our_teams->title_ar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bio_en");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $our_teams->bio_en->FldCaption(), $our_teams->bio_en->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_bio_ar");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $our_teams->bio_ar->FldCaption(), $our_teams->bio_ar->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_last_modified");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($our_teams->last_modified->FldErrMsg()) ?>");

			// Set up row object
			ew_ElementsToRow(fobj);

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	if (gridinsert && addcnt == 0) { // No row added
		alert(ewLanguage.Phrase("NoAddRecord"));
		return false;
	}
	return true;
}

// Check empty row
four_teamslist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "image", false)) return false;
	if (ew_ValueChanged(fobj, infix, "name_en", false)) return false;
	if (ew_ValueChanged(fobj, infix, "name_ar", false)) return false;
	if (ew_ValueChanged(fobj, infix, "title_en", false)) return false;
	if (ew_ValueChanged(fobj, infix, "title_ar", false)) return false;
	if (ew_ValueChanged(fobj, infix, "bio_en", false)) return false;
	if (ew_ValueChanged(fobj, infix, "bio_ar", false)) return false;
	if (ew_ValueChanged(fobj, infix, "facebook", false)) return false;
	if (ew_ValueChanged(fobj, infix, "twitter", false)) return false;
	if (ew_ValueChanged(fobj, infix, "linkedin", false)) return false;
	if (ew_ValueChanged(fobj, infix, "last_modified", false)) return false;
	return true;
}

// Form_CustomValidate event
four_teamslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
four_teamslist.ValidateRequired = true;
<?php } else { ?>
four_teamslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var four_teamslistsrch = new ew_Form("four_teamslistsrch");

// Init search panel as collapsed
if (four_teamslistsrch) four_teamslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($our_teams->Export == "") { ?>
<div class="ewToolbar">
<?php if ($our_teams->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($our_teams_list->TotalRecs > 0 && $our_teams_list->ExportOptions->Visible()) { ?>
<?php $our_teams_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($our_teams_list->SearchOptions->Visible()) { ?>
<?php $our_teams_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($our_teams->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
if ($our_teams->CurrentAction == "gridadd") {
	$our_teams->CurrentFilter = "0=1";
	$our_teams_list->StartRec = 1;
	$our_teams_list->DisplayRecs = $our_teams->GridAddRowCount;
	$our_teams_list->TotalRecs = $our_teams_list->DisplayRecs;
	$our_teams_list->StopRec = $our_teams_list->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$our_teams_list->TotalRecs = $our_teams->SelectRecordCount();
	} else {
		if ($our_teams_list->Recordset = $our_teams_list->LoadRecordset())
			$our_teams_list->TotalRecs = $our_teams_list->Recordset->RecordCount();
	}
	$our_teams_list->StartRec = 1;
	if ($our_teams_list->DisplayRecs <= 0 || ($our_teams->Export <> "" && $our_teams->ExportAll)) // Display all records
		$our_teams_list->DisplayRecs = $our_teams_list->TotalRecs;
	if (!($our_teams->Export <> "" && $our_teams->ExportAll))
		$our_teams_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$our_teams_list->Recordset = $our_teams_list->LoadRecordset($our_teams_list->StartRec-1, $our_teams_list->DisplayRecs);

	// Set no record found message
	if ($our_teams->CurrentAction == "" && $our_teams_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$our_teams_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($our_teams_list->SearchWhere == "0=101")
			$our_teams_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$our_teams_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$our_teams_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($our_teams->Export == "" && $our_teams->CurrentAction == "") { ?>
<form name="four_teamslistsrch" id="four_teamslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($our_teams_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="four_teamslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="our_teams">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($our_teams_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($our_teams_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $our_teams_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($our_teams_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($our_teams_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($our_teams_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($our_teams_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $our_teams_list->ShowPageHeader(); ?>
<?php
$our_teams_list->ShowMessage();
?>
<?php if ($our_teams_list->TotalRecs > 0 || $our_teams->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($our_teams->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($our_teams->CurrentAction <> "gridadd" && $our_teams->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($our_teams_list->Pager)) $our_teams_list->Pager = new cNumericPager($our_teams_list->StartRec, $our_teams_list->DisplayRecs, $our_teams_list->TotalRecs, $our_teams_list->RecRange) ?>
<?php if ($our_teams_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($our_teams_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_list->PageUrl() ?>start=<?php echo $our_teams_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($our_teams_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_list->PageUrl() ?>start=<?php echo $our_teams_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($our_teams_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $our_teams_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($our_teams_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_list->PageUrl() ?>start=<?php echo $our_teams_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($our_teams_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_list->PageUrl() ?>start=<?php echo $our_teams_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $our_teams_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $our_teams_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $our_teams_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($our_teams_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="our_teams">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="10"<?php if ($our_teams_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="25"<?php if ($our_teams_list->DisplayRecs == 25) { ?> selected="selected"<?php } ?>>25</option>
<option value="50"<?php if ($our_teams_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($our_teams_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($our_teams->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($our_teams_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="four_teamslist" id="four_teamslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($our_teams_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $our_teams_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="our_teams">
<div id="gmp_our_teams" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($our_teams_list->TotalRecs > 0) { ?>
<table id="tbl_our_teamslist" class="table ewTable">
<?php echo $our_teams->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$our_teams_list->RenderListOptions();

// Render list options (header, left)
$our_teams_list->ListOptions->Render("header", "left");
?>
<?php if ($our_teams->id->Visible) { // id ?>
	<?php if ($our_teams->SortUrl($our_teams->id) == "") { ?>
		<th data-name="id"><div id="elh_our_teams_id" class="our_teams_id"><div class="ewTableHeaderCaption"><?php echo $our_teams->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_teams->SortUrl($our_teams->id) ?>',1);"><div id="elh_our_teams_id" class="our_teams_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_teams->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($our_teams->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_teams->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($our_teams->image->Visible) { // image ?>
	<?php if ($our_teams->SortUrl($our_teams->image) == "") { ?>
		<th data-name="image"><div id="elh_our_teams_image" class="our_teams_image"><div class="ewTableHeaderCaption"><?php echo $our_teams->image->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="image"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_teams->SortUrl($our_teams->image) ?>',1);"><div id="elh_our_teams_image" class="our_teams_image">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_teams->image->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($our_teams->image->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_teams->image->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($our_teams->name_en->Visible) { // name_en ?>
	<?php if ($our_teams->SortUrl($our_teams->name_en) == "") { ?>
		<th data-name="name_en"><div id="elh_our_teams_name_en" class="our_teams_name_en"><div class="ewTableHeaderCaption"><?php echo $our_teams->name_en->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="name_en"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_teams->SortUrl($our_teams->name_en) ?>',1);"><div id="elh_our_teams_name_en" class="our_teams_name_en">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_teams->name_en->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($our_teams->name_en->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_teams->name_en->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($our_teams->name_ar->Visible) { // name_ar ?>
	<?php if ($our_teams->SortUrl($our_teams->name_ar) == "") { ?>
		<th data-name="name_ar"><div id="elh_our_teams_name_ar" class="our_teams_name_ar"><div class="ewTableHeaderCaption"><?php echo $our_teams->name_ar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="name_ar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_teams->SortUrl($our_teams->name_ar) ?>',1);"><div id="elh_our_teams_name_ar" class="our_teams_name_ar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_teams->name_ar->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($our_teams->name_ar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_teams->name_ar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($our_teams->title_en->Visible) { // title_en ?>
	<?php if ($our_teams->SortUrl($our_teams->title_en) == "") { ?>
		<th data-name="title_en"><div id="elh_our_teams_title_en" class="our_teams_title_en"><div class="ewTableHeaderCaption"><?php echo $our_teams->title_en->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="title_en"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_teams->SortUrl($our_teams->title_en) ?>',1);"><div id="elh_our_teams_title_en" class="our_teams_title_en">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_teams->title_en->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($our_teams->title_en->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_teams->title_en->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($our_teams->title_ar->Visible) { // title_ar ?>
	<?php if ($our_teams->SortUrl($our_teams->title_ar) == "") { ?>
		<th data-name="title_ar"><div id="elh_our_teams_title_ar" class="our_teams_title_ar"><div class="ewTableHeaderCaption"><?php echo $our_teams->title_ar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="title_ar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_teams->SortUrl($our_teams->title_ar) ?>',1);"><div id="elh_our_teams_title_ar" class="our_teams_title_ar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_teams->title_ar->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($our_teams->title_ar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_teams->title_ar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($our_teams->bio_en->Visible) { // bio_en ?>
	<?php if ($our_teams->SortUrl($our_teams->bio_en) == "") { ?>
		<th data-name="bio_en"><div id="elh_our_teams_bio_en" class="our_teams_bio_en"><div class="ewTableHeaderCaption"><?php echo $our_teams->bio_en->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bio_en"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_teams->SortUrl($our_teams->bio_en) ?>',1);"><div id="elh_our_teams_bio_en" class="our_teams_bio_en">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_teams->bio_en->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($our_teams->bio_en->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_teams->bio_en->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($our_teams->bio_ar->Visible) { // bio_ar ?>
	<?php if ($our_teams->SortUrl($our_teams->bio_ar) == "") { ?>
		<th data-name="bio_ar"><div id="elh_our_teams_bio_ar" class="our_teams_bio_ar"><div class="ewTableHeaderCaption"><?php echo $our_teams->bio_ar->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="bio_ar"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_teams->SortUrl($our_teams->bio_ar) ?>',1);"><div id="elh_our_teams_bio_ar" class="our_teams_bio_ar">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_teams->bio_ar->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($our_teams->bio_ar->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_teams->bio_ar->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($our_teams->facebook->Visible) { // facebook ?>
	<?php if ($our_teams->SortUrl($our_teams->facebook) == "") { ?>
		<th data-name="facebook"><div id="elh_our_teams_facebook" class="our_teams_facebook"><div class="ewTableHeaderCaption"><?php echo $our_teams->facebook->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="facebook"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_teams->SortUrl($our_teams->facebook) ?>',1);"><div id="elh_our_teams_facebook" class="our_teams_facebook">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_teams->facebook->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($our_teams->facebook->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_teams->facebook->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($our_teams->twitter->Visible) { // twitter ?>
	<?php if ($our_teams->SortUrl($our_teams->twitter) == "") { ?>
		<th data-name="twitter"><div id="elh_our_teams_twitter" class="our_teams_twitter"><div class="ewTableHeaderCaption"><?php echo $our_teams->twitter->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="twitter"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_teams->SortUrl($our_teams->twitter) ?>',1);"><div id="elh_our_teams_twitter" class="our_teams_twitter">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_teams->twitter->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($our_teams->twitter->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_teams->twitter->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($our_teams->linkedin->Visible) { // linkedin ?>
	<?php if ($our_teams->SortUrl($our_teams->linkedin) == "") { ?>
		<th data-name="linkedin"><div id="elh_our_teams_linkedin" class="our_teams_linkedin"><div class="ewTableHeaderCaption"><?php echo $our_teams->linkedin->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="linkedin"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_teams->SortUrl($our_teams->linkedin) ?>',1);"><div id="elh_our_teams_linkedin" class="our_teams_linkedin">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_teams->linkedin->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($our_teams->linkedin->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_teams->linkedin->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($our_teams->last_modified->Visible) { // last_modified ?>
	<?php if ($our_teams->SortUrl($our_teams->last_modified) == "") { ?>
		<th data-name="last_modified"><div id="elh_our_teams_last_modified" class="our_teams_last_modified"><div class="ewTableHeaderCaption"><?php echo $our_teams->last_modified->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="last_modified"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_teams->SortUrl($our_teams->last_modified) ?>',1);"><div id="elh_our_teams_last_modified" class="our_teams_last_modified">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_teams->last_modified->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($our_teams->last_modified->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_teams->last_modified->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$our_teams_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($our_teams->ExportAll && $our_teams->Export <> "") {
	$our_teams_list->StopRec = $our_teams_list->TotalRecs;
} else {

	// Set the last record to display
	if ($our_teams_list->TotalRecs > $our_teams_list->StartRec + $our_teams_list->DisplayRecs - 1)
		$our_teams_list->StopRec = $our_teams_list->StartRec + $our_teams_list->DisplayRecs - 1;
	else
		$our_teams_list->StopRec = $our_teams_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($our_teams_list->FormKeyCountName) && ($our_teams->CurrentAction == "gridadd" || $our_teams->CurrentAction == "gridedit" || $our_teams->CurrentAction == "F")) {
		$our_teams_list->KeyCount = $objForm->GetValue($our_teams_list->FormKeyCountName);
		$our_teams_list->StopRec = $our_teams_list->StartRec + $our_teams_list->KeyCount - 1;
	}
}
$our_teams_list->RecCnt = $our_teams_list->StartRec - 1;
if ($our_teams_list->Recordset && !$our_teams_list->Recordset->EOF) {
	$our_teams_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $our_teams_list->StartRec > 1)
		$our_teams_list->Recordset->Move($our_teams_list->StartRec - 1);
} elseif (!$our_teams->AllowAddDeleteRow && $our_teams_list->StopRec == 0) {
	$our_teams_list->StopRec = $our_teams->GridAddRowCount;
}

// Initialize aggregate
$our_teams->RowType = EW_ROWTYPE_AGGREGATEINIT;
$our_teams->ResetAttrs();
$our_teams_list->RenderRow();
if ($our_teams->CurrentAction == "gridadd")
	$our_teams_list->RowIndex = 0;
while ($our_teams_list->RecCnt < $our_teams_list->StopRec) {
	$our_teams_list->RecCnt++;
	if (intval($our_teams_list->RecCnt) >= intval($our_teams_list->StartRec)) {
		$our_teams_list->RowCnt++;
		if ($our_teams->CurrentAction == "gridadd" || $our_teams->CurrentAction == "gridedit" || $our_teams->CurrentAction == "F") {
			$our_teams_list->RowIndex++;
			$objForm->Index = $our_teams_list->RowIndex;
			if ($objForm->HasValue($our_teams_list->FormActionName))
				$our_teams_list->RowAction = strval($objForm->GetValue($our_teams_list->FormActionName));
			elseif ($our_teams->CurrentAction == "gridadd")
				$our_teams_list->RowAction = "insert";
			else
				$our_teams_list->RowAction = "";
		}

		// Set up key count
		$our_teams_list->KeyCount = $our_teams_list->RowIndex;

		// Init row class and style
		$our_teams->ResetAttrs();
		$our_teams->CssClass = "";
		if ($our_teams->CurrentAction == "gridadd") {
			$our_teams_list->LoadDefaultValues(); // Load default values
		} else {
			$our_teams_list->LoadRowValues($our_teams_list->Recordset); // Load row values
		}
		$our_teams->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($our_teams->CurrentAction == "gridadd") // Grid add
			$our_teams->RowType = EW_ROWTYPE_ADD; // Render add
		if ($our_teams->CurrentAction == "gridadd" && $our_teams->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$our_teams_list->RestoreCurrentRowFormValues($our_teams_list->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$our_teams->RowAttrs = array_merge($our_teams->RowAttrs, array('data-rowindex'=>$our_teams_list->RowCnt, 'id'=>'r' . $our_teams_list->RowCnt . '_our_teams', 'data-rowtype'=>$our_teams->RowType));

		// Render row
		$our_teams_list->RenderRow();

		// Render list options
		$our_teams_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($our_teams_list->RowAction <> "delete" && $our_teams_list->RowAction <> "insertdelete" && !($our_teams_list->RowAction == "insert" && $our_teams->CurrentAction == "F" && $our_teams_list->EmptyRow())) {
?>
	<tr<?php echo $our_teams->RowAttributes() ?>>
<?php

// Render list options (body, left)
$our_teams_list->ListOptions->Render("body", "left", $our_teams_list->RowCnt);
?>
	<?php if ($our_teams->id->Visible) { // id ?>
		<td data-name="id"<?php echo $our_teams->id->CellAttributes() ?>>
<?php if ($our_teams->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id" name="o<?php echo $our_teams_list->RowIndex ?>_id" id="o<?php echo $our_teams_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($our_teams->id->OldValue) ?>">
<?php } ?>
<?php if ($our_teams->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $our_teams->id->ViewAttributes() ?>>
<?php echo $our_teams->id->ListViewValue() ?></span>
<?php } ?>
<a id="<?php echo $our_teams_list->PageObjName . "_row_" . $our_teams_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($our_teams->image->Visible) { // image ?>
		<td data-name="image"<?php echo $our_teams->image->CellAttributes() ?>>
<?php if ($our_teams->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $our_teams_list->RowCnt ?>_our_teams_image" class="form-group our_teams_image">
<div id="fd_x<?php echo $our_teams_list->RowIndex ?>_image">
<span title="<?php echo $our_teams->image->FldTitle() ? $our_teams->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($our_teams->image->ReadOnly || $our_teams->image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_image" name="x<?php echo $our_teams_list->RowIndex ?>_image" id="x<?php echo $our_teams_list->RowIndex ?>_image">
</span>
<input type="hidden" name="fn_x<?php echo $our_teams_list->RowIndex ?>_image" id= "fn_x<?php echo $our_teams_list->RowIndex ?>_image" value="<?php echo $our_teams->image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $our_teams_list->RowIndex ?>_image" id= "fa_x<?php echo $our_teams_list->RowIndex ?>_image" value="0">
<input type="hidden" name="fs_x<?php echo $our_teams_list->RowIndex ?>_image" id= "fs_x<?php echo $our_teams_list->RowIndex ?>_image" value="255">
<input type="hidden" name="fx_x<?php echo $our_teams_list->RowIndex ?>_image" id= "fx_x<?php echo $our_teams_list->RowIndex ?>_image" value="<?php echo $our_teams->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $our_teams_list->RowIndex ?>_image" id= "fm_x<?php echo $our_teams_list->RowIndex ?>_image" value="<?php echo $our_teams->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $our_teams_list->RowIndex ?>_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_image" name="o<?php echo $our_teams_list->RowIndex ?>_image" id="o<?php echo $our_teams_list->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($our_teams->image->OldValue) ?>">
<?php } ?>
<?php if ($our_teams->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span>
<?php echo ew_GetFileViewTag($our_teams->image, $our_teams->image->ListViewValue()) ?>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($our_teams->name_en->Visible) { // name_en ?>
		<td data-name="name_en"<?php echo $our_teams->name_en->CellAttributes() ?>>
<?php if ($our_teams->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $our_teams_list->RowCnt ?>_our_teams_name_en" class="form-group our_teams_name_en">
<input type="text" data-field="x_name_en" name="x<?php echo $our_teams_list->RowIndex ?>_name_en" id="x<?php echo $our_teams_list->RowIndex ?>_name_en" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->name_en->PlaceHolder) ?>" value="<?php echo $our_teams->name_en->EditValue ?>"<?php echo $our_teams->name_en->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_name_en" name="o<?php echo $our_teams_list->RowIndex ?>_name_en" id="o<?php echo $our_teams_list->RowIndex ?>_name_en" value="<?php echo ew_HtmlEncode($our_teams->name_en->OldValue) ?>">
<?php } ?>
<?php if ($our_teams->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $our_teams->name_en->ViewAttributes() ?>>
<?php echo $our_teams->name_en->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($our_teams->name_ar->Visible) { // name_ar ?>
		<td data-name="name_ar"<?php echo $our_teams->name_ar->CellAttributes() ?>>
<?php if ($our_teams->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $our_teams_list->RowCnt ?>_our_teams_name_ar" class="form-group our_teams_name_ar">
<input type="text" data-field="x_name_ar" name="x<?php echo $our_teams_list->RowIndex ?>_name_ar" id="x<?php echo $our_teams_list->RowIndex ?>_name_ar" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->name_ar->PlaceHolder) ?>" value="<?php echo $our_teams->name_ar->EditValue ?>"<?php echo $our_teams->name_ar->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_name_ar" name="o<?php echo $our_teams_list->RowIndex ?>_name_ar" id="o<?php echo $our_teams_list->RowIndex ?>_name_ar" value="<?php echo ew_HtmlEncode($our_teams->name_ar->OldValue) ?>">
<?php } ?>
<?php if ($our_teams->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $our_teams->name_ar->ViewAttributes() ?>>
<?php echo $our_teams->name_ar->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($our_teams->title_en->Visible) { // title_en ?>
		<td data-name="title_en"<?php echo $our_teams->title_en->CellAttributes() ?>>
<?php if ($our_teams->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $our_teams_list->RowCnt ?>_our_teams_title_en" class="form-group our_teams_title_en">
<input type="text" data-field="x_title_en" name="x<?php echo $our_teams_list->RowIndex ?>_title_en" id="x<?php echo $our_teams_list->RowIndex ?>_title_en" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->title_en->PlaceHolder) ?>" value="<?php echo $our_teams->title_en->EditValue ?>"<?php echo $our_teams->title_en->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_title_en" name="o<?php echo $our_teams_list->RowIndex ?>_title_en" id="o<?php echo $our_teams_list->RowIndex ?>_title_en" value="<?php echo ew_HtmlEncode($our_teams->title_en->OldValue) ?>">
<?php } ?>
<?php if ($our_teams->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $our_teams->title_en->ViewAttributes() ?>>
<?php echo $our_teams->title_en->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($our_teams->title_ar->Visible) { // title_ar ?>
		<td data-name="title_ar"<?php echo $our_teams->title_ar->CellAttributes() ?>>
<?php if ($our_teams->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $our_teams_list->RowCnt ?>_our_teams_title_ar" class="form-group our_teams_title_ar">
<input type="text" data-field="x_title_ar" name="x<?php echo $our_teams_list->RowIndex ?>_title_ar" id="x<?php echo $our_teams_list->RowIndex ?>_title_ar" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->title_ar->PlaceHolder) ?>" value="<?php echo $our_teams->title_ar->EditValue ?>"<?php echo $our_teams->title_ar->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_title_ar" name="o<?php echo $our_teams_list->RowIndex ?>_title_ar" id="o<?php echo $our_teams_list->RowIndex ?>_title_ar" value="<?php echo ew_HtmlEncode($our_teams->title_ar->OldValue) ?>">
<?php } ?>
<?php if ($our_teams->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $our_teams->title_ar->ViewAttributes() ?>>
<?php echo $our_teams->title_ar->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($our_teams->bio_en->Visible) { // bio_en ?>
		<td data-name="bio_en"<?php echo $our_teams->bio_en->CellAttributes() ?>>
<?php if ($our_teams->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $our_teams_list->RowCnt ?>_our_teams_bio_en" class="form-group our_teams_bio_en">
<input type="text" data-field="x_bio_en" name="x<?php echo $our_teams_list->RowIndex ?>_bio_en" id="x<?php echo $our_teams_list->RowIndex ?>_bio_en" size="150" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->bio_en->PlaceHolder) ?>" value="<?php echo $our_teams->bio_en->EditValue ?>"<?php echo $our_teams->bio_en->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_bio_en" name="o<?php echo $our_teams_list->RowIndex ?>_bio_en" id="o<?php echo $our_teams_list->RowIndex ?>_bio_en" value="<?php echo ew_HtmlEncode($our_teams->bio_en->OldValue) ?>">
<?php } ?>
<?php if ($our_teams->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $our_teams->bio_en->ViewAttributes() ?>>
<?php echo $our_teams->bio_en->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($our_teams->bio_ar->Visible) { // bio_ar ?>
		<td data-name="bio_ar"<?php echo $our_teams->bio_ar->CellAttributes() ?>>
<?php if ($our_teams->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $our_teams_list->RowCnt ?>_our_teams_bio_ar" class="form-group our_teams_bio_ar">
<input type="text" data-field="x_bio_ar" name="x<?php echo $our_teams_list->RowIndex ?>_bio_ar" id="x<?php echo $our_teams_list->RowIndex ?>_bio_ar" size="150" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->bio_ar->PlaceHolder) ?>" value="<?php echo $our_teams->bio_ar->EditValue ?>"<?php echo $our_teams->bio_ar->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_bio_ar" name="o<?php echo $our_teams_list->RowIndex ?>_bio_ar" id="o<?php echo $our_teams_list->RowIndex ?>_bio_ar" value="<?php echo ew_HtmlEncode($our_teams->bio_ar->OldValue) ?>">
<?php } ?>
<?php if ($our_teams->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $our_teams->bio_ar->ViewAttributes() ?>>
<?php echo $our_teams->bio_ar->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($our_teams->facebook->Visible) { // facebook ?>
		<td data-name="facebook"<?php echo $our_teams->facebook->CellAttributes() ?>>
<?php if ($our_teams->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $our_teams_list->RowCnt ?>_our_teams_facebook" class="form-group our_teams_facebook">
<input type="text" data-field="x_facebook" name="x<?php echo $our_teams_list->RowIndex ?>_facebook" id="x<?php echo $our_teams_list->RowIndex ?>_facebook" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->facebook->PlaceHolder) ?>" value="<?php echo $our_teams->facebook->EditValue ?>"<?php echo $our_teams->facebook->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_facebook" name="o<?php echo $our_teams_list->RowIndex ?>_facebook" id="o<?php echo $our_teams_list->RowIndex ?>_facebook" value="<?php echo ew_HtmlEncode($our_teams->facebook->OldValue) ?>">
<?php } ?>
<?php if ($our_teams->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $our_teams->facebook->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($our_teams->facebook->ListViewValue()) && $our_teams->facebook->LinkAttributes() <> "") { ?>
<a<?php echo $our_teams->facebook->LinkAttributes() ?>><?php echo $our_teams->facebook->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $our_teams->facebook->ListViewValue() ?>
<?php } ?>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($our_teams->twitter->Visible) { // twitter ?>
		<td data-name="twitter"<?php echo $our_teams->twitter->CellAttributes() ?>>
<?php if ($our_teams->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $our_teams_list->RowCnt ?>_our_teams_twitter" class="form-group our_teams_twitter">
<input type="text" data-field="x_twitter" name="x<?php echo $our_teams_list->RowIndex ?>_twitter" id="x<?php echo $our_teams_list->RowIndex ?>_twitter" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->twitter->PlaceHolder) ?>" value="<?php echo $our_teams->twitter->EditValue ?>"<?php echo $our_teams->twitter->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_twitter" name="o<?php echo $our_teams_list->RowIndex ?>_twitter" id="o<?php echo $our_teams_list->RowIndex ?>_twitter" value="<?php echo ew_HtmlEncode($our_teams->twitter->OldValue) ?>">
<?php } ?>
<?php if ($our_teams->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $our_teams->twitter->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($our_teams->twitter->ListViewValue()) && $our_teams->twitter->LinkAttributes() <> "") { ?>
<a<?php echo $our_teams->twitter->LinkAttributes() ?>><?php echo $our_teams->twitter->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $our_teams->twitter->ListViewValue() ?>
<?php } ?>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($our_teams->linkedin->Visible) { // linkedin ?>
		<td data-name="linkedin"<?php echo $our_teams->linkedin->CellAttributes() ?>>
<?php if ($our_teams->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $our_teams_list->RowCnt ?>_our_teams_linkedin" class="form-group our_teams_linkedin">
<input type="text" data-field="x_linkedin" name="x<?php echo $our_teams_list->RowIndex ?>_linkedin" id="x<?php echo $our_teams_list->RowIndex ?>_linkedin" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->linkedin->PlaceHolder) ?>" value="<?php echo $our_teams->linkedin->EditValue ?>"<?php echo $our_teams->linkedin->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_linkedin" name="o<?php echo $our_teams_list->RowIndex ?>_linkedin" id="o<?php echo $our_teams_list->RowIndex ?>_linkedin" value="<?php echo ew_HtmlEncode($our_teams->linkedin->OldValue) ?>">
<?php } ?>
<?php if ($our_teams->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $our_teams->linkedin->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($our_teams->linkedin->ListViewValue()) && $our_teams->linkedin->LinkAttributes() <> "") { ?>
<a<?php echo $our_teams->linkedin->LinkAttributes() ?>><?php echo $our_teams->linkedin->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $our_teams->linkedin->ListViewValue() ?>
<?php } ?>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($our_teams->last_modified->Visible) { // last_modified ?>
		<td data-name="last_modified"<?php echo $our_teams->last_modified->CellAttributes() ?>>
<?php if ($our_teams->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $our_teams_list->RowCnt ?>_our_teams_last_modified" class="form-group our_teams_last_modified">
<input type="text" data-field="x_last_modified" name="x<?php echo $our_teams_list->RowIndex ?>_last_modified" id="x<?php echo $our_teams_list->RowIndex ?>_last_modified" placeholder="<?php echo ew_HtmlEncode($our_teams->last_modified->PlaceHolder) ?>" value="<?php echo $our_teams->last_modified->EditValue ?>"<?php echo $our_teams->last_modified->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_last_modified" name="o<?php echo $our_teams_list->RowIndex ?>_last_modified" id="o<?php echo $our_teams_list->RowIndex ?>_last_modified" value="<?php echo ew_HtmlEncode($our_teams->last_modified->OldValue) ?>">
<?php } ?>
<?php if ($our_teams->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $our_teams->last_modified->ViewAttributes() ?>>
<?php echo $our_teams->last_modified->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$our_teams_list->ListOptions->Render("body", "right", $our_teams_list->RowCnt);
?>
	</tr>
<?php if ($our_teams->RowType == EW_ROWTYPE_ADD || $our_teams->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
four_teamslist.UpdateOpts(<?php echo $our_teams_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($our_teams->CurrentAction <> "gridadd")
		if (!$our_teams_list->Recordset->EOF) $our_teams_list->Recordset->MoveNext();
}
?>
<?php
	if ($our_teams->CurrentAction == "gridadd" || $our_teams->CurrentAction == "gridedit") {
		$our_teams_list->RowIndex = '$rowindex$';
		$our_teams_list->LoadDefaultValues();

		// Set row properties
		$our_teams->ResetAttrs();
		$our_teams->RowAttrs = array_merge($our_teams->RowAttrs, array('data-rowindex'=>$our_teams_list->RowIndex, 'id'=>'r0_our_teams', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($our_teams->RowAttrs["class"], "ewTemplate");
		$our_teams->RowType = EW_ROWTYPE_ADD;

		// Render row
		$our_teams_list->RenderRow();

		// Render list options
		$our_teams_list->RenderListOptions();
		$our_teams_list->StartRowCnt = 0;
?>
	<tr<?php echo $our_teams->RowAttributes() ?>>
<?php

// Render list options (body, left)
$our_teams_list->ListOptions->Render("body", "left", $our_teams_list->RowIndex);
?>
	<?php if ($our_teams->id->Visible) { // id ?>
		<td>
<input type="hidden" data-field="x_id" name="o<?php echo $our_teams_list->RowIndex ?>_id" id="o<?php echo $our_teams_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($our_teams->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($our_teams->image->Visible) { // image ?>
		<td>
<span id="el$rowindex$_our_teams_image" class="form-group our_teams_image">
<div id="fd_x<?php echo $our_teams_list->RowIndex ?>_image">
<span title="<?php echo $our_teams->image->FldTitle() ? $our_teams->image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($our_teams->image->ReadOnly || $our_teams->image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_image" name="x<?php echo $our_teams_list->RowIndex ?>_image" id="x<?php echo $our_teams_list->RowIndex ?>_image">
</span>
<input type="hidden" name="fn_x<?php echo $our_teams_list->RowIndex ?>_image" id= "fn_x<?php echo $our_teams_list->RowIndex ?>_image" value="<?php echo $our_teams->image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $our_teams_list->RowIndex ?>_image" id= "fa_x<?php echo $our_teams_list->RowIndex ?>_image" value="0">
<input type="hidden" name="fs_x<?php echo $our_teams_list->RowIndex ?>_image" id= "fs_x<?php echo $our_teams_list->RowIndex ?>_image" value="255">
<input type="hidden" name="fx_x<?php echo $our_teams_list->RowIndex ?>_image" id= "fx_x<?php echo $our_teams_list->RowIndex ?>_image" value="<?php echo $our_teams->image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $our_teams_list->RowIndex ?>_image" id= "fm_x<?php echo $our_teams_list->RowIndex ?>_image" value="<?php echo $our_teams->image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $our_teams_list->RowIndex ?>_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_image" name="o<?php echo $our_teams_list->RowIndex ?>_image" id="o<?php echo $our_teams_list->RowIndex ?>_image" value="<?php echo ew_HtmlEncode($our_teams->image->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($our_teams->name_en->Visible) { // name_en ?>
		<td>
<span id="el$rowindex$_our_teams_name_en" class="form-group our_teams_name_en">
<input type="text" data-field="x_name_en" name="x<?php echo $our_teams_list->RowIndex ?>_name_en" id="x<?php echo $our_teams_list->RowIndex ?>_name_en" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->name_en->PlaceHolder) ?>" value="<?php echo $our_teams->name_en->EditValue ?>"<?php echo $our_teams->name_en->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_name_en" name="o<?php echo $our_teams_list->RowIndex ?>_name_en" id="o<?php echo $our_teams_list->RowIndex ?>_name_en" value="<?php echo ew_HtmlEncode($our_teams->name_en->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($our_teams->name_ar->Visible) { // name_ar ?>
		<td>
<span id="el$rowindex$_our_teams_name_ar" class="form-group our_teams_name_ar">
<input type="text" data-field="x_name_ar" name="x<?php echo $our_teams_list->RowIndex ?>_name_ar" id="x<?php echo $our_teams_list->RowIndex ?>_name_ar" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->name_ar->PlaceHolder) ?>" value="<?php echo $our_teams->name_ar->EditValue ?>"<?php echo $our_teams->name_ar->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_name_ar" name="o<?php echo $our_teams_list->RowIndex ?>_name_ar" id="o<?php echo $our_teams_list->RowIndex ?>_name_ar" value="<?php echo ew_HtmlEncode($our_teams->name_ar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($our_teams->title_en->Visible) { // title_en ?>
		<td>
<span id="el$rowindex$_our_teams_title_en" class="form-group our_teams_title_en">
<input type="text" data-field="x_title_en" name="x<?php echo $our_teams_list->RowIndex ?>_title_en" id="x<?php echo $our_teams_list->RowIndex ?>_title_en" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->title_en->PlaceHolder) ?>" value="<?php echo $our_teams->title_en->EditValue ?>"<?php echo $our_teams->title_en->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_title_en" name="o<?php echo $our_teams_list->RowIndex ?>_title_en" id="o<?php echo $our_teams_list->RowIndex ?>_title_en" value="<?php echo ew_HtmlEncode($our_teams->title_en->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($our_teams->title_ar->Visible) { // title_ar ?>
		<td>
<span id="el$rowindex$_our_teams_title_ar" class="form-group our_teams_title_ar">
<input type="text" data-field="x_title_ar" name="x<?php echo $our_teams_list->RowIndex ?>_title_ar" id="x<?php echo $our_teams_list->RowIndex ?>_title_ar" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->title_ar->PlaceHolder) ?>" value="<?php echo $our_teams->title_ar->EditValue ?>"<?php echo $our_teams->title_ar->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_title_ar" name="o<?php echo $our_teams_list->RowIndex ?>_title_ar" id="o<?php echo $our_teams_list->RowIndex ?>_title_ar" value="<?php echo ew_HtmlEncode($our_teams->title_ar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($our_teams->bio_en->Visible) { // bio_en ?>
		<td>
<span id="el$rowindex$_our_teams_bio_en" class="form-group our_teams_bio_en">
<input type="text" data-field="x_bio_en" name="x<?php echo $our_teams_list->RowIndex ?>_bio_en" id="x<?php echo $our_teams_list->RowIndex ?>_bio_en" size="150" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->bio_en->PlaceHolder) ?>" value="<?php echo $our_teams->bio_en->EditValue ?>"<?php echo $our_teams->bio_en->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_bio_en" name="o<?php echo $our_teams_list->RowIndex ?>_bio_en" id="o<?php echo $our_teams_list->RowIndex ?>_bio_en" value="<?php echo ew_HtmlEncode($our_teams->bio_en->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($our_teams->bio_ar->Visible) { // bio_ar ?>
		<td>
<span id="el$rowindex$_our_teams_bio_ar" class="form-group our_teams_bio_ar">
<input type="text" data-field="x_bio_ar" name="x<?php echo $our_teams_list->RowIndex ?>_bio_ar" id="x<?php echo $our_teams_list->RowIndex ?>_bio_ar" size="150" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->bio_ar->PlaceHolder) ?>" value="<?php echo $our_teams->bio_ar->EditValue ?>"<?php echo $our_teams->bio_ar->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_bio_ar" name="o<?php echo $our_teams_list->RowIndex ?>_bio_ar" id="o<?php echo $our_teams_list->RowIndex ?>_bio_ar" value="<?php echo ew_HtmlEncode($our_teams->bio_ar->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($our_teams->facebook->Visible) { // facebook ?>
		<td>
<span id="el$rowindex$_our_teams_facebook" class="form-group our_teams_facebook">
<input type="text" data-field="x_facebook" name="x<?php echo $our_teams_list->RowIndex ?>_facebook" id="x<?php echo $our_teams_list->RowIndex ?>_facebook" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->facebook->PlaceHolder) ?>" value="<?php echo $our_teams->facebook->EditValue ?>"<?php echo $our_teams->facebook->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_facebook" name="o<?php echo $our_teams_list->RowIndex ?>_facebook" id="o<?php echo $our_teams_list->RowIndex ?>_facebook" value="<?php echo ew_HtmlEncode($our_teams->facebook->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($our_teams->twitter->Visible) { // twitter ?>
		<td>
<span id="el$rowindex$_our_teams_twitter" class="form-group our_teams_twitter">
<input type="text" data-field="x_twitter" name="x<?php echo $our_teams_list->RowIndex ?>_twitter" id="x<?php echo $our_teams_list->RowIndex ?>_twitter" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->twitter->PlaceHolder) ?>" value="<?php echo $our_teams->twitter->EditValue ?>"<?php echo $our_teams->twitter->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_twitter" name="o<?php echo $our_teams_list->RowIndex ?>_twitter" id="o<?php echo $our_teams_list->RowIndex ?>_twitter" value="<?php echo ew_HtmlEncode($our_teams->twitter->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($our_teams->linkedin->Visible) { // linkedin ?>
		<td>
<span id="el$rowindex$_our_teams_linkedin" class="form-group our_teams_linkedin">
<input type="text" data-field="x_linkedin" name="x<?php echo $our_teams_list->RowIndex ?>_linkedin" id="x<?php echo $our_teams_list->RowIndex ?>_linkedin" size="90" maxlength="255" placeholder="<?php echo ew_HtmlEncode($our_teams->linkedin->PlaceHolder) ?>" value="<?php echo $our_teams->linkedin->EditValue ?>"<?php echo $our_teams->linkedin->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_linkedin" name="o<?php echo $our_teams_list->RowIndex ?>_linkedin" id="o<?php echo $our_teams_list->RowIndex ?>_linkedin" value="<?php echo ew_HtmlEncode($our_teams->linkedin->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($our_teams->last_modified->Visible) { // last_modified ?>
		<td>
<span id="el$rowindex$_our_teams_last_modified" class="form-group our_teams_last_modified">
<input type="text" data-field="x_last_modified" name="x<?php echo $our_teams_list->RowIndex ?>_last_modified" id="x<?php echo $our_teams_list->RowIndex ?>_last_modified" placeholder="<?php echo ew_HtmlEncode($our_teams->last_modified->PlaceHolder) ?>" value="<?php echo $our_teams->last_modified->EditValue ?>"<?php echo $our_teams->last_modified->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_last_modified" name="o<?php echo $our_teams_list->RowIndex ?>_last_modified" id="o<?php echo $our_teams_list->RowIndex ?>_last_modified" value="<?php echo ew_HtmlEncode($our_teams->last_modified->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$our_teams_list->ListOptions->Render("body", "right", $our_teams_list->RowCnt);
?>
<script type="text/javascript">
four_teamslist.UpdateOpts(<?php echo $our_teams_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($our_teams->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $our_teams_list->FormKeyCountName ?>" id="<?php echo $our_teams_list->FormKeyCountName ?>" value="<?php echo $our_teams_list->KeyCount ?>">
<?php echo $our_teams_list->MultiSelectKey ?>
<?php } ?>
<?php if ($our_teams->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($our_teams_list->Recordset)
	$our_teams_list->Recordset->Close();
?>
<?php if ($our_teams->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($our_teams->CurrentAction <> "gridadd" && $our_teams->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($our_teams_list->Pager)) $our_teams_list->Pager = new cNumericPager($our_teams_list->StartRec, $our_teams_list->DisplayRecs, $our_teams_list->TotalRecs, $our_teams_list->RecRange) ?>
<?php if ($our_teams_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($our_teams_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_list->PageUrl() ?>start=<?php echo $our_teams_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($our_teams_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_list->PageUrl() ?>start=<?php echo $our_teams_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($our_teams_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $our_teams_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($our_teams_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_list->PageUrl() ?>start=<?php echo $our_teams_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($our_teams_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $our_teams_list->PageUrl() ?>start=<?php echo $our_teams_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $our_teams_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $our_teams_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $our_teams_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($our_teams_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="our_teams">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="10"<?php if ($our_teams_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="25"<?php if ($our_teams_list->DisplayRecs == 25) { ?> selected="selected"<?php } ?>>25</option>
<option value="50"<?php if ($our_teams_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($our_teams_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($our_teams->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($our_teams_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($our_teams_list->TotalRecs == 0 && $our_teams->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($our_teams_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($our_teams->Export == "") { ?>
<script type="text/javascript">
four_teamslistsrch.Init();
four_teamslist.Init();
</script>
<?php } ?>
<?php
$our_teams_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($our_teams->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$our_teams_list->Page_Terminate();
?>
