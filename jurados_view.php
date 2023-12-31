<?php
// This script and data application were generated by AppGini 23.15
// Download AppGini for free from https://bigprof.com/appgini/download/

	include_once(__DIR__ . '/lib.php');
	@include_once(__DIR__ . '/hooks/jurados.php');
	include_once(__DIR__ . '/jurados_dml.php');

	// mm: can the current member access this page?
	$perm = getTablePermissions('jurados');
	if(!$perm['access']) {
		echo error_message($Translation['tableAccessDenied']);
		exit;
	}

	$x = new DataList;
	$x->TableName = 'jurados';

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = [
		"`jurados`.`PUESTO`" => "PUESTO",
		"`jurados`.`CEDULA`" => "CEDULA",
		"`jurados`.`NOMBRE`" => "NOMBRE",
		"`jurados`.`CELULAR`" => "CELULAR",
		"`jurados`.`DIRECION`" => "DIRECION",
		"`jurados`.`CONTACTO`" => "CONTACTO",
	];
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = [
		1 => 1,
		2 => 2,
		3 => 3,
		4 => 4,
		5 => 5,
		6 => 6,
	];

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = [
		"`jurados`.`PUESTO`" => "PUESTO",
		"`jurados`.`CEDULA`" => "CEDULA",
		"`jurados`.`NOMBRE`" => "NOMBRE",
		"`jurados`.`CELULAR`" => "CELULAR",
		"`jurados`.`DIRECION`" => "DIRECION",
		"`jurados`.`CONTACTO`" => "CONTACTO",
	];
	// Fields that can be filtered
	$x->QueryFieldsFilters = [
		"`jurados`.`PUESTO`" => "PUESTO",
		"`jurados`.`CEDULA`" => "CEDULA",
		"`jurados`.`NOMBRE`" => "NOMBRE",
		"`jurados`.`CELULAR`" => "CELULAR",
		"`jurados`.`DIRECION`" => "DIRECION",
		"`jurados`.`CONTACTO`" => "CONTACTO",
	];

	// Fields that can be quick searched
	$x->QueryFieldsQS = [
		"`jurados`.`PUESTO`" => "PUESTO",
		"`jurados`.`CEDULA`" => "CEDULA",
		"`jurados`.`NOMBRE`" => "NOMBRE",
		"`jurados`.`CELULAR`" => "CELULAR",
		"`jurados`.`DIRECION`" => "DIRECION",
		"`jurados`.`CONTACTO`" => "CONTACTO",
	];

	// Lookup fields that can be used as filterers
	$x->filterers = [];

	$x->QueryFrom = "`jurados` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 1;
	$x->HideTableView = ($perm['view'] == 0 ? 1 : 0);
	$x->AllowDelete = $perm['delete'];
	$x->AllowMassDelete = (getLoggedAdmin() !== false);
	$x->AllowInsert = $perm['insert'];
	$x->AllowUpdate = $perm['edit'];
	$x->SeparateDV = 1;
	$x->AllowDeleteOfParents = 0;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = (getLoggedAdmin() !== false);
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowPrintingDV = 1;
	$x->AllowCSV = 1;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation['quick search'];
	$x->ScriptFileName = 'jurados_view.php';
	$x->TableTitle = 'Jurados';
	$x->TableIcon = 'table.gif';
	$x->PrimaryKey = '`jurados`.`CEDULA`';

	$x->ColWidth = [150, 150, 150, 150, 150, 150, ];
	$x->ColCaption = ['PUESTO', 'CEDULA', 'NOMBRE', 'CELULAR', 'DIRECION', 'CONTACTO', ];
	$x->ColFieldName = ['PUESTO', 'CEDULA', 'NOMBRE', 'CELULAR', 'DIRECION', 'CONTACTO', ];
	$x->ColNumber  = [1, 2, 3, 4, 5, 6, ];

	// template paths below are based on the app main directory
	$x->Template = 'templates/jurados_templateTV.html';
	$x->SelectedTemplate = 'templates/jurados_templateTVS.html';
	$x->TemplateDV = 'templates/jurados_templateDV.html';
	$x->TemplateDVP = 'templates/jurados_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HasCalculatedFields = false;
	$x->AllowConsoleLog = false;
	$x->AllowDVNavigation = true;

	// hook: jurados_init
	$render = true;
	if(function_exists('jurados_init')) {
		$args = [];
		$render = jurados_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: jurados_header
	$headerCode = '';
	if(function_exists('jurados_header')) {
		$args = [];
		$headerCode = jurados_header($x->ContentType, getMemberInfo(), $args);
	}

	if(!$headerCode) {
		include_once(__DIR__ . '/header.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/header.php');
		echo str_replace('<%%HEADER%%>', ob_get_clean(), $headerCode);
	}

	echo $x->HTML;

	// hook: jurados_footer
	$footerCode = '';
	if(function_exists('jurados_footer')) {
		$args = [];
		$footerCode = jurados_footer($x->ContentType, getMemberInfo(), $args);
	}

	if(!$footerCode) {
		include_once(__DIR__ . '/footer.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/footer.php');
		echo str_replace('<%%FOOTER%%>', ob_get_clean(), $footerCode);
	}
