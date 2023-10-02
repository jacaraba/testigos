<?php
// This script and data application were generated by AppGini 23.15
// Download AppGini for free from https://bigprof.com/appgini/download/

	include_once(__DIR__ . '/lib.php');
	@include_once(__DIR__ . '/hooks/divpol2022valle.php');
	include_once(__DIR__ . '/divpol2022valle_dml.php');

	// mm: can the current member access this page?
	$perm = getTablePermissions('divpol2022valle');
	if(!$perm['access']) {
		echo error_message($Translation['tableAccessDenied']);
		exit;
	}

	$x = new DataList;
	$x->TableName = 'divpol2022valle';

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = [
		"`divpol2022valle`.`PUESTO`" => "PUESTO",
		"`divpol2022valle`.`dd`" => "dd",
		"`divpol2022valle`.`mm`" => "mm",
		"`divpol2022valle`.`zz`" => "zz",
		"`divpol2022valle`.`pp`" => "pp",
		"`divpol2022valle`.`departamento`" => "departamento",
		"`divpol2022valle`.`municipio`" => "municipio",
		"`divpol2022valle`.`nompue`" => "nompue",
		"`divpol2022valle`.`direccion`" => "direccion",
		"`divpol2022valle`.`mujeres`" => "mujeres",
		"`divpol2022valle`.`hombres`" => "hombres",
		"`divpol2022valle`.`total`" => "total",
		"`divpol2022valle`.`mesas`" => "mesas",
		"`divpol2022valle`.`comuna`" => "comuna",
		"`divpol2022valle`.`nomcomuna`" => "nomcomuna",
	];
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = [
		1 => 1,
		2 => 2,
		3 => 3,
		4 => 4,
		5 => 5,
		6 => 6,
		7 => 7,
		8 => 8,
		9 => 9,
		10 => 10,
		11 => 11,
		12 => 12,
		13 => 13,
		14 => 14,
		15 => 15,
	];

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = [
		"`divpol2022valle`.`PUESTO`" => "PUESTO",
		"`divpol2022valle`.`dd`" => "dd",
		"`divpol2022valle`.`mm`" => "mm",
		"`divpol2022valle`.`zz`" => "zz",
		"`divpol2022valle`.`pp`" => "pp",
		"`divpol2022valle`.`departamento`" => "departamento",
		"`divpol2022valle`.`municipio`" => "municipio",
		"`divpol2022valle`.`nompue`" => "nompue",
		"`divpol2022valle`.`direccion`" => "direccion",
		"`divpol2022valle`.`mujeres`" => "mujeres",
		"`divpol2022valle`.`hombres`" => "hombres",
		"`divpol2022valle`.`total`" => "total",
		"`divpol2022valle`.`mesas`" => "mesas",
		"`divpol2022valle`.`comuna`" => "comuna",
		"`divpol2022valle`.`nomcomuna`" => "nomcomuna",
	];
	// Fields that can be filtered
	$x->QueryFieldsFilters = [
		"`divpol2022valle`.`PUESTO`" => "PUESTO",
		"`divpol2022valle`.`dd`" => "Dd",
		"`divpol2022valle`.`mm`" => "Mm",
		"`divpol2022valle`.`zz`" => "Zz",
		"`divpol2022valle`.`pp`" => "Pp",
		"`divpol2022valle`.`departamento`" => "Departamento",
		"`divpol2022valle`.`municipio`" => "Municipio",
		"`divpol2022valle`.`nompue`" => "Nompue",
		"`divpol2022valle`.`direccion`" => "Direccion",
		"`divpol2022valle`.`mujeres`" => "Mujeres",
		"`divpol2022valle`.`hombres`" => "Hombres",
		"`divpol2022valle`.`total`" => "Total",
		"`divpol2022valle`.`mesas`" => "Mesas",
		"`divpol2022valle`.`comuna`" => "Comuna",
		"`divpol2022valle`.`nomcomuna`" => "Nomcomuna",
	];

	// Fields that can be quick searched
	$x->QueryFieldsQS = [
		"`divpol2022valle`.`PUESTO`" => "PUESTO",
		"`divpol2022valle`.`dd`" => "dd",
		"`divpol2022valle`.`mm`" => "mm",
		"`divpol2022valle`.`zz`" => "zz",
		"`divpol2022valle`.`pp`" => "pp",
		"`divpol2022valle`.`departamento`" => "departamento",
		"`divpol2022valle`.`municipio`" => "municipio",
		"`divpol2022valle`.`nompue`" => "nompue",
		"`divpol2022valle`.`direccion`" => "direccion",
		"`divpol2022valle`.`mujeres`" => "mujeres",
		"`divpol2022valle`.`hombres`" => "hombres",
		"`divpol2022valle`.`total`" => "total",
		"`divpol2022valle`.`mesas`" => "mesas",
		"`divpol2022valle`.`comuna`" => "comuna",
		"`divpol2022valle`.`nomcomuna`" => "nomcomuna",
	];

	// Lookup fields that can be used as filterers
	$x->filterers = [];

	$x->QueryFrom = "`divpol2022valle` ";
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
	$x->ScriptFileName = 'divpol2022valle_view.php';
	$x->TableTitle = 'Divpol2022valle';
	$x->TableIcon = 'table.gif';
	$x->PrimaryKey = '`divpol2022valle`.`PUESTO`';

	$x->ColWidth = [150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150, 150, ];
	$x->ColCaption = ['PUESTO', 'Dd', 'Mm', 'Zz', 'Pp', 'Departamento', 'Municipio', 'Nompue', 'Direccion', 'Mujeres', 'Hombres', 'Total', 'Mesas', 'Comuna', 'Nomcomuna', ];
	$x->ColFieldName = ['PUESTO', 'dd', 'mm', 'zz', 'pp', 'departamento', 'municipio', 'nompue', 'direccion', 'mujeres', 'hombres', 'total', 'mesas', 'comuna', 'nomcomuna', ];
	$x->ColNumber  = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, ];

	// template paths below are based on the app main directory
	$x->Template = 'templates/divpol2022valle_templateTV.html';
	$x->SelectedTemplate = 'templates/divpol2022valle_templateTVS.html';
	$x->TemplateDV = 'templates/divpol2022valle_templateDV.html';
	$x->TemplateDVP = 'templates/divpol2022valle_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HasCalculatedFields = false;
	$x->AllowConsoleLog = false;
	$x->AllowDVNavigation = true;

	// hook: divpol2022valle_init
	$render = true;
	if(function_exists('divpol2022valle_init')) {
		$args = [];
		$render = divpol2022valle_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: divpol2022valle_header
	$headerCode = '';
	if(function_exists('divpol2022valle_header')) {
		$args = [];
		$headerCode = divpol2022valle_header($x->ContentType, getMemberInfo(), $args);
	}

	if(!$headerCode) {
		include_once(__DIR__ . '/header.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/header.php');
		echo str_replace('<%%HEADER%%>', ob_get_clean(), $headerCode);
	}

	echo $x->HTML;

	// hook: divpol2022valle_footer
	$footerCode = '';
	if(function_exists('divpol2022valle_footer')) {
		$args = [];
		$footerCode = divpol2022valle_footer($x->ContentType, getMemberInfo(), $args);
	}

	if(!$footerCode) {
		include_once(__DIR__ . '/footer.php'); 
	} else {
		ob_start();
		include_once(__DIR__ . '/footer.php');
		echo str_replace('<%%FOOTER%%>', ob_get_clean(), $footerCode);
	}