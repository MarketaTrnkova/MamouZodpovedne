<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Models\UzivateleManager;
class  VybavaProMiminkoPresenter extends ZakladniPresenter{
    public function __construct(UzivateleManager $uzivateleManager)
    {
        parent::__construct($uzivateleManager);
    }
}