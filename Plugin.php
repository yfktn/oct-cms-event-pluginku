<?php namespace Yfktn\EventGubernur;

use System\Classes\PluginBase;
use Yfktn\EventGubernur\Models\EGItem as EventGubModel;


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

	public function boot()
	{

		// lakukan penambahan untuk mendengarkan pencarian dokumen
		\Event::listen('offline.sitesearch.query', function ($query) {
			$provider = \Config::get('yfktn.tulisan::offlineSiteSearchResult.provider', 'Tulisan');
			// lakukan query ke model milik tulisan
			$items = EventGubModel::where('judul', 'like', "%{$query}%")
			->orWhere('penjelasan', 'like', "%{$query}%")
			->orWhere('lokasi', 'like', "%{$query}%")
			->get();
			// bangun hasilnya
			$results = $items->map(function ($item) use ($query) {
				// If the query is found in the title, set a relevance of 2
				$relevance = mb_stripos($item->judul, $query) !== false ? 2 : 1;
				$generatedUrl = \Config::get('yfktn.tulisan::offlineSiteSearchResult.url');
				if (\Config::get('yfktn.tulisan::offlineSiteSearchResult.paramDetail') == 'slug') {
					$generatedUrl .= '/' . $item->slug;
				} else {
					$generatedUrl .= '/' . $item->id;
				}
				return [
					'title' => $item->judul,
					'text' => $item->isi,
					'url' => $generatedUrl,
					// 'thumb'     => $item->images->first(), // Instance of System\Models\File
					'relevance' => $relevance, // higher relevance results in a higher
					// position in the results listing
					// 'meta' => 'data',       // optional, any other information you want
					// to associate with this result
					// 'model' => $item,       // optional, pass along the original model
				];
			});
			return [
				'provider' => $provider,
				'results' => $results
			];
		});

    
	}
		
}
