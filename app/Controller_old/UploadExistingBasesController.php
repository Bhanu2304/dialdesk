<?php
class UploadExistingBasesController extends AppController{
	public $helpers = array('Html', 'Form','Js');
	public $components = array('RequestHandler','Session');
	public $uses=array('ClientCategory','FieldCreation','FieldValue','UploadExistingBase');
	
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(
			'index',
			'add','download');
    }
	
	public function index() {
		$this->layout='user';		
		$ClientId = $this->Session->read('companyid');
	}
	
	public function download() {
		$this->layout='ajax';
		$ClientId = $this->Session->read('companyid');
		
		$this->set('Data',$this->ClientCategory->query("SELECT CONCAT(GROUP_CONCAT(DISTINCT(CONCAT('Category',Label))),',',GROUP_CONCAT(distinct(FieldName)),',',concat('MSISDN',',','CallDate',',','CloseLoopCate1',',','CloseLoopCate2',',','CloseLoopingDate')) CatField FROM ecr_master t1 JOIN field_master t2 ON t1.Client=t2.ClientId WHERE t1.Client='$ClientId' AND t2.FieldStatus IS NULL"));
	
	}
	
        /*
	public function add() {
		$this->layout='user';
		$ClientId = $this->Session->read('companyid');
		$this->set('Filename',$this->request->data);
                     
               
		$FileTye = $this->request->data['UploadExistingBases']['File']['type'];
		$info = explode(".",$this->request->data['UploadExistingBases']['File']['name']);
               
		if(($FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv")
		{
		$Datax = $this->ClientCategory->query("SELECT CONCAT(GROUP_CONCAT(DISTINCT(CONCAT('Category',Label))),',',GROUP_CONCAT(DISTINCT(CONCAT('field',fieldNumber))),',',CONCAT('MSISDN',',','CallDate',',','CloseLoopCate1',',','CloseLoopCate2',',','CloseLoopingDate')) CatField FROM ecr_master t1 JOIN field_master t2 ON t1.Client=t2.ClientId WHERE t1.Client='$ClientId' AND t2.FieldStatus IS NULL")	;
		
		$CatField = $Datax[0][0]['CatField'];	
		
               
                
                
		$FilePath = $this->request->data['UploadExistingBases']['File']['tmp_name'];
                
                
		 //print_r("LOAD DATA LOCAL INFILE '$FilePath' INTO TABLE tmp_call_master FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES(MSISDN,$CatField,Field1,Field2,Field3,Field4,Field5,Field6,Field7,Field8,Field9,Field10,Field11,Field12,Field13,Field14,Field15,Field16,Field17,Field18,Field19,Field20)"); exit;
                  
                $Res = $this->UploadExistingBase->query("LOAD DATA LOCAL INFILE '$FilePath' INTO TABLE tmp_call_master FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES($CatField)");
		
		$this->UploadExistingBase->query("insert into call_master( ClientId,SrNo,$CatField)  select '$ClientId',getSrno('$ClientId'),$CatField from tmp_call_master");
		
                $this->Session->setFlash('File uploaded Success!');
                
		//$Res = $this->UploadExistingBase->query("truncate table tmp_call_master");
		}
		else
		{
			$this->Session->setFlash('File Format not valid!=>'.$FileTye);
		}
		 $this->redirect(array('action'=>'index'));
	}
        
        */
        
        
        
        public function add() {
            $this->layout='user';
            $ClientId = $this->Session->read('companyid');
            $this->set('Filename',$this->request->data);

            $csv_file = $this->request->data['UploadExistingBases']['File']['tmp_name'];
            $FileTye = $this->request->data['UploadExistingBases']['File']['type'];
            $info = explode(".",$this->request->data['UploadExistingBases']['File']['name']);
               
            if(($FileTye=='application/vnd.ms-excel' || $FileTye=='application/octet-stream') && strtolower(end($info)) == "csv"){
           
                $Datax = $this->ClientCategory->query("SELECT CONCAT(GROUP_CONCAT(DISTINCT(CONCAT('Category',Label))),',',GROUP_CONCAT(DISTINCT(CONCAT('field',fieldNumber))),',',CONCAT('MSISDN',',','CallDate',',','CloseLoopCate1',',','CloseLoopCate2',',','CloseLoopingDate')) CatField FROM ecr_master t1 JOIN field_master t2 ON t1.Client=t2.ClientId WHERE t1.Client='$ClientId' AND t2.FieldStatus IS NULL ");
                $CatField=$Datax[0][0]['CatField'];
                $exp=  explode(',', $CatField);
               
                $datekey=0;
                foreach($exp as $key=>$val){
                    if($val =="CallDate"){
                    $datekey=$key+1;
                    }
                }
                
                
                if (($handle = fopen($csv_file, "r")) !== FALSE) {
                    $filedata = fgetcsv($handle, 1000, ",");
                                    
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                        if(count($data) == count($exp)){                         
                            $i=1;
                            $qryVal="";
                            foreach($data as $val){
                                if(count($data) !=$i){$cama=",";}else{$cama="";}
                                
                                if($i ==$datekey){ 
                                    $CallDate=$date = date('Y-m-d H:i:s');
                                    $qryVal.="'$CallDate'$cama";
                                }
                                else{
                                  $qryVal.="'".addslashes($val)."'$cama";  
                                }
                                                            
                                $i++;
                            }
                           
                            $this->UploadExistingBase->query("insert into call_master( CallType,ClientId,SrNo,$CatField)  values('Upload','$ClientId',getSrno('$ClientId'),$qryVal)");
                        }
                    }
                    $this->Session->setFlash('<span style="color:green;">CSV data upload successfully.</span>');
                    $this->redirect(array('action'=>'index')); 
		}
            }
            else{
                $this->Session->setFlash('File Format not valid!=>'.$FileTye);
                $this->redirect(array('action'=>'index'));
            }
            
	}
        
        
        
}
?>