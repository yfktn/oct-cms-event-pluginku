<?php namespace Yfktn\EventGubernur\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateYanfriskantoniEventgubernurItem extends Migration
{
    public function up()
    {
        Schema::create('yfktn_eventgubernur_item', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('judul', 2024);
            $table->text('penjelasan')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('yfktn_eventgubernur_item');
    }
}
