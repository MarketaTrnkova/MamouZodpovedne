<?php
declare(strict_types=1);

namespace App\Models;

use Nette;
use Nette\Database\Explorer;
use Nette\Application\UI\Form;
use Nette\Http\Session;
use Nette\Utils\Strings;


class DiskuseManager{
    public ?string $aktualniVybranaKategorie = null;
    public function __construct(
        private Explorer $explorer,
        protected Session $session
    ){

    }
    public function vytvoritDiskusiSuccess(Form $form, \stdClass $dataFomulare){
        try{
            $vybranaKategorie=$dataFomulare->kategorie;
            $sessionSection = $this->session->getSection('Uzivatel');
            if (isset($sessionSection->jePrihlasen)){
                $vlozenyRadek = $this->explorer->table('Diskuse')->insert([
                    'UzivateleId'=> $sessionSection->uzivatelId,
                    'Nadpis'=> $dataFomulare->nadpisDiskuse,
                    'Obsah'=> $dataFomulare->textDiskuse,
                    'Url' => Strings::webalize  ($dataFomulare->nadpisDiskuse),
                    'DiskuseKategorieId'=> $vybranaKategorie
                ]);  
                if ($vlozenyRadek instanceof \Nette\Database\Table\ActiveRow){
                    return $form;
                }
            }else{
                $form->addError('Pro vytvoření diskuse se musíte přihlásit.');
            }
            
        } catch(\Exception $e){
            $form->addError($e . 'Nevalidní data.');
        }
    }
    public function vytvoritKomentarSucces($form,$dataFomulare){
        $sessionSection = $this->session->getSection('Uzivatel');
        $this->explorer->table('Komentare')
        ->insert([
                'UzivateleId' => $sessionSection->uzivatelId,
                'DiskuseId' => $dataFomulare->diskuseId,
                'Obsah'=> $dataFomulare->textKomentare
        ]);
        return true;
    }
    public function vytvorReaciNaKomentarSucces($form,$dataFomulare){
        $sessionSection = $this->session->getSection('Uzivatel');
        $this->explorer->table('Komentare')
        ->insert([
                'UzivateleId' => $sessionSection->uzivatelId,
                'HlavniKomentarId' => $dataFomulare->hlavniKomentarId,
                'Obsah'=> $dataFomulare->textKomentare
        ]);
        return true;
    }
    public function vratKategorie():array{
        $kategorie = $this->explorer->table('DiskuseKategorie')->fetchPairs('DiskuseKategorieId', 'Nazev');
        return $kategorie;
    }
    public function vratKategoriePole():array{
        return $this->explorer->table('DiskuseKategorie')->fetchAll();
    }


    public function vyberKategoriiSucces(Form $form, \stdClass $dataFomulare){
        $sessionSection = $this->session->getSection('filtrKategorie');
        $sessionSection->aktualniKategorie = $dataFomulare->vybranaKategorie;
        return true;
    }
    public function vratDiskuseZDb($aktualniKategorie = null){
        try{
            $sqlDotaz='SELECT "Diskuse".*, "Uzivatele"."Prezdivka" AS "Prezdivka", "DiskuseKategorie"."Nazev" AS "NazevKategorie", "DiskuseKategorie"."Kod", (select count(*) from "Komentare" where "Komentare"."DiskuseId"="Diskuse"."DiskuseId" ) AS "PocetKomentaru"
            FROM "Diskuse"
                INNER JOIN "Uzivatele" ON "Diskuse"."UzivateleId" = "Uzivatele"."UzivateleId"
                INNER JOIN "DiskuseKategorie" ON "DiskuseKategorie"."DiskuseKategorieId" = "Diskuse"."DiskuseKategorieId" ';

            //pokud je zadana kategorie, pridam do sql dotazu podminku pro vypsani diskusi s konretni kategorii 
            if($aktualniKategorie != null){
                $sqlDotaz .= 'WHERE "DiskuseKategorie"."Kod" = ? ORDER BY ("Diskuse"."Cas") DESC';
                $vsechnyDiskuse = $this->explorer->query($sqlDotaz, $aktualniKategorie)
                ->fetchAll();
                return $vsechnyDiskuse;
            }else{
                $sqlDotaz .='ORDER BY ("Diskuse"."Cas") DESC';
                $vsechnyDiskuse = $this->explorer
                ->query($sqlDotaz)
                ->fetchAll();
                return $vsechnyDiskuse;
            }
        }catch(\Exception $e){
            return $e;
        }
    }

    public function vratDetailDiskuse(string $title){
        $diskuseDetail = $this->explorer
        ->query('SELECT "Diskuse".*, "Uzivatele"."Prezdivka", "DiskuseKategorie"."Nazev" AS "NazevKategorie" 
            FROM "Diskuse"
            INNER JOIN "Uzivatele" ON "Diskuse"."UzivateleId" = "Uzivatele"."UzivateleId"
            INNER JOIN "DiskuseKategorie" ON "DiskuseKategorie"."DiskuseKategorieId" = "Diskuse"."DiskuseKategorieId"
            WHERE "Diskuse"."Url" = ?
            ORDER BY "Diskuse"."Cas" DESC
        ', $title)
        ->fetch();
        return $diskuseDetail;
    }
    public function vratKomentareKDiskusi(string $title){
        $komentare = $this->explorer
        ->query('SELECT "Komentare"."Obsah" AS "ObsahKomentare", "Komentare"."KomentareId", "Uzivatele"."Prezdivka" AS "AutorKomentare", "DiskuseKategorie"."Nazev" AS "NazevKategorie", "Komentare"."Cas" AS "CasKomentare", (SELECT COUNT(*) AS "PocetVedlejsichKomentaru" FROM "Komentare" AS "k" WHERE "k"."HlavniKomentarId" = "Komentare"."KomentareId" )
        FROM "Diskuse"
        INNER JOIN "DiskuseKategorie" ON "DiskuseKategorie"."DiskuseKategorieId" = "Diskuse"."DiskuseKategorieId"
        INNER JOIN "Komentare" ON "Komentare"."DiskuseId" = "Diskuse"."DiskuseId"
		INNER JOIN "Uzivatele" ON "Komentare"."UzivateleId" = "Uzivatele"."UzivateleId"
        WHERE "Diskuse"."Url" = ?
        ORDER BY "Komentare"."Cas" DESC
    ', $title)
        ->fetchAll();
        return $komentare;
    }

    public function vratVedlejsiKomentare(){
        $komentare = $this->explorer->table('Komentare')
        ->select('Komentare.KomentareId, Komentare.HlavniKomentarId, Uzivatele.Prezdivka AS Prezdivka, Komentare.Cas, Komentare.Obsah')
        ->where('Komentare.UzivateleId = Uzivatele.UzivateleId' )
        ->where('DiskuseId IS NULL')
        ->fetchAll();
        return $komentare;
    }
}