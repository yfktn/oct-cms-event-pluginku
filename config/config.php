<?php
/* 
 * Di sini adalah setting untuk aplikasi ini
 */
return [
    // default timezone untuk front end
    'defaultFrontEndTZ' => 'Asia/Jakarta',
    'defaultFrontEndTZLabel' => 'WIB',
     // merupakan setting untuk integrasi dengan plugin offline.sitesearch
    'offlineSiteSearchResult' => [
        // set nilai ini dengan url untuk detail sebuah record tulisan
        'url'         => 'agenda/baca',
        // ini dengan nilai param detailnya, pilihan yang ada adalah: 
        // id atau slug
        'paramDetail' => 'slug',
        // kategori ini nantinya ditampilkan di halaman hasil pencarian!
        'provider' => 'Agenda'
    ]
];