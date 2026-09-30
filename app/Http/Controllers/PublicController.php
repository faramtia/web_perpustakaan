<?php

namespace App\Http\Controllers;

class PublicController extends PublicController{
    public function home(){
        return view('public.home',[
            'totalKoleksi'   =>0,
            'totalJudul'     =>0,
            'koleksiPopuler' =>[],
            'koleksiBaru'    =>[],
            'artikel'        =>[],
            ]);
    }
}