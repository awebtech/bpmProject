<?php

	/**
	 * Description of Mapping
	 *
	 * @author akornida
	 */

	class Mapping {
		static function Get($prefix, $str, $forward = true) {
			if (is_array($prefix)) {
				$prefix = implode('|', $prefix);
			}

			$hash = sha1($prefix.$str);

			$column_name = $forward ? 'mapping2' : 'mapping1';

			$sql = "
				SELECT
					$column_name AS str
				FROM
					".TABLE_PREFIX."mapping
				WHERE
					".($forward ? 'hash1' : 'hash2')." = '$hash'
			";

			$mapping = DB::executeOne($sql);

			if (empty($mapping)) {
				return false;
			}

			return $mapping['str'];
		}
	}

?>
