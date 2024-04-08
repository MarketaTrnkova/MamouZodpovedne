<?php

declare(strict_types=1);

namespace App\Presenters;


use Nette;
use App\Presenters\ZakladniPresenter;
use App\Models\UzivateleManager;
use App\Models\DiskuseManager;
use Nette\Application\UI\Form;
use Nette\Http\Session;

class DiskusePresenter extends ZakladniPresenter{
    private ?string $kodAktualniKategorie = null;
    private $detailDiskuse;

    public function __construct(
        protected UzivateleManager $uzivateleManager, 
        private DiskuseManager $diskuseManager,
        protected Session $session
        )
    {
        parent::__construct($uzivateleManager, $session);
    }

  
    public function createComponentVytvoritDiskusi():Form{
        $form = new Form();
        $form->addText('nadpisDiskuse', 'Nadpis')
        ->setRequired('Prosím, vyplňte nadpis.');

        $form->addText('textDiskuse', 'Text diskuse')
        ->setRequired('Prosím, vyplňte text diskuse');
        $form->addSelect('kategorie', 'Kategorie:')
        ->setItems($this->diskuseManager->vratKategorie());
        $form->addSubmit('vytvoritDiskusiSubmit', 'Vytvořit diskusi');
        $form->onSuccess[] = function(Form $form, \stdClass $dataFormulare){
            $vysledekZpracovaniFormulare = $this->diskuseManager->vytvoritDiskusiSuccess($form, $dataFormulare);
            if ($vysledekZpracovaniFormulare){
                $this->flashMessage('Diskuse vytvořena','success');
            }else{
                $this->flashMessage('Nepodařilo se vytvořit diskusi', 'error');
            }
        };
        return $form;
    }

    public function actionShow(string $title){
        // ulozim si do atributu detaily o diskusi pro pozdejsi vyuziti pri vytvareni formulare pro komentar ke konretni diskusi
        $this->detailDiskuse = $this->diskuseManager->vratDetailDiskuse($title);
    }
    public function createComponentVytvoritKomentar(): Form{
        $form = new Form();
        $form->addText('textKomentare', 'Váš komentář')
        ->setRequired('Prosím, napište text komentáře.');
        $form->addHidden('diskuseId', $this->detailDiskuse->DiskuseId);
        $form->addSubmit('pridaKomentarSubmit', 'Přidat Komentář');

        $form->onSuccess[] = function (Form $form, \stdClass $dataFomulare){
            $vysledekSuccesFunkce = $this->diskuseManager->vytvoritKomentarSucces($form,$dataFomulare);
            if ($vysledekSuccesFunkce){
                $this->flashMessage('Komentář úspěšně odeslán', 'success');
                $this->redirect('this');
            }else{
                $this->flashMessage('Nepodařilo se vložit komentář');
            }
        };
        return $form;
    }

    public function createComponentVytvorReakciNaKomentar(): Form{
        $form = new Form();
        $form->addText('textKomentare', 'Vaše reakce:')
        ->setRequired('Prosím, vyplňte text vaší reakce na komentář.');
        $form->addHidden('hlavniKomentarId', null);
        $form->addSubmit('reakceSubmit', 'Odeslat reakci');
        $form->onSuccess[] = function (Form $form, \stdClass $dataFomulare){
            $vysledekSuccesFunkce = $this->diskuseManager->vytvorReaciNaKomentarSucces($form,$dataFomulare);
            if ($vysledekSuccesFunkce){
                $this->flashMessage('Reakce úspěšně odeslána');
                $this->redirect('this');
            }
        };
        return $form;
    } 

    public function createComponentVyberKategorii(): Form{
        $form = new Form();
        $form->addHidden('vybranaKategorie');
        $form->setHtmlAttribute('class', 'hidden');
        $form->onSuccess[] = function (Form $form, \stdClass $dataFomulare){
            $vysledekSuccessMetody=$this->diskuseManager->vyberKategoriiSucces($form,$dataFomulare);

            if ($vysledekSuccessMetody){
                $this->redirect('this');
            }
        };
        return $form;
    }

    public function beforeRender(){
        parent::beforeRender();

        //pokud neni vytvorena sekce pro session, zde ji vytvorim
        $sessionSection = $this->session->getSection('filtrKategorie');

        if (isset($sessionSection->aktualniKategorie)){
            $this->kodAktualniKategorie = $sessionSection->aktualniKategorie;
            $sessionSection->remove('aktualniKategorie');
        }else {
            $this->kodAktualniKategorie = null;
        }

    }
    public function renderDefault(){
        $this->template->vsechnyKategorie = $this->diskuseManager->vratKategoriePole();
        if ($this->kodAktualniKategorie != null){
            $this->template->vsechnyDiskuse = $this->diskuseManager->vratDiskuseZDb($this->kodAktualniKategorie);
            $this->template->aktivniKategorie = $this->kodAktualniKategorie;
        }else {
        $this->template->vsechnyDiskuse = $this->diskuseManager->vratDiskuseZDb();
        }
    }
    public function renderShow(string $title){
       $this->template->title = $title;
       $this->template->detailDiskuse=$this->detailDiskuse;
       $this->template->komentareKDiskusi=$this->diskuseManager->vratKomentareKDiskusi($title);
       $this->template->vedlejsiKomentare=$this->diskuseManager->vratVedlejsiKomentare();
    }
    
}