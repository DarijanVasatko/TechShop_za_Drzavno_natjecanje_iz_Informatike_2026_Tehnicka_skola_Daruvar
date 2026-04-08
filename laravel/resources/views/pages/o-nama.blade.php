@extends('layouts.app')

@section('title', 'O nama — TechShop')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <h2 class="fw-bold text-center text-primary mb-4">
                <i class="bi bi-people-fill me-2"></i> O nama
            </h2>

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 p-md-5">

                    <p>
                        Mi smo, <strong>Darijan Vašatko</strong> i <strong>Dominik Dušek</strong>, učenici četvrtog razreda
                        Tehničke škole Daruvar, smjer Tehničar za računalstvo. Naše prijateljstvo započelo je prvog dana
                        srednje škole i od tada dijelimo zajedničku strast prema programiranju i razvoju web-aplikacija.
                    </p>

                    <p>
                        Ideja za izradu web-aplikacije internetske trgovine rodila se iz naših vlastitih iskustava s online
                        kupnjom. Kao mladi ljudi koji aktivno prate tehnologiju i često kupuju računalnu opremu putem
                        interneta, primijetili smo brojne nedostatke na postojećim platformama – od nepreglednih sučelja do
                        kompliciranih procesa naručivanja. Tako smo došli na ideju da mi to možemo napraviti bolje.
                    </p>

                    <p>
                        U trećem razredu, tijekom nastave predmeta Dizajn baza podataka i Skriptni jezici i web
                        programiranje, svoje prve web-aplikacije razvijali smo koristeći "čisti" PHP i MySQL. Brzo smo
                        shvatili koliko je takav pristup zahtjevan – svaki put smo morali ispočetka pisati kod za
                        autentifikaciju, sigurnosne provjere i povezivanje s bazom podataka. Projekti su postajali sve
                        složeniji, a količina ponavljajućeg koda sve veća. Naša nastavnica i mentorica, kojoj je bilo žao
                        gledati kako se mučimo, predložila nam je da krenemo malo unaprijed s gradivom i upoznamo Laravel
                        framework. Bili smo fascinirani elegancijom i brzinom implementacije. Nakon istraživanja, odlučili
                        smo se za Laravel zbog izvrsne dokumentacije, velike zajednice developera, ugrađenih alata za
                        sigurnost te Eloquent ORM-a koji je učinio rad s bazom podataka intuitivnijim. Laravel nam je
                        omogućio da se usredotočimo na razvoj funkcionalnosti koje će korisnicima pružiti izvrsno iskustvo
                        kupnje, umjesto da gubimo vrijeme na infrastrukturu koda.
                    </p>

                    <p>
                        Od samog početka posao je bio jasno podijeljen prema afinitetima i iskustvu svakog člana — Darijan
                        je preuzeo backend i logiku, Dominik frontend i vizualni dio, a mentorica je vodila code review i
                        arhitekturalne odluke. Suradnja je organizirana putem Git feature brancheva: svaka nova
                        funkcionalnost razvijana je na zasebnoj grani, a spajanje u glavnu granu odrađivali smo pažljivo
                        pregledavajući kod i rješavajući nastale konflikte.
                    </p>

                    <p class="mb-0">
                        Projekt je nastajao u dvije faze kroz školsku godinu 2025./2026. Prva faza trajala je od rujna
                        2025. do veljače 2026. i obuhvatila je temeljnu arhitekturu aplikacije. Ta je verzija odnesena na
                        Županijsko natjecanje. Druga, još aktivnija faza trajala je od veljače do travnja 2026. i donijela
                        je 68 novih commitova sa značajnim proširenjima: Google OAuth prijava, sustav promo kodova,
                        recenzije s moderacijom, AI preporuka konfiguracije, produkcijski deployment i GitHub Actions CI/CD
                        pipeline.
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
