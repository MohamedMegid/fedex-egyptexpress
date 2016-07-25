<?php

// Global variable for table object
$accounts = NULL;

//
// Table class for accounts
//
class caccounts extends cTable {
	var $id;
	var $avatar;
	var $first_name;
	var $last_name;
	var $_email;
	var $integra_account_id;
	var $company;
	var $job_title;
	var $phone;
	var $address;
	var $ship_monthly;
	var $commercial_register;
	var $password;
	var $status;
	var $created;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'accounts';
		$this->TableName = 'accounts';
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
		$this->id = new cField('accounts', 'accounts', 'x_id', 'id', '`id`', '`id`', 3, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// avatar
		$this->avatar = new cField('accounts', 'accounts', 'x_avatar', 'avatar', '`avatar`', '`avatar`', 200, -1, TRUE, '`avatar`', FALSE, FALSE, FALSE, 'IMAGE');
		$this->fields['avatar'] = &$this->avatar;

		// first_name
		$this->first_name = new cField('accounts', 'accounts', 'x_first_name', 'first_name', '`first_name`', '`first_name`', 200, -1, FALSE, '`first_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['first_name'] = &$this->first_name;

		// last_name
		$this->last_name = new cField('accounts', 'accounts', 'x_last_name', 'last_name', '`last_name`', '`last_name`', 200, -1, FALSE, '`last_name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['last_name'] = &$this->last_name;

		// email
		$this->_email = new cField('accounts', 'accounts', 'x__email', 'email', '`email`', '`email`', 200, -1, FALSE, '`email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['email'] = &$this->_email;

		// integra_account_id
		$this->integra_account_id = new cField('accounts', 'accounts', 'x_integra_account_id', 'integra_account_id', '`integra_account_id`', '`integra_account_id`', 200, -1, FALSE, '`integra_account_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['integra_account_id'] = &$this->integra_account_id;

		// company
		$this->company = new cField('accounts', 'accounts', 'x_company', 'company', '`company`', '`company`', 200, -1, FALSE, '`company`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['company'] = &$this->company;

		// job_title
		$this->job_title = new cField('accounts', 'accounts', 'x_job_title', 'job_title', '`job_title`', '`job_title`', 200, -1, FALSE, '`job_title`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['job_title'] = &$this->job_title;

		// phone
		$this->phone = new cField('accounts', 'accounts', 'x_phone', 'phone', '`phone`', '`phone`', 3, -1, FALSE, '`phone`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->phone->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['phone'] = &$this->phone;

		// address
		$this->address = new cField('accounts', 'accounts', 'x_address', 'address', '`address`', '`address`', 200, -1, FALSE, '`address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['address'] = &$this->address;

		// ship_monthly
		$this->ship_monthly = new cField('accounts', 'accounts', 'x_ship_monthly', 'ship_monthly', '`ship_monthly`', '`ship_monthly`', 200, -1, FALSE, '`ship_monthly`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['ship_monthly'] = &$this->ship_monthly;

		// commercial_register
		$this->commercial_register = new cField('accounts', 'accounts', 'x_commercial_register', 'commercial_register', '`commercial_register`', '`commercial_register`', 200, -1, TRUE, '`commercial_register`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['commercial_register'] = &$this->commercial_register;

		// password
		$this->password = new cField('accounts', 'accounts', 'x_password', 'password', '`password`', '`password`', 200, -1, FALSE, '`password`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['password'] = &$this->password;

		// status
		$this->status = new cField('accounts', 'accounts', 'x_status', 'status', '`status`', '`status`', 202, -1, FALSE, '`status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['status'] = &$this->status;

		// created
		$this->created = new cField('accounts', 'accounts', 'x_created', 'created', '`created`', 'DATE_FORMAT(`created`, \'%d/%m/%Y\')', 135, 7, FALSE, '`created`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
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

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`accounts`";
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
	var $UpdateTable = "`accounts`";

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
			return "accountslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "accountslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("accountsview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("accountsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			return "accountsadd.php?" . $this->UrlParm($parm);
		else
			return "accountsadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("accountsedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("accountsadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("accountsdelete.php", $this->UrlParm());
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
		$this->avatar->Upload->DbValue = $rs->fields('avatar');
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
		$this->password->setDbValue($rs->fields('password'));
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
			$this->avatar->LinkAttrs["data-rel"] = "accounts_x_avatar";
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

		// address
		$this->address->LinkCustomAttributes = "";
		$this->address->HrefValue = "";
		$this->address->TooltipValue = "";

		// ship_monthly
		$this->ship_monthly->LinkCustomAttributes = "";
		$this->ship_monthly->HrefValue = "";
		$this->ship_monthly->TooltipValue = "";

		// commercial_register
		$this->commercial_register->LinkCustomAttributes = "";
		$this->commercial_register->UploadPath = '../webroot/uploads/accounts/';
		if (!ew_Empty($this->commercial_register->Upload->DbValue)) {
			$this->commercial_register->HrefValue = ew_UploadPathEx(FALSE, $this->commercial_register->UploadPath) . $this->commercial_register->Upload->DbValue; // Add prefix/suffix
			$this->commercial_register->LinkAttrs["target"] = "_blank"; // Add target
			if ($this->Export <> "") $this->commercial_register->HrefValue = ew_ConvertFullUrl($this->commercial_register->HrefValue);
		} else {
			$this->commercial_register->HrefValue = "";
		}
		$this->commercial_register->HrefValue2 = $this->commercial_register->UploadPath . $this->commercial_register->Upload->DbValue;
		$this->commercial_register->TooltipValue = "";

		// password
		$this->password->LinkCustomAttributes = "";
		$this->password->HrefValue = "";
		$this->password->TooltipValue = "";

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

		// avatar
		$this->avatar->EditAttrs["class"] = "form-control";
		$this->avatar->EditCustomAttributes = "";
		$this->avatar->UploadPath = '../webroot/uploads/accounts/';
		if (!ew_Empty($this->avatar->Upload->DbValue)) {
			$this->avatar->ImageWidth = 100;
			$this->avatar->ImageHeight = 0;
			$this->avatar->ImageAlt = $this->avatar->FldAlt();
			$this->avatar->EditValue = ew_UploadPathEx(FALSE, $this->avatar->UploadPath) . $this->avatar->Upload->DbValue;
			if ($this->CustomExport == "pdf" || $this->CustomExport == "email") {
				$this->avatar->EditValue = ew_UploadPathEx(TRUE, $this->avatar->UploadPath) . $this->avatar->Upload->DbValue;
			}
		} else {
			$this->avatar->EditValue = "";
		}
		if (!ew_Empty($this->avatar->CurrentValue))
			$this->avatar->Upload->FileName = $this->avatar->CurrentValue;

		// first_name
		$this->first_name->EditAttrs["class"] = "form-control";
		$this->first_name->EditCustomAttributes = "";
		$this->first_name->EditValue = ew_HtmlEncode($this->first_name->CurrentValue);
		$this->first_name->PlaceHolder = ew_RemoveHtml($this->first_name->FldCaption());

		// last_name
		$this->last_name->EditAttrs["class"] = "form-control";
		$this->last_name->EditCustomAttributes = "";
		$this->last_name->EditValue = ew_HtmlEncode($this->last_name->CurrentValue);
		$this->last_name->PlaceHolder = ew_RemoveHtml($this->last_name->FldCaption());

		// email
		$this->_email->EditAttrs["class"] = "form-control";
		$this->_email->EditCustomAttributes = "";
		$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);
		$this->_email->PlaceHolder = ew_RemoveHtml($this->_email->FldCaption());

		// integra_account_id
		$this->integra_account_id->EditAttrs["class"] = "form-control";
		$this->integra_account_id->EditCustomAttributes = "";
		$this->integra_account_id->EditValue = ew_HtmlEncode($this->integra_account_id->CurrentValue);
		$this->integra_account_id->PlaceHolder = ew_RemoveHtml($this->integra_account_id->FldCaption());

		// company
		$this->company->EditAttrs["class"] = "form-control";
		$this->company->EditCustomAttributes = "";
		$this->company->EditValue = ew_HtmlEncode($this->company->CurrentValue);
		$this->company->PlaceHolder = ew_RemoveHtml($this->company->FldCaption());

		// job_title
		$this->job_title->EditAttrs["class"] = "form-control";
		$this->job_title->EditCustomAttributes = "";
		$this->job_title->EditValue = ew_HtmlEncode($this->job_title->CurrentValue);
		$this->job_title->PlaceHolder = ew_RemoveHtml($this->job_title->FldCaption());

		// phone
		$this->phone->EditAttrs["class"] = "form-control";
		$this->phone->EditCustomAttributes = "";
		$this->phone->EditValue = ew_HtmlEncode($this->phone->CurrentValue);
		$this->phone->PlaceHolder = ew_RemoveHtml($this->phone->FldCaption());

		// address
		$this->address->EditAttrs["class"] = "form-control";
		$this->address->EditCustomAttributes = "";
		$this->address->EditValue = ew_HtmlEncode($this->address->CurrentValue);
		$this->address->PlaceHolder = ew_RemoveHtml($this->address->FldCaption());

		// ship_monthly
		$this->ship_monthly->EditAttrs["class"] = "form-control";
		$this->ship_monthly->EditCustomAttributes = "";
		$this->ship_monthly->EditValue = ew_HtmlEncode($this->ship_monthly->CurrentValue);
		$this->ship_monthly->PlaceHolder = ew_RemoveHtml($this->ship_monthly->FldCaption());

		// commercial_register
		$this->commercial_register->EditAttrs["class"] = "form-control";
		$this->commercial_register->EditCustomAttributes = "";
		$this->commercial_register->UploadPath = '../webroot/uploads/accounts/';
		if (!ew_Empty($this->commercial_register->Upload->DbValue)) {
			$this->commercial_register->EditValue = $this->commercial_register->Upload->DbValue;
		} else {
			$this->commercial_register->EditValue = "";
		}
		if (!ew_Empty($this->commercial_register->CurrentValue))
			$this->commercial_register->Upload->FileName = $this->commercial_register->CurrentValue;

		// password
		$this->password->EditAttrs["class"] = "form-control";
		$this->password->EditCustomAttributes = "";
		$this->password->EditValue = ew_HtmlEncode($this->password->CurrentValue);
		$this->password->PlaceHolder = ew_RemoveHtml($this->password->FldCaption());

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
			$this->id->Count++; // Increment count
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
			$this->id->CurrentValue = $this->id->Count;
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";
			$this->id->HrefValue = ""; // Clear href value
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
					if ($this->avatar->Exportable) $Doc->ExportCaption($this->avatar);
					if ($this->first_name->Exportable) $Doc->ExportCaption($this->first_name);
					if ($this->last_name->Exportable) $Doc->ExportCaption($this->last_name);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->integra_account_id->Exportable) $Doc->ExportCaption($this->integra_account_id);
					if ($this->company->Exportable) $Doc->ExportCaption($this->company);
					if ($this->job_title->Exportable) $Doc->ExportCaption($this->job_title);
					if ($this->phone->Exportable) $Doc->ExportCaption($this->phone);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->ship_monthly->Exportable) $Doc->ExportCaption($this->ship_monthly);
					if ($this->commercial_register->Exportable) $Doc->ExportCaption($this->commercial_register);
					if ($this->password->Exportable) $Doc->ExportCaption($this->password);
					if ($this->status->Exportable) $Doc->ExportCaption($this->status);
					if ($this->created->Exportable) $Doc->ExportCaption($this->created);
				} else {
					if ($this->id->Exportable) $Doc->ExportCaption($this->id);
					if ($this->avatar->Exportable) $Doc->ExportCaption($this->avatar);
					if ($this->first_name->Exportable) $Doc->ExportCaption($this->first_name);
					if ($this->last_name->Exportable) $Doc->ExportCaption($this->last_name);
					if ($this->_email->Exportable) $Doc->ExportCaption($this->_email);
					if ($this->integra_account_id->Exportable) $Doc->ExportCaption($this->integra_account_id);
					if ($this->company->Exportable) $Doc->ExportCaption($this->company);
					if ($this->job_title->Exportable) $Doc->ExportCaption($this->job_title);
					if ($this->phone->Exportable) $Doc->ExportCaption($this->phone);
					if ($this->address->Exportable) $Doc->ExportCaption($this->address);
					if ($this->ship_monthly->Exportable) $Doc->ExportCaption($this->ship_monthly);
					if ($this->commercial_register->Exportable) $Doc->ExportCaption($this->commercial_register);
					if ($this->password->Exportable) $Doc->ExportCaption($this->password);
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
				$this->AggregateListRowValues(); // Aggregate row values

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->avatar->Exportable) $Doc->ExportField($this->avatar);
						if ($this->first_name->Exportable) $Doc->ExportField($this->first_name);
						if ($this->last_name->Exportable) $Doc->ExportField($this->last_name);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->integra_account_id->Exportable) $Doc->ExportField($this->integra_account_id);
						if ($this->company->Exportable) $Doc->ExportField($this->company);
						if ($this->job_title->Exportable) $Doc->ExportField($this->job_title);
						if ($this->phone->Exportable) $Doc->ExportField($this->phone);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->ship_monthly->Exportable) $Doc->ExportField($this->ship_monthly);
						if ($this->commercial_register->Exportable) $Doc->ExportField($this->commercial_register);
						if ($this->password->Exportable) $Doc->ExportField($this->password);
						if ($this->status->Exportable) $Doc->ExportField($this->status);
						if ($this->created->Exportable) $Doc->ExportField($this->created);
					} else {
						if ($this->id->Exportable) $Doc->ExportField($this->id);
						if ($this->avatar->Exportable) $Doc->ExportField($this->avatar);
						if ($this->first_name->Exportable) $Doc->ExportField($this->first_name);
						if ($this->last_name->Exportable) $Doc->ExportField($this->last_name);
						if ($this->_email->Exportable) $Doc->ExportField($this->_email);
						if ($this->integra_account_id->Exportable) $Doc->ExportField($this->integra_account_id);
						if ($this->company->Exportable) $Doc->ExportField($this->company);
						if ($this->job_title->Exportable) $Doc->ExportField($this->job_title);
						if ($this->phone->Exportable) $Doc->ExportField($this->phone);
						if ($this->address->Exportable) $Doc->ExportField($this->address);
						if ($this->ship_monthly->Exportable) $Doc->ExportField($this->ship_monthly);
						if ($this->commercial_register->Exportable) $Doc->ExportField($this->commercial_register);
						if ($this->password->Exportable) $Doc->ExportField($this->password);
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

		// Export aggregates (horizontal format only)
		if ($Doc->Horizontal) {
			$this->RowType = EW_ROWTYPE_AGGREGATE;
			$this->ResetAttrs();
			$this->AggregateListRow();
			if (!$Doc->ExportCustom) {
				$Doc->BeginExportRow(-1);
				$Doc->ExportAggregate($this->id, 'COUNT');
				$Doc->ExportAggregate($this->avatar, '');
				$Doc->ExportAggregate($this->first_name, '');
				$Doc->ExportAggregate($this->last_name, '');
				$Doc->ExportAggregate($this->_email, '');
				$Doc->ExportAggregate($this->integra_account_id, '');
				$Doc->ExportAggregate($this->company, '');
				$Doc->ExportAggregate($this->job_title, '');
				$Doc->ExportAggregate($this->phone, '');
				$Doc->ExportAggregate($this->address, '');
				$Doc->ExportAggregate($this->ship_monthly, '');
				$Doc->ExportAggregate($this->commercial_register, '');
				$Doc->ExportAggregate($this->password, '');
				$Doc->ExportAggregate($this->status, '');
				$Doc->ExportAggregate($this->created, '');
				$Doc->EndExportRow();
			}
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
