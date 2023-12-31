<?php

// Data functions (insert, update, delete, form) for table testigos

// This script and data application were generated by AppGini 23.15
// Download AppGini for free from https://bigprof.com/appgini/download/

function testigos_insert(&$error_message = '') {
	global $Translation;

	// mm: can member insert record?
	$arrPerm = getTablePermissions('testigos');
	if(!$arrPerm['insert']) return false;

	$data = [
		'PUESTO' => Request::lookup('PUESTO', ''),
		'CEDULA' => Request::val('CEDULA', ''),
		'NOMBRE' => Request::val('NOMBRE', ''),
		'CELULAR' => Request::val('CELULAR', ''),
		'DIRECION' => Request::val('DIRECION', ''),
		'CONTACTO' => Request::val('CONTACTO', ''),
	];

	if($data['CEDULA'] === '') {
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'CEDULA': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">' . $Translation['< back'] . '</a></div>';
		exit;
	}

	// hook: testigos_before_insert
	if(function_exists('testigos_before_insert')) {
		$args = [];
		if(!testigos_before_insert($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$error = '';
	// set empty fields to NULL
	$data = array_map(function($v) { return ($v === '' ? NULL : $v); }, $data);
	insert('testigos', backtick_keys_once($data), $error);
	if($error) {
		$error_message = $error;
		return false;
	}

	$recID = $data['CEDULA'];

	update_calc_fields('testigos', $recID, calculated_fields()['testigos']);

	// hook: testigos_after_insert
	if(function_exists('testigos_after_insert')) {
		$res = sql("SELECT * FROM `testigos` WHERE `CEDULA`='" . makeSafe($recID, false) . "' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)) {
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args = [];
		if(!testigos_after_insert($data, getMemberInfo(), $args)) { return $recID; }
	}

	// mm: save ownership data
	set_record_owner('testigos', $recID, getLoggedMemberID());

	// if this record is a copy of another record, copy children if applicable
	if(strlen(Request::val('SelectedID'))) testigos_copy_children($recID, Request::val('SelectedID'));

	return $recID;
}

function testigos_copy_children($destination_id, $source_id) {
	global $Translation;
	$requests = []; // array of curl handlers for launching insert requests
	$eo = ['silentErrors' => true];
	$safe_sid = makeSafe($source_id);

	// launch requests, asynchronously
	curl_batch($requests);
}

function testigos_delete($selected_id, $AllowDeleteOfParents = false, $skipChecks = false) {
	// insure referential integrity ...
	global $Translation;
	$selected_id = makeSafe($selected_id);

	// mm: can member delete record?
	if(!check_record_permission('testigos', $selected_id, 'delete')) {
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: testigos_before_delete
	if(function_exists('testigos_before_delete')) {
		$args = [];
		if(!testigos_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'] . (
				!empty($args['error_message']) ?
					'<div class="text-bold">' . strip_tags($args['error_message']) . '</div>'
					: '' 
			);
	}

	// child table: testigospuestos
	$res = sql("SELECT `CEDULA` FROM `testigos` WHERE `CEDULA`='{$selected_id}'", $eo);
	$CEDULA = db_fetch_row($res);
	$rires = sql("SELECT COUNT(1) FROM `testigospuestos` WHERE `CEDULA`='" . makeSafe($CEDULA[0]) . "'", $eo);
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
		$RetMsg = str_replace('<Delete>', '<input type="button" class="btn btn-danger" value="' . html_attr($Translation['yes']) . '" onClick="window.location = \'testigos_view.php?SelectedID=' . urlencode($selected_id) . '&delete_x=1&confirmed=1&csrf_token=' . urlencode(csrf_token(false, true)) . '\';">', $RetMsg);
		$RetMsg = str_replace('<Cancel>', '<input type="button" class="btn btn-success" value="' . html_attr($Translation[ 'no']) . '" onClick="window.location = \'testigos_view.php?SelectedID=' . urlencode($selected_id) . '\';">', $RetMsg);
		return $RetMsg;
	}

	sql("DELETE FROM `testigos` WHERE `CEDULA`='{$selected_id}'", $eo);

	// hook: testigos_after_delete
	if(function_exists('testigos_after_delete')) {
		$args = [];
		testigos_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("DELETE FROM `membership_userrecords` WHERE `tableName`='testigos' AND `pkValue`='{$selected_id}'", $eo);
}

function testigos_update(&$selected_id, &$error_message = '') {
	global $Translation;

	// mm: can member edit record?
	if(!check_record_permission('testigos', $selected_id, 'edit')) return false;

	$data = [
		'PUESTO' => Request::lookup('PUESTO', ''),
		'CEDULA' => Request::val('CEDULA', ''),
		'NOMBRE' => Request::val('NOMBRE', ''),
		'CELULAR' => Request::val('CELULAR', ''),
		'DIRECION' => Request::val('DIRECION', ''),
		'CONTACTO' => Request::val('CONTACTO', ''),
	];

	if($data['CEDULA'] === '') {
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'CEDULA': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">' . $Translation['< back'] . '</a></div>';
		exit;
	}
	// get existing values
	$old_data = getRecord('testigos', $selected_id);
	if(is_array($old_data)) {
		$old_data = array_map('makeSafe', $old_data);
		$old_data['selectedID'] = makeSafe($selected_id);
	}

	$data['selectedID'] = makeSafe($selected_id);

	// hook: testigos_before_update
	if(function_exists('testigos_before_update')) {
		$args = ['old_data' => $old_data];
		if(!testigos_before_update($data, getMemberInfo(), $args)) {
			if(isset($args['error_message'])) $error_message = $args['error_message'];
			return false;
		}
	}

	$set = $data; unset($set['selectedID']);
	foreach ($set as $field => $value) {
		$set[$field] = ($value !== '' && $value !== NULL) ? $value : NULL;
	}

	if(!update(
		'testigos', 
		backtick_keys_once($set), 
		['`CEDULA`' => $selected_id], 
		$error_message
	)) {
		echo $error_message;
		echo '<a href="testigos_view.php?SelectedID=' . urlencode($selected_id) . "\">{$Translation['< back']}</a>";
		exit;
	}

	$data['selectedID'] = $data['CEDULA'];
	$newID = $data['CEDULA'];

	$eo = ['silentErrors' => true];

	update_calc_fields('testigos', $data['selectedID'], calculated_fields()['testigos']);

	// hook: testigos_after_update
	if(function_exists('testigos_after_update')) {
		$res = sql("SELECT * FROM `testigos` WHERE `CEDULA`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)) $data = array_map('makeSafe', $row);

		$data['selectedID'] = $data['CEDULA'];
		$args = ['old_data' => $old_data];
		if(!testigos_after_update($data, getMemberInfo(), $args)) return;
	}

	// mm: update ownership data
	sql("UPDATE `membership_userrecords` SET `dateUpdated`='" . time() . "', `pkValue`='{$data['CEDULA']}' WHERE `tableName`='testigos' AND `pkValue`='" . makeSafe($selected_id) . "'", $eo);

	// if PK value changed, update $selected_id
	$selected_id = $newID;
}

function testigos_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $separateDV = 0, $TemplateDV = '', $TemplateDVP = '') {
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
	$arrPerm = getTablePermissions('testigos');
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

	$filterer_PUESTO = Request::val('filterer_PUESTO');

	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: PUESTO
	$combo_PUESTO = new DataCombo;

	if($selected_id) {
		if(!check_record_permission('testigos', $selected_id, 'view'))
			return $Translation['tableAccessDenied'];

		// can edit?
		$AllowUpdate = check_record_permission('testigos', $selected_id, 'edit');

		// can delete?
		$AllowDelete = check_record_permission('testigos', $selected_id, 'delete');

		$res = sql("SELECT * FROM `testigos` WHERE `CEDULA`='" . makeSafe($selected_id) . "'", $eo);
		if(!($row = db_fetch_array($res))) {
			return error_message($Translation['No records found'], 'testigos_view.php', false);
		}
		$combo_PUESTO->SelectedData = $row['PUESTO'];
		$urow = $row; /* unsanitized data */
		$row = array_map('safe_html', $row);
	} else {
		$filterField = Request::val('FilterField');
		$filterOperator = Request::val('FilterOperator');
		$filterValue = Request::val('FilterValue');
		$combo_PUESTO->SelectedData = $filterer_PUESTO;
	}
	$combo_PUESTO->HTML = '<span id="PUESTO-container' . $rnd1 . '"></span><input type="hidden" name="PUESTO" id="PUESTO' . $rnd1 . '" value="' . html_attr($combo_PUESTO->SelectedData) . '">';
	$combo_PUESTO->MatchText = '<span id="PUESTO-container-readonly' . $rnd1 . '"></span><input type="hidden" name="PUESTO" id="PUESTO' . $rnd1 . '" value="' . html_attr($combo_PUESTO->SelectedData) . '">';

	ob_start();
	?>

	<script>
		// initial lookup values
		AppGini.current_PUESTO__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['PUESTO'] : htmlspecialchars($filterer_PUESTO, ENT_QUOTES)); ?>"};

		jQuery(function() {
			setTimeout(function() {
				if(typeof(PUESTO_reload__RAND__) == 'function') PUESTO_reload__RAND__();
			}, 50); /* we need to slightly delay client-side execution of the above code to allow AppGini.ajaxCache to work */
		});
		function PUESTO_reload__RAND__() {
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint) { ?>

			$j("#PUESTO-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c) {
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_PUESTO__RAND__.value, t: 'testigos', f: 'PUESTO' },
						success: function(resp) {
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="PUESTO"]').val(resp.results[0].id);
							$j('[id=PUESTO-container-readonly__RAND__]').html('<span class="match-text" id="PUESTO-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=divpol2022vallecali_view_parent]').hide(); } else { $j('.btn[id=divpol2022vallecali_view_parent]').show(); }


							if(typeof(PUESTO_update_autofills__RAND__) == 'function') PUESTO_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term) { return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page) { return { s: term, p: page, t: 'testigos', f: 'PUESTO' }; },
					results: function(resp, page) { return resp; }
				},
				escapeMarkup: function(str) { return str; }
			}).on('change', function(e) {
				AppGini.current_PUESTO__RAND__.value = e.added.id;
				AppGini.current_PUESTO__RAND__.text = e.added.text;
				$j('[name="PUESTO"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=divpol2022vallecali_view_parent]').hide(); } else { $j('.btn[id=divpol2022vallecali_view_parent]').show(); }


				if(typeof(PUESTO_update_autofills__RAND__) == 'function') PUESTO_update_autofills__RAND__();
			});

			if(!$j("#PUESTO-container__RAND__").length) {
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_PUESTO__RAND__.value, t: 'testigos', f: 'PUESTO' },
					success: function(resp) {
						$j('[name="PUESTO"]').val(resp.results[0].id);
						$j('[id=PUESTO-container-readonly__RAND__]').html('<span class="match-text" id="PUESTO-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=divpol2022vallecali_view_parent]').hide(); } else { $j('.btn[id=divpol2022vallecali_view_parent]').show(); }

						if(typeof(PUESTO_update_autofills__RAND__) == 'function') PUESTO_update_autofills__RAND__();
					}
				});
			}

		<?php } else { ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_PUESTO__RAND__.value, t: 'testigos', f: 'PUESTO' },
				success: function(resp) {
					$j('[id=PUESTO-container__RAND__], [id=PUESTO-container-readonly__RAND__]').html('<span class="match-text" id="PUESTO-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>') { $j('.btn[id=divpol2022vallecali_view_parent]').hide(); } else { $j('.btn[id=divpol2022vallecali_view_parent]').show(); }

					if(typeof(PUESTO_update_autofills__RAND__) == 'function') PUESTO_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_clean());


	// code for template based detail view forms

	// open the detail view template
	$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/testigos_templateDV.html';
	$templateCode = @file_get_contents($template_file);

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Detail View', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', (Request::val('Embedded') ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($AllowInsert) {
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return testigos_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return testigos_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
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
		if($AllowUpdate)
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return testigos_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
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
		$jsReadOnly .= "\tjQuery('#PUESTO').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#PUESTO_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#CEDULA').replaceWith('<div class=\"form-control-static\" id=\"CEDULA\">' + (jQuery('#CEDULA').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#NOMBRE').replaceWith('<div class=\"form-control-static\" id=\"NOMBRE\">' + (jQuery('#NOMBRE').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#CELULAR').replaceWith('<div class=\"form-control-static\" id=\"CELULAR\">' + (jQuery('#CELULAR').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#DIRECION').replaceWith('<div class=\"form-control-static\" id=\"DIRECION\">' + (jQuery('#DIRECION').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#CONTACTO').replaceWith('<div class=\"form-control-static\" id=\"CONTACTO\">' + (jQuery('#CONTACTO').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	} elseif($AllowInsert) {
		$jsEditable = "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode = str_replace('<%%COMBO(PUESTO)%%>', $combo_PUESTO->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(PUESTO)%%>', $combo_PUESTO->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(PUESTO)%%>', urlencode($combo_PUESTO->MatchText), $templateCode);

	/* lookup fields array: 'lookup field name' => ['parent table name', 'lookup field caption'] */
	$lookup_fields = ['PUESTO' => ['divpol2022vallecali', 'PUESTO'], ];
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
	$templateCode = str_replace('<%%UPLOADFILE(CEDULA)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(NOMBRE)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(CELULAR)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(DIRECION)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(CONTACTO)%%>', '', $templateCode);

	// process values
	if($selected_id) {
		if( $dvprint) $templateCode = str_replace('<%%VALUE(PUESTO)%%>', safe_html($urow['PUESTO']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(PUESTO)%%>', html_attr($row['PUESTO']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(PUESTO)%%>', urlencode($urow['PUESTO']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(CEDULA)%%>', safe_html($urow['CEDULA']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(CEDULA)%%>', html_attr($row['CEDULA']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(CEDULA)%%>', urlencode($urow['CEDULA']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(NOMBRE)%%>', safe_html($urow['NOMBRE']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(NOMBRE)%%>', html_attr($row['NOMBRE']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(NOMBRE)%%>', urlencode($urow['NOMBRE']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(CELULAR)%%>', safe_html($urow['CELULAR']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(CELULAR)%%>', html_attr($row['CELULAR']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(CELULAR)%%>', urlencode($urow['CELULAR']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(DIRECION)%%>', safe_html($urow['DIRECION']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(DIRECION)%%>', html_attr($row['DIRECION']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(DIRECION)%%>', urlencode($urow['DIRECION']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(CONTACTO)%%>', safe_html($urow['CONTACTO']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(CONTACTO)%%>', html_attr($row['CONTACTO']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(CONTACTO)%%>', urlencode($urow['CONTACTO']), $templateCode);
	} else {
		$templateCode = str_replace('<%%VALUE(PUESTO)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(PUESTO)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(CEDULA)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(CEDULA)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(NOMBRE)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(NOMBRE)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(CELULAR)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(CELULAR)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(DIRECION)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(DIRECION)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(CONTACTO)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(CONTACTO)%%>', urlencode(''), $templateCode);
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
	$rdata = $jdata = get_defaults('testigos');
	if($selected_id) {
		$jdata = get_joined_record('testigos', $selected_id);
		if($jdata === false) $jdata = get_defaults('testigos');
		$rdata = $row;
	}
	$templateCode .= loadView('testigos-ajax-cache', ['rdata' => $rdata, 'jdata' => $jdata]);

	// hook: testigos_dv
	if(function_exists('testigos_dv')) {
		$args = [];
		testigos_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}