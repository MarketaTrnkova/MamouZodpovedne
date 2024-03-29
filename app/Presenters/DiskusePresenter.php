<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Presenters\ZakladniPresenter;
use App\Models\UzivateleManager;

class DiskusePresenter extends ZakladniPresenter{
    public function __construct(UzivateleManager $uzivateleManager)
    {
        parent::__construct($uzivateleManager);
    }
}