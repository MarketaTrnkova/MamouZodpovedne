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
        $hrackyDo3Produkty = $this->produktManager->vypisProdukty('hrackyDo3');
        $this->template->hrackyDo3Produkty = $hrackyDo3Produkty;
    }


}
