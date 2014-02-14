<?php
/**
 * @class  hbookModel
 * @author CRA (developers@developers.com)
 * @brief Model class of the hbook module
 **/

class hbookModel extends hbook {

	function init () {

	}
		// 목록 가져오기
		function getHbookContentList($args){
            $output = executeQueryArray('hbook.getHbookContentList', $args);
			return $output;
		}


		// 내용 가져오기
		function getHbookContentHbook($args){
            $output = executeQueryArray('hbook.getHbookContentHbook', $args);
			return $output;
		}

		// 주문정보 가져오기
		function getHbookOrder($args){
            $output = executeQueryArray('hbook.getHbookOrder', $args);
			return $output;
		}


		// 검색 하기
		function getHbookContentSearch($args){
			$output = executeQueryArray('hbook.getHbookContentSearch', $args);
			return $output;
		}

		// 거래 하기
		function tradeBook($args){
			return executeQueryArray('hbook.tradeBook', $args);
			
		}

		// 구매자 정보넣기
		function orderBook($args){
			return executeQueryArray('hbook.orderBook', $args);
		}

		// 내구매정보 가져오기
		function getHbookMyOrder($args){
			$output = executeQueryArray('hbook.getHbookMyOrder', $args);
			return $output;
		}
		// 내판매정보 가져오기
		function getHbookMySell($args){
			$output = executeQueryArray('hbook.getHbookMySell', $args);
			return $output;
		}
		
		// 내 찜 정보 확인하기
		function getHbookWishCheck($args){
			$output = executeQueryArray('hbook.getHbookWishCheck', $args);
			return $output;
		}
		
		// 내 찜 정보 가져오기
		function getHbookMyWish($args){
			$output = executeQueryArray('hbook.getHbookMyWish', $args);
			debugPrint($output);
			return $output;
		}
}
?>
