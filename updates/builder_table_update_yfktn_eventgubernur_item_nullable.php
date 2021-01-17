<?php namespace Yfktn\EventGubernur\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Rubah beberapa field menjadi nullable sebagaimana issu nomor 3
 * @package Yfktn\EventGubernur\Updates
 */
class BuilderTableUpdateYfktnEventgubernurItemNullable extends Migration
{
    public function up()
    {
        Schema::table('yfktn_eventgubernur_item', function($table)
        {
            $table->string('lokasi')->nullable()->change();
            $table->string('peserta')->nullable()->change();
            $table->string('pakaian')->nullable()->change();
        });
    }
    
    public function down()
    {
        // nothing to do!
    }
}
