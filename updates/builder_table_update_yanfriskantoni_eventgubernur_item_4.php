<?php namespace YanFriskantoni\EventGubernur\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateYanfriskantoniEventgubernurItem4 extends Migration
{
    public function up()
    {
        Schema::table('yanfriskantoni_eventgubernur_item', function($table)
        {
            $table->string('pelaksana_kegiatan', 2024)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('yanfriskantoni_eventgubernur_item', function($table)
        {
            $table->dropColumn('pelaksana_kegiatan');
        });
    }
}
