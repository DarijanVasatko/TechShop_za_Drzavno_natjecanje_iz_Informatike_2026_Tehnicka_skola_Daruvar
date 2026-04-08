<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDV stopa
    |--------------------------------------------------------------------------
    | Stopa poreza na dodanu vrijednost kao decimalni broj (0.25 = 25%).
    | Sve cijene u shopu se prikazuju s PDV-om uključenim.
    */
    'pdv_stopa' => (float) env('SHOP_PDV_STOPA', 0.25),

    /*
    |--------------------------------------------------------------------------
    | ID kartičnog načina plaćanja
    |--------------------------------------------------------------------------
    | Primarni ključ retka u tablici nacin_placanja koji odgovara
    | kartičnom plaćanju — preusmjerava na FakePay gateway.
    */
    'card_payment_id' => (int) env('SHOP_CARD_PAYMENT_ID', 7),

    /*
    |--------------------------------------------------------------------------
    | Valuta
    |--------------------------------------------------------------------------
    */
    'valuta' => env('SHOP_VALUTA', 'EUR'),

];
