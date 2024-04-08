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
    public function vytvoritDiskusiSuccess($form, $dataFomulare){
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
                    return true;
                }
            }else{
                $form->addError('Pro vytvoření diskuse se musíte přihlásit.');
                return false;
            } 
        }catch(\Exception $e){
            $form->addError($e . 'Nevalidní data.');
            return false;
        }
    }
    public function vytvoritKomentarSucces($form, $dataFomulare){
        $sessionSection = $this->session->getSection('Uzivatel');
        try{
            $this->explorer->table('Komentare')
            ->insert([
                    'UzivateleId' => $sessionSection->uzivatelId,
                    'DiskuseId' => $dataFomulare->diskuseId,
                    'Obsah'=> $dataFomulare->textKomentare
            ]);
            return true;
        }catch(\Exception $e){
            $form->addError($e . 'Nevalidní data.');
            return false;
        }
 
    }
    public function vytvorReaciNaKomentarSucces($form,$dataFomulare){
        $sessionSection = $this->session->getSection('Uzivatel');
        try{
            $this->explorer->table('Komentare')
            ->insert([
                    'UzivateleId' => $sessionSection->uzivatelId,
                    'HlavniKomentarId' => $dataFomulare->hlavniKomentarId,
                    'Obsah'=> $dataFomulare->textKomentare
            ]);
            return true;
        }catch(\Exception $e){
            $form->addError($e . 'Nepodařilo se vložit reakci');
            return false;
        }
    }

    public function vratKategorie():array{
        $kategorie = $this->explorer->table('DiskuseKategorie')->fetchPairs('DiskuseKategorieId', 'Nazev');
        return $kategorie;
    }
    public function vratKategoriePole():array{
        return $this->explorer->table('DiskuseKategorie')->fetchAll();
    }

    public function vyberKategoriiSucces($form, $dataFomulare){
        $sessionSection = $this->session->getSection('filtrKategorie');
        $sessionSection->aktualniKategorie = $dataFomulare->vybranaKategorie;
        return true;
    }
    public function vratDiskuseZDb($aktualniKategorie = null){
        try{
            $diskuseZDb = $this->explorer->query('SELECT * FROM vratDiskuseZDbFunkce(?)',$aktualniKategorie)
            ->fetchAll();
                return $diskuseZDb;
            }catch(\Exception $e){
                return $e;
            }
    }

    public function vratDetailDiskuse(string $title){
        $diskuseDetail = $this->explorer
        ->query('SELECT * FROM vratDetailDiskuseFunkce(?)
        ', $title)
        ->fetch();
        return $diskuseDetail;
    }
    public function vratKomentareKDiskusi(string $title){
        $komentare = $this->explorer
        ->query('SELECT * FROM vratKomentareKDiskusiFunkce(?)', $title)
        ->fetchAll();
        return $komentare;
    }

    public function vratVedlejsiKomentare(){
        $komentare = $this->explorer->table('Komentare')
        ->select('Komentare.KomentareId, Komentare.HlavniKomentarId, Uzivatele.Prezdivka AS Prezdivka, Komentare.Cas, Komentare.Obsah')
        ->where('Komentare.UzivateleId = Uzivatele.UzivateleId')
        ->where('DiskuseId IS NULL')
        ->fetchAll();
        return $komentare;
    }
}