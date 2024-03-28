<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Models\ProduktManager;
use Nette\Application\UI\Form;
use App\Models\UzivateleManager;

final class DomuPresenter extends Nette\Application\UI\Presenter
{
    public function __construct
    ( 
        private ProduktManager $produktManager,
        private UzivateleManager $uzivateleManager
    ){}

    public function renderDefault(){
        $hrackyDo3Produkty = $this->produktManager->vypisProdukty('hrackyDo3');
        $this->template->hrackyDo3Produkty = $hrackyDo3Produkty;
        if ($this->uzivateleManager->jePrihlasen){
            $this->template->jePrihlasen = true;
        }else {
            $this->template->jePrihlasen = false;
        }
    }
    public function createComponentPrihlasitSe(): Form{
        $form = new Form;

        $form->addEmail('email', 'email')
             ->setRequired('Prosím, zadejte svůj email.');
    
        $form->addPassword('heslo', 'Heslo')
             ->setRequired('Prosím, zadejte své heslo.');
    
        $form->addSubmit('prihlasitSe', 'Přihlásit se');
        $form->onSuccess[] = $this->uzivateleManager->prihlasMe(...);
        return $form;
    }


    public function createComponentRegistrovatSe(): Form{
        $form = new Form;
        $form->addEmail('email', 'Email')
             ->setRequired('Prosím, zadejte svůj email.');
        $form->addText('prezdivka', 'zadejte přezdívku')
            ->setRequired('Prosím, zadejte přezdívku.');
        $form->addPassword('heslo', 'Heslo')
             ->setRequired('Prosím, zadejte heslo.');
    
        $form->addSubmit('registrovatSe', 'Registrovat se');
        $form->onSuccess[] = $this->uzivateleManager->registrujMe(...);
        return $form;
    }

  public function createComponentOdhlasitSe():Form{
    $form = new Form;
    $form->addSubmit('odhlasitSe', 'Odhlásit se');
    $form->onSuccess[] = $this->uzivateleManager->odhlasMe(...);
    return $form;
  }
}
