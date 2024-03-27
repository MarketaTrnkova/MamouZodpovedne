<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Models\ProduktManager;

final class DomuPresenter extends Nette\Application\UI\Presenter
{
    public function __construct
    ( 
        private ProduktManager $produktManager
    ){}

    public function renderDefault(){
        $hrackyDo3Produkty = $this->produktManager->vypisProdukty('hrackyDo3');
        $this->template->hrackyDo3Produkty = $hrackyDo3Produkty;
    }
}
