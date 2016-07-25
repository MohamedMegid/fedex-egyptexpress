<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(2, "mi_accounts", $Language->MenuPhrase("2", "MenuText"), "accountslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(12, "mi_pickup_requests", $Language->MenuPhrase("12", "MenuText"), "pickup_requestslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, "mi_contact_inquiries", $Language->MenuPhrase("4", "MenuText"), "contact_inquirieslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(8, "mi_jobs", $Language->MenuPhrase("8", "MenuText"), "jobslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(7, "mi_job_candidates", $Language->MenuPhrase("7", "MenuText"), "job_candidateslist.php?cmd=resetall", 8, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(15, "mci_Website_Settings", $Language->MenuPhrase("15", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(3, "mi_cms", $Language->MenuPhrase("3", "MenuText"), "cmslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(6, "mi_home_page_panels", $Language->MenuPhrase("6", "MenuText"), "home_page_panelslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(13, "mi_slider", $Language->MenuPhrase("13", "MenuText"), "sliderlist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(9, "mi_latest_shots", $Language->MenuPhrase("9", "MenuText"), "latest_shotslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(10, "mi_our_clients", $Language->MenuPhrase("10", "MenuText"), "our_clientslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(11, "mi_our_teams", $Language->MenuPhrase("11", "MenuText"), "our_teamslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(17, "mi_services", $Language->MenuPhrase("17", "MenuText"), "serviceslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(5, "mi_faq", $Language->MenuPhrase("5", "MenuText"), "faqlist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(14, "mi_translation", $Language->MenuPhrase("14", "MenuText"), "translationlist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(16, "mi_branches", $Language->MenuPhrase("16", "MenuText"), "brancheslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(18, "mi_integra_status", $Language->MenuPhrase("18", "MenuText"), "integra_statuslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(20, "mi_notification_emails", $Language->MenuPhrase("20", "MenuText"), "notification_emailslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(19, "mi_contactus_departments", $Language->MenuPhrase("19", "MenuText"), "contactus_departmentslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(21, "mi_zones", $Language->MenuPhrase("21", "MenuText"), "zoneslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(22, "mi_zones_prices", $Language->MenuPhrase("22", "MenuText"), "zones_priceslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-2, "mi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
