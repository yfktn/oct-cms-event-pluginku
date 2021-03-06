<?php namespace Yfktn\EventGubernur\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateYanfriskantoniEventgubernurGambars extends Migration
{
    public function up()
    {
        Schema::create('yfktn_eventgubernur_gambars', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->smallInteger('tampil')->unsigned()->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('yfktn_eventgubernur_gambars');
    }
}
