<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Models\ProduktManager;
use Nette\Application\UI\Form;
use App\Models\UzivateleManager;
use App\Presenters\ZakladniPresenter;
use Nette\Http\Session;

final class DomuPresenter extends ZakladniPresenter
{
    public function __construct(
        protected UzivateleManager $uzivateleManager,
        private ProduktManager $produktManager,
        protected Session $session
    )
    {
        parent::__construct($uzivateleManager, $session);
    }
  
    public function renderDefault(){
        
        //kod pro kategorii Hracky do 3 let = hrackyDo3
        $hrackyDo3Produkty = $this->produktManager->vypisProdukty('hrackyDo3');
        $this->template->hrackyDo3Produkty = $hrackyDo3Produkty;
        //kod pro kategorii Hracky od 3 let = hrackyOd3
        $hrackyOd3Produkty = $this->produktManager->vypisProdukty('hrackyOd3');
        $this->template->hrackyOd3Produkty = $hrackyOd3Produkty;
        //kod pro kategorii vybava pro miminka = vybavaPromiminka
        $vybavaProMiminko = $this->produktManager->vypisProdukty('vybavaProMiminko');
        $this->template->vybavaProMiminko = $vybavaProMiminko;
        //kod pro kategorii detsky pokoj = detskyPokoj
        $detskyPokoj = $this->produktManager->vypisProdukty('detskyPokoj');
        $this->template->detskyPokoj = $detskyPokoj;
        //kod pro kategorii kurzy pro rodice = kurzy
        $kurzy = $this->produktManager->vypisProdukty('kurzy');
        $this->template->kurzy = $kurzy;
    }


}
