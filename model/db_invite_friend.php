<?php

/////////////////////////////////////////////////////////////////
//INEOSN开源轻博, Copyright (C)   2010 - 2011  www.ineosn.com 
//EMAIL:rovun@foxmail.com     开发者Q群:329354214
//邀请过的好友模块数据模型

class db_invite_friend extends ybModel
{
    var $pk='touid';
    var $table='invite_friend';
	var $linker = array(  
        array(  
            'type' => 'hasone',   // 关联类型，这里是一对一关联  
            'map' => 'user',    // 关联的标识  
            'mapkey' => 'touid', // 本表与对应表关联的字段名  
            'fclass' => 'db_member', // 对应表的类名  
            'fkey' => 'uid',    // 对应表中关联的字段名
			'field'=>'uid,username,domain,blogtag ',//你要限制的字段     
            'enabled' => true     // 启用关联  
        ));
	
	/**
	 *
     *
	 */
	 public function createInvitedFriend($uid,$fuid)
	{
		if('' == $fuid) return false;
		if(1 == $this->create(array('uid'=>$uid, 'touid'=>$fuid))){
			return true;
		}else{
			return false;
		}
	}
    
}

?>
