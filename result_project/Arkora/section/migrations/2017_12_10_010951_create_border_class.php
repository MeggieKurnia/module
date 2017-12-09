<?php
 	 use Illuminate\Database\Schema\Blueprint;
 	 use Illuminate\Database\Migrations\Migration;
 
 	 class CreateBorderClass extends Migration{
 
 	 public function up(){ 
 	 	 	 	 Schema::create('border', function(Blueprint $table){
 	 	 	 	 	$table->increments("id");
 	 	 	 	 	$table->tinyInteger("is_active")->default(1);
 	 	 	 	 	 $table->string('image', 100);
 	 	 	 	 	 $table->string('title', 100);
 	 	 	 	}); 
 	 	 	 	 Schema::create('border_i18n', function(Blueprint $table){
 	 	 	 	 	$table->increments("id");
 	 	 	 	 	$table->char("lang_code", 2);
 	 	 	 	 	$table->unsignedInteger("border_id");
 	 	 	 	 	 $table->string('title', 100)->nullable();
 	 	 	 	 	$table->datetime("create_on");
 	 	 	 	});
 	 }
 	  public function down(){
 	 	 	 Schema::drop('border');
 	 	 	 Schema::drop('border_i18n');
 	 }
 
}