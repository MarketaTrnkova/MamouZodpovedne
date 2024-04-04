<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Models\UzivateleManager;
use Nette\Http\Session;
class  VybavaProMiminkoPresenter extends ZakladniPresenter{
    public function __construct(
        UzivateleManager $uzivateleManager,
        Session $session
        )
    {
        parent::__construct($uzivateleManager, $session);
    }
}