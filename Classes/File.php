<?php

namespace Classes;

class File{
	private $dirProject = "result_project/";
	private $project = "";
	private $section = "";
	private $table = "";
	private $field = array();
	private $type = array();
	private $multi = array();

	function __construct(array $post){
		session_start();
		$this->project = $post['project'];
		$this->section = strtolower($post['section']);
		$this->table = $post['table'];
		$this->field = $post['field'];
		$this->type = $post['type'];
		$this->multi = $post['multilang'];
		if(isset($post['project']) && isset($post['section'])){
			$this->createProject();
		}
	}

	protected function createProject(){
		$_SESSION['project'] = $this->project;
		$_SESSION['section'] = $this->section;
		$dir = $this->dirProject.$this->project;
		if(!is_dir($dir)){
			mkdir($dir);
			chmod($dir, 0777);
		}
		if(!is_dir($dir."/".$this->section)){
		   mkdir($dir."/".$this->section);
		   chmod($dir."/".$this->section, 0777);
		}
		$this->dirProject = $dir."/".$this->section;
		$this->createDir('panel');
		$this->createDir('tables');
		$this->createDir('migrations');
	} 	

	protected function createDir($param){
		if(is_dir($this->dirProject)){
			mkdir($this->dirProject."/".$param);
			if(chmod($this->dirProject."/".$param,0777)){
				if($param == 'migrations')
					$sec = date('Y')."_".date('m')."_".date('d')."_".date(His)."_create_".$this->table."_class";
				else
					$sec = $this->table;
				if(!file_exists($this->dirProject."/".$param."/".$sec.".php")){
					if($param == "panel")
						$php_txt = $this->generateFilePanel();
					else if($param == "tables")
						$php_txt = $this->generateFileTable();
					else
						$php_txt = $this->generateFileMigration();
					
					$file = fopen($this->dirProject."/".$param."/".$sec.".php", "w") or die('Error Generate Panel File');
					fwrite($file,$php_txt);
					fclose($file);
					chmod($this->dirProject."/".$param."/".$sec.".php", 0777);
				}
			}
		}
	}

	private function generateFilePanel(){
		$php_txt = "<?php";
		$php_txt.="\n return[";
			$php_txt.="\n \t \t \t 'type'=>'listing',";
			$php_txt.="\n \t \t \t 'listing'=>[";
			$php_txt.="\n \t \t \t \t 'headers'=>[";
			$php_txt.="\n \t \t \t \t \t 'columns'=>[";
			$php_txt.="\n \t \t \t \t \t 'id' ";
			$php_txt.="\n \t \t \t \t \t ] ";
			$php_txt.="\n \t \t \t \t ] ";
			$php_txt.="\n \t \t \t ], ";

			$php_txt.="\n \t \t \t 'actions' => [";
			$php_txt.="\n \t \t \t \t 'create' => [";
			$php_txt.="\n \t \t \t \t \t 'form' => [";
			$php_txt.="\n \t \t \t \t \t \t 'attributes' => ['enctype' => 'multipart/form-data'],";

			$php_txt.=$this->loopData('insert');
			
			$php_txt.="\n \t \t \t \t \t \t],";
			$php_txt.="\n \t \t \t \t],";
			$php_txt.="\n \t \t \t \t 'edit' => [";
			$php_txt.="\n \t \t \t \t \t 'form' => [";
			$php_txt.="\n \t \t \t \t \t \t 'attributes' => ['enctype' => 'multipart/form-data'],";

			$php_txt.=$this->loopData('edit');

			$php_txt.="\n \t \t \t \t ]";
			$php_txt.="\n \t \t \t ],'delete','activeness'";
			$php_txt.="\n \t \t]";
			
		$php_txt.="\n ];";
		return $php_txt;
	}

	private function loopData($param){
		$cfield = count($this->field);
		$ctype = count($this->type);
		$php_txt = "";
		if($cfield == $ctype){
			for($i=0; $i < $cfield; $i++){
				$php_txt.="\n \t \t \t \t \t \t '".$this->section.".".$this->table.".".$this->field[$i]."'";
				if($this->type[$i] != "text"){
					if($this->type[$i] == "textarea")
						$php_txt.="=>['type'=>'textarea']";
					if($this->type[$i] == "ckeditor")
						$php_txt.="=>['type'=>'textarea','class'=>'ckeditor']";
					if($this->type[$i] == "file"){
						$php_txt.="=>['file' =>['type'=>'image','mimes'=>['jpg','jpeg','png'],'max'=>1024, 'upload-dir' => 'site/uploads/slides'";
						if($param == "edit")
							$php_txt.=",'preview'=>true";
						$php_txt.="],";
						$php_txt.="]";
					}
				}
				if($i != ($ctype - 1) )
						$php_txt.=",";
			}	
		}
		return $php_txt;
	}

	private function generateFileTable(){
		$php_txt = "<?php";
		$php_txt.="\n return[";
		$cfield = count($this->field);
			$php_txt.="\n \t \t \t \t ['master' => 'id'],";
			for($i = 0; $i < $cfield; $i++){
				$php_txt.="\n \t \t \t \t ['master' =>";
				if($this->type[$i] == "textarea" || $this->type[$i] == "ckeditor"){
					if($this->type[$i] == "textarea")
						$php_txt.="'shortIntro'";
					else
						$php_txt.="'description'";
				}else{
					$php_txt.="'title'";
				}
				$php_txt.=", 'name'=>'".$this->field[$i]."'";
				if($this->multi[$i] == "true"){
					$php_txt.=", 'multilingual' => true";
				}
				$php_txt.="],";
			}
			$php_txt.="\n \t \t \t \t ['master' => 'falseBool', 'name'=>'is_active']";
		$php_txt.="\n ];";
		return $php_txt;
	}

	private function generateFileMigration(){
		$xfield = count($this->field);
		$php_txt="<?php";
		$php_txt.="\n \t use Illuminate\Database\Schema\Blueprint;";
		$php_txt.="\n \t use Illuminate\Database\Migrations\Migration;";
		$php_txt.="\n \n \t class Create".ucfirst($this->table)."Class extends Migration{";
			$php_txt.="\n \n \t public function up(){";
				$tab = '$table';
				$php_txt.=" \n \t \t \t \t Schema::create('".$this->table."', function(Blueprint ".$tab."){";
					$id = '$table->increments("id");';
					$is_active = '$table->tinyInteger("is_active")->default(1);';
					$php_txt.="\n \t \t \t \t \t".$id;
					$php_txt.="\n \t \t \t \t \t".$is_active;
					$multi = false;
					for($i=0; $i < $xfield; $i++){
						if($this->multi[$i] == "true")
							$multi = true;
						$type = "";
						if($this->type[$i] == "text" || $this->type[$i] == "file")
							$type = $tab."->string('".$this->field[$i]."', 100);";
						else if($this->type[$i] == "textarea")
							$type = $tab."->string('".$this->field[$i]."', 2000);";
						else
							$type = $tab."->text('".$this->field[$i]."');";
						$php_txt.="\n \t \t \t \t \t ".$type;
					}
				$php_txt.="\n \t \t \t \t});"; //end schema
				if($multi)
					$php_txt.=$this->createSchemaLang();
			$php_txt.="\n \t }"; //end function up
			$php_txt.="\n \t  public function down(){";
				$php_txt.="\n \t \t \t Schema::drop('".$this->table."');";
				if($multi)
					$php_txt.="\n \t \t \t Schema::drop('".$this->table."_i18n');";
			$php_txt.="\n \t }"; //end function down
		$php_txt.="\n \n}"; //End Class
		return $php_txt;
	}

	private function createSchemaLang(){
		$xfield = count($this->field);
		$php_txt="";
		$tab = '$table';
		$php_txt.=" \n \t \t \t \t Schema::create('".$this->table."_i18n', function(Blueprint ".$tab."){";
			$id = '$table->increments("id");';
			$lang_code = '$table->char("lang_code", 2);';
			$tab_lang = '$table->unsignedInteger("'.$this->table.'_id");';
			$php_txt.="\n \t \t \t \t \t".$id;
			$php_txt.="\n \t \t \t \t \t".$lang_code;
			$php_txt.="\n \t \t \t \t \t".$tab_lang;
			for($i=0; $i < $xfield; $i++){
				if($this->multi[$i] == "true"){
					$type = "";
					if($this->type[$i] == "text" || $this->type[$i] == "file")
						$type = $tab."->string('".$this->field[$i]."', 100)->nullable();";
					else if($this->type[$i] == "textarea")
						$type = $tab."->string('".$this->field[$i]."', 2000)->nullable();";
					else
						$type = $tab."->text('".$this->field[$i]."')->nullable();";
					$php_txt.="\n \t \t \t \t \t ".$type;
				}
			}
			$create_on ='$table->datetime("create_on");';
			$php_txt.="\n \t \t \t \t \t".$create_on;
		$php_txt.="\n \t \t \t \t});"; //end schema
		return $php_txt;
	}
}