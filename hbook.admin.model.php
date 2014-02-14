<?php
/**
 * @class  memoAdminModel
 * @author CRA (developers@developers.com)
 * admin model class of memo module
 **/

class hbookAdminModel extends hbook {

	function init() {
	}


	function getHbookAdminList($args){
            $output = executeQueryArray('hbook.getHbookAdminList', $args);
			return $output;
		}
}
?>
