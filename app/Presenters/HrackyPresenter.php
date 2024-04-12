<?php
declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Presenters\ZakladniPresenter;
use App\Models\UzivateleManager;
use App\Models\ProduktManager;
use Nette\Http\Session;

class HrackyPresenter extends ZakladniPresenter{
    public function __construct(
        protected UzivateleManager $uzivateleManager,
        private ProduktManager $produktManager,
        protected Session $session
        )
    {
        parent::__construct($uzivateleManager, $session);
    }

    public function renderDefault(){
        $hrackyDo3 = $this->produktManager->vypisProdukty('hrackyDo3');
        $this->template->hrackyDo3 = $hrackyDo3;
        $this->template->aktualniStranka = 'hracky';
    }
}