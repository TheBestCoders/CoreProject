<?php

class Paginate
{
	public $_total = 0;
	public $_limit = 0;
	public $_page = 1;
	public $_pages_num = 0;
	public $_html = '';
	public $_all_results;
	
	public function Paginate(&$query)
	{
		$this->_limit = _PER_PAGE_;
		$this->_page = Core::getValue('page', 1);
		$start = ($this->_page - 1) * $this->_limit;
		
		$_SESSION[_COOKIE_KEY_]['paging'] = '';
		$_SESSION[_COOKIE_KEY_]['query_before'] = base64_encode($query);
		
		if($query != ''){
			$this->_total = Db::getInstance()->countRow($query);
			$this->_all_results = Db::getInstance()->getLastResult();
			Db::getInstance()->freeResult();
			
			$this->_pages_num = ceil($this->_total / $this->_limit);
			
			if($this->_total > $this->_limit)
				$query .= ' LIMIT '.$start.', '.$this->_limit;
		}
	}
	
	public function getAllResults()
	{
		return $this->_all_results;
	}
	
	public function getLinks()
	{
		if($this->_pages_num > 1)
		{
			$uri = $_SERVER['PHP_SELF'];
			$queryS = $_SERVER['QUERY_STRING'];

			$queryS = (((string)strpos($queryS, 'page=')!='') ? substr($queryS, 0, (strpos($queryS, 'page=')==0 ? 0 : strpos($queryS, 'page=')-1)) : $queryS);

			if($queryS!='')
				$uri .= '?'.$queryS;
			
			if(strpos($uri, '?')) $uri .= '&page=';
			else $uri .= '?page=';
			
			$next = $this->_page + 1;
			$prev = $this->_page - 1;
			
			$this->_html = '<div class="paginate"><ul>';
			if($this->_page != 1){
				$this->_html .= '<li><a href="'.$uri.$prev.'">'.l(prev).'</a></li>';
			} else {
				$this->_html .= '<li class="disabled"><span>'.l(prev).'</span></li>';
			}
			
			for($l=1; $l <= $this->_pages_num; $l++)
			{
				if($this->_page == $l){
					$this->_html .= '<li class="current"><span>'.$l.'</span></li>';
				} else {
					$this->_html .= '<li><a href="'.$uri.$l.'">'.$l.'</a></li>';
				}
			}
			
			
			if($this->_page != $this->_pages_num){
				$this->_html .= '<li><a href="'.$uri.$next.'">'.l(next).'</a></li>';
			} else {
				$this->_html .= '<li class="disabled"><span>'.l(next).'</span></li>';
			}
			$this->_html .= '</ul>';
			$this->_html .= '<span class="counts">'.$this->_page.' '.l(of).' '.$this->_pages_num.' pages. Total '.$this->_total.' '.l(results).'</span>';
			$this->_html .= '<br clear="all" /></div>';
		}
		$_SESSION[_COOKIE_KEY_]['paging'] = $this->_html;
		return $this->_html;
	}
	
	public static function getPagination()
	{
		if(isset($_SESSION[_COOKIE_KEY_]['paging']))
			return $_SESSION[_COOKIE_KEY_]['paging'];
		else 
			return '';
	}
	
	public static function getLastQuery()
	{
		if(isset($_SESSION[_COOKIE_KEY_]['query_before']))
			return base64_decode($_SESSION[_COOKIE_KEY_]['query_before']);
		else
			return '';
	}
	
}
?>