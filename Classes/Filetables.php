<?php

namespace Classes;

use Classes\File;

private $field = array();
class Filetables extends File{
	function __construct($data){
		$this->field = $data;
		$this->createDir('tables');
	}
}