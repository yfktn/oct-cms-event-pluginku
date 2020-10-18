<?php namespace Yfktn\EventGubernur;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            '\Yfktn\EventGubernur\Components\CalendarGub' => 'calendargub',
            '\Yfktn\EventGubernur\Components\DaftarKegiatan' => 'daftarkegiatan',
            '\Yfktn\EventGubernur\Components\DaftarKegiatanPada' => 'daftarkegiatanpada',
			'\Yfktn\EventGubernur\Components\DaftarKegiatanPadaBulan' => 'daftarKegiatanPadaBulan',
            '\Yfktn\EventGubernur\Components\Detail' => 'detail',
            '\Yfktn\EventGubernur\Components\SearchEvents' => 'pencarian'
        ];
    }

    public function registerSettings()
    {
    }
	
	public function registerMarkupTags() 
	{
		return [
			'filters' => [
				// tambah untuk ganti panggilan bulan ke bahasa indonesia
				'panggilbulan' => [$this, 'panggilBulan'],
			]
		];
	}
	
	public function panggilBulan($bulan)
	{
		$b = (int) $bulan;
		$d = "NOT-FOUND";
		switch($b)
		{
			case 1: $d = "Januari"; break;
			case 2: $d = "Februari"; break;
			case 3: $d = "Maret"; break;
			case 4: $d = "April"; break;
			case 5: $d = "Mei"; break;
			case 6: $d = "Juni"; break;
			case 7: $d = "Juli"; break;
			case 8: $d = "Agustus"; break;
			case 9: $d = "September"; break;
			case 10: $d = "Oktober"; break;
			case 11: $d = "Nopember"; break;
			case 12: $d = "Desember"; break;
		}
		return $d;
	}
		
}
