<?php
declare(strict_types=1);

namespace App\Models;

use Nette;
use Nette\Database\Explorer;
use Nette\PhpGenerator\Property;

class ProduktManager{
    public function __construct
    (
        private Explorer $explorer
    ){}

    //kategorie VybavaProMiminka ma sloupecek KategorieId = 1
    public function vypisProduktyVybavaProMiminka(): array|false{
        $vysledek = $this->explorer
        ->table('Produkty_ProduktyKategorie')
        ->select('Produkty.Nazev, Produkty.HlavniObrazek, Produkty.Popis, Produkty.Cena, Produkty.UrlSrovnavac, Produkty.UrlObchod')
        ->where('Produkty_ProduktyKategorie.ProduktyKategorieId', 1)
        ->joinWhere('Produkty', 'Produkty.ProduktyId = Produkty_ProduktyKategorie.ProduktyId')
        ->fetchAll();
        return $vysledek;
    }
}