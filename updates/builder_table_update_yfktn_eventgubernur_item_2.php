<?php namespace Yfktn\EventGubernur\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateYanfriskantoniEventgubernurItem2 extends Migration
{
    public function up()
    {
        Schema::table('yfktn_eventgubernur_item', function($table)
        {
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('yfktn_eventgubernur_item', function($table)
        {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
