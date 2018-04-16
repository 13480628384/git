<?php
class Shopusers{

	private $token = null;
	private $openid = null;
	private $money=0;
	private $grade=0;
	private $type=1;

    public function __construct($token='', $openid = '',$grade,$money = 0){
        $this->token   = $token;
        $this->openid = $openid;
        $this->grade = $grade;
        $this->money = $money;
    }

    public function updateGrade(){
    	# 如果金额相等
	$scoreSet = M('Shop_scoreset')->field('chong_level')->where(array('token'=>$this->token))->find();
    	$scoreSetArray = json_decode($scoreSet['chong_level'],true);
	if($this->money != 0){
	      if(count($scoreSetArray) > 0){
		      foreach($scoreSetArray as $key=>$val){
			     if(key($val)){
				     if(key($val) == $this->money){
				     	$this->grade = $val[key($val)];
					$this->type = 2;
				     }
			     }
		      }
	      }
	}
	
    	$gradeNeedScore = $this->getGradeNeedScore($this->grade);
    	$userScore = $this->getUserScore();
    	if($gradeNeedScore){
    			if($this->updateScore($gradeNeedScore-$userScore)){
    				$logpath = LOG_PATH.date('y_m_d').'.jifen_log';
        			Log::write($this->openid.'_'.$this->type.'_'.$this->grade.'_SUCCSSS','INFO','',$logpath,'');
        			return true;
    			}else{
    				Log::write($this->openid.'_'.$this->type.'_'.$this->grade.'_SUCCSSS','INFO','',$logpath,'');
    				return false;
    			}

    	}

    }

     /*
      获取到达等级所需积分
    */
    public function getGradeNeedScore(){
    	$gradelist = M('Shopgrade')->where(array('token'=>$this->token))->order('scope asc')->select();
    	if(isset($gradelist[$this->grade -1])){
    		return 	$gradelist[$this->grade -1]['scope'];
    	}else{
    		return 0;
    	}
    }

    /*
    获取积分
    */
    public function getUserScore(){
    	# code...
    	$users = M('Shop_users')->where(array('token'=>$this->token,'openid'=>$this->openid))->find();
    	$scoredata = M('dy_score')->field('sum(score) as allscore')->where(array('token'=>$this->token,'openid'=>$this->openid,'score'=>array('gt',0)))->select();
    	$userScore = $scoredata[0]['allscore']+$users['other_score'];
    	return $userScore;
    }

    /*
    升级积分
    */
    public function updateScore($score){
    	# code...
    	$data['token']=$this->token;
        $data['openid']=$this->openid;
        $data['type']=$this->type;//代表手工增加
        $data['addtime']=date('Y-m-d h:i:s',time());
        $data['score']=$score+1;
        $logpath = LOG_PATH.date('y_m_d').'.jifen_log';
	    if(M('Dy_other_score')->add($data)){
	    	if(M('Shop_users')->where(array('token'=>$this->token,'openid'=>$this->openid))->setInc('other_score',$score+1)){
	    		return true;
	    	}else{
        		Log::write("M('Shop_users')->where(array('token'=>$this->token,'openid'=>$this->openid))->setInc('other_score',$score+1)_FAIL",'INFO','',$logpath,'');
	    		return false;
	    	}
	    }else{
	    	Log::write("M('Dy_other_score')->add($data)_FAIL",'INFO','',$logpath,'');
	    	return false;
	    }

    }


}