<?php namespace Yfktn\EventGubernur\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateYanfriskantoniEventgubernurItem extends Migration
{
    public function up()
    {
        Schema::table('yfktn_eventgubernur_item', function($table)
        {
            $table->date('tgl_mulai');
            $table->date('tgl_selesai')->nullable();
            $table->time('jam_mulai');
            $table->time('jam_selesai')->nullable();
            $table->string('lokasi', 2024);
            $table->string('peserta', 2024);
            $table->string('pakaian', 2024);
            $table->increments('id')->unsigned(false)->change();
        });
    }
    
    public function down()
    {
        Schema::table('yfktn_eventgubernur_item', function($table)
        {
            $table->dropColumn('tgl_mulai');
            $table->dropColumn('tgl_selesai');
            $table->dropColumn('jam_mulai');
            $table->dropColumn('jam_selesai');
            $table->dropColumn('lokasi');
            $table->dropColumn('peserta');
            $table->dropColumn('pakaian');
            $table->increments('id')->unsigned()->change();
        });
    }
}
