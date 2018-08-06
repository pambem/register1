<?php 


Class Answer {
		
	private $msg;
	private $status;
	private $code;
	private $data;
	
	public function __construct($msg, $status, $code=null, $data=null){
		
		$this->setMsg($msg);
		$this->setSatus($status);
		$this->setCode($code);
		$this->setData($data);
		
	}
    
    final static public function JSON(Answer $a){
		$r['msg'] = $a->getMsg();
		$r['message'] = $a->getMsg();
		$r['status'] = $a->getStatus();
		$r['code'] = $a->getCode();
		$r['data'] = $a->getData(); 
		return json_encode($r);
	}
	
	public function setMsg($msg){
		$this->msg = $msg;
	}
	
	public function setSatus($status){
		$this->status = $status;
	}
	
	public function setCode($code){
		$this->code = $code;
	}
	
	public function setData($data){
		$this->data = $data;
	}
	
	public function getMsg(){
		return $this->msg;
	}
	
	public function getStatus(){
		return $this->status;
	}
	
	public function getCode(){
		return $this->code;
	}
	
	public function getData(){
		return $this->data;
	}

}
