<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Presenters\ZakladniPresenter;
use App\Models\UzivateleManager;
use App\Models\ProduktManager;
use Nette\Http\Session;

final class DetskyPokojPresenter extends ZakladniPresenter{
    public function __construct
    (
        protected UzivateleManager $uzivateleManager,
        private ProduktManager $produktManager,
        protected Session $session
    )
    {
        parent::__construct($uzivateleManager, $session);
    }
    public function renderDefault(){
        $detskyPokoj = $this->produktManager->vypisProdukty('detskyPokoj');
        $this->template->detskyPokoj = $detskyPokoj;
    }

}