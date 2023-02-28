<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//---

class PageBuilderController extends Controller {
  
	private $data         = array();
	
	public function __construct( Request $request ) {
		
		//--- Массив для передачи в представление
		$this->data = array(
			'arData' => array(),
		);
		
		$route = $request->route();
		
		$this->request_name    = $route->getName();
		$this->data['request'] = $request;
		
		
		//--- Debug режим
		if ( 
			$request->query->has( 'dbg' ) 
			&& env('DBG_MODE') === true
		) {

			$this->data['dbg'] = array(
				'getName()'         => $route->getName(),
				'getDomain()'       => $route->getDomain(),
				'getPrefix()'       => $route->getPrefix(),
				'getActionName()'   => $route->getActionName(),
				'getActionMethod()' => $route->getActionMethod(),
				'getAction()'       => $route->getAction(),
				'getMissing()'      => $route->getMissing(),
				'getValidators()'   => $route->getValidators(),
				'getCompiled()'     => $route->getCompiled(),
			);
			
		}
		
	}
	
	
	public function index( Request $request ) {
		
		//--- Проектные части шаблона
		$this->data['view']['header'] 							= view( 'templates.header.HeaderView');
		$this->data['view']['main'] 								= view( 'templates.main.MainView');
		$this->data['view']['footer'] 							= view( 'templates.footer.FooterView');
		
		//--- Стандартные части шаблона
		$this->data['view']['meta'] 								= view( 'includes.MetaView');
		$this->data['view']['stylesHeader']        	= view( 'includes.StylesHeaderView' );
		$this->data['view']['stylesFooter']        	= view( 'includes.StylesFooterView' );
		$this->data['view']['scriptsHeader'] 				= view( 'includes.ScriptsHeaderView' );
		$this->data['view']['scriptsFooter'] 				= view( 'includes.ScriptsFooterView' );
		
		//--- Шаблон контентной части по умолчанию
		$template = 'IndexView';
		
		//--- Переключатель контента в зависимости от роута
		if ( $this->request_name === 'home_page' ) {
			
			$this->data['content'] = 'asdasd';
			$template = 'IndexView';
			
		}
		
		return view( $template, $this->data );
		
	}
	
}
