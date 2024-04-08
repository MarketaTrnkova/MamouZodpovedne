<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Models\UzivateleManager;
use Nette\Http\Session;

class ZakladniPresenter extends Nette\Application\UI\Presenter{
    public function __construct(
        protected UzivateleManager $uzivateleManager,
        protected Session $session
    ){
        parent::__construct();
    }

    public function beforeRender(){
        parent::beforeRender();
        $this->template->prihlasitSe = $this->createComponentPrihlasitSe();
        $sessionSection = $this->session->getSection('Uzivatel');
        if ($this->uzivateleManager->jePrihlasen || isset($sessionSection->prezdivka)){
            $this->template->jePrihlasen = true;
            $this->template->sessionPoleDat = [
            'email' => $sessionSection->get('email'),
            'prezdivka' => $sessionSection->get('prezdivka')
            ];
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
        $form->onSuccess[] = function(Form $form, \stdClass $dataFomulare){
            $vysledekSuccesFunkce = $this->uzivateleManager->prihlasMe($form, $dataFomulare);
            if ($vysledekSuccesFunkce){
                $this->flashMessage('Příhlášení proběhlo úspěšně');
            }else{
                $this->flashMessage('Nepodařilo se přihlásit');
            }
        };
        return $form;
    }


    public function createComponentRegistrovatSe(): Form{
        $form = new Form;
        $form->addEmail('email', 'Email')
             ->setRequired('Prosím, zadejte svůj email.')
             ->addCondition(Nette\Forms\Form::Filled)
             ->addRule([$this->uzivateleManager, 'zkontrolujEmail'], 'Uživatel s tímto emailem již existuje');
        $form->addText('prezdivka', 'zadejte přezdívku')
            ->setRequired('Prosím, zadejte přezdívku.')
            ->addCondition(Nette\Forms\Form::Filled)
            ->addRule([$this->uzivateleManager, 'zkontrolujPrezdivku'], 'Přezdívka je již obsazená jiným uživatelem');
        $form->addPassword('heslo', 'Heslo')
             ->setRequired('Prosím, zadejte heslo.');
    
        $form->addSubmit('registrovatSe', 'Registrovat se');
        $form->onSuccess[] = function(Form $form, \stdClass $dataFomulare){
            $vysledekZpracovoaniFromulare = $this->uzivateleManager->registrujMe($form, $dataFomulare);
            if ($vysledekZpracovoaniFromulare){
                $this->flashMessage('Registrace proběhla úspěšně', 'success');
            }else{
                $this->flashMessage('Registrace se nezdařila', 'error');
            }
        };
        return $form;
    }


    public function createComponentOdhlasitSe():Form{
        $form = new Form;
        $form->addSubmit('odhlasitSe', 'Odhlásit se');
        $form->onSuccess[] = function(Form $form, \stdClass $dataFomulare){
            $vysledekZpracovoaniFromulare = $this->uzivateleManager->odhlasMe($form, $dataFomulare);
            if ($vysledekZpracovoaniFromulare){
                $this->flashMessage('Úspěšně odhlášen','success');
            }
        };
        return $form;
    }

}