<?php
declare(strict_types=1);

namespace App\Models;

use Nette;
use Nette\Database\Explorer;

class ProduktManager{
    public function __construct
    (
        private Explorer $explorer
    ){}


    public function vypisProdukty(string $kodKategorie): array|false{
        $vysledek = $this->explorer
        ->query('SELECT "Produkty"."Nazev", "Produkty"."Cena", "Produkty"."Popis", "Produkty"."HlavniObrazek", "Produkty"."UrlObchod", "Produkty"."UrlSrovnavac" 
        FROM "Produkty" 
        INNER JOIN "Produkty_ProduktyKategorie" ON "Produkty_ProduktyKategorie"."ProduktyId" = "Produkty"."ProduktyId"
        INNER JOIN "ProduktyKategorie" ON "Produkty_ProduktyKategorie"."ProduktyKategorieId" = "ProduktyKategorie"."ProduktyKategorieId" 
        WHERE "ProduktyKategorie"."Kod" = ?', $kodKategorie)
        ->fetchAll();
        return $vysledek;
    }
}
