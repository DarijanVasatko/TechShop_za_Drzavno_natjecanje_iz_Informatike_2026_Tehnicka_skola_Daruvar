<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Polje :attribute mora biti prihvaćeno.',
    'accepted_if' => 'Polje :attribute mora biti prihvaćeno kada je :other :value.',
    'active_url' => 'Polje :attribute nije ispravan URL.',
    'after' => 'Polje :attribute mora biti datum nakon :date.',
    'after_or_equal' => 'Polje :attribute mora biti datum nakon ili na dan :date.',
    'alpha' => 'Polje :attribute smije sadržavati samo slova.',
    'alpha_dash' => 'Polje :attribute smije sadržavati samo slova, brojeve, crtice i podvlake.',
    'alpha_num' => 'Polje :attribute smije sadržavati samo slova i brojeve.',
    'any_of' => 'Polje :attribute je neispravno.',
    'array' => 'Polje :attribute mora biti niz.',
    'ascii' => 'Polje :attribute smije sadržavati samo jednobajtne alfanumeričke znakove i simbole.',
    'before' => 'Polje :attribute mora biti datum prije :date.',
    'before_or_equal' => 'Polje :attribute mora biti datum prije ili na dan :date.',
    'between' => [
        'array' => 'Polje :attribute mora imati između :min i :max stavki.',
        'file' => 'Polje :attribute mora biti između :min i :max kilobajta.',
        'numeric' => 'Polje :attribute mora biti između :min i :max.',
        'string' => 'Polje :attribute mora biti između :min i :max znakova.',
    ],
    'boolean' => 'Polje :attribute mora biti točno ili netočno.',
    'can' => 'Polje :attribute sadrži neovlaštenu vrijednost.',
    'confirmed' => 'Potvrda polja :attribute se ne podudara.',
    'contains' => 'Polju :attribute nedostaje obvezna vrijednost.',
    'current_password' => 'Lozinka je neispravna.',
    'date' => 'Polje :attribute nije ispravan datum.',
    'date_equals' => 'Polje :attribute mora biti datum jednak :date.',
    'date_format' => 'Polje :attribute se ne podudara s formatom :format.',
    'decimal' => 'Polje :attribute mora imati :decimal decimalnih mjesta.',
    'declined' => 'Polje :attribute mora biti odbijeno.',
    'declined_if' => 'Polje :attribute mora biti odbijeno kada je :other :value.',
    'different' => 'Polja :attribute i :other moraju biti različita.',
    'digits' => 'Polje :attribute mora imati :digits znamenki.',
    'digits_between' => 'Polje :attribute mora imati između :min i :max znamenki.',
    'dimensions' => 'Polje :attribute ima neispravne dimenzije slike.',
    'distinct' => 'Polje :attribute ima dupliciranu vrijednost.',
    'doesnt_contain' => 'Polje :attribute ne smije sadržavati ništa od sljedećeg: :values.',
    'doesnt_end_with' => 'Polje :attribute ne smije završavati s jednim od sljedećeg: :values.',
    'doesnt_start_with' => 'Polje :attribute ne smije počinjati s jednim od sljedećeg: :values.',
    'email' => 'Polje :attribute mora biti ispravna e-mail adresa.',
    'ends_with' => 'Polje :attribute mora završavati s jednim od sljedećeg: :values.',
    'enum' => 'Odabrano polje :attribute je neispravno.',
    'exists' => 'Odabrano polje :attribute je neispravno.',
    'extensions' => 'Polje :attribute mora imati jednu od sljedećih ekstenzija: :values.',
    'file' => 'Polje :attribute mora biti datoteka.',
    'filled' => 'Polje :attribute mora imati vrijednost.',
    'gt' => [
        'array' => 'Polje :attribute mora imati više od :value stavki.',
        'file' => 'Polje :attribute mora biti veće od :value kilobajta.',
        'numeric' => 'Polje :attribute mora biti veće od :value.',
        'string' => 'Polje :attribute mora biti dulje od :value znakova.',
    ],
    'gte' => [
        'array' => 'Polje :attribute mora imati :value ili više stavki.',
        'file' => 'Polje :attribute mora biti veće ili jednako :value kilobajta.',
        'numeric' => 'Polje :attribute mora biti veće ili jednako :value.',
        'string' => 'Polje :attribute mora biti dulje ili jednako :value znakova.',
    ],
    'hex_color' => 'Polje :attribute mora biti ispravna heksadecimalna boja.',
    'image' => 'Polje :attribute mora biti slika.',
    'in' => 'Odabrano polje :attribute je neispravno.',
    'in_array' => 'Polje :attribute mora postojati u :other.',
    'in_array_keys' => 'Polje :attribute mora sadržavati barem jedan od sljedećih ključeva: :values.',
    'integer' => 'Polje :attribute mora biti cijeli broj.',
    'ip' => 'Polje :attribute mora biti ispravna IP adresa.',
    'ipv4' => 'Polje :attribute mora biti ispravna IPv4 adresa.',
    'ipv6' => 'Polje :attribute mora biti ispravna IPv6 adresa.',
    'json' => 'Polje :attribute mora biti ispravan JSON niz.',
    'list' => 'Polje :attribute mora biti lista.',
    'lowercase' => 'Polje :attribute mora sadržavati samo mala slova.',
    'lt' => [
        'array' => 'Polje :attribute mora imati manje od :value stavki.',
        'file' => 'Polje :attribute mora biti manje od :value kilobajta.',
        'numeric' => 'Polje :attribute mora biti manje od :value.',
        'string' => 'Polje :attribute mora biti kraće od :value znakova.',
    ],
    'lte' => [
        'array' => 'Polje :attribute ne smije imati više od :value stavki.',
        'file' => 'Polje :attribute mora biti manje ili jednako :value kilobajta.',
        'numeric' => 'Polje :attribute mora biti manje ili jednako :value.',
        'string' => 'Polje :attribute mora biti kraće ili jednako :value znakova.',
    ],
    'mac_address' => 'Polje :attribute mora biti ispravna MAC adresa.',
    'max' => [
        'array' => 'Polje :attribute ne smije imati više od :max stavki.',
        'file' => 'Polje :attribute ne smije biti veće od :max kilobajta.',
        'numeric' => 'Polje :attribute ne smije biti veće od :max.',
        'string' => 'Polje :attribute ne smije biti dulje od :max znakova.',
    ],
    'max_digits' => 'Polje :attribute ne smije imati više od :max znamenki.',
    'mimes' => 'Polje :attribute mora biti datoteka tipa: :values.',
    'mimetypes' => 'Polje :attribute mora biti datoteka tipa: :values.',
    'min' => [
        'array' => 'Polje :attribute mora imati barem :min stavki.',
        'file' => 'Polje :attribute mora biti barem :min kilobajta.',
        'numeric' => 'Polje :attribute mora biti barem :min.',
        'string' => 'Polje :attribute mora imati barem :min znakova.',
    ],
    'min_digits' => 'Polje :attribute mora imati barem :min znamenki.',
    'missing' => 'Polje :attribute mora nedostajati.',
    'missing_if' => 'Polje :attribute mora nedostajati kada je :other :value.',
    'missing_unless' => 'Polje :attribute mora nedostajati osim ako je :other :value.',
    'missing_with' => 'Polje :attribute mora nedostajati kada je :values prisutan.',
    'missing_with_all' => 'Polje :attribute mora nedostajati kada su :values prisutni.',
    'multiple_of' => 'Polje :attribute mora biti višekratnik od :value.',
    'not_in' => 'Odabrano polje :attribute je neispravno.',
    'not_regex' => 'Format polja :attribute je neispravan.',
    'numeric' => 'Polje :attribute mora biti broj.',
    'password' => [
        'letters' => 'Polje :attribute mora sadržavati barem jedno slovo.',
        'mixed' => 'Polje :attribute mora sadržavati barem jedno veliko i jedno malo slovo.',
        'numbers' => 'Polje :attribute mora sadržavati barem jedan broj.',
        'symbols' => 'Polje :attribute mora sadržavati barem jedan simbol.',
        'uncompromised' => 'Navedeni :attribute se pojavio u curenju podataka. Molimo odaberite drugi :attribute.',
    ],
    'present' => 'Polje :attribute mora biti prisutno.',
    'present_if' => 'Polje :attribute mora biti prisutno kada je :other :value.',
    'present_unless' => 'Polje :attribute mora biti prisutno osim ako je :other :value.',
    'present_with' => 'Polje :attribute mora biti prisutno kada je :values prisutan.',
    'present_with_all' => 'Polje :attribute mora biti prisutno kada su :values prisutni.',
    'prohibited' => 'Polje :attribute je zabranjeno.',
    'prohibited_if' => 'Polje :attribute je zabranjeno kada je :other :value.',
    'prohibited_if_accepted' => 'Polje :attribute je zabranjeno kada je :other prihvaćeno.',
    'prohibited_if_declined' => 'Polje :attribute je zabranjeno kada je :other odbijeno.',
    'prohibited_unless' => 'Polje :attribute je zabranjeno osim ako je :other u :values.',
    'prohibits' => 'Polje :attribute zabranjuje prisutnost polja :other.',
    'regex' => 'Format polja :attribute je neispravan.',
    'required' => 'Polje :attribute je obvezno.',
    'required_array_keys' => 'Polje :attribute mora sadržavati unose za: :values.',
    'required_if' => 'Polje :attribute je obvezno kada je :other :value.',
    'required_if_accepted' => 'Polje :attribute je obvezno kada je :other prihvaćeno.',
    'required_if_declined' => 'Polje :attribute je obvezno kada je :other odbijeno.',
    'required_unless' => 'Polje :attribute je obvezno osim ako je :other u :values.',
    'required_with' => 'Polje :attribute je obvezno kada je :values prisutan.',
    'required_with_all' => 'Polje :attribute je obvezno kada su :values prisutni.',
    'required_without' => 'Polje :attribute je obvezno kada :values nije prisutan.',
    'required_without_all' => 'Polje :attribute je obvezno kada nijedan od :values nije prisutan.',
    'same' => 'Polja :attribute i :other se moraju podudarati.',
    'size' => [
        'array' => 'Polje :attribute mora sadržavati :size stavki.',
        'file' => 'Polje :attribute mora biti :size kilobajta.',
        'numeric' => 'Polje :attribute mora biti :size.',
        'string' => 'Polje :attribute mora biti :size znakova.',
    ],
    'starts_with' => 'Polje :attribute mora počinjati s jednim od sljedećeg: :values.',
    'string' => 'Polje :attribute mora biti tekst.',
    'timezone' => 'Polje :attribute mora biti ispravna vremenska zona.',
    'unique' => 'Vrijednost polja :attribute već postoji.',
    'uploaded' => 'Prijenos polja :attribute nije uspio.',
    'uppercase' => 'Polje :attribute mora sadržavati samo velika slova.',
    'url' => 'Polje :attribute mora biti ispravan URL.',
    'ulid' => 'Polje :attribute mora biti ispravan ULID.',
    'uuid' => 'Polje :attribute mora biti ispravan UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'vlastita-poruka',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];