<?php

// Global variable for table object
$zones_prices = NULL;

//
// Table class for zones_prices
//
class czones_prices extends cTable {
	var $weight;
	var $zone1;
	var $zone2;
	var $zone3;
	var $zone4;
	var $zone5;
	var $zone6;
	var $last_modified;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'zones_prices';
		$this->TableName = 'zones_prices';
		$this->TableType = 'TABLE';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// weight
		$this->weight = new cField('zones_prices', 'zones_prices', 'x_weight', 'weight', '`weight`', '`weight`', 3, -1, FALSE, '`weight`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->weight->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['weight'] = &$this->weight;

		// zone1
		$this->zone1 = new cField('zones_prices', 'zones_prices', 'x_zone1', 'zone1', '`zone1`', '`zone1`', 4, -1, FALSE, '`zone1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->zone1->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['zone1'] = &$this->zone1;

		// zone2
		$this->zone2 = new cField('zones_prices', 'zones_prices', 'x_zone2', 'zone2', '`zone2`', '`zone2`', 4, -1, FALSE, '`zone2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->zone2->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['zone2'] = &$this->zone2;

		// zone3
		$this->zone3 = new cField('zones_prices', 'zones_prices', 'x_zone3', 'zone3', '`zone3`', '`zone3`', 4, -1, FALSE, '`zone3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->zone3->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['zone3'] = &$this->zone3;

		// zone4
		$this->zone4 = new cField('zones_prices', 'zones_prices', 'x_zone4', 'zone4', '`zone4`', '`zone4`', 4, -1, FALSE, '`zone4`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->zone4->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['zone4'] = &$this->zone4;

		// zone5
		$this->zone5 = new cField('zones_prices', 'zones_prices', 'x_zone5', 'zone5', '`zone5`', '`zone5`', 4, -1, FALSE, '`zone5`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->zone5->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['zone5'] = &$this->zone5;

		// zone6
		$this->zone6 = new cField('zones_prices', 'zones_prices', 'x_zone6', 'zone6', '`zone6`', '`zone6`', 4, -1, FALSE, '`zone6`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->zone6->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['zone6'] = &$this->zone6;

		// last_modified
		$this->last_modified = new cField('zones_prices', 'zones_prices', 'x_last_modified', 'last_modified', '`last_modified`', 'DATE_FORMAT(`last_modified`, \'%d/%m/%Y\')', 135, 7, FALSE, '`last_modified`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->last_modified->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['last_modified'] = &$this->last_modified;
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

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`zones_prices`";
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
	var $UpdateTable = "`zones_prices`";

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
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('weight', $rs))
				ew_AddFilter($where, ew_QuotedName('weight') . '=' . ew_QuotedValue($rs['weight'], $this->weight->FldDataType));
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
		return "`weight` = @weight@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->weight->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@weight@", ew_AdjustSql($this->weight->CurrentValue), $sKeyFilter); // Replace key value
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
			return "zones_priceslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "zones_priceslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("zones_pricesview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("zones_pricesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "zones_pricesadd.php?" . $this->UrlParm($parm);
		else
			return "zones_pricesadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("zones_pricesedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("zones_pricesadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("zones_pricesdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->weight->CurrentValue)) {
			$sUrl .= "weight=" . urlencode($this->weight->CurrentValue);
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
			$arKeys[] = @$_GET["weight"]; // weight

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
			$this->weight->CurrentValue = $key;
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
		$this->weight->setDbValue($rs->fields('weight'));
		$this->zone1->setDbValue($rs->fields('zone1'));
		$this->zone2->setDbValue($rs->fields('zone2'));
		$this->zone3->setDbValue($rs->fields('zone3'));
		$this->zone4->setDbValue($rs->fields('zone4'));
		$this->zone5->setDbValue($rs->fields('zone5'));
		$this->zone6->setDbValue($rs->fields('zone6'));
		$this->last_modified->setDbValue($rs->fields('last_modified'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// weight
		// zone1
		// zone2
		// zone3
		// zone4
		// zone5
		// zone6
		// last_modified
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// weight
		$this->weight->EditAttrs["class"] = "form-control";
		$this->weight->EditCustomAttributes = "";
		$this->weight->EditValue = $this->weight->CurrentValue;
		$this->weight->ViewCustomAttributes = "";

		// zone1
		$this->zone1->EditAttrs["class"] = "form-control";
		$this->zone1->EditCustomAttributes = "";
		$this->zone1->EditValue = ew_HtmlEncode($this->zone1->CurrentValue);
		$this->zone1->PlaceHolder = ew_RemoveHtml($this->zone1->FldCaption());
		if (strval($this->zone1->EditValue) <> "" && is_numeric($this->zone1->EditValue)) $this->zone1->EditValue = ew_FormatNumber($this->zone1->EditValue, -2, -1, -2, 0);

		// zone2
		$this->zone2->EditAttrs["class"] = "form-control";
		$this->zone2->EditCustomAttributes = "";
		$this->zone2->EditValue = ew_HtmlEncode($this->zone2->CurrentValue);
		$this->zone2->PlaceHolder = ew_RemoveHtml($this->zone2->FldCaption());
		if (strval($this->zone2->EditValue) <> "" && is_numeric($this->zone2->EditValue)) $this->zone2->EditValue = ew_FormatNumber($this->zone2->EditValue, -2, -1, -2, 0);

		// zone3
		$this->zone3->EditAttrs["class"] = "form-control";
		$this->zone3->EditCustomAttributes = "";
		$this->zone3->EditValue = ew_HtmlEncode($this->zone3->CurrentValue);
		$this->zone3->PlaceHolder = ew_RemoveHtml($this->zone3->FldCaption());
		if (strval($this->zone3->EditValue) <> "" && is_numeric($this->zone3->EditValue)) $this->zone3->EditValue = ew_FormatNumber($this->zone3->EditValue, -2, -1, -2, 0);

		// zone4
		$this->zone4->EditAttrs["class"] = "form-control";
		$this->zone4->EditCustomAttributes = "";
		$this->zone4->EditValue = ew_HtmlEncode($this->zone4->CurrentValue);
		$this->zone4->PlaceHolder = ew_RemoveHtml($this->zone4->FldCaption());
		if (strval($this->zone4->EditValue) <> "" && is_numeric($this->zone4->EditValue)) $this->zone4->EditValue = ew_FormatNumber($this->zone4->EditValue, -2, -1, -2, 0);

		// zone5
		$this->zone5->EditAttrs["class"] = "form-control";
		$this->zone5->EditCustomAttributes = "";
		$this->zone5->EditValue = ew_HtmlEncode($this->zone5->CurrentValue);
		$this->zone5->PlaceHolder = ew_RemoveHtml($this->zone5->FldCaption());
		if (strval($this->zone5->EditValue) <> "" && is_numeric($this->zone5->EditValue)) $this->zone5->EditValue = ew_FormatNumber($this->zone5->EditValue, -2, -1, -2, 0);

		// zone6
		$this->zone6->EditAttrs["class"] = "form-control";
		$this->zone6->EditCustomAttributes = "";
		$this->zone6->EditValue = ew_HtmlEncode($this->zone6->CurrentValue);
		$this->zone6->PlaceHolder = ew_RemoveHtml($this->zone6->FldCaption());
		if (strval($this->zone6->EditValue) <> "" && is_numeric($this->zone6->EditValue)) $this->zone6->EditValue = ew_FormatNumber($this->zone6->EditValue, -2, -1, -2, 0);

		// last_modified
		$this->last_modified->EditAttrs["class"] = "form-control";
		$this->last_modified->EditCustomAttributes = "";
		$this->last_modified->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->last_modified->CurrentValue, 7));
		$this->last_modified->PlaceHolder = ew_RemoveHtml($this->last_modified->FldCaption());

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
					if ($this->weight->Exportable) $Doc->ExportCaption($this->weight);
					if ($this->zone1->Exportable) $Doc->ExportCaption($this->zone1);
					if ($this->zone2->Exportable) $Doc->ExportCaption($this->zone2);
					if ($this->zone3->Exportable) $Doc->ExportCaption($this->zone3);
					if ($this->zone4->Exportable) $Doc->ExportCaption($this->zone4);
					if ($this->zone5->Exportable) $Doc->ExportCaption($this->zone5);
					if ($this->zone6->Exportable) $Doc->ExportCaption($this->zone6);
					if ($this->last_modified->Exportable) $Doc->ExportCaption($this->last_modified);
				} else {
					if ($this->weight->Exportable) $Doc->ExportCaption($this->weight);
					if ($this->zone1->Exportable) $Doc->ExportCaption($this->zone1);
					if ($this->zone2->Exportable) $Doc->ExportCaption($this->zone2);
					if ($this->zone3->Exportable) $Doc->ExportCaption($this->zone3);
					if ($this->zone4->Exportable) $Doc->ExportCaption($this->zone4);
					if ($this->zone5->Exportable) $Doc->ExportCaption($this->zone5);
					if ($this->zone6->Exportable) $Doc->ExportCaption($this->zone6);
					if ($this->last_modified->Exportable) $Doc->ExportCaption($this->last_modified);
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
						if ($this->weight->Exportable) $Doc->ExportField($this->weight);
						if ($this->zone1->Exportable) $Doc->ExportField($this->zone1);
						if ($this->zone2->Exportable) $Doc->ExportField($this->zone2);
						if ($this->zone3->Exportable) $Doc->ExportField($this->zone3);
						if ($this->zone4->Exportable) $Doc->ExportField($this->zone4);
						if ($this->zone5->Exportable) $Doc->ExportField($this->zone5);
						if ($this->zone6->Exportable) $Doc->ExportField($this->zone6);
						if ($this->last_modified->Exportable) $Doc->ExportField($this->last_modified);
					} else {
						if ($this->weight->Exportable) $Doc->ExportField($this->weight);
						if ($this->zone1->Exportable) $Doc->ExportField($this->zone1);
						if ($this->zone2->Exportable) $Doc->ExportField($this->zone2);
						if ($this->zone3->Exportable) $Doc->ExportField($this->zone3);
						if ($this->zone4->Exportable) $Doc->ExportField($this->zone4);
						if ($this->zone5->Exportable) $Doc->ExportField($this->zone5);
						if ($this->zone6->Exportable) $Doc->ExportField($this->zone6);
						if ($this->last_modified->Exportable) $Doc->ExportField($this->last_modified);
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
