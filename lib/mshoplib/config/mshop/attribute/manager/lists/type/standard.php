<?php

/**
 * @copyright Metaways Infosystems GmbH, 2011
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 */

return array(
	'delete' => array(
		'ansi' => '
			DELETE FROM "mshop_attribute_list_type"
			WHERE :cond AND siteid = ?
		'
	),
	'insert' => array(
		'ansi' => '
			INSERT INTO "mshop_attribute_list_type"(
				"siteid", "code", "domain", "label", "status", "mtime",
				"editor", "ctime"
			) VALUES (
				?, ?, ?, ?, ?, ?, ?, ?
			)
		'
	),
	'update' => array(
		'ansi' => '
			UPDATE "mshop_attribute_list_type"
			SET "siteid" = ?, "code" = ?, "domain" = ?, "label" = ?,
				"status" = ?, "mtime" = ?, "editor" = ?
			WHERE "id" = ?
		'
	),
	'search' => array(
		'ansi' => '
			SELECT DISTINCT mattlity."id" AS "attribute.lists.type.id", mattlity."siteid" AS "attribute.lists.type.siteid",
				mattlity."code" AS "attribute.lists.type.code", mattlity."domain" AS "attribute.lists.type.domain",
				mattlity."label" AS "attribute.lists.type.label", mattlity."status" AS "attribute.lists.type.status",
				mattlity."mtime" AS "attribute.lists.type.mtime", mattlity."ctime" AS "attribute.lists.type.ctime",
				mattlity."editor" AS "attribute.lists.type.editor"
			FROM "mshop_attribute_list_type" AS mattlity
			:joins
			WHERE :cond
			/*-orderby*/ ORDER BY :order /*orderby-*/
			LIMIT :size OFFSET :start
		'
	),
	'count' => array(
		'ansi' => '
			SELECT COUNT(*) AS "count"
			FROM (
				SELECT DISTINCT mattlity."id"
				FROM "mshop_attribute_list_type" AS mattlity
				:joins
				WHERE :cond
				LIMIT 10000 OFFSET 0
			) AS list
		'
	),
	'newid' => array(
		'mysql' => 'SELECT LAST_INSERT_ID()'
	),
);

