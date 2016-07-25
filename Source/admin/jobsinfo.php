<?php

// Global variable for table object
$jobs = NULL;

//
// Table class for jobs
//
class cjobs extends cTable {
	var $id;
	var $image;
	var $title_en;
	var $title_ar;
	var $position_en;
	var $position_ar;
	var $experience;
	var $gender;
	var $applied;
	var $desc_en;
	var $desc_ar;
	var $created;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'jobs';
		$this->TableName = 'jobs';
		$this->TableType = 'TABLE';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id
		$this->id = new cField('jobs', 'jobs', 'x_id', 'id', '`id`', '`id`', 19, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// image
		$this->image = new cField('jobs', 'jobs', 'x_image', 'image', '`image`', '`image`', 200, -1, TRUE, '`image`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['image'] = &$this->image;

		// title_en
		$this->title_en = new cField('jobs', 'jobs', 'x_title_en', 'title_en', '`title_en`', '`title_en`', 200, -1, FALSE, '`title_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['title_en'] = &$this->title_en;

		// title_ar
		$this->title_ar = new cField('jobs', 'jobs', 'x_title_ar', 'title_ar', '`title_ar`', '`title_ar`', 200, -1, FALSE, '`title_ar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['title_ar'] = &$this->title_ar;

		// position_en
		$this->position_en = new cField('jobs', 'jobs', 'x_position_en', 'position_en', '`position_en`', '`position_en`', 200, -1, FALSE, '`position_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['position_en'] = &$this->position_en;

		// position_ar
		$this->position_ar = new cField('jobs', 'jobs', 'x_position_ar', 'position_ar', '`position_ar`', '`position_ar`', 200, -1, FALSE, '`position_ar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['position_ar'] = &$this->position_ar;

		// experience
		$this->experience = new cField('jobs', 'jobs', 'x_experience', 'experience', '`experience`', '`experience`', 2, -1, FALSE, '`experience`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->experience->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['experience'] = &$this->experience;

		// gender
		$this->gender = new cField('jobs', 'jobs', 'x_gender', 'gender', '`gender`', '`gender`', 202, -1, FALSE, '`gender`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['gender'] = &$this->gender;

		// applied
		$this->applied = new cField('jobs', 'jobs', 'x_applied', 'applied', '`applied`', '`applied`', 19, -1, FALSE, '`applied`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->applied->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['applied'] = &$this->applied;

		// desc_en
		$this->desc_en = new cField('jobs', 'jobs', 'x_desc_en', 'desc_en', '`desc_en`', '`desc_en`', 201, -1, FALSE, '`desc_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['desc_en'] = &$this->desc_en;

		// desc_ar
		$this->desc_ar = new cField('jobs', 'jobs', 'x_desc_ar', 'desc_ar', '`desc_ar`', '`desc_ar`', 201, -1, FALSE, '`desc_ar`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['desc_ar'] = &$this->desc_ar;

		// created
		$this->created = new cField('jobs', 'jobs', 'x_created', 'created', '`created`', 'DATE_FORMAT(`created`, \'%d/%m/%Y\')', 135, 7, FALSE, '`created`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->created->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['created'] = &$this->created;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "job_candidates") {
			$sDetailUrl = $GLOBALS["job_candidates"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&fk_id=" . urlencode($this->id->CurrentValue);
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "jobslist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`jobs`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`jobs`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;

		// Cascade update detail field 'job_id'
		if (!is_null($rsold) && (isset($rs['id']) && $rsold['id'] <> $rs['id'])) {
			if (!isset($GLOBALS["job_candidates"])) $GLOBALS["job_candidates"] = new cjob_candidates();
			$rscascade = array();
			$rscascade['job_id'] = $rs['id']; 
			$GLOBALS["job_candidates"]->Update($rscascade, "`job_id` = " . ew_QuotedValue($rsold['id'], EW_DATATYPE_NUMBER));
		}
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('id', $rs))
				ew_AddFilter($where, ew_QuotedName('id') . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "jobslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "jobslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("jobsview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("jobsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "jobsadd.php?" . $this->UrlParm($parm);
		else
			return "jobsadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("jobsedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("jobsedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("jobsadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("jobsadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("jobsdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "id=" . urlencode($this->id->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["id"]; // id

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id->setDbValue($rs->fields('id'));
		$this->image->Upload->DbValue = $rs->fields('image');
		$this->title_en->setDbValue($rs->fields('title_en'));
		$this->title_ar->setDbValue($rs->fields('title_ar'));
		$this->position_en->setDbValue($rs->fields('position_en'));
		$this->position_ar->setDbValue($rs->fields('position_ar'));
		$this->experience->setDbValue($rs->fields('experience'));
		$this->gender->setDbValue($rs->fields('gender'));
		$this->applied->setDbValue($rs->fields('applied'));
		$this->desc_en->setDbValue($rs->fields('desc_en'));
		$this->desc_ar->setDbValue($rs->fields('desc_ar'));
		$this->created->setDbValue($rs->fields('created'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id
		// image
		// title_en
		// title_ar
		// position_en
		// position_ar
		// experience
		// gender
		// applied
		// desc_en
		// desc_ar
		// created
		// id

		$this->id->ViewValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// image
		$this->image->UploadPath = '../webroot/uploads/jobs/';
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

		// title_en
		$this->title_en->ViewValue = $this->title_en->CurrentValue;
		$this->title_en->ViewCustomAttributes = "";

		// title_ar
		$this->title_ar->ViewValue = $this->title_ar->CurrentValue;
		$this->title_ar->ViewCustomAttributes = "";

		// position_en
		$this->position_en->ViewValue = $this->position_en->CurrentValue;
		$this->position_en->ViewCustomAttributes = "";

		// position_ar
		$this->position_ar->ViewValue = $this->position_ar->CurrentValue;
		$this->position_ar->ViewCustomAttributes = "";

		// experience
		$this->experience->ViewValue = $this->experience->CurrentValue;
		$this->experience->ViewCustomAttributes = "";

		// gender
		if (strval($this->gender->CurrentValue) <> "") {
			switch ($this->gender->CurrentValue) {
				case $this->gender->FldTagValue(1):
					$this->gender->ViewValue = $this->gender->FldTagCaption(1) <> "" ? $this->gender->FldTagCaption(1) : $this->gender->CurrentValue;
					break;
				case $this->gender->FldTagValue(2):
					$this->gender->ViewValue = $this->gender->FldTagCaption(2) <> "" ? $this->gender->FldTagCaption(2) : $this->gender->CurrentValue;
					break;
				case $this->gender->FldTagValue(3):
					$this->gender->ViewValue = $this->gender->FldTagCaption(3) <> "" ? $this->gender->FldTagCaption(3) : $this->gender->CurrentValue;
					break;
				default:
					$this->gender->ViewValue = $this->gender->CurrentValue;
			}
		} else {
			$this->gender->ViewValue = NULL;
		}
		$this->gender->ViewCustomAttributes = "";

		// applied
		$this->applied->ViewValue = $this->applied->CurrentValue;
		$this->applied->ViewCustomAttributes = "";

		// desc_en
		$this->desc_en->ViewValue = $this->desc_en->CurrentValue;
		$this->desc_en->ViewCustomAttributes = "";

		// desc_ar
		$this->desc_ar->ViewValue = $this->desc_ar->CurrentValue;
		$this->desc_ar->ViewCustomAttributes = "";

		// created
		$this->created->ViewValue = $this->created->CurrentValue;
		$this->created->ViewValue = ew_FormatDateTime($this->created->ViewValue, 7);
		$this->created->ViewCustomAttributes = "";

		// id
		$this->id->LinkCustomAttributes = "";
		$this->id->HrefValue = "";
		$this->id->TooltipValue = "";

		// image
		$this->image->LinkCustomAttributes = "";
		$this->image->UploadPath = '../webroot/uploads/jobs/';
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
			$this->image->LinkAttrs["data-rel"] = "jobs_x_image";
			$this->image->LinkAttrs["class"] = "ewLightbox";
		}

		// title_en
		$this->title_en->LinkCustomAttributes = "";
		$this->title_en->HrefValue = "";
		$this->title_en->TooltipValue = "";

		// title_ar
		$this->title_ar->LinkCustomAttributes = "";
		$this->title_ar->HrefValue = "";
		$this->title_ar->TooltipValue = "";

		// position_en
		$this->position_en->LinkCustomAttributes = "";
		$this->position_en->HrefValue = "";
		$this->position_en->TooltipValue = "";

		// position_ar
		$this->position_ar->LinkCustomAttributes = "";
		$this->position_ar->HrefValue = "";
		$this->position_ar->TooltipValue = "";

		// experience
		$this->experience->LinkCustomAttributes = "";
		$this->experience->HrefValue = "";
		$this->experience->TooltipValue = "";

		// gender
		$this->gender->LinkCustomAttributes = "";
		$this->gender->HrefValue = "";
		$this->gender->TooltipValue = "";

		// applied
		$this->applied->LinkCustomAttributes = "";
		$this->applied->HrefValue = "";
		$this->applied->TooltipValue = "";

		// desc_en
		$this->desc_en->LinkCustomAttributes = "";
		$this->desc_en->HrefValue = "";
		$this->desc_en->TooltipValue = "";

		// desc_ar
		$this->desc_ar->LinkCustomAttributes = "";
		$this->desc_ar->HrefValue = "";
		$this->desc_ar->TooltipValue = "";

		// created
		$this->created->LinkCustomAttributes = "";
		$this->created->HrefValue = "";
		$this->created->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// id
		$this->id->EditAttrs["class"] = "form-control";
		$this->id->EditCustomAttributes = "";
		$this->id->EditValue = $this->id->CurrentValue;
		$this->id->ViewCustomAttributes = "";

		// image
		$this->image->EditAttrs["class"] = "form-control";
		$this->image->EditCustomAttributes = "";
		$this->image->UploadPath = '../webroot/uploads/jobs/';
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

		// position_en
		$this->position_en->EditAttrs["class"] = "form-control";
		$this->position_en->EditCustomAttributes = "";
		$this->position_en->EditValue = ew_HtmlEncode($this->position_en->CurrentValue);
		$this->position_en->PlaceHolder = ew_RemoveHtml($this->position_en->FldCaption());

		// position_ar
		$this->position_ar->EditAttrs["class"] = "form-control";
		$this->position_ar->EditCustomAttributes = "";
		$this->position_ar->EditValue = ew_HtmlEncode($this->position_ar->CurrentValue);
		$this->position_ar->PlaceHolder = ew_RemoveHtml($this->position_ar->FldCaption());

		// experience
		$this->experience->EditAttrs["class"] = "form-control";
		$this->experience->EditCustomAttributes = "";
		$this->experience->EditValue = ew_HtmlEncode($this->experience->CurrentValue);
		$this->experience->PlaceHolder = ew_RemoveHtml($this->experience->FldCaption());

		// gender
		$this->gender->EditAttrs["class"] = "form-control";
		$this->gender->EditCustomAttributes = "";
		$arwrk = array();
		$arwrk[] = array($this->gender->FldTagValue(1), $this->gender->FldTagCaption(1) <> "" ? $this->gender->FldTagCaption(1) : $this->gender->FldTagValue(1));
		$arwrk[] = array($this->gender->FldTagValue(2), $this->gender->FldTagCaption(2) <> "" ? $this->gender->FldTagCaption(2) : $this->gender->FldTagValue(2));
		$arwrk[] = array($this->gender->FldTagValue(3), $this->gender->FldTagCaption(3) <> "" ? $this->gender->FldTagCaption(3) : $this->gender->FldTagValue(3));
		array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
		$this->gender->EditValue = $arwrk;

		// applied
		$this->applied->EditAttrs["class"] = "form-control";
		$this->applied->EditCustomAttributes = "";
		$this->applied->EditValue = ew_HtmlEncode($this->applied->CurrentValue);
		$this->applied->PlaceHolder = ew_RemoveHtml($this->applied->FldCaption());

		// desc_en
		$this->desc_en->EditAttrs["class"] = "form-control";
		$this->desc_en->EditCustomAttributes = "";
		$this->desc_en->EditValue = ew_HtmlEncode($this->desc_en->CurrentValue);
		$this->desc_en->PlaceHolder = ew_RemoveHtml($this->desc_en->FldCaption());

		// desc_ar
		$this->desc_ar->EditAttrs["class"] = "form-control";
		$this->desc_ar->EditCustomAttributes = "";
		$this->desc_ar->EditValue = ew_HtmlEncode($this->desc_ar->CurrentValue);
		$this->desc_ar->PlaceHolder = ew_RemoveHtml($this->desc_ar->FldCaption());

		// created
		$this->created->EditAttrs["class"] = "form-control";
		$this->created->EditCustomAttributes = "";
		$this->created->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->created->CurrentValue, 7));
		$this->created->PlaceHolder = ew_RemoveHtml($this->created->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->image->Exportable) $Doc->ExportCaption($this->image);
					if ($this->title_en->Exportable) $Doc->ExportCaption($this->title_en);
					if ($this->title_ar->Exportable) $Doc->ExportCaption($this->title_ar);
					if ($this->position_en->Exportable) $Doc->ExportCaption($this->position_en);
					if ($this->position_ar->Exportable) $Doc->ExportCaption($this->position_ar);
					if ($this->experience->Exportable) $Doc->ExportCaption($this->experience);
					if ($this->gender->Exportable) $Doc->ExportCaption($this->gender);
					if ($this->applied->Exportable) $Doc->ExportCaption($this->applied);
					if ($this->desc_en->Exportable) $Doc->ExportCaption($this->desc_en);
					if ($this->desc_ar->Exportable) $Doc->ExportCaption($this->desc_ar);
					if ($this->created->Exportable) $Doc->ExportCaption($this->created);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->image->Exportable) $Doc->ExportCaption($this->image);
					if ($this->title_en->Exportable) $Doc->ExportCaption($this->title_en);
					if ($this->title_ar->Exportable) $Doc->ExportCaption($this->title_ar);
					if ($this->position_en->Exportable) $Doc->ExportCaption($this->position_en);
					if ($this->position_ar->Exportable) $Doc->ExportCaption($this->position_ar);
					if ($this->experience->Exportable) $Doc->ExportCaption($this->experience);
					if ($this->gender->Exportable) $Doc->ExportCaption($this->gender);
					if ($this->applied->Exportable) $Doc->ExportCaption($this->applied);
					if ($this->desc_en->Exportable) $Doc->ExportCaption($this->desc_en);
					if ($this->desc_ar->Exportable) $Doc->ExportCaption($this->desc_ar);
					if ($this->created->Exportable) $Doc->ExportCaption($this->created);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->image->Exportable) $Doc->ExportField($this->image);
						if ($this->title_en->Exportable) $Doc->ExportField($this->title_en);
						if ($this->title_ar->Exportable) $Doc->ExportField($this->title_ar);
						if ($this->position_en->Exportable) $Doc->ExportField($this->position_en);
						if ($this->position_ar->Exportable) $Doc->ExportField($this->position_ar);
						if ($this->experience->Exportable) $Doc->ExportField($this->experience);
						if ($this->gender->Exportable) $Doc->ExportField($this->gender);
						if ($this->applied->Exportable) $Doc->ExportField($this->applied);
						if ($this->desc_en->Exportable) $Doc->ExportField($this->desc_en);
						if ($this->desc_ar->Exportable) $Doc->ExportField($this->desc_ar);
						if ($this->created->Exportable) $Doc->ExportField($this->created);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->image->Exportable) $Doc->ExportField($this->image);
						if ($this->title_en->Exportable) $Doc->ExportField($this->title_en);
						if ($this->title_ar->Exportable) $Doc->ExportField($this->title_ar);
						if ($this->position_en->Exportable) $Doc->ExportField($this->position_en);
						if ($this->position_ar->Exportable) $Doc->ExportField($this->position_ar);
						if ($this->experience->Exportable) $Doc->ExportField($this->experience);
						if ($this->gender->Exportable) $Doc->ExportField($this->gender);
						if ($this->applied->Exportable) $Doc->ExportField($this->applied);
						if ($this->desc_en->Exportable) $Doc->ExportField($this->desc_en);
						if ($this->desc_ar->Exportable) $Doc->ExportField($this->desc_ar);
						if ($this->created->Exportable) $Doc->ExportField($this->created);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
