<?php
declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Models\UzivateleManager;
use App\Presenters\ZakladniPresenter;

class KurzyProRodicePresenter extends ZakladniPresenter{
    public function __construct(UzivateleManager $uzivateleManager)
    {
        parent::__construct($uzivateleManager);
    }
}