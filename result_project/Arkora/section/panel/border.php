<?php
 return[
 	 	 	 'type'=>'listing',
 	 	 	 'listing'=>[
 	 	 	 	 'headers'=>[
 	 	 	 	 	 'columns'=>[
 	 	 	 	 	 'id' 
 	 	 	 	 	 ] 
 	 	 	 	 ] 
 	 	 	 ], 
 	 	 	 'actions' => [
 	 	 	 	 'create' => [
 	 	 	 	 	 'form' => [
 	 	 	 	 	 	 'attributes' => ['enctype' => 'multipart/form-data'],
 	 	 	 	 	 	 'section.border.image'=>['file' =>['type'=>'image','mimes'=>['jpg','jpeg','png'],'max'=>1024, 'upload-dir' => 'site/uploads/slides'],],
 	 	 	 	 	 	 'section.border.title'
 	 	 	 	 	 	],
 	 	 	 	],
 	 	 	 	 'edit' => [
 	 	 	 	 	 'form' => [
 	 	 	 	 	 	 'attributes' => ['enctype' => 'multipart/form-data'],
 	 	 	 	 	 	 'section.border.image'=>['file' =>['type'=>'image','mimes'=>['jpg','jpeg','png'],'max'=>1024, 'upload-dir' => 'site/uploads/slides','preview'=>true],],
 	 	 	 	 	 	 'section.border.title'
 	 	 	 	 ]
 	 	 	 ],'delete','activeness'
 	 	]
 ];