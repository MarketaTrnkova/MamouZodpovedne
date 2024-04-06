<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Models\UzivateleManager;
use App\Models\ProduktManager;
use Nette\Http\Session;
class  VybavaProMiminkoPresenter extends ZakladniPresenter{
    public function __construct(
        protected UzivateleManager $uzivateleManager,
        private ProduktManager $produktManager,
        protected Session $session
        )
    {
        parent::__construct($uzivateleManager, $session);
    }

    public function renderDefault(){
        $vybavaProMiminko = $this->produktManager->vypisProdukty('vybavaProMiminko');
        $this->template->vybavaProMiminko = $vybavaProMiminko;
    }
}