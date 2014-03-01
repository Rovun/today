<?php
/////////////////////////////////////////////////////////////////
//INEOSN开源轻博, Copyright (C)   2010 - 2011  www.ineosn.com 
//EMAIL:rovun@foxmail.com     开发者Q群:329354214                              
//$Id: db_category.php 730 2012-06-06 13:29:57Z rovun $ 

class db_category extends ybModel  
{  
	var $pk = "cid"; // 主键  
	var $table = "catetags"; // 数据表的名称 
	
	
	function findCate()
	{
		if(!spAccess('r','systag')){  //读取设置
			$tag = $this->findAll('','sort ASC');
			spAccess('w','systag',$tag,-1);
		}else{
			$tag = spAccess('r','systag');
		}
		return $tag;
	}
	
	
	function saveCate($post)
	{
		if(is_array($post['cate']))
		{
			foreach($post['cate'] as $k=>$v)
			{
				$arr['sort'] = $v['sort'];
				$arr['catename'] = $v['catename'];
				$this->update(array('cid'=>$k),$arr);
			}
		}
	}

}
?>
