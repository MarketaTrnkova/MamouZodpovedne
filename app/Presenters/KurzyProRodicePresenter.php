<?php
declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Models\UzivateleManager;
use App\Presenters\ZakladniPresenter;
use Nette\Http\Session;

class KurzyProRodicePresenter extends ZakladniPresenter{
    public function __construct(
        UzivateleManager $uzivateleManager,
        Session $session
        )
    {
        parent::__construct($uzivateleManager, $session);
    }
}