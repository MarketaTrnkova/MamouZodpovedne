<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Presenters\ZakladniPresenter;
use App\Models\UzivateleManager;
use Nette\Http\Session;

final class DetskyPokojPresenter extends ZakladniPresenter{
    public function __construct
    (
        UzivateleManager $uzivateleManager,
        Session $session
    )
    {
        parent::__construct($uzivateleManager, $session);
    }

}