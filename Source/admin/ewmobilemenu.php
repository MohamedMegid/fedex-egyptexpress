<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(2, "mmi_accounts", $Language->MenuPhrase("2", "MenuText"), "accountslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(12, "mmi_pickup_requests", $Language->MenuPhrase("12", "MenuText"), "pickup_requestslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(4, "mmi_contact_inquiries", $Language->MenuPhrase("4", "MenuText"), "contact_inquirieslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(8, "mmi_jobs", $Language->MenuPhrase("8", "MenuText"), "jobslist.php", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(7, "mmi_job_candidates", $Language->MenuPhrase("7", "MenuText"), "job_candidateslist.php?cmd=resetall", 8, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(15, "mmci_Website_Settings", $Language->MenuPhrase("15", "MenuText"), "", -1, "", IsLoggedIn(), TRUE, TRUE);
$RootMenu->AddMenuItem(3, "mmi_cms", $Language->MenuPhrase("3", "MenuText"), "cmslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(6, "mmi_home_page_panels", $Language->MenuPhrase("6", "MenuText"), "home_page_panelslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(13, "mmi_slider", $Language->MenuPhrase("13", "MenuText"), "sliderlist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(9, "mmi_latest_shots", $Language->MenuPhrase("9", "MenuText"), "latest_shotslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(10, "mmi_our_clients", $Language->MenuPhrase("10", "MenuText"), "our_clientslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(11, "mmi_our_teams", $Language->MenuPhrase("11", "MenuText"), "our_teamslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(17, "mmi_services", $Language->MenuPhrase("17", "MenuText"), "serviceslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(5, "mmi_faq", $Language->MenuPhrase("5", "MenuText"), "faqlist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(14, "mmi_translation", $Language->MenuPhrase("14", "MenuText"), "translationlist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(16, "mmi_branches", $Language->MenuPhrase("16", "MenuText"), "brancheslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(18, "mmi_integra_status", $Language->MenuPhrase("18", "MenuText"), "integra_statuslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(20, "mmi_notification_emails", $Language->MenuPhrase("20", "MenuText"), "notification_emailslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(19, "mmi_contactus_departments", $Language->MenuPhrase("19", "MenuText"), "contactus_departmentslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(21, "mmi_zones", $Language->MenuPhrase("21", "MenuText"), "zoneslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(22, "mmi_zones_prices", $Language->MenuPhrase("22", "MenuText"), "zones_priceslist.php", 15, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(-2, "mmi_changepwd", $Language->Phrase("ChangePwd"), "changepwd.php", -1, "", IsLoggedIn() && !IsSysAdmin());
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
