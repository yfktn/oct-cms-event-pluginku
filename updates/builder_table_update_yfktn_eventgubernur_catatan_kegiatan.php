<?php namespace Yfktn\EventGubernur\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Rubah beberapa field menjadi nullable sebagaimana issu nomor 3
 * @package Yfktn\EventGubernur\Updates
 */
class BuilderTableUpdateYfktnEventgubernurCatatanKegiatan extends Migration
{
    public function up()
    {
        Schema::table('yfktn_eventgubernur_item', function($table)
        {
            $table->text('agenda')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('yfktn_eventgubernur_item', function ($table) 
        {
            $table->dropColumn('agenda');
        });
    }
}
