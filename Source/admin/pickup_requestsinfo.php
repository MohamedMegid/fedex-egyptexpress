<?php

// Global variable for table object
$pickup_requests = NULL;

//
// Table class for pickup_requests
//
class cpickup_requests extends cTable {
	var $id;
	var $account_id;
	var $from_time;
	var $to_time;
	var $contact_name;
	var $account_type;
	var $account_number;
	var $company;
	var $contact_phone;
	var $_email;
	var $content;
	var $weight;
	var $source_pickup_address;
	var $source_pickup_city;
	var $source_governorate;
	var $destination_pickup_address;
	var $destination_pickup_city;
	var $destination_governorate;
	var $no_of_pieces;
	var $pickup_date;
	var $product_type;
	var $status;
	var $created;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'pickup_requests';
		$this->TableName = 'pickup_requests';
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

		// id
		$this->id = new cField('pickup_requests', 'pickup_requests', 'x_id', 'id', '`id`', '`id`', 19, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// account_id
		$this->account_id = new cField('pickup_requests', 'pickup_requests', 'x_account_id', 'account_id', '`account_id`', '`account_id`', 19, -1, FALSE, '`EV__account_id`', TRUE, TRUE, FALSE, 'FORMATTED TEXT');
		$this->account_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['account_id'] = &$this->account_id;

		// from_time
		$this->from_time = new cField('pickup_requests', 'pickup_requests', 'x_from_time', 'from_time', '`from_time`', '`from_time`', 200, -1, FALSE, '`from_time`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['from_time'] = &$this->from_time;

		// to_time
		$this->to_time = new cField('pickup_requests', 'pickup_requests', 'x_to_time', 'to_time', '`to_time`', '`to_time`', 200, -1, FALSE, '`to_time`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['to_time'] = &$this->to_time;

		// contact_name
		$this->contact_name = new cField('pickup_requests', 'pickup_requests', 'x_contact_name', 'contact_name', '`contact_name`', '`contact_name`', 200, -1, FALSE, '`contact_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['contact_name'] = &$this->contact_name;

		// account_type
		$this->account_type = new cField('pickup_requests', 'pickup_requests', 'x_account_type', 'account_type', '`account_type`', '`account_type`', 200, -1, FALSE, '`account_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['account_type'] = &$this->account_type;

		// account_number
		$this->account_number = new cField('pickup_requests', 'pickup_requests', 'x_account_number', 'account_number', '`account_number`', '`account_number`', 200, -1, FALSE, '`account_number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['account_number'] = &$this->account_number;

		// company
		$this->company = new cField('pickup_requests', 'pickup_requests', 'x_company', 'company', '`company`', '`company`', 200, -1, FALSE, '`company`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['company'] = &$this->company;

		// contact_phone
		$this->contact_phone = new cField('pickup_requests', 'pickup_requests', 'x_contact_phone', 'contact_phone', '`contact_phone`', '`contact_phone`', 200, -1, FALSE, '`contact_phone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['contact_phone'] = &$this->contact_phone;

		// email
		$this->_email = new cField('pickup_requests', 'pickup_requests', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['email'] = &$this->_email;

		// content
		$this->content = new cField('pickup_requests', 'pickup_requests', 'x_content', 'content', '`content`', '`content`', 201, -1, FALSE, '`content`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['content'] = &$this->content;

		// weight
		$this->weight = new cField('pickup_requests', 'pickup_requests', 'x_weight', 'weight', '`weight`', '`weight`', 4, -1, FALSE, '`weight`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->weight->FldDefaultErrMsg = $Language->Phrase("IncorrectFloat");
		$this->fields['weight'] = &$this->weight;

		// source_pickup_address
		$this->source_pickup_address = new cField('pickup_requests', 'pickup_requests', 'x_source_pickup_address', 'source_pickup_address', '`source_pickup_address`', '`source_pickup_address`', 201, -1, FALSE, '`source_pickup_address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['source_pickup_address'] = &$this->source_pickup_address;

		// source_pickup_city
		$this->source_pickup_city = new cField('pickup_requests', 'pickup_requests', 'x_source_pickup_city', 'source_pickup_city', '`source_pickup_city`', '`source_pickup_city`', 200, -1, FALSE, '`source_pickup_city`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['source_pickup_city'] = &$this->source_pickup_city;

		// source_governorate
		$this->source_governorate = new cField('pickup_requests', 'pickup_requests', 'x_source_governorate', 'source_governorate', '`source_governorate`', '`source_governorate`', 200, -1, FALSE, '`source_governorate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['source_governorate'] = &$this->source_governorate;

		// destination_pickup_address
		$this->destination_pickup_address = new cField('pickup_requests', 'pickup_requests', 'x_destination_pickup_address', 'destination_pickup_address', '`destination_pickup_address`', '`destination_pickup_address`', 201, -1, FALSE, '`destination_pickup_address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['destination_pickup_address'] = &$this->destination_pickup_address;

		// destination_pickup_city
		$this->destination_pickup_city = new cField('pickup_requests', 'pickup_requests', 'x_destination_pickup_city', 'destination_pickup_city', '`destination_pickup_city`', '`destination_pickup_city`', 200, -1, FALSE, '`destination_pickup_city`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['destination_pickup_city'] = &$this->destination_pickup_city;

		// destination_governorate
		$this->destination_governorate = new cField('pickup_requests', 'pickup_requests', 'x_destination_governorate', 'destination_governorate', '`destination_governorate`', '`destination_governorate`', 200, -1, FALSE, '`destination_governorate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['destination_governorate'] = &$this->destination_governorate;

		// no_of_pieces
		$this->no_of_pieces = new cField('pickup_requests', 'pickup_requests', 'x_no_of_pieces', 'no_of_pieces', '`no_of_pieces`', '`no_of_pieces`', 2, -1, FALSE, '`no_of_pieces`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->no_of_pieces->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['no_of_pieces'] = &$this->no_of_pieces;

		// pickup_date
		$this->pickup_date = new cField('pickup_requests', 'pickup_requests', 'x_pickup_date', 'pickup_date', '`pickup_date`', 'DATE_FORMAT(`pickup_date`, \'%d/%m/%Y\')', 133, 7, FALSE, '`pickup_date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->pickup_date->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateDMY"));
		$this->fields['pickup_date'] = &$this->pickup_date;

		// product_type
		$this->product_type = new cField('pickup_requests', 'pickup_requests', 'x_product_type', 'product_type', '`product_type`', '`product_type`', 200, -1, FALSE, '`product_type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['product_type'] = &$this->product_type;

		// status
		$this->status = new cField('pickup_requests', 'pickup_requests', 'x_status', 'status', '`status`', '`status`', 202, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['status'] = &$this->status;

		// created
		$this->created = new cField('pickup_requests', 'pickup_requests', 'x_created', 'created', '`created`', 'DATE_FORMAT(`created`, \'%d/%m/%Y\')', 135, 7, FALSE, '`created`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
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
			$sSortFieldList = ($ofld->FldVirtualExpression <> "") ? $ofld->FldVirtualExpression : $sSortField;
			$this->setSessionOrderByList($sSortFieldList . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Session ORDER BY for List page
	function getSessionOrderByList() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST];
	}

	function setSessionOrderByList($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_ORDER_BY_LIST] = $v;
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`pickup_requests`";
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
	var $_SqlSelectList = "";

	function getSqlSelectList() { // Select for List page
		$select = "";
		$select = "SELECT * FROM (" .
			"SELECT *, (SELECT DISTINCT CONCAT(`first_name`,'" . ew_ValueSeparator(1, $this->account_id) . "',`last_name`) FROM `accounts` `EW_TMP_LOOKUPTABLE` WHERE `EW_TMP_LOOKUPTABLE`.`id` = `pickup_requests`.`account_id` LIMIT 1) AS `EV__account_id` FROM `pickup_requests`" .
			") `EW_TMP_TABLE`";
		return ($this->_SqlSelectList <> "") ? $this->_SqlSelectList : $select;
	}

	function SqlSelectList() { // For backward compatibility
    	return $this->getSqlSelectList();
	}

	function setSqlSelectList($v) {
    	$this->_SqlSelectList = $v;
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
		if ($this->UseVirtualFields()) {
			$sSort = $this->getSessionOrderByList();
			return ew_BuildSelectSql($this->getSqlSelectList(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		} else {
			$sSort = $this->getSessionOrderBy();
			return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
				$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
		}
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = ($this->UseVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Check if virtual fields is used in SQL
	function UseVirtualFields() {
		$sWhere = $this->getSessionWhere();
		$sOrderBy = $this->getSessionOrderByList();
		if ($sWhere <> "")
			$sWhere = " " . str_replace(array("(",")"), array("",""), $sWhere) . " ";
		if ($sOrderBy <> "")
			$sOrderBy = " " . str_replace(array("(",")"), array("",""), $sOrderBy) . " ";
		if (strpos($sOrderBy, " " . $this->account_id->FldVirtualExpression . " ") !== FALSE)
			return TRUE;
		return FALSE;
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
	var $UpdateTable = "`pickup_requests`";

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
			return "pickup_requestslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "pickup_requestslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("pickup_requestsview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("pickup_requestsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "pickup_requestsadd.php?" . $this->UrlParm($parm);
		else
			return "pickup_requestsadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("pickup_requestsedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("pickup_requestsadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("pickup_requestsdelete.php", $this->UrlParm());
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
		$this->account_id->setDbValue($rs->fields('account_id'));
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// account_id
		$this->account_id->EditAttrs["class"] = "form-control";
		$this->account_id->EditCustomAttributes = "";

		// from_time
		$this->from_time->EditAttrs["class"] = "form-control";
		$this->from_time->EditCustomAttributes = "";
		$this->from_time->EditValue = ew_HtmlEncode($this->from_time->CurrentValue);
		$this->from_time->PlaceHolder = ew_RemoveHtml($this->from_time->FldCaption());

		// to_time
		$this->to_time->EditAttrs["class"] = "form-control";
		$this->to_time->EditCustomAttributes = "";
		$this->to_time->EditValue = ew_HtmlEncode($this->to_time->CurrentValue);
		$this->to_time->PlaceHolder = ew_RemoveHtml($this->to_time->FldCaption());

		// contact_name
		$this->contact_name->EditAttrs["class"] = "form-control";
		$this->contact_name->EditCustomAttributes = "";
		$this->contact_name->EditValue = ew_HtmlEncode($this->contact_name->CurrentValue);
		$this->contact_name->PlaceHolder = ew_RemoveHtml($this->contact_name->FldCaption());

		// account_type
		$this->account_type->EditAttrs["class"] = "form-control";
		$this->account_type->EditCustomAttributes = "";

		// account_number
		$this->account_number->EditAttrs["class"] = "form-control";
		$this->account_number->EditCustomAttributes = "";
		$this->account_number->EditValue = ew_HtmlEncode($this->account_number->CurrentValue);
		$this->account_number->PlaceHolder = ew_RemoveHtml($this->account_number->FldCaption());

		// company
		$this->company->EditAttrs["class"] = "form-control";
		$this->company->EditCustomAttributes = "";
		$this->company->EditValue = ew_HtmlEncode($this->company->CurrentValue);
		$this->company->PlaceHolder = ew_RemoveHtml($this->company->FldCaption());

		// contact_phone
		$this->contact_phone->EditAttrs["class"] = "form-control";
		$this->contact_phone->EditCustomAttributes = "";
		$this->contact_phone->EditValue = ew_HtmlEncode($this->contact_phone->CurrentValue);
		$this->contact_phone->PlaceHolder = ew_RemoveHtml($this->contact_phone->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// content
		$this->content->EditAttrs["class"] = "form-control";
		$this->content->EditCustomAttributes = "";
		$this->content->EditValue = ew_HtmlEncode($this->content->CurrentValue);
		$this->content->PlaceHolder = ew_RemoveHtml($this->content->FldCaption());

		// weight
		$this->weight->EditAttrs["class"] = "form-control";
		$this->weight->EditCustomAttributes = "";
		$this->weight->EditValue = ew_HtmlEncode($this->weight->CurrentValue);
		$this->weight->PlaceHolder = ew_RemoveHtml($this->weight->FldCaption());
		if (strval($this->weight->EditValue) <> "" && is_numeric($this->weight->EditValue)) $this->weight->EditValue = ew_FormatNumber($this->weight->EditValue, -2, -1, -2, 0);

		// source_pickup_address
		$this->source_pickup_address->EditAttrs["class"] = "form-control";
		$this->source_pickup_address->EditCustomAttributes = "";
		$this->source_pickup_address->EditValue = ew_HtmlEncode($this->source_pickup_address->CurrentValue);
		$this->source_pickup_address->PlaceHolder = ew_RemoveHtml($this->source_pickup_address->FldCaption());

		// source_pickup_city
		$this->source_pickup_city->EditAttrs["class"] = "form-control";
		$this->source_pickup_city->EditCustomAttributes = "";
		$this->source_pickup_city->EditValue = ew_HtmlEncode($this->source_pickup_city->CurrentValue);
		$this->source_pickup_city->PlaceHolder = ew_RemoveHtml($this->source_pickup_city->FldCaption());

		// source_governorate
		$this->source_governorate->EditAttrs["class"] = "form-control";
		$this->source_governorate->EditCustomAttributes = "";
		$this->source_governorate->EditValue = ew_HtmlEncode($this->source_governorate->CurrentValue);
		$this->source_governorate->PlaceHolder = ew_RemoveHtml($this->source_governorate->FldCaption());

		// destination_pickup_address
		$this->destination_pickup_address->EditAttrs["class"] = "form-control";
		$this->destination_pickup_address->EditCustomAttributes = "";
		$this->destination_pickup_address->EditValue = ew_HtmlEncode($this->destination_pickup_address->CurrentValue);
		$this->destination_pickup_address->PlaceHolder = ew_RemoveHtml($this->destination_pickup_address->FldCaption());

		// destination_pickup_city
		$this->destination_pickup_city->EditAttrs["class"] = "form-control";
		$this->destination_pickup_city->EditCustomAttributes = "";
		$this->destination_pickup_city->EditValue = ew_HtmlEncode($this->destination_pickup_city->CurrentValue);
		$this->destination_pickup_city->PlaceHolder = ew_RemoveHtml($this->destination_pickup_city->FldCaption());

		// destination_governorate
		$this->destination_governorate->EditAttrs["class"] = "form-control";
		$this->destination_governorate->EditCustomAttributes = "";
		$this->destination_governorate->EditValue = ew_HtmlEncode($this->destination_governorate->CurrentValue);
		$this->destination_governorate->PlaceHolder = ew_RemoveHtml($this->destination_governorate->FldCaption());

		// no_of_pieces
		$this->no_of_pieces->EditAttrs["class"] = "form-control";
		$this->no_of_pieces->EditCustomAttributes = "";
		$this->no_of_pieces->EditValue = ew_HtmlEncode($this->no_of_pieces->CurrentValue);
		$this->no_of_pieces->PlaceHolder = ew_RemoveHtml($this->no_of_pieces->FldCaption());

		// pickup_date
		$this->pickup_date->EditAttrs["class"] = "form-control";
		$this->pickup_date->EditCustomAttributes = "";
		$this->pickup_date->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->pickup_date->CurrentValue, 7));
		$this->pickup_date->PlaceHolder = ew_RemoveHtml($this->pickup_date->FldCaption());

		// product_type
		$this->product_type->EditAttrs["class"] = "form-control";
		$this->product_type->EditCustomAttributes = "";
		$this->product_type->EditValue = ew_HtmlEncode($this->product_type->CurrentValue);
		$this->product_type->PlaceHolder = ew_RemoveHtml($this->product_type->FldCaption());

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
					if ($this->account_id->Exportable) $Doc->ExportCaption($this->account_id);
					if ($this->from_time->Exportable) $Doc->ExportCaption($this->from_time);
					if ($this->to_time->Exportable) $Doc->ExportCaption($this->to_time);
					if ($this->contact_name->Exportable) $Doc->ExportCaption($this->contact_name);
					if ($this->account_type->Exportable) $Doc->ExportCaption($this->account_type);
					if ($this->account_number->Exportable) $Doc->ExportCaption($this->account_number);
					if ($this->company->Exportable) $Doc->ExportCaption($this->company);
					if ($this->contact_phone->Exportable) $Doc->ExportCaption($this->contact_phone);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->content->Exportable) $Doc->ExportCaption($this->content);
					if ($this->weight->Exportable) $Doc->ExportCaption($this->weight);
					if ($this->source_pickup_address->Exportable) $Doc->ExportCaption($this->source_pickup_address);
					if ($this->source_pickup_city->Exportable) $Doc->ExportCaption($this->source_pickup_city);
					if ($this->source_governorate->Exportable) $Doc->ExportCaption($this->source_governorate);
					if ($this->destination_pickup_address->Exportable) $Doc->ExportCaption($this->destination_pickup_address);
					if ($this->destination_pickup_city->Exportable) $Doc->ExportCaption($this->destination_pickup_city);
					if ($this->destination_governorate->Exportable) $Doc->ExportCaption($this->destination_governorate);
					if ($this->no_of_pieces->Exportable) $Doc->ExportCaption($this->no_of_pieces);
					if ($this->pickup_date->Exportable) $Doc->ExportCaption($this->pickup_date);
					if ($this->product_type->Exportable) $Doc->ExportCaption($this->product_type);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->created->Exportable) $Doc->ExportCaption($this->created);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->account_id->Exportable) $Doc->ExportCaption($this->account_id);
					if ($this->from_time->Exportable) $Doc->ExportCaption($this->from_time);
					if ($this->to_time->Exportable) $Doc->ExportCaption($this->to_time);
					if ($this->contact_name->Exportable) $Doc->ExportCaption($this->contact_name);
					if ($this->account_type->Exportable) $Doc->ExportCaption($this->account_type);
					if ($this->account_number->Exportable) $Doc->ExportCaption($this->account_number);
					if ($this->company->Exportable) $Doc->ExportCaption($this->company);
					if ($this->contact_phone->Exportable) $Doc->ExportCaption($this->contact_phone);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->content->Exportable) $Doc->ExportCaption($this->content);
					if ($this->weight->Exportable) $Doc->ExportCaption($this->weight);
					if ($this->source_pickup_address->Exportable) $Doc->ExportCaption($this->source_pickup_address);
					if ($this->source_pickup_city->Exportable) $Doc->ExportCaption($this->source_pickup_city);
					if ($this->source_governorate->Exportable) $Doc->ExportCaption($this->source_governorate);
					if ($this->destination_pickup_city->Exportable) $Doc->ExportCaption($this->destination_pickup_city);
					if ($this->destination_governorate->Exportable) $Doc->ExportCaption($this->destination_governorate);
					if ($this->no_of_pieces->Exportable) $Doc->ExportCaption($this->no_of_pieces);
					if ($this->pickup_date->Exportable) $Doc->ExportCaption($this->pickup_date);
					if ($this->product_type->Exportable) $Doc->ExportCaption($this->product_type);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
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
						if ($this->account_id->Exportable) $Doc->ExportField($this->account_id);
						if ($this->from_time->Exportable) $Doc->ExportField($this->from_time);
						if ($this->to_time->Exportable) $Doc->ExportField($this->to_time);
						if ($this->contact_name->Exportable) $Doc->ExportField($this->contact_name);
						if ($this->account_type->Exportable) $Doc->ExportField($this->account_type);
						if ($this->account_number->Exportable) $Doc->ExportField($this->account_number);
						if ($this->company->Exportable) $Doc->ExportField($this->company);
						if ($this->contact_phone->Exportable) $Doc->ExportField($this->contact_phone);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->content->Exportable) $Doc->ExportField($this->content);
						if ($this->weight->Exportable) $Doc->ExportField($this->weight);
						if ($this->source_pickup_address->Exportable) $Doc->ExportField($this->source_pickup_address);
						if ($this->source_pickup_city->Exportable) $Doc->ExportField($this->source_pickup_city);
						if ($this->source_governorate->Exportable) $Doc->ExportField($this->source_governorate);
						if ($this->destination_pickup_address->Exportable) $Doc->ExportField($this->destination_pickup_address);
						if ($this->destination_pickup_city->Exportable) $Doc->ExportField($this->destination_pickup_city);
						if ($this->destination_governorate->Exportable) $Doc->ExportField($this->destination_governorate);
						if ($this->no_of_pieces->Exportable) $Doc->ExportField($this->no_of_pieces);
						if ($this->pickup_date->Exportable) $Doc->ExportField($this->pickup_date);
						if ($this->product_type->Exportable) $Doc->ExportField($this->product_type);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->created->Exportable) $Doc->ExportField($this->created);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->account_id->Exportable) $Doc->ExportField($this->account_id);
						if ($this->from_time->Exportable) $Doc->ExportField($this->from_time);
						if ($this->to_time->Exportable) $Doc->ExportField($this->to_time);
						if ($this->contact_name->Exportable) $Doc->ExportField($this->contact_name);
						if ($this->account_type->Exportable) $Doc->ExportField($this->account_type);
						if ($this->account_number->Exportable) $Doc->ExportField($this->account_number);
						if ($this->company->Exportable) $Doc->ExportField($this->company);
						if ($this->contact_phone->Exportable) $Doc->ExportField($this->contact_phone);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->content->Exportable) $Doc->ExportField($this->content);
						if ($this->weight->Exportable) $Doc->ExportField($this->weight);
						if ($this->source_pickup_address->Exportable) $Doc->ExportField($this->source_pickup_address);
						if ($this->source_pickup_city->Exportable) $Doc->ExportField($this->source_pickup_city);
						if ($this->source_governorate->Exportable) $Doc->ExportField($this->source_governorate);
						if ($this->destination_pickup_city->Exportable) $Doc->ExportField($this->destination_pickup_city);
						if ($this->destination_governorate->Exportable) $Doc->ExportField($this->destination_governorate);
						if ($this->no_of_pieces->Exportable) $Doc->ExportField($this->no_of_pieces);
						if ($this->pickup_date->Exportable) $Doc->ExportField($this->pickup_date);
						if ($this->product_type->Exportable) $Doc->ExportField($this->product_type);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
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
