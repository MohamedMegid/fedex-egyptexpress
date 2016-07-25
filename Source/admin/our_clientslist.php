<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "our_clientsinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$our_clients_list = NULL; // Initialize page object first

class cour_clients_list extends cour_clients {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'our_clients';

	// Page object name
	var $PageObjName = 'our_clients_list';

	// Grid form hidden field names
	var $FormName = 'four_clientslist';
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

		// Table object (our_clients)
		if (!isset($GLOBALS["our_clients"]) || get_class($GLOBALS["our_clients"]) == "cour_clients") {
			$GLOBALS["our_clients"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["our_clients"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "our_clientsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "our_clientsdelete.php";
		$this->MultiUpdateUrl = "our_clientsupdate.php";

		// Table object (administrator)
		if (!isset($GLOBALS['administrator'])) $GLOBALS['administrator'] = new cadministrator();

		// User table object (administrator)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cadministrator();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'our_clients', TRUE);

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
		global $EW_EXPORT, $our_clients;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($our_clients);
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
		if (!ew_Empty($this->off_image->Upload->Value))
			return FALSE;
		if (!ew_Empty($this->on_image->Upload->Value))
			return FALSE;
		if ($objForm->HasValue("x_created") && $objForm->HasValue("o_created") && $this->created->CurrentValue <> $this->created->OldValue)
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
		$this->BuildBasicSearchSQL($sWhere, $this->off_image, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->on_image, $arKeywords, $type);
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
			$this->UpdateSort($this->off_image); // off_image
			$this->UpdateSort($this->on_image); // on_image
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
				$this->off_image->setSort("");
				$this->on_image->setSort("");
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
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitSelected(document.four_clientslist, '" . $this->MultiDeleteUrl . "', ewLanguage.Phrase('DeleteMultiConfirmMsg'));return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.four_clientslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"four_clientslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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
		$this->off_image->Upload->Index = $objForm->Index;
		$this->off_image->Upload->UploadFile();
		$this->off_image->CurrentValue = $this->off_image->Upload->FileName;
		$this->on_image->Upload->Index = $objForm->Index;
		$this->on_image->Upload->UploadFile();
		$this->on_image->CurrentValue = $this->on_image->Upload->FileName;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->off_image->Upload->DbValue = NULL;
		$this->off_image->OldValue = $this->off_image->Upload->DbValue;
		$this->on_image->Upload->DbValue = NULL;
		$this->on_image->OldValue = $this->on_image->Upload->DbValue;
		$this->created->CurrentValue = NULL;
		$this->created->OldValue = $this->created->CurrentValue;
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
		if (!$this->created->FldIsDetailKey) {
			$this->created->setFormValue($objForm->GetValue("x_created"));
			$this->created->CurrentValue = ew_UnFormatDateTime($this->created->CurrentValue, 7);
		}
		$this->created->setOldValue($objForm->GetValue("o_created"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->id->CurrentValue = $this->id->FormValue;
		$this->created->CurrentValue = $this->created->FormValue;
		$this->created->CurrentValue = ew_UnFormatDateTime($this->created->CurrentValue, 7);
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
		$this->off_image->Upload->DbValue = $rs->fields('off_image');
		$this->off_image->CurrentValue = $this->off_image->Upload->DbValue;
		$this->on_image->Upload->DbValue = $rs->fields('on_image');
		$this->on_image->CurrentValue = $this->on_image->Upload->DbValue;
		$this->created->setDbValue($rs->fields('created'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->off_image->Upload->DbValue = $row['off_image'];
		$this->on_image->Upload->DbValue = $row['on_image'];
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
		// off_image
		// on_image
		// created

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// off_image
			$this->off_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->off_image->Upload->DbValue)) {
				$this->off_image->ImageWidth = 150;
				$this->off_image->ImageHeight = 0;
				$this->off_image->ImageAlt = $this->off_image->FldAlt();
				$this->off_image->ViewValue = ew_UploadPathEx(FALSE, $this->off_image->UploadPath) . $this->off_image->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->off_image->ViewValue = ew_UploadPathEx(TRUE, $this->off_image->UploadPath) . $this->off_image->Upload->DbValue;
				}
			} else {
				$this->off_image->ViewValue = "";
			}
			$this->off_image->ViewCustomAttributes = "";

			// on_image
			$this->on_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->on_image->Upload->DbValue)) {
				$this->on_image->ImageWidth = 150;
				$this->on_image->ImageHeight = 0;
				$this->on_image->ImageAlt = $this->on_image->FldAlt();
				$this->on_image->ViewValue = ew_UploadPathEx(FALSE, $this->on_image->UploadPath) . $this->on_image->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->on_image->ViewValue = ew_UploadPathEx(TRUE, $this->on_image->UploadPath) . $this->on_image->Upload->DbValue;
				}
			} else {
				$this->on_image->ViewValue = "";
			}
			$this->on_image->ViewCustomAttributes = "";

			// created
			$this->created->ViewValue = $this->created->CurrentValue;
			$this->created->ViewValue = ew_FormatDateTime($this->created->ViewValue, 7);
			$this->created->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// off_image
			$this->off_image->LinkCustomAttributes = "";
			$this->off_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->off_image->Upload->DbValue)) {
				$this->off_image->HrefValue = ew_UploadPathEx(FALSE, $this->off_image->UploadPath) . $this->off_image->Upload->DbValue; // Add prefix/suffix
				$this->off_image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->off_image->HrefValue = ew_ConvertFullUrl($this->off_image->HrefValue);
			} else {
				$this->off_image->HrefValue = "";
			}
			$this->off_image->HrefValue2 = $this->off_image->UploadPath . $this->off_image->Upload->DbValue;
			$this->off_image->TooltipValue = "";
			if ($this->off_image->UseColorbox) {
				$this->off_image->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->off_image->LinkAttrs["data-rel"] = "our_clients_x" . $this->RowCnt . "_off_image";
				$this->off_image->LinkAttrs["class"] = "ewLightbox";
			}

			// on_image
			$this->on_image->LinkCustomAttributes = "";
			$this->on_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->on_image->Upload->DbValue)) {
				$this->on_image->HrefValue = ew_UploadPathEx(FALSE, $this->on_image->UploadPath) . $this->on_image->Upload->DbValue; // Add prefix/suffix
				$this->on_image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->on_image->HrefValue = ew_ConvertFullUrl($this->on_image->HrefValue);
			} else {
				$this->on_image->HrefValue = "";
			}
			$this->on_image->HrefValue2 = $this->on_image->UploadPath . $this->on_image->Upload->DbValue;
			$this->on_image->TooltipValue = "";
			if ($this->on_image->UseColorbox) {
				$this->on_image->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->on_image->LinkAttrs["data-rel"] = "our_clients_x" . $this->RowCnt . "_on_image";
				$this->on_image->LinkAttrs["class"] = "ewLightbox";
			}

			// created
			$this->created->LinkCustomAttributes = "";
			$this->created->HrefValue = "";
			$this->created->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// id
			// off_image

			$this->off_image->EditAttrs["class"] = "form-control";
			$this->off_image->EditCustomAttributes = "";
			$this->off_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->off_image->Upload->DbValue)) {
				$this->off_image->ImageWidth = 150;
				$this->off_image->ImageHeight = 0;
				$this->off_image->ImageAlt = $this->off_image->FldAlt();
				$this->off_image->EditValue = ew_UploadPathEx(FALSE, $this->off_image->UploadPath) . $this->off_image->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->off_image->EditValue = ew_UploadPathEx(TRUE, $this->off_image->UploadPath) . $this->off_image->Upload->DbValue;
				}
			} else {
				$this->off_image->EditValue = "";
			}
			if (!ew_Empty($this->off_image->CurrentValue))
				$this->off_image->Upload->FileName = $this->off_image->CurrentValue;
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->off_image, $this->RowIndex);

			// on_image
			$this->on_image->EditAttrs["class"] = "form-control";
			$this->on_image->EditCustomAttributes = "";
			$this->on_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->on_image->Upload->DbValue)) {
				$this->on_image->ImageWidth = 150;
				$this->on_image->ImageHeight = 0;
				$this->on_image->ImageAlt = $this->on_image->FldAlt();
				$this->on_image->EditValue = ew_UploadPathEx(FALSE, $this->on_image->UploadPath) . $this->on_image->Upload->DbValue;
				if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
					$this->on_image->EditValue = ew_UploadPathEx(TRUE, $this->on_image->UploadPath) . $this->on_image->Upload->DbValue;
				}
			} else {
				$this->on_image->EditValue = "";
			}
			if (!ew_Empty($this->on_image->CurrentValue))
				$this->on_image->Upload->FileName = $this->on_image->CurrentValue;
			if (is_numeric($this->RowIndex) && !$this->EventCancelled) ew_RenderUploadField($this->on_image, $this->RowIndex);

			// created
			$this->created->EditAttrs["class"] = "form-control";
			$this->created->EditCustomAttributes = "";
			$this->created->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->created->CurrentValue, 7));
			$this->created->PlaceHolder = ew_RemoveHtml($this->created->FldCaption());

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// off_image
			$this->off_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->off_image->Upload->DbValue)) {
				$this->off_image->HrefValue = ew_UploadPathEx(FALSE, $this->off_image->UploadPath) . $this->off_image->Upload->DbValue; // Add prefix/suffix
				$this->off_image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->off_image->HrefValue = ew_ConvertFullUrl($this->off_image->HrefValue);
			} else {
				$this->off_image->HrefValue = "";
			}
			$this->off_image->HrefValue2 = $this->off_image->UploadPath . $this->off_image->Upload->DbValue;

			// on_image
			$this->on_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->on_image->Upload->DbValue)) {
				$this->on_image->HrefValue = ew_UploadPathEx(FALSE, $this->on_image->UploadPath) . $this->on_image->Upload->DbValue; // Add prefix/suffix
				$this->on_image->LinkAttrs["target"] = ""; // Add target
				if ($this->Export <> "") $this->on_image->HrefValue = ew_ConvertFullUrl($this->on_image->HrefValue);
			} else {
				$this->on_image->HrefValue = "";
			}
			$this->on_image->HrefValue2 = $this->on_image->UploadPath . $this->on_image->Upload->DbValue;

			// created
			$this->created->HrefValue = "";
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
		if ($this->off_image->Upload->FileName == "" && !$this->off_image->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->off_image->FldCaption(), $this->off_image->ReqErrMsg));
		}
		if ($this->on_image->Upload->FileName == "" && !$this->on_image->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->on_image->FldCaption(), $this->on_image->ReqErrMsg));
		}
		if (!ew_CheckEuroDate($this->created->FormValue)) {
			ew_AddMessage($gsFormError, $this->created->FldErrMsg());
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
				$this->off_image->OldUploadPath = '../webroot/uploads/images/';
				@unlink(ew_UploadPathEx(TRUE, $this->off_image->OldUploadPath) . $row['off_image']);
				$this->on_image->OldUploadPath = '../webroot/uploads/images/';
				@unlink(ew_UploadPathEx(TRUE, $this->on_image->OldUploadPath) . $row['on_image']);
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
			$this->off_image->OldUploadPath = '../webroot/uploads/images/';
			$this->off_image->UploadPath = $this->off_image->OldUploadPath;
			$this->on_image->OldUploadPath = '../webroot/uploads/images/';
			$this->on_image->UploadPath = $this->on_image->OldUploadPath;
		}
		$rsnew = array();

		// off_image
		if (!$this->off_image->Upload->KeepFile) {
			$this->off_image->Upload->DbValue = ""; // No need to delete old file
			if ($this->off_image->Upload->FileName == "") {
				$rsnew['off_image'] = NULL;
			} else {
				$rsnew['off_image'] = $this->off_image->Upload->FileName;
			}
		}

		// on_image
		if (!$this->on_image->Upload->KeepFile) {
			$this->on_image->Upload->DbValue = ""; // No need to delete old file
			if ($this->on_image->Upload->FileName == "") {
				$rsnew['on_image'] = NULL;
			} else {
				$rsnew['on_image'] = $this->on_image->Upload->FileName;
			}
		}

		// created
		$this->created->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->created->CurrentValue, 7), NULL, FALSE);
		if (!$this->off_image->Upload->KeepFile) {
			$this->off_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->off_image->Upload->Value)) {
				if ($this->off_image->Upload->FileName == $this->off_image->Upload->DbValue) { // Overwrite if same file name
					$this->off_image->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['off_image'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->off_image->UploadPath), $rsnew['off_image']); // Get new file name
				}
			}
		}
		if (!$this->on_image->Upload->KeepFile) {
			$this->on_image->UploadPath = '../webroot/uploads/images/';
			if (!ew_Empty($this->on_image->Upload->Value)) {
				if ($this->on_image->Upload->FileName == $this->on_image->Upload->DbValue) { // Overwrite if same file name
					$this->on_image->Upload->DbValue = ""; // No need to delete any more
				} else {
					$rsnew['on_image'] = ew_UploadFileNameEx(ew_UploadPathEx(TRUE, $this->on_image->UploadPath), $rsnew['on_image']); // Get new file name
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
				if (!$this->off_image->Upload->KeepFile) {
					if (!ew_Empty($this->off_image->Upload->Value)) {
						$this->off_image->Upload->SaveToFile($this->off_image->UploadPath, $rsnew['off_image'], TRUE);
					}
					if ($this->off_image->Upload->DbValue <> "")
						@unlink(ew_UploadPathEx(TRUE, $this->off_image->OldUploadPath) . $this->off_image->Upload->DbValue);
				}
				if (!$this->on_image->Upload->KeepFile) {
					if (!ew_Empty($this->on_image->Upload->Value)) {
						$this->on_image->Upload->SaveToFile($this->on_image->UploadPath, $rsnew['on_image'], TRUE);
					}
					if ($this->on_image->Upload->DbValue <> "")
						@unlink(ew_UploadPathEx(TRUE, $this->on_image->OldUploadPath) . $this->on_image->Upload->DbValue);
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

		// off_image
		ew_CleanUploadTempPath($this->off_image, $this->off_image->Upload->Index);

		// on_image
		ew_CleanUploadTempPath($this->on_image, $this->on_image->Upload->Index);
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
		$item->Body = "<button id=\"emf_our_clients\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_our_clients',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.four_clientslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($our_clients_list)) $our_clients_list = new cour_clients_list();

// Page init
$our_clients_list->Page_Init();

// Page main
$our_clients_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$our_clients_list->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<?php if ($our_clients->Export == "") { ?>
<script type="text/javascript">

// Page object
var our_clients_list = new ew_Page("our_clients_list");
our_clients_list.PageID = "list"; // Page ID
var EW_PAGE_ID = our_clients_list.PageID; // For backward compatibility

// Form object
var four_clientslist = new ew_Form("four_clientslist");
four_clientslist.FormKeyCountName = '<?php echo $our_clients_list->FormKeyCountName ?>';

// Validate form
four_clientslist.Validate = function() {
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
			felm = this.GetElements("x" + infix + "_off_image");
			elm = this.GetElements("fn_x" + infix + "_off_image");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $our_clients->off_image->FldCaption(), $our_clients->off_image->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_on_image");
			elm = this.GetElements("fn_x" + infix + "_on_image");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $our_clients->on_image->FldCaption(), $our_clients->on_image->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_created");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($our_clients->created->FldErrMsg()) ?>");

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
four_clientslist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "off_image", false)) return false;
	if (ew_ValueChanged(fobj, infix, "on_image", false)) return false;
	if (ew_ValueChanged(fobj, infix, "created", false)) return false;
	return true;
}

// Form_CustomValidate event
four_clientslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
four_clientslist.ValidateRequired = true;
<?php } else { ?>
four_clientslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var four_clientslistsrch = new ew_Form("four_clientslistsrch");

// Init search panel as collapsed
if (four_clientslistsrch) four_clientslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($our_clients->Export == "") { ?>
<div class="ewToolbar">
<?php if ($our_clients->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($our_clients_list->TotalRecs > 0 && $our_clients_list->ExportOptions->Visible()) { ?>
<?php $our_clients_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($our_clients_list->SearchOptions->Visible()) { ?>
<?php $our_clients_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($our_clients->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
if ($our_clients->CurrentAction == "gridadd") {
	$our_clients->CurrentFilter = "0=1";
	$our_clients_list->StartRec = 1;
	$our_clients_list->DisplayRecs = $our_clients->GridAddRowCount;
	$our_clients_list->TotalRecs = $our_clients_list->DisplayRecs;
	$our_clients_list->StopRec = $our_clients_list->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$our_clients_list->TotalRecs = $our_clients->SelectRecordCount();
	} else {
		if ($our_clients_list->Recordset = $our_clients_list->LoadRecordset())
			$our_clients_list->TotalRecs = $our_clients_list->Recordset->RecordCount();
	}
	$our_clients_list->StartRec = 1;
	if ($our_clients_list->DisplayRecs <= 0 || ($our_clients->Export <> "" && $our_clients->ExportAll)) // Display all records
		$our_clients_list->DisplayRecs = $our_clients_list->TotalRecs;
	if (!($our_clients->Export <> "" && $our_clients->ExportAll))
		$our_clients_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$our_clients_list->Recordset = $our_clients_list->LoadRecordset($our_clients_list->StartRec-1, $our_clients_list->DisplayRecs);

	// Set no record found message
	if ($our_clients->CurrentAction == "" && $our_clients_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$our_clients_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($our_clients_list->SearchWhere == "0=101")
			$our_clients_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$our_clients_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$our_clients_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($our_clients->Export == "" && $our_clients->CurrentAction == "") { ?>
<form name="four_clientslistsrch" id="four_clientslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($our_clients_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="four_clientslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="our_clients">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($our_clients_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($our_clients_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $our_clients_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($our_clients_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($our_clients_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($our_clients_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($our_clients_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $our_clients_list->ShowPageHeader(); ?>
<?php
$our_clients_list->ShowMessage();
?>
<?php if ($our_clients_list->TotalRecs > 0 || $our_clients->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($our_clients->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($our_clients->CurrentAction <> "gridadd" && $our_clients->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($our_clients_list->Pager)) $our_clients_list->Pager = new cNumericPager($our_clients_list->StartRec, $our_clients_list->DisplayRecs, $our_clients_list->TotalRecs, $our_clients_list->RecRange) ?>
<?php if ($our_clients_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($our_clients_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $our_clients_list->PageUrl() ?>start=<?php echo $our_clients_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($our_clients_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $our_clients_list->PageUrl() ?>start=<?php echo $our_clients_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($our_clients_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $our_clients_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($our_clients_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $our_clients_list->PageUrl() ?>start=<?php echo $our_clients_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($our_clients_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $our_clients_list->PageUrl() ?>start=<?php echo $our_clients_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $our_clients_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $our_clients_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $our_clients_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($our_clients_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="our_clients">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="10"<?php if ($our_clients_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="25"<?php if ($our_clients_list->DisplayRecs == 25) { ?> selected="selected"<?php } ?>>25</option>
<option value="50"<?php if ($our_clients_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($our_clients_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($our_clients->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($our_clients_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="four_clientslist" id="four_clientslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($our_clients_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $our_clients_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="our_clients">
<div id="gmp_our_clients" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($our_clients_list->TotalRecs > 0) { ?>
<table id="tbl_our_clientslist" class="table ewTable">
<?php echo $our_clients->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$our_clients_list->RenderListOptions();

// Render list options (header, left)
$our_clients_list->ListOptions->Render("header", "left");
?>
<?php if ($our_clients->id->Visible) { // id ?>
	<?php if ($our_clients->SortUrl($our_clients->id) == "") { ?>
		<th data-name="id"><div id="elh_our_clients_id" class="our_clients_id"><div class="ewTableHeaderCaption"><?php echo $our_clients->id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_clients->SortUrl($our_clients->id) ?>',1);"><div id="elh_our_clients_id" class="our_clients_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_clients->id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($our_clients->id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_clients->id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($our_clients->off_image->Visible) { // off_image ?>
	<?php if ($our_clients->SortUrl($our_clients->off_image) == "") { ?>
		<th data-name="off_image"><div id="elh_our_clients_off_image" class="our_clients_off_image"><div class="ewTableHeaderCaption"><?php echo $our_clients->off_image->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="off_image"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_clients->SortUrl($our_clients->off_image) ?>',1);"><div id="elh_our_clients_off_image" class="our_clients_off_image">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_clients->off_image->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($our_clients->off_image->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_clients->off_image->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($our_clients->on_image->Visible) { // on_image ?>
	<?php if ($our_clients->SortUrl($our_clients->on_image) == "") { ?>
		<th data-name="on_image"><div id="elh_our_clients_on_image" class="our_clients_on_image"><div class="ewTableHeaderCaption"><?php echo $our_clients->on_image->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="on_image"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_clients->SortUrl($our_clients->on_image) ?>',1);"><div id="elh_our_clients_on_image" class="our_clients_on_image">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_clients->on_image->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($our_clients->on_image->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_clients->on_image->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($our_clients->created->Visible) { // created ?>
	<?php if ($our_clients->SortUrl($our_clients->created) == "") { ?>
		<th data-name="created"><div id="elh_our_clients_created" class="our_clients_created"><div class="ewTableHeaderCaption"><?php echo $our_clients->created->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="created"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $our_clients->SortUrl($our_clients->created) ?>',1);"><div id="elh_our_clients_created" class="our_clients_created">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $our_clients->created->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($our_clients->created->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($our_clients->created->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$our_clients_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($our_clients->ExportAll && $our_clients->Export <> "") {
	$our_clients_list->StopRec = $our_clients_list->TotalRecs;
} else {

	// Set the last record to display
	if ($our_clients_list->TotalRecs > $our_clients_list->StartRec + $our_clients_list->DisplayRecs - 1)
		$our_clients_list->StopRec = $our_clients_list->StartRec + $our_clients_list->DisplayRecs - 1;
	else
		$our_clients_list->StopRec = $our_clients_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($our_clients_list->FormKeyCountName) && ($our_clients->CurrentAction == "gridadd" || $our_clients->CurrentAction == "gridedit" || $our_clients->CurrentAction == "F")) {
		$our_clients_list->KeyCount = $objForm->GetValue($our_clients_list->FormKeyCountName);
		$our_clients_list->StopRec = $our_clients_list->StartRec + $our_clients_list->KeyCount - 1;
	}
}
$our_clients_list->RecCnt = $our_clients_list->StartRec - 1;
if ($our_clients_list->Recordset && !$our_clients_list->Recordset->EOF) {
	$our_clients_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $our_clients_list->StartRec > 1)
		$our_clients_list->Recordset->Move($our_clients_list->StartRec - 1);
} elseif (!$our_clients->AllowAddDeleteRow && $our_clients_list->StopRec == 0) {
	$our_clients_list->StopRec = $our_clients->GridAddRowCount;
}

// Initialize aggregate
$our_clients->RowType = EW_ROWTYPE_AGGREGATEINIT;
$our_clients->ResetAttrs();
$our_clients_list->RenderRow();
if ($our_clients->CurrentAction == "gridadd")
	$our_clients_list->RowIndex = 0;
while ($our_clients_list->RecCnt < $our_clients_list->StopRec) {
	$our_clients_list->RecCnt++;
	if (intval($our_clients_list->RecCnt) >= intval($our_clients_list->StartRec)) {
		$our_clients_list->RowCnt++;
		if ($our_clients->CurrentAction == "gridadd" || $our_clients->CurrentAction == "gridedit" || $our_clients->CurrentAction == "F") {
			$our_clients_list->RowIndex++;
			$objForm->Index = $our_clients_list->RowIndex;
			if ($objForm->HasValue($our_clients_list->FormActionName))
				$our_clients_list->RowAction = strval($objForm->GetValue($our_clients_list->FormActionName));
			elseif ($our_clients->CurrentAction == "gridadd")
				$our_clients_list->RowAction = "insert";
			else
				$our_clients_list->RowAction = "";
		}

		// Set up key count
		$our_clients_list->KeyCount = $our_clients_list->RowIndex;

		// Init row class and style
		$our_clients->ResetAttrs();
		$our_clients->CssClass = "";
		if ($our_clients->CurrentAction == "gridadd") {
			$our_clients_list->LoadDefaultValues(); // Load default values
		} else {
			$our_clients_list->LoadRowValues($our_clients_list->Recordset); // Load row values
		}
		$our_clients->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($our_clients->CurrentAction == "gridadd") // Grid add
			$our_clients->RowType = EW_ROWTYPE_ADD; // Render add
		if ($our_clients->CurrentAction == "gridadd" && $our_clients->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$our_clients_list->RestoreCurrentRowFormValues($our_clients_list->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$our_clients->RowAttrs = array_merge($our_clients->RowAttrs, array('data-rowindex'=>$our_clients_list->RowCnt, 'id'=>'r' . $our_clients_list->RowCnt . '_our_clients', 'data-rowtype'=>$our_clients->RowType));

		// Render row
		$our_clients_list->RenderRow();

		// Render list options
		$our_clients_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($our_clients_list->RowAction <> "delete" && $our_clients_list->RowAction <> "insertdelete" && !($our_clients_list->RowAction == "insert" && $our_clients->CurrentAction == "F" && $our_clients_list->EmptyRow())) {
?>
	<tr<?php echo $our_clients->RowAttributes() ?>>
<?php

// Render list options (body, left)
$our_clients_list->ListOptions->Render("body", "left", $our_clients_list->RowCnt);
?>
	<?php if ($our_clients->id->Visible) { // id ?>
		<td data-name="id"<?php echo $our_clients->id->CellAttributes() ?>>
<?php if ($our_clients->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-field="x_id" name="o<?php echo $our_clients_list->RowIndex ?>_id" id="o<?php echo $our_clients_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($our_clients->id->OldValue) ?>">
<?php } ?>
<?php if ($our_clients->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $our_clients->id->ViewAttributes() ?>>
<?php echo $our_clients->id->ListViewValue() ?></span>
<?php } ?>
<a id="<?php echo $our_clients_list->PageObjName . "_row_" . $our_clients_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($our_clients->off_image->Visible) { // off_image ?>
		<td data-name="off_image"<?php echo $our_clients->off_image->CellAttributes() ?>>
<?php if ($our_clients->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $our_clients_list->RowCnt ?>_our_clients_off_image" class="form-group our_clients_off_image">
<div id="fd_x<?php echo $our_clients_list->RowIndex ?>_off_image">
<span title="<?php echo $our_clients->off_image->FldTitle() ? $our_clients->off_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($our_clients->off_image->ReadOnly || $our_clients->off_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_off_image" name="x<?php echo $our_clients_list->RowIndex ?>_off_image" id="x<?php echo $our_clients_list->RowIndex ?>_off_image">
</span>
<input type="hidden" name="fn_x<?php echo $our_clients_list->RowIndex ?>_off_image" id= "fn_x<?php echo $our_clients_list->RowIndex ?>_off_image" value="<?php echo $our_clients->off_image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $our_clients_list->RowIndex ?>_off_image" id= "fa_x<?php echo $our_clients_list->RowIndex ?>_off_image" value="0">
<input type="hidden" name="fs_x<?php echo $our_clients_list->RowIndex ?>_off_image" id= "fs_x<?php echo $our_clients_list->RowIndex ?>_off_image" value="255">
<input type="hidden" name="fx_x<?php echo $our_clients_list->RowIndex ?>_off_image" id= "fx_x<?php echo $our_clients_list->RowIndex ?>_off_image" value="<?php echo $our_clients->off_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $our_clients_list->RowIndex ?>_off_image" id= "fm_x<?php echo $our_clients_list->RowIndex ?>_off_image" value="<?php echo $our_clients->off_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $our_clients_list->RowIndex ?>_off_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_off_image" name="o<?php echo $our_clients_list->RowIndex ?>_off_image" id="o<?php echo $our_clients_list->RowIndex ?>_off_image" value="<?php echo ew_HtmlEncode($our_clients->off_image->OldValue) ?>">
<?php } ?>
<?php if ($our_clients->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span>
<?php echo ew_GetFileViewTag($our_clients->off_image, $our_clients->off_image->ListViewValue()) ?>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($our_clients->on_image->Visible) { // on_image ?>
		<td data-name="on_image"<?php echo $our_clients->on_image->CellAttributes() ?>>
<?php if ($our_clients->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $our_clients_list->RowCnt ?>_our_clients_on_image" class="form-group our_clients_on_image">
<div id="fd_x<?php echo $our_clients_list->RowIndex ?>_on_image">
<span title="<?php echo $our_clients->on_image->FldTitle() ? $our_clients->on_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($our_clients->on_image->ReadOnly || $our_clients->on_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_on_image" name="x<?php echo $our_clients_list->RowIndex ?>_on_image" id="x<?php echo $our_clients_list->RowIndex ?>_on_image">
</span>
<input type="hidden" name="fn_x<?php echo $our_clients_list->RowIndex ?>_on_image" id= "fn_x<?php echo $our_clients_list->RowIndex ?>_on_image" value="<?php echo $our_clients->on_image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $our_clients_list->RowIndex ?>_on_image" id= "fa_x<?php echo $our_clients_list->RowIndex ?>_on_image" value="0">
<input type="hidden" name="fs_x<?php echo $our_clients_list->RowIndex ?>_on_image" id= "fs_x<?php echo $our_clients_list->RowIndex ?>_on_image" value="255">
<input type="hidden" name="fx_x<?php echo $our_clients_list->RowIndex ?>_on_image" id= "fx_x<?php echo $our_clients_list->RowIndex ?>_on_image" value="<?php echo $our_clients->on_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $our_clients_list->RowIndex ?>_on_image" id= "fm_x<?php echo $our_clients_list->RowIndex ?>_on_image" value="<?php echo $our_clients->on_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $our_clients_list->RowIndex ?>_on_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_on_image" name="o<?php echo $our_clients_list->RowIndex ?>_on_image" id="o<?php echo $our_clients_list->RowIndex ?>_on_image" value="<?php echo ew_HtmlEncode($our_clients->on_image->OldValue) ?>">
<?php } ?>
<?php if ($our_clients->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span>
<?php echo ew_GetFileViewTag($our_clients->on_image, $our_clients->on_image->ListViewValue()) ?>
</span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($our_clients->created->Visible) { // created ?>
		<td data-name="created"<?php echo $our_clients->created->CellAttributes() ?>>
<?php if ($our_clients->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $our_clients_list->RowCnt ?>_our_clients_created" class="form-group our_clients_created">
<input type="text" data-field="x_created" name="x<?php echo $our_clients_list->RowIndex ?>_created" id="x<?php echo $our_clients_list->RowIndex ?>_created" placeholder="<?php echo ew_HtmlEncode($our_clients->created->PlaceHolder) ?>" value="<?php echo $our_clients->created->EditValue ?>"<?php echo $our_clients->created->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_created" name="o<?php echo $our_clients_list->RowIndex ?>_created" id="o<?php echo $our_clients_list->RowIndex ?>_created" value="<?php echo ew_HtmlEncode($our_clients->created->OldValue) ?>">
<?php } ?>
<?php if ($our_clients->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $our_clients->created->ViewAttributes() ?>>
<?php echo $our_clients->created->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$our_clients_list->ListOptions->Render("body", "right", $our_clients_list->RowCnt);
?>
	</tr>
<?php if ($our_clients->RowType == EW_ROWTYPE_ADD || $our_clients->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
four_clientslist.UpdateOpts(<?php echo $our_clients_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($our_clients->CurrentAction <> "gridadd")
		if (!$our_clients_list->Recordset->EOF) $our_clients_list->Recordset->MoveNext();
}
?>
<?php
	if ($our_clients->CurrentAction == "gridadd" || $our_clients->CurrentAction == "gridedit") {
		$our_clients_list->RowIndex = '$rowindex$';
		$our_clients_list->LoadDefaultValues();

		// Set row properties
		$our_clients->ResetAttrs();
		$our_clients->RowAttrs = array_merge($our_clients->RowAttrs, array('data-rowindex'=>$our_clients_list->RowIndex, 'id'=>'r0_our_clients', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($our_clients->RowAttrs["class"], "ewTemplate");
		$our_clients->RowType = EW_ROWTYPE_ADD;

		// Render row
		$our_clients_list->RenderRow();

		// Render list options
		$our_clients_list->RenderListOptions();
		$our_clients_list->StartRowCnt = 0;
?>
	<tr<?php echo $our_clients->RowAttributes() ?>>
<?php

// Render list options (body, left)
$our_clients_list->ListOptions->Render("body", "left", $our_clients_list->RowIndex);
?>
	<?php if ($our_clients->id->Visible) { // id ?>
		<td>
<input type="hidden" data-field="x_id" name="o<?php echo $our_clients_list->RowIndex ?>_id" id="o<?php echo $our_clients_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($our_clients->id->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($our_clients->off_image->Visible) { // off_image ?>
		<td>
<span id="el$rowindex$_our_clients_off_image" class="form-group our_clients_off_image">
<div id="fd_x<?php echo $our_clients_list->RowIndex ?>_off_image">
<span title="<?php echo $our_clients->off_image->FldTitle() ? $our_clients->off_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($our_clients->off_image->ReadOnly || $our_clients->off_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_off_image" name="x<?php echo $our_clients_list->RowIndex ?>_off_image" id="x<?php echo $our_clients_list->RowIndex ?>_off_image">
</span>
<input type="hidden" name="fn_x<?php echo $our_clients_list->RowIndex ?>_off_image" id= "fn_x<?php echo $our_clients_list->RowIndex ?>_off_image" value="<?php echo $our_clients->off_image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $our_clients_list->RowIndex ?>_off_image" id= "fa_x<?php echo $our_clients_list->RowIndex ?>_off_image" value="0">
<input type="hidden" name="fs_x<?php echo $our_clients_list->RowIndex ?>_off_image" id= "fs_x<?php echo $our_clients_list->RowIndex ?>_off_image" value="255">
<input type="hidden" name="fx_x<?php echo $our_clients_list->RowIndex ?>_off_image" id= "fx_x<?php echo $our_clients_list->RowIndex ?>_off_image" value="<?php echo $our_clients->off_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $our_clients_list->RowIndex ?>_off_image" id= "fm_x<?php echo $our_clients_list->RowIndex ?>_off_image" value="<?php echo $our_clients->off_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $our_clients_list->RowIndex ?>_off_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_off_image" name="o<?php echo $our_clients_list->RowIndex ?>_off_image" id="o<?php echo $our_clients_list->RowIndex ?>_off_image" value="<?php echo ew_HtmlEncode($our_clients->off_image->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($our_clients->on_image->Visible) { // on_image ?>
		<td>
<span id="el$rowindex$_our_clients_on_image" class="form-group our_clients_on_image">
<div id="fd_x<?php echo $our_clients_list->RowIndex ?>_on_image">
<span title="<?php echo $our_clients->on_image->FldTitle() ? $our_clients->on_image->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($our_clients->on_image->ReadOnly || $our_clients->on_image->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-field="x_on_image" name="x<?php echo $our_clients_list->RowIndex ?>_on_image" id="x<?php echo $our_clients_list->RowIndex ?>_on_image">
</span>
<input type="hidden" name="fn_x<?php echo $our_clients_list->RowIndex ?>_on_image" id= "fn_x<?php echo $our_clients_list->RowIndex ?>_on_image" value="<?php echo $our_clients->on_image->Upload->FileName ?>">
<input type="hidden" name="fa_x<?php echo $our_clients_list->RowIndex ?>_on_image" id= "fa_x<?php echo $our_clients_list->RowIndex ?>_on_image" value="0">
<input type="hidden" name="fs_x<?php echo $our_clients_list->RowIndex ?>_on_image" id= "fs_x<?php echo $our_clients_list->RowIndex ?>_on_image" value="255">
<input type="hidden" name="fx_x<?php echo $our_clients_list->RowIndex ?>_on_image" id= "fx_x<?php echo $our_clients_list->RowIndex ?>_on_image" value="<?php echo $our_clients->on_image->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?php echo $our_clients_list->RowIndex ?>_on_image" id= "fm_x<?php echo $our_clients_list->RowIndex ?>_on_image" value="<?php echo $our_clients->on_image->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?php echo $our_clients_list->RowIndex ?>_on_image" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-field="x_on_image" name="o<?php echo $our_clients_list->RowIndex ?>_on_image" id="o<?php echo $our_clients_list->RowIndex ?>_on_image" value="<?php echo ew_HtmlEncode($our_clients->on_image->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($our_clients->created->Visible) { // created ?>
		<td>
<span id="el$rowindex$_our_clients_created" class="form-group our_clients_created">
<input type="text" data-field="x_created" name="x<?php echo $our_clients_list->RowIndex ?>_created" id="x<?php echo $our_clients_list->RowIndex ?>_created" placeholder="<?php echo ew_HtmlEncode($our_clients->created->PlaceHolder) ?>" value="<?php echo $our_clients->created->EditValue ?>"<?php echo $our_clients->created->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_created" name="o<?php echo $our_clients_list->RowIndex ?>_created" id="o<?php echo $our_clients_list->RowIndex ?>_created" value="<?php echo ew_HtmlEncode($our_clients->created->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$our_clients_list->ListOptions->Render("body", "right", $our_clients_list->RowCnt);
?>
<script type="text/javascript">
four_clientslist.UpdateOpts(<?php echo $our_clients_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($our_clients->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $our_clients_list->FormKeyCountName ?>" id="<?php echo $our_clients_list->FormKeyCountName ?>" value="<?php echo $our_clients_list->KeyCount ?>">
<?php echo $our_clients_list->MultiSelectKey ?>
<?php } ?>
<?php if ($our_clients->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($our_clients_list->Recordset)
	$our_clients_list->Recordset->Close();
?>
<?php if ($our_clients->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($our_clients->CurrentAction <> "gridadd" && $our_clients->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($our_clients_list->Pager)) $our_clients_list->Pager = new cNumericPager($our_clients_list->StartRec, $our_clients_list->DisplayRecs, $our_clients_list->TotalRecs, $our_clients_list->RecRange) ?>
<?php if ($our_clients_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($our_clients_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $our_clients_list->PageUrl() ?>start=<?php echo $our_clients_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($our_clients_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $our_clients_list->PageUrl() ?>start=<?php echo $our_clients_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($our_clients_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $our_clients_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($our_clients_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $our_clients_list->PageUrl() ?>start=<?php echo $our_clients_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($our_clients_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $our_clients_list->PageUrl() ?>start=<?php echo $our_clients_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $our_clients_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $our_clients_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $our_clients_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($our_clients_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="our_clients">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="10"<?php if ($our_clients_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="25"<?php if ($our_clients_list->DisplayRecs == 25) { ?> selected="selected"<?php } ?>>25</option>
<option value="50"<?php if ($our_clients_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($our_clients_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($our_clients->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($our_clients_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($our_clients_list->TotalRecs == 0 && $our_clients->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($our_clients_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($our_clients->Export == "") { ?>
<script type="text/javascript">
four_clientslistsrch.Init();
four_clientslist.Init();
</script>
<?php } ?>
<?php
$our_clients_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($our_clients->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$our_clients_list->Page_Terminate();
?>
