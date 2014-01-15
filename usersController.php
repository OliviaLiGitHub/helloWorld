<?php

class usersController extends AppController
{	
	public $components = array('Session');
	
	public function login(){
		
		//echo "currentUser: ".$a=$this->Session->read('online_username');
		if($this->Session->read('online_username')!=NULL){
			$this->Session->write('online_username',NULL);
			echo $this->Session->read('online_username');
			
		} 	
	  	$this->layout = "index";	  				
		$this->loadModel('User');
		if($this->Session->read('online_username')==NULL && isset($_POST['username']) && isset($_POST['password'])){			
			$this->loadModel('User');
			$checkUser=$this->User->find('all',array('conditions' => array('User.username'=>$_POST['username'], 'User.password'=>$_POST['password']
			)));						
			if(sizeof($checkUser)==0){				
				$this->redirect(array("controller"=>"users","action"=>'login'));
			}			
			else{
				$this->Session->write('online_username',$_POST['username']);				
				$this->redirect(array("controller"=>"users","action"=>'allStatus'));
			}
		}	  	
	}
	
	public function logout(){
		if($this->Session->read('online_username')==NULL)$this->redirect(array("controller"=>"users","action"=>'login'));
		$this->Session->write('online_username',NULL);				
		$this->redirect(array("controller"=>"users","action"=>'login'));
	}

	public function register(){
		$this->layout = "empty"; 
		$this->loadModel('User');				
		if(isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['email'])){			
			$condition="INSERT INTO users (username, password, email) VALUES ("."'".$_POST['username']."',"."'".$_POST['password']."','".$_POST['email']."')";
			$userRegister = $this->User->query($condition);
			$this->redirect(array("controller"=>"users","action"=>'login'));	
		}
		 
	}
	
	public function updateStatusSubmit(){
		$this->layout = null;
		if($this->Session->read('online_username')!=NULL){
			$newStatus=$_POST['newStatus'];	
			$this->loadModel('User');		
			$condition="SELECT status, postTime FROM users WHERE users.username='".$this->Session->read('online_username')."'";
			$oldStatusTime=$this->User->query($condition);
			$oldStatus=$oldStatusTime[0]['users']['status'];			
			$newLength=strlen($newStatus);			
			$oldLength=strlen($oldStatus);
			$lengthMin=min($newLength,$oldLength);
			$lengthDifference=abs($oldLength-$newLength);
			
			if($lengthDifference>500) $updateMark=0;
			elseif(strpos($newStatus,$oldStatus)||strpos($oldStatus,$newStatus)){
				$updateMark=1;				
			}else{
				$difference=$lengthDifference;
				$updateMark=1;
				for($i=0;$i<$lengthMin;$i++){
					if(substr($newStatus,$i,1)!=substr($oldStatus,$i,1))$difference++;
					if($difference>500){
						$updateMark=0;
						break;
					}
				}
			}
			$postTime="0000-00-00 00:00:00";
			if($updateMark==1){
				if(empty($oldStatusTime[0]['users']['postTime'])||$oldStatusTime[0]['users']['postTime']=="0000-00-00 00:00:00"){
					$postTime=date('Y-m-d H:i:s');
					$updateCondition="UPDATE users SET status='".$newStatus."',postTime='".$postTime."' WHERE users.username='".$this->Session->read('online_username')."'";	
				}else $updateCondition="UPDATE users SET status='".$newStatus."' WHERE users.username='".$this->Session->read('online_username')."'";
				$this->User->query($updateCondition);//need to check whether succeed!
			}
			$result[0]=$updateMark;
			$result[1]=$this->Session->read('online_username');
			$result[2]=(($updateMark==0)?$oldStatus:$newStatus);

			$result[3]=((empty($oldStatusTime[0]['users']['postTime'])||$oldStatusTime[0]['users']['postTime']=="0000-00-00 00:00:00")?$postTime:$oldStatusTime[0]['users']['postTime']);
			$this->set('result',$result);		
		}		
	}
	
	public function allStatus(){
		if($this->Session->read('online_username')==NULL)$this->redirect(array("controller"=>"users","action"=>'login'));
		$this->layout = "index";
		$this->loadModel('User');
		$allUser = $this->User->find('all',
				array(
					'fields' => array('User.postTime','user.username','user.status'),
					'order' => 'User.postTime ASC'              
		 ));	
		$this->set('allUser',$allUser); 
		$this->set('currentUser',$this->Session->read('online_username'));			
	}
	
	public function deleteStatus(){
		$this->layout = null;
		if($this->Session->read('online_username')==NULL)$this->redirect(array("controller"=>"users","action"=>'login'));
		$this->loadModel('User');
		$condition="UPDATE users SET status='', postTime='' WHERE users.username='".$this->Session->read('online_username')."'";
		$this->User->query($condition);
		$result[0]=1;
		$result[1]=$this->Session->read('online_username');
		$this->set('result',$result);
		
		
	}	
}
?>
