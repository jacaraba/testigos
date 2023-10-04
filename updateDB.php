<?php
	// check this file's MD5 to make sure it wasn't called before
	$tenantId = Authentication::tenantIdPadded();
	$setupHash = __DIR__ . "/setup{$tenantId}.md5";

	$prevMD5 = @file_get_contents($setupHash);
	$thisMD5 = md5_file(__FILE__);

	// check if this setup file already run
	if($thisMD5 != $prevMD5) {
		// set up tables
		setupTable(
			'departamentos', " 
			CREATE TABLE IF NOT EXISTS `departamentos` ( 
				`dd` VARCHAR(2) NOT NULL,
				PRIMARY KEY (`dd`),
				`departamento` VARCHAR(12) NULL
			) CHARSET utf8mb4"
		);

		setupTable(
			'divpol2022', " 
			CREATE TABLE IF NOT EXISTS `divpol2022` ( 
				`PUESTO` VARCHAR(9) NOT NULL,
				PRIMARY KEY (`PUESTO`),
				`dd` VARCHAR(2) NULL,
				`mm` VARCHAR(3) NULL,
				`zz` VARCHAR(2) NULL,
				`pp` VARCHAR(2) NULL,
				`departamento` VARCHAR(12) NULL,
				`municipio` VARCHAR(15) NULL,
				`nompue` VARCHAR(40) NULL,
				`direccion` VARCHAR(50) NULL,
				`mujeres` VARCHAR(5) NULL,
				`hombres` VARCHAR(5) NULL,
				`total` VARCHAR(5) NULL,
				`mesas` VARCHAR(2) NULL
			) CHARSET utf8mb4"
		);

		setupTable(
			'divpol2022valle', " 
			CREATE TABLE IF NOT EXISTS `divpol2022valle` ( 
				`PUESTO` VARCHAR(9) NOT NULL,
				PRIMARY KEY (`PUESTO`),
				`dd` VARCHAR(2) NULL,
				`mm` VARCHAR(3) NULL,
				`zz` VARCHAR(2) NULL,
				`pp` VARCHAR(2) NULL,
				`departamento` VARCHAR(12) NULL,
				`municipio` VARCHAR(15) NULL,
				`nompue` VARCHAR(40) NULL,
				`direccion` VARCHAR(50) NULL,
				`mujeres` VARCHAR(5) NULL,
				`hombres` VARCHAR(5) NULL,
				`total` VARCHAR(5) NULL,
				`mesas` VARCHAR(2) NULL,
				`comuna` VARCHAR(2) NULL,
				`nomcomuna` VARCHAR(30) NULL
			) CHARSET utf8mb4"
		);

		setupTable(
			'divpol2022vallecali', " 
			CREATE TABLE IF NOT EXISTS `divpol2022vallecali` ( 
				`PUESTO` VARCHAR(9) NOT NULL,
				PRIMARY KEY (`PUESTO`),
				`dd` VARCHAR(2) NULL,
				`mm` VARCHAR(3) NULL,
				`zz` VARCHAR(2) NULL,
				`pp` VARCHAR(2) NULL,
				`departamento` VARCHAR(12) NULL,
				`municipio` VARCHAR(15) NULL,
				`nompue` VARCHAR(40) NULL,
				`direccion` VARCHAR(50) NULL,
				`mujeres` VARCHAR(5) NULL,
				`hombres` VARCHAR(5) NULL,
				`total` VARCHAR(5) NULL,
				`mesas` VARCHAR(2) NULL,
				`comuna` VARCHAR(2) NULL,
				`nomcomuna` VARCHAR(30) NULL
			) CHARSET utf8mb4"
		);

		setupTable(
			'jurados', " 
			CREATE TABLE IF NOT EXISTS `jurados` ( 
				`PUESTO` VARCHAR(9) NULL,
				`CEDULA` VARCHAR(10) NOT NULL,
				PRIMARY KEY (`CEDULA`),
				`NOMBRE` VARCHAR(50) NULL,
				`CELULAR` VARCHAR(10) NULL,
				`DIRECION` VARCHAR(50) NULL,
				`CONTACTO` VARCHAR(10) NULL
			) CHARSET utf8mb4"
		);

		setupTable(
			'municipios', " 
			CREATE TABLE IF NOT EXISTS `municipios` ( 
				`ddmm` VARCHAR(5) NOT NULL,
				PRIMARY KEY (`ddmm`),
				`dd` VARCHAR(2) NULL,
				`mm` VARCHAR(3) NULL,
				`municipio` VARCHAR(30) NULL
			) CHARSET utf8mb4"
		);

		setupTable(
			'numeros', " 
			CREATE TABLE IF NOT EXISTS `numeros` ( 
				`curul` INT(4) NOT NULL,
				PRIMARY KEY (`curul`)
			) CHARSET utf8mb4"
		);

		setupTable(
			'partidos2019', " 
			CREATE TABLE IF NOT EXISTS `partidos2019` ( 
				`partido` VARCHAR(4) NOT NULL,
				PRIMARY KEY (`partido`),
				`nompartido` VARCHAR(79) NULL
			) CHARSET utf8mb4"
		);

		setupTable(
			'partidos2022', " 
			CREATE TABLE IF NOT EXISTS `partidos2022` ( 
				`partido` VARCHAR(4) NOT NULL,
				PRIMARY KEY (`partido`),
				`nompartido` VARCHAR(79) NULL
			) CHARSET utf8mb4"
		);

		setupTable(
			'testigos', " 
			CREATE TABLE IF NOT EXISTS `testigos` ( 
				`PUESTO` VARCHAR(9) NULL,
				`CEDULA` VARCHAR(10) NOT NULL,
				PRIMARY KEY (`CEDULA`),
				`NOMBRE` VARCHAR(50) NULL,
				`CELULAR` VARCHAR(10) NULL,
				`DIRECION` VARCHAR(50) NULL,
				`CONTACTO` VARCHAR(10) NULL
			) CHARSET utf8mb4"
		);
		setupIndexes('testigos', ['PUESTO',]);

		setupTable(
			'testigospuestos', " 
			CREATE TABLE IF NOT EXISTS `testigospuestos` ( 
				`llave` INT(3) NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`llave`),
				`CEDULA` VARCHAR(10) NULL,
				`PUESTO` VARCHAR(9) NULL
			) CHARSET utf8mb4"
		);
		setupIndexes('testigospuestos', ['CEDULA','PUESTO',]);

		setupTable(
			'concejo2019vallecalivotos', " 
			CREATE TABLE IF NOT EXISTS `concejo2019vallecalivotos` ( 
				`CVOTOS` VARCHAR(31) NOT NULL,
				PRIMARY KEY (`CVOTOS`),
				`PUESTO` VARCHAR(9) NULL,
				`MESA` VARCHAR(15) NULL,
				`PARTIDO` VARCHAR(4) NULL,
				`MESA_` INT(4) NULL,
				`CANDIDATO` VARCHAR(3) NOT NULL,
				`VOTOS` VARCHAR(8) NOT NULL
			) CHARSET utf8mb4"
		);
		setupIndexes('concejo2019vallecalivotos', ['PUESTO','PARTIDO',]);



		// save MD5
		@file_put_contents($setupHash, $thisMD5);
	}


	function setupIndexes($tableName, $arrFields) {
		if(!is_array($arrFields) || !count($arrFields)) return false;

		foreach($arrFields as $fieldName) {
			if(!$res = @db_query("SHOW COLUMNS FROM `$tableName` like '$fieldName'")) continue;
			if(!$row = @db_fetch_assoc($res)) continue;
			if($row['Key']) continue;

			@db_query("ALTER TABLE `$tableName` ADD INDEX `$fieldName` (`$fieldName`)");
		}
	}


	function setupTable($tableName, $createSQL = '', $arrAlter = '') {
		global $Translation;
		$oldTableName = '';
		ob_start();

		echo '<div style="padding: 5px; border-bottom:solid 1px silver; font-family: verdana, arial; font-size: 10px;">';

		// is there a table rename query?
		if(is_array($arrAlter)) {
			$matches = [];
			if(preg_match("/ALTER TABLE `(.*)` RENAME `$tableName`/i", $arrAlter[0], $matches)) {
				$oldTableName = $matches[1];
			}
		}

		if($res = @db_query("SELECT COUNT(1) FROM `$tableName`")) { // table already exists
			if($row = @db_fetch_array($res)) {
				echo str_replace(['<TableName>', '<NumRecords>'], [$tableName, $row[0]], $Translation['table exists']);
				if(is_array($arrAlter)) {
					echo '<br>';
					foreach($arrAlter as $alter) {
						if($alter != '') {
							echo "$alter ... ";
							if(!@db_query($alter)) {
								echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
								echo '<div class="text-danger">' . $Translation['mysql said'] . ' ' . db_error(db_link()) . '</div>';
							} else {
								echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
							}
						}
					}
				} else {
					echo $Translation['table uptodate'];
				}
			} else {
				echo str_replace('<TableName>', $tableName, $Translation['couldnt count']);
			}
		} else { // given tableName doesn't exist

			if($oldTableName != '') { // if we have a table rename query
				if($ro = @db_query("SELECT COUNT(1) FROM `$oldTableName`")) { // if old table exists, rename it.
					$renameQuery = array_shift($arrAlter); // get and remove rename query

					echo "$renameQuery ... ";
					if(!@db_query($renameQuery)) {
						echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
						echo '<div class="text-danger">' . $Translation['mysql said'] . ' ' . db_error(db_link()) . '</div>';
					} else {
						echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
					}

					if(is_array($arrAlter)) setupTable($tableName, $createSQL, false, $arrAlter); // execute Alter queries on renamed table ...
				} else { // if old tableName doesn't exist (nor the new one since we're here), then just create the table.
					setupTable($tableName, $createSQL, false); // no Alter queries passed ...
				}
			} else { // tableName doesn't exist and no rename, so just create the table
				echo str_replace("<TableName>", $tableName, $Translation["creating table"]);
				if(!@db_query($createSQL)) {
					echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
					echo '<div class="text-danger">' . $Translation['mysql said'] . db_error(db_link()) . '</div>';

					// create table with a dummy field
					@db_query("CREATE TABLE IF NOT EXISTS `$tableName` (`_dummy_deletable_field` TINYINT)");
				} else {
					echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
				}
			}

			// set Admin group permissions for newly created table if membership_grouppermissions exists
			if($ro = @db_query("SELECT COUNT(1) FROM `membership_grouppermissions`")) {
				// get Admins group id
				$ro = @db_query("SELECT `groupID` FROM `membership_groups` WHERE `name`='Admins'");
				if($ro) {
					$adminGroupID = intval(db_fetch_row($ro)[0]);
					if($adminGroupID) @db_query("INSERT IGNORE INTO `membership_grouppermissions` SET
						`groupID`='$adminGroupID',
						`tableName`='$tableName',
						`allowInsert`=1, `allowView`=1, `allowEdit`=1, `allowDelete`=1
					");
				}
			}
		}

		echo '</div>';

		$out = ob_get_clean();
		if(defined('APPGINI_SETUP') && APPGINI_SETUP) echo $out;
	}
