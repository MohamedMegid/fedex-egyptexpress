<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
$EW_RELATIVE_PATH = "";
?>
<?php include_once $EW_RELATIVE_PATH . "ewcfg11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "ewmysql11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "phpfn11.php" ?>
<?php include_once $EW_RELATIVE_PATH . "zones_pricesinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "administratorinfo.php" ?>
<?php include_once $EW_RELATIVE_PATH . "userfn11.php" ?>
<?php

//
// Page class
//

$zones_prices_list = NULL; // Initialize page object first

class czones_prices_list extends czones_prices {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{51AFFC50-D173-42CF-8568-DFD13A65B5CC}";

	// Table name
	var $TableName = 'zones_prices';

	// Page object name
	var $PageObjName = 'zones_prices_list';

	// Grid form hidden field names
	var $FormName = 'fzones_priceslist';
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

		// Table object (zones_prices)
		if (!isset($GLOBALS["zones_prices"]) || get_class($GLOBALS["zones_prices"]) == "czones_prices") {
			$GLOBALS["zones_prices"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["zones_prices"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "zones_pricesadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "zones_pricesdelete.php";
		$this->MultiUpdateUrl = "zones_pricesupdate.php";

		// Table object (administrator)
		if (!isset($GLOBALS['administrator'])) $GLOBALS['administrator'] = new cadministrator();

		// User table object (administrator)
		if (!isset($GLOBALS["UserTable"])) $GLOBALS["UserTable"] = new cadministrator();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'zones_prices', TRUE);

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
		global $EW_EXPORT, $zones_prices;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($zones_prices);
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
		$this->zone1->FormValue = ""; // Clear form value
		$this->zone2->FormValue = ""; // Clear form value
		$this->zone3->FormValue = ""; // Clear form value
		$this->zone4->FormValue = ""; // Clear form value
		$this->zone5->FormValue = ""; // Clear form value
		$this->zone6->FormValue = ""; // Clear form value
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
			$this->weight->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->weight->FormValue))
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
					$sKey .= $this->weight->CurrentValue;

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
		if ($objForm->HasValue("x_weight") && $objForm->HasValue("o_weight") && $this->weight->CurrentValue <> $this->weight->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_zone1") && $objForm->HasValue("o_zone1") && $this->zone1->CurrentValue <> $this->zone1->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_zone2") && $objForm->HasValue("o_zone2") && $this->zone2->CurrentValue <> $this->zone2->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_zone3") && $objForm->HasValue("o_zone3") && $this->zone3->CurrentValue <> $this->zone3->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_zone4") && $objForm->HasValue("o_zone4") && $this->zone4->CurrentValue <> $this->zone4->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_zone5") && $objForm->HasValue("o_zone5") && $this->zone5->CurrentValue <> $this->zone5->OldValue)
			return FALSE;
		if ($objForm->HasValue("x_zone6") && $objForm->HasValue("o_zone6") && $this->zone6->CurrentValue <> $this->zone6->OldValue)
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
		$this->BuildBasicSearchSQL($sWhere, $this->weight, $arKeywords, $type);
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
			$this->UpdateSort($this->weight); // weight
			$this->UpdateSort($this->zone1); // zone1
			$this->UpdateSort($this->zone2); // zone2
			$this->UpdateSort($this->zone3); // zone3
			$this->UpdateSort($this->zone4); // zone4
			$this->UpdateSort($this->zone5); // zone5
			$this->UpdateSort($this->zone6); // zone6
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
				$this->weight->setSort("");
				$this->zone1->setSort("");
				$this->zone2->setSort("");
				$this->zone3->setSort("");
				$this->zone4->setSort("");
				$this->zone5->setSort("");
				$this->zone6->setSort("");
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

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" value=\"" . ew_HtmlEncode($this->weight->CurrentValue) . "\" onclick='ew_ClickMultiCheckbox(event, this);'>";
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
		$item->Body = "<a class=\"ewAction ewMultiDelete\" title=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteSelectedLink")) . "\" href=\"\" onclick=\"ew_SubmitSelected(document.fzones_priceslist, '" . $this->MultiDeleteUrl . "', ewLanguage.Phrase('DeleteMultiConfirmMsg'));return false;\">" . $Language->Phrase("DeleteSelectedLink") . "</a>";
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
				$item->Body = "<a class=\"ewAction ewCustomAction\" href=\"\" onclick=\"ew_SubmitSelected(document.fzones_priceslist, '" . ew_CurrentUrl() . "', null, '" . $action . "');return false;\">" . $name . "</a>";
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fzones_priceslistsrch\">" . $Language->Phrase("SearchBtn") . "</button>";
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

	// Load default values
	function LoadDefaultValues() {
		$this->weight->CurrentValue = NULL;
		$this->weight->OldValue = $this->weight->CurrentValue;
		$this->zone1->CurrentValue = NULL;
		$this->zone1->OldValue = $this->zone1->CurrentValue;
		$this->zone2->CurrentValue = NULL;
		$this->zone2->OldValue = $this->zone2->CurrentValue;
		$this->zone3->CurrentValue = NULL;
		$this->zone3->OldValue = $this->zone3->CurrentValue;
		$this->zone4->CurrentValue = NULL;
		$this->zone4->OldValue = $this->zone4->CurrentValue;
		$this->zone5->CurrentValue = NULL;
		$this->zone5->OldValue = $this->zone5->CurrentValue;
		$this->zone6->CurrentValue = NULL;
		$this->zone6->OldValue = $this->zone6->CurrentValue;
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
		if (!$this->weight->FldIsDetailKey) {
			$this->weight->setFormValue($objForm->GetValue("x_weight"));
		}
		$this->weight->setOldValue($objForm->GetValue("o_weight"));
		if (!$this->zone1->FldIsDetailKey) {
			$this->zone1->setFormValue($objForm->GetValue("x_zone1"));
		}
		$this->zone1->setOldValue($objForm->GetValue("o_zone1"));
		if (!$this->zone2->FldIsDetailKey) {
			$this->zone2->setFormValue($objForm->GetValue("x_zone2"));
		}
		$this->zone2->setOldValue($objForm->GetValue("o_zone2"));
		if (!$this->zone3->FldIsDetailKey) {
			$this->zone3->setFormValue($objForm->GetValue("x_zone3"));
		}
		$this->zone3->setOldValue($objForm->GetValue("o_zone3"));
		if (!$this->zone4->FldIsDetailKey) {
			$this->zone4->setFormValue($objForm->GetValue("x_zone4"));
		}
		$this->zone4->setOldValue($objForm->GetValue("o_zone4"));
		if (!$this->zone5->FldIsDetailKey) {
			$this->zone5->setFormValue($objForm->GetValue("x_zone5"));
		}
		$this->zone5->setOldValue($objForm->GetValue("o_zone5"));
		if (!$this->zone6->FldIsDetailKey) {
			$this->zone6->setFormValue($objForm->GetValue("x_zone6"));
		}
		$this->zone6->setOldValue($objForm->GetValue("o_zone6"));
		if (!$this->last_modified->FldIsDetailKey) {
			$this->last_modified->setFormValue($objForm->GetValue("x_last_modified"));
			$this->last_modified->CurrentValue = ew_UnFormatDateTime($this->last_modified->CurrentValue, 7);
		}
		$this->last_modified->setOldValue($objForm->GetValue("o_last_modified"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->weight->CurrentValue = $this->weight->FormValue;
		$this->zone1->CurrentValue = $this->zone1->FormValue;
		$this->zone2->CurrentValue = $this->zone2->FormValue;
		$this->zone3->CurrentValue = $this->zone3->FormValue;
		$this->zone4->CurrentValue = $this->zone4->FormValue;
		$this->zone5->CurrentValue = $this->zone5->FormValue;
		$this->zone6->CurrentValue = $this->zone6->FormValue;
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
		$this->weight->setDbValue($rs->fields('weight'));
		$this->zone1->setDbValue($rs->fields('zone1'));
		$this->zone2->setDbValue($rs->fields('zone2'));
		$this->zone3->setDbValue($rs->fields('zone3'));
		$this->zone4->setDbValue($rs->fields('zone4'));
		$this->zone5->setDbValue($rs->fields('zone5'));
		$this->zone6->setDbValue($rs->fields('zone6'));
		$this->last_modified->setDbValue($rs->fields('last_modified'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->weight->DbValue = $row['weight'];
		$this->zone1->DbValue = $row['zone1'];
		$this->zone2->DbValue = $row['zone2'];
		$this->zone3->DbValue = $row['zone3'];
		$this->zone4->DbValue = $row['zone4'];
		$this->zone5->DbValue = $row['zone5'];
		$this->zone6->DbValue = $row['zone6'];
		$this->last_modified->DbValue = $row['last_modified'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("weight")) <> "")
			$this->weight->CurrentValue = $this->getKey("weight"); // weight
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

		// Convert decimal values if posted back
		if ($this->zone1->FormValue == $this->zone1->CurrentValue && is_numeric(ew_StrToFloat($this->zone1->CurrentValue)))
			$this->zone1->CurrentValue = ew_StrToFloat($this->zone1->CurrentValue);

		// Convert decimal values if posted back
		if ($this->zone2->FormValue == $this->zone2->CurrentValue && is_numeric(ew_StrToFloat($this->zone2->CurrentValue)))
			$this->zone2->CurrentValue = ew_StrToFloat($this->zone2->CurrentValue);

		// Convert decimal values if posted back
		if ($this->zone3->FormValue == $this->zone3->CurrentValue && is_numeric(ew_StrToFloat($this->zone3->CurrentValue)))
			$this->zone3->CurrentValue = ew_StrToFloat($this->zone3->CurrentValue);

		// Convert decimal values if posted back
		if ($this->zone4->FormValue == $this->zone4->CurrentValue && is_numeric(ew_StrToFloat($this->zone4->CurrentValue)))
			$this->zone4->CurrentValue = ew_StrToFloat($this->zone4->CurrentValue);

		// Convert decimal values if posted back
		if ($this->zone5->FormValue == $this->zone5->CurrentValue && is_numeric(ew_StrToFloat($this->zone5->CurrentValue)))
			$this->zone5->CurrentValue = ew_StrToFloat($this->zone5->CurrentValue);

		// Convert decimal values if posted back
		if ($this->zone6->FormValue == $this->zone6->CurrentValue && is_numeric(ew_StrToFloat($this->zone6->CurrentValue)))
			$this->zone6->CurrentValue = ew_StrToFloat($this->zone6->CurrentValue);

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// weight
		// zone1
		// zone2
		// zone3
		// zone4
		// zone5
		// zone6
		// last_modified

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// weight
			$this->weight->ViewValue = $this->weight->CurrentValue;
			$this->weight->ViewCustomAttributes = "";

			// zone1
			$this->zone1->ViewValue = $this->zone1->CurrentValue;
			$this->zone1->ViewCustomAttributes = "";

			// zone2
			$this->zone2->ViewValue = $this->zone2->CurrentValue;
			$this->zone2->ViewCustomAttributes = "";

			// zone3
			$this->zone3->ViewValue = $this->zone3->CurrentValue;
			$this->zone3->ViewCustomAttributes = "";

			// zone4
			$this->zone4->ViewValue = $this->zone4->CurrentValue;
			$this->zone4->ViewCustomAttributes = "";

			// zone5
			$this->zone5->ViewValue = $this->zone5->CurrentValue;
			$this->zone5->ViewCustomAttributes = "";

			// zone6
			$this->zone6->ViewValue = $this->zone6->CurrentValue;
			$this->zone6->ViewCustomAttributes = "";

			// last_modified
			$this->last_modified->ViewValue = $this->last_modified->CurrentValue;
			$this->last_modified->ViewValue = ew_FormatDateTime($this->last_modified->ViewValue, 7);
			$this->last_modified->ViewCustomAttributes = "";

			// weight
			$this->weight->LinkCustomAttributes = "";
			$this->weight->HrefValue = "";
			$this->weight->TooltipValue = "";

			// zone1
			$this->zone1->LinkCustomAttributes = "";
			$this->zone1->HrefValue = "";
			$this->zone1->TooltipValue = "";

			// zone2
			$this->zone2->LinkCustomAttributes = "";
			$this->zone2->HrefValue = "";
			$this->zone2->TooltipValue = "";

			// zone3
			$this->zone3->LinkCustomAttributes = "";
			$this->zone3->HrefValue = "";
			$this->zone3->TooltipValue = "";

			// zone4
			$this->zone4->LinkCustomAttributes = "";
			$this->zone4->HrefValue = "";
			$this->zone4->TooltipValue = "";

			// zone5
			$this->zone5->LinkCustomAttributes = "";
			$this->zone5->HrefValue = "";
			$this->zone5->TooltipValue = "";

			// zone6
			$this->zone6->LinkCustomAttributes = "";
			$this->zone6->HrefValue = "";
			$this->zone6->TooltipValue = "";

			// last_modified
			$this->last_modified->LinkCustomAttributes = "";
			$this->last_modified->HrefValue = "";
			$this->last_modified->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// weight
			$this->weight->EditAttrs["class"] = "form-control";
			$this->weight->EditCustomAttributes = "";
			$this->weight->EditValue = ew_HtmlEncode($this->weight->CurrentValue);
			$this->weight->PlaceHolder = ew_RemoveHtml($this->weight->FldCaption());

			// zone1
			$this->zone1->EditAttrs["class"] = "form-control";
			$this->zone1->EditCustomAttributes = "";
			$this->zone1->EditValue = ew_HtmlEncode($this->zone1->CurrentValue);
			$this->zone1->PlaceHolder = ew_RemoveHtml($this->zone1->FldCaption());
			if (strval($this->zone1->EditValue) <> "" && is_numeric($this->zone1->EditValue)) {
			$this->zone1->EditValue = ew_FormatNumber($this->zone1->EditValue, -2, -1, -2, 0);
			$this->zone1->OldValue = $this->zone1->EditValue;
			}

			// zone2
			$this->zone2->EditAttrs["class"] = "form-control";
			$this->zone2->EditCustomAttributes = "";
			$this->zone2->EditValue = ew_HtmlEncode($this->zone2->CurrentValue);
			$this->zone2->PlaceHolder = ew_RemoveHtml($this->zone2->FldCaption());
			if (strval($this->zone2->EditValue) <> "" && is_numeric($this->zone2->EditValue)) {
			$this->zone2->EditValue = ew_FormatNumber($this->zone2->EditValue, -2, -1, -2, 0);
			$this->zone2->OldValue = $this->zone2->EditValue;
			}

			// zone3
			$this->zone3->EditAttrs["class"] = "form-control";
			$this->zone3->EditCustomAttributes = "";
			$this->zone3->EditValue = ew_HtmlEncode($this->zone3->CurrentValue);
			$this->zone3->PlaceHolder = ew_RemoveHtml($this->zone3->FldCaption());
			if (strval($this->zone3->EditValue) <> "" && is_numeric($this->zone3->EditValue)) {
			$this->zone3->EditValue = ew_FormatNumber($this->zone3->EditValue, -2, -1, -2, 0);
			$this->zone3->OldValue = $this->zone3->EditValue;
			}

			// zone4
			$this->zone4->EditAttrs["class"] = "form-control";
			$this->zone4->EditCustomAttributes = "";
			$this->zone4->EditValue = ew_HtmlEncode($this->zone4->CurrentValue);
			$this->zone4->PlaceHolder = ew_RemoveHtml($this->zone4->FldCaption());
			if (strval($this->zone4->EditValue) <> "" && is_numeric($this->zone4->EditValue)) {
			$this->zone4->EditValue = ew_FormatNumber($this->zone4->EditValue, -2, -1, -2, 0);
			$this->zone4->OldValue = $this->zone4->EditValue;
			}

			// zone5
			$this->zone5->EditAttrs["class"] = "form-control";
			$this->zone5->EditCustomAttributes = "";
			$this->zone5->EditValue = ew_HtmlEncode($this->zone5->CurrentValue);
			$this->zone5->PlaceHolder = ew_RemoveHtml($this->zone5->FldCaption());
			if (strval($this->zone5->EditValue) <> "" && is_numeric($this->zone5->EditValue)) {
			$this->zone5->EditValue = ew_FormatNumber($this->zone5->EditValue, -2, -1, -2, 0);
			$this->zone5->OldValue = $this->zone5->EditValue;
			}

			// zone6
			$this->zone6->EditAttrs["class"] = "form-control";
			$this->zone6->EditCustomAttributes = "";
			$this->zone6->EditValue = ew_HtmlEncode($this->zone6->CurrentValue);
			$this->zone6->PlaceHolder = ew_RemoveHtml($this->zone6->FldCaption());
			if (strval($this->zone6->EditValue) <> "" && is_numeric($this->zone6->EditValue)) {
			$this->zone6->EditValue = ew_FormatNumber($this->zone6->EditValue, -2, -1, -2, 0);
			$this->zone6->OldValue = $this->zone6->EditValue;
			}

			// last_modified
			$this->last_modified->EditAttrs["class"] = "form-control";
			$this->last_modified->EditCustomAttributes = "";
			$this->last_modified->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->last_modified->CurrentValue, 7));
			$this->last_modified->PlaceHolder = ew_RemoveHtml($this->last_modified->FldCaption());

			// Edit refer script
			// weight

			$this->weight->HrefValue = "";

			// zone1
			$this->zone1->HrefValue = "";

			// zone2
			$this->zone2->HrefValue = "";

			// zone3
			$this->zone3->HrefValue = "";

			// zone4
			$this->zone4->HrefValue = "";

			// zone5
			$this->zone5->HrefValue = "";

			// zone6
			$this->zone6->HrefValue = "";

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
		if (!$this->weight->FldIsDetailKey && !is_null($this->weight->FormValue) && $this->weight->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->weight->FldCaption(), $this->weight->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->weight->FormValue)) {
			ew_AddMessage($gsFormError, $this->weight->FldErrMsg());
		}
		if (!$this->zone1->FldIsDetailKey && !is_null($this->zone1->FormValue) && $this->zone1->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->zone1->FldCaption(), $this->zone1->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->zone1->FormValue)) {
			ew_AddMessage($gsFormError, $this->zone1->FldErrMsg());
		}
		if (!$this->zone2->FldIsDetailKey && !is_null($this->zone2->FormValue) && $this->zone2->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->zone2->FldCaption(), $this->zone2->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->zone2->FormValue)) {
			ew_AddMessage($gsFormError, $this->zone2->FldErrMsg());
		}
		if (!$this->zone3->FldIsDetailKey && !is_null($this->zone3->FormValue) && $this->zone3->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->zone3->FldCaption(), $this->zone3->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->zone3->FormValue)) {
			ew_AddMessage($gsFormError, $this->zone3->FldErrMsg());
		}
		if (!$this->zone4->FldIsDetailKey && !is_null($this->zone4->FormValue) && $this->zone4->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->zone4->FldCaption(), $this->zone4->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->zone4->FormValue)) {
			ew_AddMessage($gsFormError, $this->zone4->FldErrMsg());
		}
		if (!$this->zone5->FldIsDetailKey && !is_null($this->zone5->FormValue) && $this->zone5->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->zone5->FldCaption(), $this->zone5->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->zone5->FormValue)) {
			ew_AddMessage($gsFormError, $this->zone5->FldErrMsg());
		}
		if (!$this->zone6->FldIsDetailKey && !is_null($this->zone6->FormValue) && $this->zone6->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->zone6->FldCaption(), $this->zone6->ReqErrMsg));
		}
		if (!ew_CheckNumber($this->zone6->FormValue)) {
			ew_AddMessage($gsFormError, $this->zone6->FldErrMsg());
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
				$sThisKey .= $row['weight'];
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
		}
		$rsnew = array();

		// weight
		$this->weight->SetDbValueDef($rsnew, $this->weight->CurrentValue, 0, FALSE);

		// zone1
		$this->zone1->SetDbValueDef($rsnew, $this->zone1->CurrentValue, 0, FALSE);

		// zone2
		$this->zone2->SetDbValueDef($rsnew, $this->zone2->CurrentValue, 0, FALSE);

		// zone3
		$this->zone3->SetDbValueDef($rsnew, $this->zone3->CurrentValue, 0, FALSE);

		// zone4
		$this->zone4->SetDbValueDef($rsnew, $this->zone4->CurrentValue, 0, FALSE);

		// zone5
		$this->zone5->SetDbValueDef($rsnew, $this->zone5->CurrentValue, 0, FALSE);

		// zone6
		$this->zone6->SetDbValueDef($rsnew, $this->zone6->CurrentValue, 0, FALSE);

		// last_modified
		$this->last_modified->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->last_modified->CurrentValue, 7), NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['weight']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
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
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
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
		$item->Body = "<button id=\"emf_zones_prices\" class=\"ewExportLink ewEmail\" title=\"" . $Language->Phrase("ExportToEmailText") . "\" data-caption=\"" . $Language->Phrase("ExportToEmailText") . "\" onclick=\"ew_EmailDialogShow({lnk:'emf_zones_prices',hdr:ewLanguage.Phrase('ExportToEmailText'),f:document.fzones_priceslist,sel:false" . $url . "});\">" . $Language->Phrase("ExportToEmail") . "</button>";
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
if (!isset($zones_prices_list)) $zones_prices_list = new czones_prices_list();

// Page init
$zones_prices_list->Page_Init();

// Page main
$zones_prices_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$zones_prices_list->Page_Render();
?>
<?php include_once $EW_RELATIVE_PATH . "header.php" ?>
<?php if ($zones_prices->Export == "") { ?>
<script type="text/javascript">

// Page object
var zones_prices_list = new ew_Page("zones_prices_list");
zones_prices_list.PageID = "list"; // Page ID
var EW_PAGE_ID = zones_prices_list.PageID; // For backward compatibility

// Form object
var fzones_priceslist = new ew_Form("fzones_priceslist");
fzones_priceslist.FormKeyCountName = '<?php echo $zones_prices_list->FormKeyCountName ?>';

// Validate form
fzones_priceslist.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_weight");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zones_prices->weight->FldCaption(), $zones_prices->weight->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_weight");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zones_prices->weight->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_zone1");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zones_prices->zone1->FldCaption(), $zones_prices->zone1->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zone1");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zones_prices->zone1->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_zone2");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zones_prices->zone2->FldCaption(), $zones_prices->zone2->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zone2");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zones_prices->zone2->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_zone3");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zones_prices->zone3->FldCaption(), $zones_prices->zone3->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zone3");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zones_prices->zone3->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_zone4");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zones_prices->zone4->FldCaption(), $zones_prices->zone4->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zone4");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zones_prices->zone4->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_zone5");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zones_prices->zone5->FldCaption(), $zones_prices->zone5->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zone5");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zones_prices->zone5->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_zone6");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $zones_prices->zone6->FldCaption(), $zones_prices->zone6->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_zone6");
			if (elm && !ew_CheckNumber(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zones_prices->zone6->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_last_modified");
			if (elm && !ew_CheckEuroDate(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($zones_prices->last_modified->FldErrMsg()) ?>");

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
fzones_priceslist.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "weight", false)) return false;
	if (ew_ValueChanged(fobj, infix, "zone1", false)) return false;
	if (ew_ValueChanged(fobj, infix, "zone2", false)) return false;
	if (ew_ValueChanged(fobj, infix, "zone3", false)) return false;
	if (ew_ValueChanged(fobj, infix, "zone4", false)) return false;
	if (ew_ValueChanged(fobj, infix, "zone5", false)) return false;
	if (ew_ValueChanged(fobj, infix, "zone6", false)) return false;
	if (ew_ValueChanged(fobj, infix, "last_modified", false)) return false;
	return true;
}

// Form_CustomValidate event
fzones_priceslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fzones_priceslist.ValidateRequired = true;
<?php } else { ?>
fzones_priceslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var fzones_priceslistsrch = new ew_Form("fzones_priceslistsrch");

// Init search panel as collapsed
if (fzones_priceslistsrch) fzones_priceslistsrch.InitSearchPanel = true;
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($zones_prices->Export == "") { ?>
<div class="ewToolbar">
<?php if ($zones_prices->Export == "") { ?>
<?php $Breadcrumb->Render(); ?>
<?php } ?>
<?php if ($zones_prices_list->TotalRecs > 0 && $zones_prices_list->ExportOptions->Visible()) { ?>
<?php $zones_prices_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($zones_prices_list->SearchOptions->Visible()) { ?>
<?php $zones_prices_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($zones_prices->Export == "") { ?>
<?php echo $Language->SelectionForm(); ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php
if ($zones_prices->CurrentAction == "gridadd") {
	$zones_prices->CurrentFilter = "0=1";
	$zones_prices_list->StartRec = 1;
	$zones_prices_list->DisplayRecs = $zones_prices->GridAddRowCount;
	$zones_prices_list->TotalRecs = $zones_prices_list->DisplayRecs;
	$zones_prices_list->StopRec = $zones_prices_list->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$zones_prices_list->TotalRecs = $zones_prices->SelectRecordCount();
	} else {
		if ($zones_prices_list->Recordset = $zones_prices_list->LoadRecordset())
			$zones_prices_list->TotalRecs = $zones_prices_list->Recordset->RecordCount();
	}
	$zones_prices_list->StartRec = 1;
	if ($zones_prices_list->DisplayRecs <= 0 || ($zones_prices->Export <> "" && $zones_prices->ExportAll)) // Display all records
		$zones_prices_list->DisplayRecs = $zones_prices_list->TotalRecs;
	if (!($zones_prices->Export <> "" && $zones_prices->ExportAll))
		$zones_prices_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$zones_prices_list->Recordset = $zones_prices_list->LoadRecordset($zones_prices_list->StartRec-1, $zones_prices_list->DisplayRecs);

	// Set no record found message
	if ($zones_prices->CurrentAction == "" && $zones_prices_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$zones_prices_list->setWarningMessage($Language->Phrase("NoPermission"));
		if ($zones_prices_list->SearchWhere == "0=101")
			$zones_prices_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$zones_prices_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$zones_prices_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($zones_prices->Export == "" && $zones_prices->CurrentAction == "") { ?>
<form name="fzones_priceslistsrch" id="fzones_priceslistsrch" class="form-inline ewForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($zones_prices_list->SearchWhere <> "") ? " in" : ""; ?>
<div id="fzones_priceslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="zones_prices">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($zones_prices_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($zones_prices_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $zones_prices_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($zones_prices_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($zones_prices_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($zones_prices_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($zones_prices_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $zones_prices_list->ShowPageHeader(); ?>
<?php
$zones_prices_list->ShowMessage();
?>
<?php if ($zones_prices_list->TotalRecs > 0 || $zones_prices->CurrentAction <> "") { ?>
<div class="ewGrid">
<?php if ($zones_prices->Export == "") { ?>
<div class="ewGridUpperPanel">
<?php if ($zones_prices->CurrentAction <> "gridadd" && $zones_prices->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="form-inline ewForm ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($zones_prices_list->Pager)) $zones_prices_list->Pager = new cNumericPager($zones_prices_list->StartRec, $zones_prices_list->DisplayRecs, $zones_prices_list->TotalRecs, $zones_prices_list->RecRange) ?>
<?php if ($zones_prices_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($zones_prices_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_list->PageUrl() ?>start=<?php echo $zones_prices_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($zones_prices_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_list->PageUrl() ?>start=<?php echo $zones_prices_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($zones_prices_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $zones_prices_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($zones_prices_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_list->PageUrl() ?>start=<?php echo $zones_prices_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($zones_prices_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_list->PageUrl() ?>start=<?php echo $zones_prices_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $zones_prices_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $zones_prices_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $zones_prices_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($zones_prices_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="zones_prices">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="10"<?php if ($zones_prices_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="25"<?php if ($zones_prices_list->DisplayRecs == 25) { ?> selected="selected"<?php } ?>>25</option>
<option value="50"<?php if ($zones_prices_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($zones_prices_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($zones_prices->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($zones_prices_list->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fzones_priceslist" id="fzones_priceslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($zones_prices_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $zones_prices_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="zones_prices">
<div id="gmp_zones_prices" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<?php if ($zones_prices_list->TotalRecs > 0) { ?>
<table id="tbl_zones_priceslist" class="table ewTable">
<?php echo $zones_prices->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$zones_prices_list->RenderListOptions();

// Render list options (header, left)
$zones_prices_list->ListOptions->Render("header", "left");
?>
<?php if ($zones_prices->weight->Visible) { // weight ?>
	<?php if ($zones_prices->SortUrl($zones_prices->weight) == "") { ?>
		<th data-name="weight"><div id="elh_zones_prices_weight" class="zones_prices_weight"><div class="ewTableHeaderCaption"><?php echo $zones_prices->weight->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="weight"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zones_prices->SortUrl($zones_prices->weight) ?>',1);"><div id="elh_zones_prices_weight" class="zones_prices_weight">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zones_prices->weight->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($zones_prices->weight->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zones_prices->weight->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zones_prices->zone1->Visible) { // zone1 ?>
	<?php if ($zones_prices->SortUrl($zones_prices->zone1) == "") { ?>
		<th data-name="zone1"><div id="elh_zones_prices_zone1" class="zones_prices_zone1"><div class="ewTableHeaderCaption"><?php echo $zones_prices->zone1->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="zone1"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zones_prices->SortUrl($zones_prices->zone1) ?>',1);"><div id="elh_zones_prices_zone1" class="zones_prices_zone1">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zones_prices->zone1->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zones_prices->zone1->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zones_prices->zone1->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zones_prices->zone2->Visible) { // zone2 ?>
	<?php if ($zones_prices->SortUrl($zones_prices->zone2) == "") { ?>
		<th data-name="zone2"><div id="elh_zones_prices_zone2" class="zones_prices_zone2"><div class="ewTableHeaderCaption"><?php echo $zones_prices->zone2->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="zone2"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zones_prices->SortUrl($zones_prices->zone2) ?>',1);"><div id="elh_zones_prices_zone2" class="zones_prices_zone2">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zones_prices->zone2->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zones_prices->zone2->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zones_prices->zone2->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zones_prices->zone3->Visible) { // zone3 ?>
	<?php if ($zones_prices->SortUrl($zones_prices->zone3) == "") { ?>
		<th data-name="zone3"><div id="elh_zones_prices_zone3" class="zones_prices_zone3"><div class="ewTableHeaderCaption"><?php echo $zones_prices->zone3->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="zone3"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zones_prices->SortUrl($zones_prices->zone3) ?>',1);"><div id="elh_zones_prices_zone3" class="zones_prices_zone3">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zones_prices->zone3->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zones_prices->zone3->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zones_prices->zone3->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zones_prices->zone4->Visible) { // zone4 ?>
	<?php if ($zones_prices->SortUrl($zones_prices->zone4) == "") { ?>
		<th data-name="zone4"><div id="elh_zones_prices_zone4" class="zones_prices_zone4"><div class="ewTableHeaderCaption"><?php echo $zones_prices->zone4->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="zone4"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zones_prices->SortUrl($zones_prices->zone4) ?>',1);"><div id="elh_zones_prices_zone4" class="zones_prices_zone4">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zones_prices->zone4->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zones_prices->zone4->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zones_prices->zone4->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zones_prices->zone5->Visible) { // zone5 ?>
	<?php if ($zones_prices->SortUrl($zones_prices->zone5) == "") { ?>
		<th data-name="zone5"><div id="elh_zones_prices_zone5" class="zones_prices_zone5"><div class="ewTableHeaderCaption"><?php echo $zones_prices->zone5->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="zone5"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zones_prices->SortUrl($zones_prices->zone5) ?>',1);"><div id="elh_zones_prices_zone5" class="zones_prices_zone5">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zones_prices->zone5->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zones_prices->zone5->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zones_prices->zone5->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zones_prices->zone6->Visible) { // zone6 ?>
	<?php if ($zones_prices->SortUrl($zones_prices->zone6) == "") { ?>
		<th data-name="zone6"><div id="elh_zones_prices_zone6" class="zones_prices_zone6"><div class="ewTableHeaderCaption"><?php echo $zones_prices->zone6->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="zone6"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zones_prices->SortUrl($zones_prices->zone6) ?>',1);"><div id="elh_zones_prices_zone6" class="zones_prices_zone6">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zones_prices->zone6->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zones_prices->zone6->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zones_prices->zone6->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php if ($zones_prices->last_modified->Visible) { // last_modified ?>
	<?php if ($zones_prices->SortUrl($zones_prices->last_modified) == "") { ?>
		<th data-name="last_modified"><div id="elh_zones_prices_last_modified" class="zones_prices_last_modified"><div class="ewTableHeaderCaption"><?php echo $zones_prices->last_modified->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="last_modified"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $zones_prices->SortUrl($zones_prices->last_modified) ?>',1);"><div id="elh_zones_prices_last_modified" class="zones_prices_last_modified">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $zones_prices->last_modified->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($zones_prices->last_modified->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($zones_prices->last_modified->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$zones_prices_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($zones_prices->ExportAll && $zones_prices->Export <> "") {
	$zones_prices_list->StopRec = $zones_prices_list->TotalRecs;
} else {

	// Set the last record to display
	if ($zones_prices_list->TotalRecs > $zones_prices_list->StartRec + $zones_prices_list->DisplayRecs - 1)
		$zones_prices_list->StopRec = $zones_prices_list->StartRec + $zones_prices_list->DisplayRecs - 1;
	else
		$zones_prices_list->StopRec = $zones_prices_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($zones_prices_list->FormKeyCountName) && ($zones_prices->CurrentAction == "gridadd" || $zones_prices->CurrentAction == "gridedit" || $zones_prices->CurrentAction == "F")) {
		$zones_prices_list->KeyCount = $objForm->GetValue($zones_prices_list->FormKeyCountName);
		$zones_prices_list->StopRec = $zones_prices_list->StartRec + $zones_prices_list->KeyCount - 1;
	}
}
$zones_prices_list->RecCnt = $zones_prices_list->StartRec - 1;
if ($zones_prices_list->Recordset && !$zones_prices_list->Recordset->EOF) {
	$zones_prices_list->Recordset->MoveFirst();
	$bSelectLimit = EW_SELECT_LIMIT;
	if (!$bSelectLimit && $zones_prices_list->StartRec > 1)
		$zones_prices_list->Recordset->Move($zones_prices_list->StartRec - 1);
} elseif (!$zones_prices->AllowAddDeleteRow && $zones_prices_list->StopRec == 0) {
	$zones_prices_list->StopRec = $zones_prices->GridAddRowCount;
}

// Initialize aggregate
$zones_prices->RowType = EW_ROWTYPE_AGGREGATEINIT;
$zones_prices->ResetAttrs();
$zones_prices_list->RenderRow();
if ($zones_prices->CurrentAction == "gridadd")
	$zones_prices_list->RowIndex = 0;
while ($zones_prices_list->RecCnt < $zones_prices_list->StopRec) {
	$zones_prices_list->RecCnt++;
	if (intval($zones_prices_list->RecCnt) >= intval($zones_prices_list->StartRec)) {
		$zones_prices_list->RowCnt++;
		if ($zones_prices->CurrentAction == "gridadd" || $zones_prices->CurrentAction == "gridedit" || $zones_prices->CurrentAction == "F") {
			$zones_prices_list->RowIndex++;
			$objForm->Index = $zones_prices_list->RowIndex;
			if ($objForm->HasValue($zones_prices_list->FormActionName))
				$zones_prices_list->RowAction = strval($objForm->GetValue($zones_prices_list->FormActionName));
			elseif ($zones_prices->CurrentAction == "gridadd")
				$zones_prices_list->RowAction = "insert";
			else
				$zones_prices_list->RowAction = "";
		}

		// Set up key count
		$zones_prices_list->KeyCount = $zones_prices_list->RowIndex;

		// Init row class and style
		$zones_prices->ResetAttrs();
		$zones_prices->CssClass = "";
		if ($zones_prices->CurrentAction == "gridadd") {
			$zones_prices_list->LoadDefaultValues(); // Load default values
		} else {
			$zones_prices_list->LoadRowValues($zones_prices_list->Recordset); // Load row values
		}
		$zones_prices->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($zones_prices->CurrentAction == "gridadd") // Grid add
			$zones_prices->RowType = EW_ROWTYPE_ADD; // Render add
		if ($zones_prices->CurrentAction == "gridadd" && $zones_prices->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$zones_prices_list->RestoreCurrentRowFormValues($zones_prices_list->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$zones_prices->RowAttrs = array_merge($zones_prices->RowAttrs, array('data-rowindex'=>$zones_prices_list->RowCnt, 'id'=>'r' . $zones_prices_list->RowCnt . '_zones_prices', 'data-rowtype'=>$zones_prices->RowType));

		// Render row
		$zones_prices_list->RenderRow();

		// Render list options
		$zones_prices_list->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($zones_prices_list->RowAction <> "delete" && $zones_prices_list->RowAction <> "insertdelete" && !($zones_prices_list->RowAction == "insert" && $zones_prices->CurrentAction == "F" && $zones_prices_list->EmptyRow())) {
?>
	<tr<?php echo $zones_prices->RowAttributes() ?>>
<?php

// Render list options (body, left)
$zones_prices_list->ListOptions->Render("body", "left", $zones_prices_list->RowCnt);
?>
	<?php if ($zones_prices->weight->Visible) { // weight ?>
		<td data-name="weight"<?php echo $zones_prices->weight->CellAttributes() ?>>
<?php if ($zones_prices->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $zones_prices_list->RowCnt ?>_zones_prices_weight" class="form-group zones_prices_weight">
<input type="text" data-field="x_weight" name="x<?php echo $zones_prices_list->RowIndex ?>_weight" id="x<?php echo $zones_prices_list->RowIndex ?>_weight" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->weight->PlaceHolder) ?>" value="<?php echo $zones_prices->weight->EditValue ?>"<?php echo $zones_prices->weight->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_weight" name="o<?php echo $zones_prices_list->RowIndex ?>_weight" id="o<?php echo $zones_prices_list->RowIndex ?>_weight" value="<?php echo ew_HtmlEncode($zones_prices->weight->OldValue) ?>">
<?php } ?>
<?php if ($zones_prices->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $zones_prices->weight->ViewAttributes() ?>>
<?php echo $zones_prices->weight->ListViewValue() ?></span>
<?php } ?>
<a id="<?php echo $zones_prices_list->PageObjName . "_row_" . $zones_prices_list->RowCnt ?>"></a></td>
	<?php } ?>
	<?php if ($zones_prices->zone1->Visible) { // zone1 ?>
		<td data-name="zone1"<?php echo $zones_prices->zone1->CellAttributes() ?>>
<?php if ($zones_prices->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $zones_prices_list->RowCnt ?>_zones_prices_zone1" class="form-group zones_prices_zone1">
<input type="text" data-field="x_zone1" name="x<?php echo $zones_prices_list->RowIndex ?>_zone1" id="x<?php echo $zones_prices_list->RowIndex ?>_zone1" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone1->PlaceHolder) ?>" value="<?php echo $zones_prices->zone1->EditValue ?>"<?php echo $zones_prices->zone1->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_zone1" name="o<?php echo $zones_prices_list->RowIndex ?>_zone1" id="o<?php echo $zones_prices_list->RowIndex ?>_zone1" value="<?php echo ew_HtmlEncode($zones_prices->zone1->OldValue) ?>">
<?php } ?>
<?php if ($zones_prices->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $zones_prices->zone1->ViewAttributes() ?>>
<?php echo $zones_prices->zone1->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($zones_prices->zone2->Visible) { // zone2 ?>
		<td data-name="zone2"<?php echo $zones_prices->zone2->CellAttributes() ?>>
<?php if ($zones_prices->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $zones_prices_list->RowCnt ?>_zones_prices_zone2" class="form-group zones_prices_zone2">
<input type="text" data-field="x_zone2" name="x<?php echo $zones_prices_list->RowIndex ?>_zone2" id="x<?php echo $zones_prices_list->RowIndex ?>_zone2" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone2->PlaceHolder) ?>" value="<?php echo $zones_prices->zone2->EditValue ?>"<?php echo $zones_prices->zone2->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_zone2" name="o<?php echo $zones_prices_list->RowIndex ?>_zone2" id="o<?php echo $zones_prices_list->RowIndex ?>_zone2" value="<?php echo ew_HtmlEncode($zones_prices->zone2->OldValue) ?>">
<?php } ?>
<?php if ($zones_prices->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $zones_prices->zone2->ViewAttributes() ?>>
<?php echo $zones_prices->zone2->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($zones_prices->zone3->Visible) { // zone3 ?>
		<td data-name="zone3"<?php echo $zones_prices->zone3->CellAttributes() ?>>
<?php if ($zones_prices->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $zones_prices_list->RowCnt ?>_zones_prices_zone3" class="form-group zones_prices_zone3">
<input type="text" data-field="x_zone3" name="x<?php echo $zones_prices_list->RowIndex ?>_zone3" id="x<?php echo $zones_prices_list->RowIndex ?>_zone3" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone3->PlaceHolder) ?>" value="<?php echo $zones_prices->zone3->EditValue ?>"<?php echo $zones_prices->zone3->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_zone3" name="o<?php echo $zones_prices_list->RowIndex ?>_zone3" id="o<?php echo $zones_prices_list->RowIndex ?>_zone3" value="<?php echo ew_HtmlEncode($zones_prices->zone3->OldValue) ?>">
<?php } ?>
<?php if ($zones_prices->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $zones_prices->zone3->ViewAttributes() ?>>
<?php echo $zones_prices->zone3->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($zones_prices->zone4->Visible) { // zone4 ?>
		<td data-name="zone4"<?php echo $zones_prices->zone4->CellAttributes() ?>>
<?php if ($zones_prices->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $zones_prices_list->RowCnt ?>_zones_prices_zone4" class="form-group zones_prices_zone4">
<input type="text" data-field="x_zone4" name="x<?php echo $zones_prices_list->RowIndex ?>_zone4" id="x<?php echo $zones_prices_list->RowIndex ?>_zone4" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone4->PlaceHolder) ?>" value="<?php echo $zones_prices->zone4->EditValue ?>"<?php echo $zones_prices->zone4->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_zone4" name="o<?php echo $zones_prices_list->RowIndex ?>_zone4" id="o<?php echo $zones_prices_list->RowIndex ?>_zone4" value="<?php echo ew_HtmlEncode($zones_prices->zone4->OldValue) ?>">
<?php } ?>
<?php if ($zones_prices->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $zones_prices->zone4->ViewAttributes() ?>>
<?php echo $zones_prices->zone4->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($zones_prices->zone5->Visible) { // zone5 ?>
		<td data-name="zone5"<?php echo $zones_prices->zone5->CellAttributes() ?>>
<?php if ($zones_prices->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $zones_prices_list->RowCnt ?>_zones_prices_zone5" class="form-group zones_prices_zone5">
<input type="text" data-field="x_zone5" name="x<?php echo $zones_prices_list->RowIndex ?>_zone5" id="x<?php echo $zones_prices_list->RowIndex ?>_zone5" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone5->PlaceHolder) ?>" value="<?php echo $zones_prices->zone5->EditValue ?>"<?php echo $zones_prices->zone5->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_zone5" name="o<?php echo $zones_prices_list->RowIndex ?>_zone5" id="o<?php echo $zones_prices_list->RowIndex ?>_zone5" value="<?php echo ew_HtmlEncode($zones_prices->zone5->OldValue) ?>">
<?php } ?>
<?php if ($zones_prices->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $zones_prices->zone5->ViewAttributes() ?>>
<?php echo $zones_prices->zone5->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($zones_prices->zone6->Visible) { // zone6 ?>
		<td data-name="zone6"<?php echo $zones_prices->zone6->CellAttributes() ?>>
<?php if ($zones_prices->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $zones_prices_list->RowCnt ?>_zones_prices_zone6" class="form-group zones_prices_zone6">
<input type="text" data-field="x_zone6" name="x<?php echo $zones_prices_list->RowIndex ?>_zone6" id="x<?php echo $zones_prices_list->RowIndex ?>_zone6" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone6->PlaceHolder) ?>" value="<?php echo $zones_prices->zone6->EditValue ?>"<?php echo $zones_prices->zone6->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_zone6" name="o<?php echo $zones_prices_list->RowIndex ?>_zone6" id="o<?php echo $zones_prices_list->RowIndex ?>_zone6" value="<?php echo ew_HtmlEncode($zones_prices->zone6->OldValue) ?>">
<?php } ?>
<?php if ($zones_prices->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $zones_prices->zone6->ViewAttributes() ?>>
<?php echo $zones_prices->zone6->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
	<?php if ($zones_prices->last_modified->Visible) { // last_modified ?>
		<td data-name="last_modified"<?php echo $zones_prices->last_modified->CellAttributes() ?>>
<?php if ($zones_prices->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $zones_prices_list->RowCnt ?>_zones_prices_last_modified" class="form-group zones_prices_last_modified">
<input type="text" data-field="x_last_modified" name="x<?php echo $zones_prices_list->RowIndex ?>_last_modified" id="x<?php echo $zones_prices_list->RowIndex ?>_last_modified" placeholder="<?php echo ew_HtmlEncode($zones_prices->last_modified->PlaceHolder) ?>" value="<?php echo $zones_prices->last_modified->EditValue ?>"<?php echo $zones_prices->last_modified->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_last_modified" name="o<?php echo $zones_prices_list->RowIndex ?>_last_modified" id="o<?php echo $zones_prices_list->RowIndex ?>_last_modified" value="<?php echo ew_HtmlEncode($zones_prices->last_modified->OldValue) ?>">
<?php } ?>
<?php if ($zones_prices->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $zones_prices->last_modified->ViewAttributes() ?>>
<?php echo $zones_prices->last_modified->ListViewValue() ?></span>
<?php } ?>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$zones_prices_list->ListOptions->Render("body", "right", $zones_prices_list->RowCnt);
?>
	</tr>
<?php if ($zones_prices->RowType == EW_ROWTYPE_ADD || $zones_prices->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fzones_priceslist.UpdateOpts(<?php echo $zones_prices_list->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($zones_prices->CurrentAction <> "gridadd")
		if (!$zones_prices_list->Recordset->EOF) $zones_prices_list->Recordset->MoveNext();
}
?>
<?php
	if ($zones_prices->CurrentAction == "gridadd" || $zones_prices->CurrentAction == "gridedit") {
		$zones_prices_list->RowIndex = '$rowindex$';
		$zones_prices_list->LoadDefaultValues();

		// Set row properties
		$zones_prices->ResetAttrs();
		$zones_prices->RowAttrs = array_merge($zones_prices->RowAttrs, array('data-rowindex'=>$zones_prices_list->RowIndex, 'id'=>'r0_zones_prices', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($zones_prices->RowAttrs["class"], "ewTemplate");
		$zones_prices->RowType = EW_ROWTYPE_ADD;

		// Render row
		$zones_prices_list->RenderRow();

		// Render list options
		$zones_prices_list->RenderListOptions();
		$zones_prices_list->StartRowCnt = 0;
?>
	<tr<?php echo $zones_prices->RowAttributes() ?>>
<?php

// Render list options (body, left)
$zones_prices_list->ListOptions->Render("body", "left", $zones_prices_list->RowIndex);
?>
	<?php if ($zones_prices->weight->Visible) { // weight ?>
		<td>
<span id="el$rowindex$_zones_prices_weight" class="form-group zones_prices_weight">
<input type="text" data-field="x_weight" name="x<?php echo $zones_prices_list->RowIndex ?>_weight" id="x<?php echo $zones_prices_list->RowIndex ?>_weight" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->weight->PlaceHolder) ?>" value="<?php echo $zones_prices->weight->EditValue ?>"<?php echo $zones_prices->weight->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_weight" name="o<?php echo $zones_prices_list->RowIndex ?>_weight" id="o<?php echo $zones_prices_list->RowIndex ?>_weight" value="<?php echo ew_HtmlEncode($zones_prices->weight->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($zones_prices->zone1->Visible) { // zone1 ?>
		<td>
<span id="el$rowindex$_zones_prices_zone1" class="form-group zones_prices_zone1">
<input type="text" data-field="x_zone1" name="x<?php echo $zones_prices_list->RowIndex ?>_zone1" id="x<?php echo $zones_prices_list->RowIndex ?>_zone1" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone1->PlaceHolder) ?>" value="<?php echo $zones_prices->zone1->EditValue ?>"<?php echo $zones_prices->zone1->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_zone1" name="o<?php echo $zones_prices_list->RowIndex ?>_zone1" id="o<?php echo $zones_prices_list->RowIndex ?>_zone1" value="<?php echo ew_HtmlEncode($zones_prices->zone1->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($zones_prices->zone2->Visible) { // zone2 ?>
		<td>
<span id="el$rowindex$_zones_prices_zone2" class="form-group zones_prices_zone2">
<input type="text" data-field="x_zone2" name="x<?php echo $zones_prices_list->RowIndex ?>_zone2" id="x<?php echo $zones_prices_list->RowIndex ?>_zone2" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone2->PlaceHolder) ?>" value="<?php echo $zones_prices->zone2->EditValue ?>"<?php echo $zones_prices->zone2->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_zone2" name="o<?php echo $zones_prices_list->RowIndex ?>_zone2" id="o<?php echo $zones_prices_list->RowIndex ?>_zone2" value="<?php echo ew_HtmlEncode($zones_prices->zone2->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($zones_prices->zone3->Visible) { // zone3 ?>
		<td>
<span id="el$rowindex$_zones_prices_zone3" class="form-group zones_prices_zone3">
<input type="text" data-field="x_zone3" name="x<?php echo $zones_prices_list->RowIndex ?>_zone3" id="x<?php echo $zones_prices_list->RowIndex ?>_zone3" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone3->PlaceHolder) ?>" value="<?php echo $zones_prices->zone3->EditValue ?>"<?php echo $zones_prices->zone3->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_zone3" name="o<?php echo $zones_prices_list->RowIndex ?>_zone3" id="o<?php echo $zones_prices_list->RowIndex ?>_zone3" value="<?php echo ew_HtmlEncode($zones_prices->zone3->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($zones_prices->zone4->Visible) { // zone4 ?>
		<td>
<span id="el$rowindex$_zones_prices_zone4" class="form-group zones_prices_zone4">
<input type="text" data-field="x_zone4" name="x<?php echo $zones_prices_list->RowIndex ?>_zone4" id="x<?php echo $zones_prices_list->RowIndex ?>_zone4" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone4->PlaceHolder) ?>" value="<?php echo $zones_prices->zone4->EditValue ?>"<?php echo $zones_prices->zone4->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_zone4" name="o<?php echo $zones_prices_list->RowIndex ?>_zone4" id="o<?php echo $zones_prices_list->RowIndex ?>_zone4" value="<?php echo ew_HtmlEncode($zones_prices->zone4->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($zones_prices->zone5->Visible) { // zone5 ?>
		<td>
<span id="el$rowindex$_zones_prices_zone5" class="form-group zones_prices_zone5">
<input type="text" data-field="x_zone5" name="x<?php echo $zones_prices_list->RowIndex ?>_zone5" id="x<?php echo $zones_prices_list->RowIndex ?>_zone5" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone5->PlaceHolder) ?>" value="<?php echo $zones_prices->zone5->EditValue ?>"<?php echo $zones_prices->zone5->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_zone5" name="o<?php echo $zones_prices_list->RowIndex ?>_zone5" id="o<?php echo $zones_prices_list->RowIndex ?>_zone5" value="<?php echo ew_HtmlEncode($zones_prices->zone5->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($zones_prices->zone6->Visible) { // zone6 ?>
		<td>
<span id="el$rowindex$_zones_prices_zone6" class="form-group zones_prices_zone6">
<input type="text" data-field="x_zone6" name="x<?php echo $zones_prices_list->RowIndex ?>_zone6" id="x<?php echo $zones_prices_list->RowIndex ?>_zone6" size="30" placeholder="<?php echo ew_HtmlEncode($zones_prices->zone6->PlaceHolder) ?>" value="<?php echo $zones_prices->zone6->EditValue ?>"<?php echo $zones_prices->zone6->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_zone6" name="o<?php echo $zones_prices_list->RowIndex ?>_zone6" id="o<?php echo $zones_prices_list->RowIndex ?>_zone6" value="<?php echo ew_HtmlEncode($zones_prices->zone6->OldValue) ?>">
</td>
	<?php } ?>
	<?php if ($zones_prices->last_modified->Visible) { // last_modified ?>
		<td>
<span id="el$rowindex$_zones_prices_last_modified" class="form-group zones_prices_last_modified">
<input type="text" data-field="x_last_modified" name="x<?php echo $zones_prices_list->RowIndex ?>_last_modified" id="x<?php echo $zones_prices_list->RowIndex ?>_last_modified" placeholder="<?php echo ew_HtmlEncode($zones_prices->last_modified->PlaceHolder) ?>" value="<?php echo $zones_prices->last_modified->EditValue ?>"<?php echo $zones_prices->last_modified->EditAttributes() ?>>
</span>
<input type="hidden" data-field="x_last_modified" name="o<?php echo $zones_prices_list->RowIndex ?>_last_modified" id="o<?php echo $zones_prices_list->RowIndex ?>_last_modified" value="<?php echo ew_HtmlEncode($zones_prices->last_modified->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$zones_prices_list->ListOptions->Render("body", "right", $zones_prices_list->RowCnt);
?>
<script type="text/javascript">
fzones_priceslist.UpdateOpts(<?php echo $zones_prices_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($zones_prices->CurrentAction == "gridadd") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $zones_prices_list->FormKeyCountName ?>" id="<?php echo $zones_prices_list->FormKeyCountName ?>" value="<?php echo $zones_prices_list->KeyCount ?>">
<?php echo $zones_prices_list->MultiSelectKey ?>
<?php } ?>
<?php if ($zones_prices->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($zones_prices_list->Recordset)
	$zones_prices_list->Recordset->Close();
?>
<?php if ($zones_prices->Export == "") { ?>
<div class="ewGridLowerPanel">
<?php if ($zones_prices->CurrentAction <> "gridadd" && $zones_prices->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($zones_prices_list->Pager)) $zones_prices_list->Pager = new cNumericPager($zones_prices_list->StartRec, $zones_prices_list->DisplayRecs, $zones_prices_list->TotalRecs, $zones_prices_list->RecRange) ?>
<?php if ($zones_prices_list->Pager->RecordCount > 0) { ?>
<div class="ewPager">
<div class="ewNumericPage"><ul class="pagination">
	<?php if ($zones_prices_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_list->PageUrl() ?>start=<?php echo $zones_prices_list->Pager->FirstButton->Start ?>"><?php echo $Language->Phrase("PagerFirst") ?></a></li>
	<?php } ?>
	<?php if ($zones_prices_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_list->PageUrl() ?>start=<?php echo $zones_prices_list->Pager->PrevButton->Start ?>"><?php echo $Language->Phrase("PagerPrevious") ?></a></li>
	<?php } ?>
	<?php foreach ($zones_prices_list->Pager->Items as $PagerItem) { ?>
		<li<?php if (!$PagerItem->Enabled) { echo " class=\" active\""; } ?>><a href="<?php if ($PagerItem->Enabled) { echo $zones_prices_list->PageUrl() . "start=" . $PagerItem->Start; } else { echo "#"; } ?>"><?php echo $PagerItem->Text ?></a></li>
	<?php } ?>
	<?php if ($zones_prices_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_list->PageUrl() ?>start=<?php echo $zones_prices_list->Pager->NextButton->Start ?>"><?php echo $Language->Phrase("PagerNext") ?></a></li>
	<?php } ?>
	<?php if ($zones_prices_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $zones_prices_list->PageUrl() ?>start=<?php echo $zones_prices_list->Pager->LastButton->Start ?>"><?php echo $Language->Phrase("PagerLast") ?></a></li>
	<?php } ?>
</ul></div>
</div>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $zones_prices_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $zones_prices_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $zones_prices_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
<?php if ($zones_prices_list->TotalRecs > 0) { ?>
<div class="ewPager">
<input type="hidden" name="t" value="zones_prices">
<select name="<?php echo EW_TABLE_REC_PER_PAGE ?>" class="form-control input-sm" onchange="this.form.submit();">
<option value="10"<?php if ($zones_prices_list->DisplayRecs == 10) { ?> selected="selected"<?php } ?>>10</option>
<option value="25"<?php if ($zones_prices_list->DisplayRecs == 25) { ?> selected="selected"<?php } ?>>25</option>
<option value="50"<?php if ($zones_prices_list->DisplayRecs == 50) { ?> selected="selected"<?php } ?>>50</option>
<option value="100"<?php if ($zones_prices_list->DisplayRecs == 100) { ?> selected="selected"<?php } ?>>100</option>
<option value="ALL"<?php if ($zones_prices->getRecordsPerPage() == -1) { ?> selected="selected"<?php } ?>><?php echo $Language->Phrase("AllRecords") ?></option>
</select>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($zones_prices_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div>
<?php } ?>
<?php if ($zones_prices_list->TotalRecs == 0 && $zones_prices->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($zones_prices_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($zones_prices->Export == "") { ?>
<script type="text/javascript">
fzones_priceslistsrch.Init();
fzones_priceslist.Init();
</script>
<?php } ?>
<?php
$zones_prices_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($zones_prices->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once $EW_RELATIVE_PATH . "footer.php" ?>
<?php
$zones_prices_list->Page_Terminate();
?>
