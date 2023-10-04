<?php

// Data functions (insert, update, delete, form) for table divpol2022vallecali

// This script and data application were generated by AppGini 23.15
// Download AppGini for free from https://bigprof.com/appgini/download/

function divpol2022vallecali_insert(&$error_message = '') {
	global $Translation;

	// mm: can member insert record?
	$arrPerm = getTablePermissions('divpol2022vallecali');
	if(!$arrPerm['insert']) return false;

	$data = [
		'PUESTO' => Request::val('PUESTO', ''),
		'dd' => Request::val('dd', ''),
		'mm' => Request::val('mm', ''),
		'zz' => Request::val('zz', ''),
		'pp' => Request::val('pp', ''),
		'departamento' => Request::val('departamento', ''),
		'municipio' => Request::val('municipio', ''),
		'nompue' => Request::val('nompue', ''),
		'direccion' => Request::val('direccion', ''),
		'mujeres' => Request::val('mujeres', ''),
		'hombres' => Request::val('hombres', ''),
		'total' => Request::val('total', ''),
		'mesas' => Request::val('mesas', ''),
		'comuna' => Request::val('comuna', ''),
		'nomcomuna' => Request::val('nomcomuna', ''),
	];

	if($data['PUESTO'] === '') {
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'PUESTO': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">' . $Translation['< back'] . '</a></div>';
		exit;
	}

	// hook: divpol2022vallecali_before_insert
	if(function_exists('divpol2022vallecali_before_insert')) {
		$args = [];
		if(!divpol2022vallecali_before_insert($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$error = '';
	// set empty fields to NULL
	$data = array_map(function($v) { return ($v === '' ? NULL : $v); }, $data);
	insert('divpol2022vallecali', backtick_keys_once($data), $error);
	if($error) {
		$error_message = $error;
		return false;
	}

	$recID = $data['PUESTO'];

	update_calc_fields('divpol2022vallecali', $recID, calculated_fields()['divpol2022vallecali']);

	// hook: divpol2022vallecali_after_insert
	if(function_exists('divpol2022vallecali_after_insert')) {
		$res = sql("SELECT * FROM `divpol2022vallecali` WHERE `PUESTO`='" . makeSafe($recID, false) . "' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)) {
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args = [];
		if(!divpol2022vallecali_after_insert($data, getMemberInfo(), $args)) { return $recID; }
	}

	// mm: save ownership data
	set_record_owner('divpol2022vallecali', $recID, getLoggedMemberID());

	// if this record is a copy of another record, copy children if applicable
	if(strlen(Request::val('SelectedID'))) divpol2022vallecali_copy_children($recID, Request::val('SelectedID'));

	return $recID;
}

function divpol2022vallecali_copy_children($destination_id, $source_id) {
	global $Translation;
	$requests = []; // array of curl handlers for launching insert requests
	$eo = ['silentErrors' => true];
	$safe_sid = makeSafe($source_id);

	// launch requests, asynchronously
	curl_batch($requests);
}

function divpol2022vallecali_delete($selected_id, $AllowDeleteOfParents = false, $skipChecks = false) {
	// insure referential integrity ...
	global $Translation;
	$selected_id = makeSafe($selected_id);

	// mm: can member delete record?
	if(!check_record_permission('divpol2022vallecali', $selected_id, 'delete')) {
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: divpol2022vallecali_before_delete
	if(function_exists('divpol2022vallecali_before_delete')) {
		$args = [];
		if(!divpol2022vallecali_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'] . (
				!empty($args['error_message']) ?
					'<div class="text-bold">' . strip_tags($args['error_message']) . '</div>'
					: '' 
			);
	}

	// child table: testigos
	$res = sql("SELECT `PUESTO` FROM `divpol2022vallecali` WHERE `PUESTO`='{$selected_id}'", $eo);
	$PUESTO = db_fetch_row($res);
	$rires = sql("SELECT COUNT(1) FROM `testigos` WHERE `PUESTO`='" . makeSafe($PUESTO[0]) . "'", $eo);
	$rirow = db_fetch_row($rires);
	if($rirow[0] && !$AllowDeleteOfParents && !$skipChecks) {
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace('<RelatedRecords>', $rirow[0], $RetMsg);
		$RetMsg = str_replace('<TableName>', 'testigos', $RetMsg);
		return $RetMsg;
	} elseif($rirow[0] && $AllowDeleteOfParents && !$skipChecks) {
		$RetMsg = $Translation['confirm delete'];
		$RetMsg = str_replace('<RelatedRecords>', $rirow[0], $RetMsg);
		$RetMsg = str_replace('<TableName>', 'testigos', $RetMsg);
		$RetMsg = str_replace('<Delete>', '<input type="button" class="btn btn-danger" value="' . html_attr($Translation['yes']) . '" onClick="window.location = \'divpol2022vallecali_view.php?SelectedID=' . urlencode($selected_id) . '&delete_x=1&confirmed=1&csrf_token=' . urlencode(csrf_token(false, true)) . '\';">', $RetMsg);
		$RetMsg = str_replace('<Cancel>', '<input type="button" class="btn btn-success" value="' . html_attr($Translation[ 'no']) . '" onClick="window.location = \'divpol2022vallecali_view.php?SelectedID=' . urlencode($selected_id) . '\';">', $RetMsg);
		return $RetMsg;
	}

	// child table: testigospuestos
	$res = sql("SELECT `PUESTO` FROM `divpol2022vallecali` WHERE `PUESTO`='{$selected_id}'", $eo);
	$PUESTO = db_fetch_row($res);
	$rires = sql("SELECT COUNT(1) FROM `testigospuestos` WHERE `PUESTO`='" . makeSafe($PUESTO[0]) . "'", $eo);
	$rirow = db_fetch_row($rires);
	if($rirow[0] && !$AllowDeleteOfParents && !$skipChecks) {
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace('<RelatedRecords>', $rirow[0], $RetMsg);
		$RetMsg = str_replace('<TableName>', 'testigospuestos', $RetMsg);
		return $RetMsg;
	} elseif($rirow[0] && $AllowDeleteOfParents && !$skipChecks) {
		$RetMsg = $Translation['confirm delete'];
		$RetMsg = str_replace('<RelatedRecords>', $rirow[0], $RetMsg);
		$RetMsg = str_replace('<TableName>', 'testigospuestos', $RetMsg);
		$RetMsg = str_replace('<Delete>', '<input type="button" class="btn btn-danger" value="' . html_attr($Translation['yes']) . '" onClick="window.location = \'divpol2022vallecali_view.php?SelectedID=' . urlencode($selected_id) . '&delete_x=1&confirmed=1&csrf_token=' . urlencode(csrf_token(false, true)) . '\';">', $RetMsg);
		$RetMsg = str_replace('<Cancel>', '<input type="button" class="btn btn-success" value="' . html_attr($Translation[ 'no']) . '" onClick="window.location = \'divpol2022vallecali_view.php?SelectedID=' . urlencode($selected_id) . '\';">', $RetMsg);
		return $RetMsg;
	}

	// child table: concejo2019vallecalivotos
	$res = sql("SELECT `PUESTO` FROM `divpol2022vallecali` WHERE `PUESTO`='{$selected_id}'", $eo);
	$PUESTO = db_fetch_row($res);
	$rires = sql("SELECT COUNT(1) FROM `concejo2019vallecalivotos` WHERE `PUESTO`='" . makeSafe($PUESTO[0]) . "'", $eo);
	$rirow = db_fetch_row($rires);
	if($rirow[0] && !$AllowDeleteOfParents && !$skipChecks) {
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace('<RelatedRecords>', $rirow[0], $RetMsg);
		$RetMsg = str_replace('<TableName>', 'concejo2019vallecalivotos', $RetMsg);
		return $RetMsg;
	} elseif($rirow[0] && $AllowDeleteOfParents && !$skipChecks) {
		$RetMsg = $Translation['confirm delete'];
		$RetMsg = str_replace('<RelatedRecords>', $rirow[0], $RetMsg);
		$RetMsg = str_replace('<TableName>', 'concejo2019vallecalivotos', $RetMsg);
		$RetMsg = str_replace('<Delete>', '<input type="button" class="btn btn-danger" value="' . html_attr($Translation['yes']) . '" onClick="window.location = \'divpol2022vallecali_view.php?SelectedID=' . urlencode($selected_id) . '&delete_x=1&confirmed=1&csrf_token=' . urlencode(csrf_token(false, true)) . '\';">', $RetMsg);
		$RetMsg = str_replace('<Cancel>', '<input type="button" class="btn btn-success" value="' . html_attr($Translation[ 'no']) . '" onClick="window.location = \'divpol2022vallecali_view.php?SelectedID=' . urlencode($selected_id) . '\';">', $RetMsg);
		return $RetMsg;
	}

	sql("DELETE FROM `divpol2022vallecali` WHERE `PUESTO`='{$selected_id}'", $eo);

	// hook: divpol2022vallecali_after_delete
	if(function_exists('divpol2022vallecali_after_delete')) {
		$args = [];
		divpol2022vallecali_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("DELETE FROM `membership_userrecords` WHERE `tableName`='divpol2022vallecali' AND `pkValue`='{$selected_id}'", $eo);
}

function divpol2022vallecali_update(&$selected_id, &$error_message = '') {
	global $Translation;

	// mm: can member edit record?
	if(!check_record_permission('divpol2022vallecali', $selected_id, 'edit')) return false;

	$data = [
		'PUESTO' => Request::val('PUESTO', ''),
		'dd' => Request::val('dd', ''),
		'mm' => Request::val('mm', ''),
		'zz' => Request::val('zz', ''),
		'pp' => Request::val('pp', ''),
		'departamento' => Request::val('departamento', ''),
		'municipio' => Request::val('municipio', ''),
		'nompue' => Request::val('nompue', ''),
		'direccion' => Request::val('direccion', ''),
		'mujeres' => Request::val('mujeres', ''),
		'hombres' => Request::val('hombres', ''),
		'total' => Request::val('total', ''),
		'mesas' => Request::val('mesas', ''),
		'comuna' => Request::val('comuna', ''),
		'nomcomuna' => Request::val('nomcomuna', ''),
	];

	if($data['PUESTO'] === '') {
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'PUESTO': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">' . $Translation['< back'] . '</a></div>';
		exit;
	}
	// get existing values
	$old_data = getRecord('divpol2022vallecali', $selected_id);
	if(is_array($old_data)) {
		$old_data = array_map('makeSafe', $old_data);
		$old_data['selectedID'] = makeSafe($selected_id);
	}

	$data['selectedID'] = makeSafe($selected_id);

	// hook: divpol2022vallecali_before_update
	if(function_exists('divpol2022vallecali_before_update')) {
		$args = ['old_data' => $old_data];
		if(!divpol2022vallecali_before_update($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$set = $data; unset($set['selectedID']);
	foreach ($set as $field => $value) {
		$set[$field] = ($value !== '' && $value !== NULL) ? $value : NULL;
	}

	if(!update(
		'divpol2022vallecali', 
		backtick_keys_once($set), 
		['`PUESTO`' => $selected_id], 
		$error_message
	)) {
		echo $error_message;
		echo '<a href="divpol2022vallecali_view.php?SelectedID=' . urlencode($selected_id) . "\">{$Translation['< back']}</a>";
		exit;
	}

	$data['selectedID'] = $data['PUESTO'];
	$newID = $data['PUESTO'];

	$eo = ['silentErrors' => true];

	update_calc_fields('divpol2022vallecali', $data['selectedID'], calculated_fields()['divpol2022vallecali']);

	// hook: divpol2022vallecali_after_update
	if(function_exists('divpol2022vallecali_after_update')) {
		$res = sql("SELECT * FROM `divpol2022vallecali` WHERE `PUESTO`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)) $data = array_map('makeSafe', $row);

		$data['selectedID'] = $data['PUESTO'];
		$args = ['old_data' => $old_data];
		if(!divpol2022vallecali_after_update($data, getMemberInfo(), $args)) return;
	}

	// mm: update ownership data
	sql("UPDATE `membership_userrecords` SET `dateUpdated`='" . time() . "', `pkValue`='{$data['PUESTO']}' WHERE `tableName`='divpol2022vallecali' AND `pkValue`='" . makeSafe($selected_id) . "'", $eo);

	// if PK value changed, update $selected_id
	$selected_id = $newID;
}

function divpol2022vallecali_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $separateDV = 0, $TemplateDV = '', $TemplateDVP = '') {
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;
	$eo = ['silentErrors' => true];
	$noUploads = null;
	$row = $urow = $jsReadOnly = $jsEditable = $lookups = null;

	$noSaveAsCopy = false;

	// mm: get table permissions
	$arrPerm = getTablePermissions('divpol2022vallecali');
	if(!$arrPerm['insert'] && $selected_id == '')
		// no insert permission and no record selected
		// so show access denied error unless TVDV
		return $separateDV ? $Translation['tableAccessDenied'] : '';
	$AllowInsert = ($arrPerm['insert'] ? true : false);
	// print preview?
	$dvprint = false;
	if(strlen($selected_id) && Request::val('dvprint_x') != '') {
		$dvprint = true;
	}


	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');

	if($selected_id) {
		if(!check_record_permission('divpol2022vallecali', $selected_id, 'view'))
			return $Translation['tableAccessDenied'];

		// can edit?
		$AllowUpdate = check_record_permission('divpol2022vallecali', $selected_id, 'edit');

		// can delete?
		$AllowDelete = check_record_permission('divpol2022vallecali', $selected_id, 'delete');

		$res = sql("SELECT * FROM `divpol2022vallecali` WHERE `PUESTO`='" . makeSafe($selected_id) . "'", $eo);
		if(!($row = db_fetch_array($res))) {
			return error_message($Translation['No records found'], 'divpol2022vallecali_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$row = array_map('safe_html', $row);
	} else {
		$filterField = Request::val('FilterField');
		$filterOperator = Request::val('FilterOperator');
		$filterValue = Request::val('FilterValue');
	}

	// code for template based detail view forms

	// open the detail view template
	if($dvprint) {
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/divpol2022vallecali_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	} else {
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/divpol2022vallecali_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Detail View', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', (Request::val('Embedded') ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($AllowInsert) {
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return divpol2022vallecali_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return divpol2022vallecali_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
	} else {
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '', $templateCode);
	}

	// 'Back' button action
	if(Request::val('Embedded')) {
		$backAction = 'AppGini.closeParentModal(); return false;';
	} else {
		$backAction = '$j(\'form\').eq(0).attr(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;';
	}

	if($selected_id) {
		if(!Request::val('Embedded')) $templateCode = str_replace('<%%DVPRINT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="dvprint" name="dvprint_x" value="1" onclick="$j(\'form\').eq(0).prop(\'novalidate\', true); document.myform.reset(); return true;" title="' . html_attr($Translation['Print Preview']) . '"><i class="glyphicon glyphicon-print"></i> ' . $Translation['Print Preview'] . '</button>', $templateCode);
		if($AllowUpdate)
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return divpol2022vallecali_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
		else
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);

		if($AllowDelete)
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '<button type="submit" class="btn btn-danger" id="delete" name="delete_x" value="1" title="' . html_attr($Translation['Delete']) . '"><i class="glyphicon glyphicon-trash"></i> ' . $Translation['Delete'] . '</button>', $templateCode);
		else
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);

		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>', $templateCode);
	} else {
		$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);

		// if not in embedded mode and user has insert only but no view/update/delete,
		// remove 'back' button
		if(
			$arrPerm['insert']
			&& !$arrPerm['update'] && !$arrPerm['delete'] && !$arrPerm['view']
			&& !Request::val('Embedded')
		)
			$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '', $templateCode);
		elseif($separateDV)
			$templateCode = str_replace(
				'<%%DESELECT_BUTTON%%>', 
				'<button
					type="submit" 
					class="btn btn-default" 
					id="deselect" 
					name="deselect_x" 
					value="1" 
					onclick="' . $backAction . '" 
					title="' . html_attr($Translation['Back']) . '">
						<i class="glyphicon glyphicon-chevron-left"></i> ' .
						$Translation['Back'] .
				'</button>',
				$templateCode
			);
		else
			$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '', $templateCode);
	}

	// set records to read only if user can't insert new records and can't edit current record
	if(($selected_id && !$AllowUpdate && !$AllowInsert) || (!$selected_id && !$AllowInsert)) {
		$jsReadOnly = '';
		$jsReadOnly .= "\tjQuery('#PUESTO').replaceWith('<div class=\"form-control-static\" id=\"PUESTO\">' + (jQuery('#PUESTO').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#dd').replaceWith('<div class=\"form-control-static\" id=\"dd\">' + (jQuery('#dd').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#mm').replaceWith('<div class=\"form-control-static\" id=\"mm\">' + (jQuery('#mm').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#zz').replaceWith('<div class=\"form-control-static\" id=\"zz\">' + (jQuery('#zz').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#pp').replaceWith('<div class=\"form-control-static\" id=\"pp\">' + (jQuery('#pp').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#departamento').replaceWith('<div class=\"form-control-static\" id=\"departamento\">' + (jQuery('#departamento').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#municipio').replaceWith('<div class=\"form-control-static\" id=\"municipio\">' + (jQuery('#municipio').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#nompue').replaceWith('<div class=\"form-control-static\" id=\"nompue\">' + (jQuery('#nompue').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#direccion').replaceWith('<div class=\"form-control-static\" id=\"direccion\">' + (jQuery('#direccion').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#mujeres').replaceWith('<div class=\"form-control-static\" id=\"mujeres\">' + (jQuery('#mujeres').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#hombres').replaceWith('<div class=\"form-control-static\" id=\"hombres\">' + (jQuery('#hombres').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#total').replaceWith('<div class=\"form-control-static\" id=\"total\">' + (jQuery('#total').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#mesas').replaceWith('<div class=\"form-control-static\" id=\"mesas\">' + (jQuery('#mesas').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#comuna').replaceWith('<div class=\"form-control-static\" id=\"comuna\">' + (jQuery('#comuna').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#nomcomuna').replaceWith('<div class=\"form-control-static\" id=\"nomcomuna\">' + (jQuery('#nomcomuna').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	} elseif($AllowInsert) {
		$jsEditable = "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos

	/* lookup fields array: 'lookup field name' => ['parent table name', 'lookup field caption'] */
	$lookup_fields = [];
	foreach($lookup_fields as $luf => $ptfc) {
		$pt_perm = getTablePermissions($ptfc[0]);

		// process foreign key links
		if($pt_perm['view'] || $pt_perm['edit']) {
			$templateCode = str_replace("<%%PLINK({$luf})%%>", '<button type="button" class="btn btn-default view_parent" id="' . $ptfc[0] . '_view_parent" title="' . html_attr($Translation['View'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-eye-open"></i></button>', $templateCode);
		}

		// if user has insert permission to parent table of a lookup field, put an add new button
		if($pt_perm['insert'] /* && !Request::val('Embedded')*/) {
			$templateCode = str_replace("<%%ADDNEW({$ptfc[0]})%%>", '<button type="button" class="btn btn-default add_new_parent" id="' . $ptfc[0] . '_add_new" title="' . html_attr($Translation['Add New'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-plus text-success"></i></button>', $templateCode);
		}
	}

	// process images
	$templateCode = str_replace('<%%UPLOADFILE(PUESTO)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(dd)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(mm)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(zz)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(pp)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(departamento)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(municipio)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(nompue)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(direccion)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(mujeres)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(hombres)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(total)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(mesas)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(comuna)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(nomcomuna)%%>', '', $templateCode);

	// process values
	if($selected_id) {
		if( $dvprint) $templateCode = str_replace('<%%VALUE(PUESTO)%%>', safe_html($urow['PUESTO']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(PUESTO)%%>', html_attr($row['PUESTO']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(PUESTO)%%>', urlencode($urow['PUESTO']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(dd)%%>', safe_html($urow['dd']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(dd)%%>', html_attr($row['dd']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(dd)%%>', urlencode($urow['dd']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(mm)%%>', safe_html($urow['mm']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(mm)%%>', html_attr($row['mm']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(mm)%%>', urlencode($urow['mm']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(zz)%%>', safe_html($urow['zz']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(zz)%%>', html_attr($row['zz']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(zz)%%>', urlencode($urow['zz']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(pp)%%>', safe_html($urow['pp']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(pp)%%>', html_attr($row['pp']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(pp)%%>', urlencode($urow['pp']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(departamento)%%>', safe_html($urow['departamento']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(departamento)%%>', html_attr($row['departamento']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(departamento)%%>', urlencode($urow['departamento']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(municipio)%%>', safe_html($urow['municipio']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(municipio)%%>', html_attr($row['municipio']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(municipio)%%>', urlencode($urow['municipio']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(nompue)%%>', safe_html($urow['nompue']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(nompue)%%>', html_attr($row['nompue']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(nompue)%%>', urlencode($urow['nompue']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(direccion)%%>', safe_html($urow['direccion']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(direccion)%%>', html_attr($row['direccion']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(direccion)%%>', urlencode($urow['direccion']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(mujeres)%%>', safe_html($urow['mujeres']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(mujeres)%%>', html_attr($row['mujeres']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(mujeres)%%>', urlencode($urow['mujeres']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(hombres)%%>', safe_html($urow['hombres']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(hombres)%%>', html_attr($row['hombres']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(hombres)%%>', urlencode($urow['hombres']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(total)%%>', safe_html($urow['total']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(total)%%>', html_attr($row['total']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(total)%%>', urlencode($urow['total']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(mesas)%%>', safe_html($urow['mesas']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(mesas)%%>', html_attr($row['mesas']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(mesas)%%>', urlencode($urow['mesas']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(comuna)%%>', safe_html($urow['comuna']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(comuna)%%>', html_attr($row['comuna']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(comuna)%%>', urlencode($urow['comuna']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(nomcomuna)%%>', safe_html($urow['nomcomuna']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(nomcomuna)%%>', html_attr($row['nomcomuna']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(nomcomuna)%%>', urlencode($urow['nomcomuna']), $templateCode);
	} else {
		$templateCode = str_replace('<%%VALUE(PUESTO)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(PUESTO)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(dd)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(dd)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(mm)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(mm)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(zz)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(zz)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(pp)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(pp)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(departamento)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(departamento)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(municipio)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(municipio)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(nompue)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(nompue)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(direccion)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(direccion)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(mujeres)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(mujeres)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(hombres)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(hombres)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(total)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(total)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(mesas)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(mesas)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(comuna)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(comuna)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(nomcomuna)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(nomcomuna)%%>', urlencode(''), $templateCode);
	}

	// process translations
	$templateCode = parseTemplate($templateCode);

	// clear scrap
	$templateCode = str_replace('<%%', '<!-- ', $templateCode);
	$templateCode = str_replace('%%>', ' -->', $templateCode);

	// hide links to inaccessible tables
	if(Request::val('dvprint_x') == '') {
		$templateCode .= "\n\n<script>\$j(function() {\n";
		$arrTables = getTableList();
		foreach($arrTables as $name => $caption) {
			$templateCode .= "\t\$j('#{$name}_link').removeClass('hidden');\n";
			$templateCode .= "\t\$j('#xs_{$name}_link').removeClass('hidden');\n";
		}

		$templateCode .= $jsReadOnly;
		$templateCode .= $jsEditable;

		if(!$selected_id) {
		}

		$templateCode.="\n});</script>\n";
	}

	// ajaxed auto-fill fields
	$templateCode .= '<script>';
	$templateCode .= '$j(function() {';


	$templateCode.="});";
	$templateCode.="</script>";
	$templateCode .= $lookups;

	// handle enforced parent values for read-only lookup fields
	$filterField = Request::val('FilterField');
	$filterOperator = Request::val('FilterOperator');
	$filterValue = Request::val('FilterValue');

	// don't include blank images in lightbox gallery
	$templateCode = preg_replace('/blank.gif" data-lightbox=".*?"/', 'blank.gif"', $templateCode);

	// don't display empty email links
	$templateCode=preg_replace('/<a .*?href="mailto:".*?<\/a>/', '', $templateCode);

	/* default field values */
	$rdata = $jdata = get_defaults('divpol2022vallecali');
	if($selected_id) {
		$jdata = get_joined_record('divpol2022vallecali', $selected_id);
		if($jdata === false) $jdata = get_defaults('divpol2022vallecali');
		$rdata = $row;
	}
	$templateCode .= loadView('divpol2022vallecali-ajax-cache', ['rdata' => $rdata, 'jdata' => $jdata]);

	// hook: divpol2022vallecali_dv
	if(function_exists('divpol2022vallecali_dv')) {
		$args = [];
		divpol2022vallecali_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}