<?php
/* 
 * Di sini adalah setting untuk aplikasi ini
 */
return [
    // apakah timezone ini di convert? Dari default backend timezone ke front end timezone
    // sebagaimana setting di bawah ini?
    'convertToFrontEndTimeZone' => false,
    // default timezone untuk front end
    'defaultFrontEndTZ' => 'Asia/Jakarta',
    'defaultFrontEndTZLabel' => 'WIB',
     // merupakan setting untuk integrasi dengan plugin offline.sitesearch
    'offlineSiteSearchResult' => [
        // set nilai ini dengan url untuk detail sebuah record tulisan
        'url'         => 'agenda/detail',
        // ini dengan nilai param detailnya, pilihan yang ada adalah: 
        // id atau slug
        'paramDetail' => 'slug',
        // kategori ini nantinya ditampilkan di halaman hasil pencarian!
        'provider' => 'Agenda'
    ]
];