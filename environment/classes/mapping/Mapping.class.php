<?php

	/**
	 * Description of Mapping
	 *
	 * @author akornida
	 */

	class Mapping {
		static function Get($prefix, $str, $forward = true) {
			$prefix = strtolower($prefix);
			$hash = sha1($prefix.$str);

			$name = $forward ? 'mapping2' : 'mapping1';

			$sql = "
				SELECT
					$name
				FROM
					".TABLE_PREFIX."mapping
				WHERE
					".($forward ? 'hash1' : 'hash2')." = '$hash'
			";

			$mapping = DB::executeOne($sql);

			if (empty($mapping)) {
				return false;
			}

			return $mapping['name'];
		}
	}

?>
