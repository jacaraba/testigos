<?php
	define('PREPEND_PATH', '');
	include_once(__DIR__ . '/lib.php');

	// accept a record as an assoc array, return transformed row ready to insert to table
	$transformFunctions = [
		'departamentos' => function($data, $options = []) {

			return $data;
		},
		'divpol2022' => function($data, $options = []) {

			return $data;
		},
		'divpol2022valle' => function($data, $options = []) {

			return $data;
		},
		'divpol2022vallecali' => function($data, $options = []) {

			return $data;
		},
		'jurados' => function($data, $options = []) {

			return $data;
		},
		'municipios' => function($data, $options = []) {

			return $data;
		},
		'numeros' => function($data, $options = []) {

			return $data;
		},
		'partidos2019' => function($data, $options = []) {

			return $data;
		},
		'partidos2022' => function($data, $options = []) {

			return $data;
		},
		'testigos' => function($data, $options = []) {
			if(isset($data['PUESTO'])) $data['PUESTO'] = pkGivenLookupText($data['PUESTO'], 'testigos', 'PUESTO');

			return $data;
		},
		'testigospuestos' => function($data, $options = []) {
			if(isset($data['CEDULA'])) $data['CEDULA'] = pkGivenLookupText($data['CEDULA'], 'testigospuestos', 'CEDULA');
			if(isset($data['PUESTO'])) $data['PUESTO'] = pkGivenLookupText($data['PUESTO'], 'testigospuestos', 'PUESTO');

			return $data;
		},
		'concejo2019vallecalivotos' => function($data, $options = []) {
			if(isset($data['PUESTO'])) $data['PUESTO'] = pkGivenLookupText($data['PUESTO'], 'concejo2019vallecalivotos', 'PUESTO');
			if(isset($data['PARTIDO'])) $data['PARTIDO'] = pkGivenLookupText($data['PARTIDO'], 'concejo2019vallecalivotos', 'PARTIDO');
			if(isset($data['VOTOS'])) $data['VOTOS'] = preg_replace('/[^\d\.]/', '', $data['VOTOS']);

			return $data;
		},
	];

	// accept a record as an assoc array, return a boolean indicating whether to import or skip record
	$filterFunctions = [
		'departamentos' => function($data, $options = []) { return true; },
		'divpol2022' => function($data, $options = []) { return true; },
		'divpol2022valle' => function($data, $options = []) { return true; },
		'divpol2022vallecali' => function($data, $options = []) { return true; },
		'jurados' => function($data, $options = []) { return true; },
		'municipios' => function($data, $options = []) { return true; },
		'numeros' => function($data, $options = []) { return true; },
		'partidos2019' => function($data, $options = []) { return true; },
		'partidos2022' => function($data, $options = []) { return true; },
		'testigos' => function($data, $options = []) { return true; },
		'testigospuestos' => function($data, $options = []) { return true; },
		'concejo2019vallecalivotos' => function($data, $options = []) { return true; },
	];

	/*
	Hook file for overwriting/amending $transformFunctions and $filterFunctions:
	hooks/import-csv.php
	If found, it's included below

	The way this works is by either completely overwriting any of the above 2 arrays,
	or, more commonly, overwriting a single function, for example:
		$transformFunctions['tablename'] = function($data, $options = []) {
			// new definition here
			// then you must return transformed data
			return $data;
		};

	Another scenario is transforming a specific field and leaving other fields to the default
	transformation. One possible way of doing this is to store the original transformation function
	in GLOBALS array, calling it inside the custom transformation function, then modifying the
	specific field:
		$GLOBALS['originalTransformationFunction'] = $transformFunctions['tablename'];
		$transformFunctions['tablename'] = function($data, $options = []) {
			$data = call_user_func_array($GLOBALS['originalTransformationFunction'], [$data, $options]);
			$data['fieldname'] = 'transformed value';
			return $data;
		};
	*/

	@include(__DIR__ . '/hooks/import-csv.php');

	$ui = new CSVImportUI($transformFunctions, $filterFunctions);
