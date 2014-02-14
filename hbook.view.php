<?php
/**
 * @class  hbookView
 * @author CRA (developers@developers.com)
 * @brief View class of hbook module
 **/

class hbookView extends hbook {
        /**
         * @brief 초기화
         **/
      function init() {

            // module_srl이 있으면 미리 체크하여 존재하는 모듈이면 module_info 세팅
            $module_srl = Context::get('module_srl');
            if(!$module_srl && $this->module_srl) {
                $module_srl = $this->module_srl;
                Context::set('module_srl', $module_srl);
            }

            // module model 객체 생성 
            $oModuleModel = &getModel('module');

            // module_srl이 넘어오면 해당 모듈의 정보를 미리 구해 놓음
			// 모듈의 브라우저 타이틀, 관리자, 레이아웃 등 xe_modules table의 값과 정보
            if($module_srl) {
                $module_info = $oModuleModel->getModuleInfoByModuleSrl($module_srl);
				$this->module_info = $module_info;
				Context::set('module_info',$module_info);
            }

            // 스킨 경로를 미리 template_path 라는 변수로 설정함
            // 스킨이 존재하지 않는다면 default로 변경
            $template_path = sprintf("%sskins/%s/",$this->module_path, $this->module_info->skin);
            if(!is_dir($template_path)||!$this->module_info->skin) {
                $this->module_info->skin = 'default';
                $template_path = sprintf("%sskins/%s/",$this->module_path, $this->module_info->skin);
            }
            $this->setTemplatePath($template_path);

        }
		
		//첫페이지보여주기
		function dispHbookFirst() {
			$this->setTemplateFile('booksearch');
		}
		/**
         * @brief 검색목록
         **/
        function dispHbookContentSearch() {

			// module_srl 확인
			$module_srl = Context::get('module_srl');
            $args->module_srl = $module_srl; 
            $args->page = Context::get('page');

			//$searchtype = Context::get('searchtype');
			//$args->searchtype = $searchtype;
			$searchterm = Context::get('searchterm');
			$args->searchterm = $searchterm;

			// module model 객체 생성 
            $oModuleModel = &getModel('module');

			// hbook model에서 목록을 가져옴
            $oHbookModel = &getModel('hbook');
            $output = $oHbookModel->getHbookContentSearch($args);
			if(!$output->data) $output->data = array();

			// $book_list 변수에 담는다.
			Context::set('book_list', $output->data);
            Context::set('page', $output->page);
            Context::set('page_navigation', $output->page_navigation);

            // template_file을 list.html로 지정
            $this->setTemplateFile('list');
        }


        /**
         * @brief 목록
         **/
        function dispHbookContentList() {


			// module_srl 확인
			$module_srl = Context::get('module_srl');
            $args->module_srl = $module_srl; 
            $args->page = Context::get('page');

			// module model 객체 생성 
            $oModuleModel = &getModel('module');

			// hbook model에서 목록을 가져옴
            $oHbookModel = &getModel('hbook');
            $output = $oHbookModel->getHbookContentList($args);
			if(!$output->data) $output->data = array();

			// $book_list 변수에 담는다.
			Context::set('book_list', $output->data);
            Context::set('page', $output->page);
            Context::set('page_navigation', $output->page_navigation);

            // template_file을 list.html로 지정
            $this->setTemplateFile('list');
        }

		function dispHbookMytrade() {


			// module_srl 확인
			$module_srl = Context::get('module_srl');
            $args->module_srl = $module_srl; 
            $args->page = Context::get('page');
			//***** logged_info 가져오기 *****
			$logged_info = Context::get('logged_info');
			$my_nick = $logged_info->nick_name;
			$args->my_nick = $my_nick;
			// 구매 or 판매 결정변수
			$kotrade = Context::get('kotrade');

			// module model 객체 생성 
            $oModuleModel = &getModel('module');

			// hbook model에서 목록을 가져옴
            $oHbookModel = &getModel('hbook');

			// 로그인정보와 같은 오더 가져오기
			if(!$kotrade)
				$output = $oHbookModel->getHbookMyOrder($args);
			else
				$output = $oHbookModel->getHbookMySell($args);
			if(!$output->data) $output->data = array();

			// $book_list 변수에 담는다.
			Context::set('book_list', $output->data);
            Context::set('page', $output->page);
            Context::set('page_navigation', $output->page_navigation);

            // template_file을 list.html로 지정
            $this->setTemplateFile('trade_request_list');

		}

		//게시글 화면 연결
        function dispHbookContentView() {

			// book의 식별번호를 가져옴
            $trade_srl = Context::get('trade_srl');
			$obj->trade_srl = $trade_srl;

			// hbook model에서 내용을 가져옴
			$oHbookModel = &getModel('hbook');
			$output = $oHbookModel->getHbookContentHbook($obj);
			$output2 = $oHbookModel->getHbookOrder($obj);
			
			// $output->data의 배열 형식을 변경하여 $book_info 변수에 Context 세팅
			Context::set('book_info', $this->arrangeBookInfo($output));
			Context::set('order_info', $this->arrangeOrderInfo($output2));

			// 내용 작성시 검증을 위해 사용되는 XmlJSFilter  
            Context::addJsFilter($this->module_path.'tpl/filter', 'order_insert.xml');
            Context::addJsFilter($this->module_path.'tpl/filter', 'order_cancel.xml');
			Context::addJsFilter($this->module_path.'tpl/filter', 'order_finish.xml');
			Context::addJsFilter($this->module_path.'tpl/filter', 'wish_insert.xml');
			
							// 콜백 함수를 처리하는 javascript 
            Context::addJsFile($this->module_path.'tpl/js/hbook.js');

            // template_file을 view.html로 지정
            $this->setTemplateFile('view');
        }
		
		//책 검색 새창 띄우기
		function dispHbookContentNew(){
			$this->module_info->layout_srl = 0;
            $this->setTemplateFile('info_search');
		}

        /**
         * @brief book model에서 받아온 $output->data를 스킨파일에 보내기 전에 배열 형식 변경
         **/
        function arrangeBookInfo($output) {
			// 1차 배열 형식으로 변경
			if($output->data) {
				foreach($output->data as $val) {
					$obj = null;
					$obj->trade_srl = $val->trade_srl;
					$obj->isbn = $val->isbn;
					$obj->book_title = $val->book_title;
					$obj->book_author = $val->book_author;
					$obj->book_publisher = $val->book_publisher;
					$obj->book_price = $val->book_price;
					$obj->hope_price = $val->hope_price;
					$obj->genre = $val->genre;
					$obj->regdate = $val->regdate;
					$obj->ting = $val->ting;
					$obj->phonenum = $val->phonenum;
					//$obj->seller_nick = $val->seller_nick;
				}
				return $obj;
			}
		}

		// order_info를 위한 1차 배열 형식 변경

		function arrangeOrderInfo($output) {
			if($output->data) {
				foreach($output->data as $val) {
					$obj = null;
					$obj->seller_nick = $val->seller_nick;
					$obj->trade_srl = $val->trade_srl;
					$obj->customer_nick = $val->customer_nick;
				}
				return $obj;
			}
		}


	    /**
         * @brief 내용 작성/수정 화면 출력
         **/
        function dispHbookContentWrite() {

			// trade_srl 확인
			$trade_srl = Context::get('trade_srl');


			// trade_srl 이 있는 경우 update
			if(isset($trade_srl)) {

				$obj->trade_srl = $trade_srl;

				// hbook model에서 내용을 가져옴
				$oHbookModel = &getModel('hbook');
				$output = $oHbookModel->getHbookContentHbook($obj);

			   // 변경된 $output을 $book_info 변수에 set
			   Context::set('book_info', $this->arrangeBookInfo($output));

			// trade_srl 이 없는 경우 새로 등록하기 위해서 초기화
			} else {

			  //$trade_srl = NULL;
			  //Context::set('trade_srl', $trade_srl);
			  // 또는
			  Context::set('trade_srl','',true);
			}

             // 내용 작성시 검증을 위해 사용되는 XmlJSFilter  
            Context::addJsFilter($this->module_path.'tpl/filter', 'content_insert.xml');

			// 콜백 함수를 처리하는 javascript 
            Context::addJsFile($this->module_path.'tpl/js/hbook.js');

			// 내용 작성화면 템플릿 파일 지정 write.html
            $this->setTemplateFile('write');
		}


        /**
         * @brief 삭제 화면 출력
         **/
        function dispHbookContentDelete() {

			// GET parameter에서 trade_srl을 받아 확인
            $trade_srl = Context::get('trade_srl');
			//$book_title = Context::get('book_title');

			// trade_srl이 없는 경우 오류 메시지 출력
			if(!$trade_srl) $this->alertMessage('msg_not_founded');

             // 내용 작성시 검증을 위해 사용되는 XmlJSFilter
            Context::addJsFilter($this->module_path.'tpl/filter', 'content_delete.xml');

			// 콜백 함수를 처리하는 javascript 
            Context::addJsFile($this->module_path.'tpl/js/hbook.js');

            // 템플릿 파일 지정
            $this->setTemplateFile('delete');
        }

        /**
         * @brief 메세지 출력
         **/
        function dispHbookMessage($msg_code) {
            $msg = Context::getLang($msg_code);
            if(!$msg) $msg = $msg_code;
            Context::set('message', $msg);
            $this->setTemplateFile('message');
        }

        /**
         * @brief 오류메세지를 system alert로 출력하는 method
         * 특별한 오류를 알려주어야 하는데 별도의 디자인까지는 필요 없을 경우 페이지를 모두 그린후에 오류를 출력하도록 함
         **/
        function alertMessage($message) {
            $script =  sprintf('<script type="text/javascript"> xAddEventListener(window,"load", function() { alert("%s"); } );</script>', Context::getLang($message));
            Context::addHtmlHeader( $script );
        }

		/**
		 *  == API 액션 ==
		 */
		function dispHbookAladdinSearch() { // API 검색페이지
			
			$this->setTemplateFile('AladdinSearch');
		}

		function dispHbookAladdinListResult(){ //상품 list 출력
			$lay = Context::get('lay');
			if($lay)
				$this->module_info->layout_srl = 0;
			
			$title = Context::get('title');
			$is_content = 'list';

			Context::set('title', $title); //html로 php변수를 집어넣음
			Context::set('is_content', $is_content);

			$this->setTemplateFile('AladdinListResult');
		}	

		function dispHbookAladdinContentResult() { //상품 개별 출력
			$lay = Context::get('lay');
			Context::set('lay', $lay);

			if($lay)
				$this->module_info->layout_srl = 0;

			$isbn = Context::get('isbn');
			$isbn13 = Context::get('isbn13');
			$link = Context::get('link');
			$is_content = 'content';

			Context::set('isbn', $isbn);
			Context::set('is_content', $is_content);
			Context::set('link', $link);

			$this->setTemplateFile('AladdinContentResult');
		}
		
		function dispHbookMyWish() {
		
		
			// module_srl 확인
			$module_srl = Context::get('module_srl');
			$args->module_srl = $module_srl;
			$args->page = Context::get('page');
		
			//***** logged_info 가져오기 *****
			$logged_info = Context::get('logged_info');
			$my_nick = $logged_info->nick_name;
			$args->my_nick = $my_nick;
		
		
			// module model 객체 생성
			$oModuleModel = &getModel('module');
		
			// hbook model에서 목록을 가져옴
			$oHbookModel = &getModel('hbook');
		
			 
			// 로그인정보와 같은 오더 가져오기
			$output = $oHbookModel->getHbookMyWish($args);
			if(!$output->data) $output->data = array();
		
			 
			// $book_list 변수에 담는다.
			Context::set('book_list', $output->data);
			Context::set('page', $output->page);
			Context::set('page_navigation', $output->page_navigation);
		
			// template_file을 list.html로 지정
			$this->setTemplateFile('trade_request_list');
		
		}


}
?>
