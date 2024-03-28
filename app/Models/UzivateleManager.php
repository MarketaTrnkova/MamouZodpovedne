<?php
declare(strict_types=1);

namespace App\Models;

use Nete;

use Nette\Database\Explorer;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;

class UzivateleManager
{
    private $explorer;
    public $jePrihlasen;
    public function __construct(Explorer $explorer)
    {
        $this->explorer = $explorer;

    }

    public function registrujMe(Form $form, \stdClass $dataFomulare){
        try{
            $hashHeslo = password_hash($dataFomulare->heslo,PASSWORD_BCRYPT, array("cost" => 11));
            
            $vlozenyRadek = $this->explorer->table('Uzivatele')->insert([
                'Prezdivka'=> $dataFomulare->prezdivka,
                'Email'=> $dataFomulare->email,
                'HashHeslo'=> $hashHeslo
            ]);
            if ($vlozenyRadek instanceof \Nette\Database\Table\ActiveRow){
                return "Registrace proběhla v pořádku";
            }
        } catch(UniqueConstraintViolationException $e){
            $form->addError('Nesprávné údaje.');
        }
    }

    public function prihlasMe(Form $form, \stdClass $dataFomulare){
        try{
            $radekZDb = $this->explorer->table('Uzivatele')->where('Email', $dataFomulare->email)->fetch();
            $jeShoda = password_verify($dataFomulare->heslo, $radekZDb->HashHeslo);
            if ($radekZDb && $jeShoda){
                $_SESSION["jePrihalsen"] = true;
                $this->jePrihlasen = true; 
                return "Přihlášení proběhlo v pořádku";
            }
        } catch(UniqueConstraintViolationException $e){
            $form->addError('Nesprávné údaje.');
            $this->jePrihlasen = false; 
        }
    }
    public function odhlasMe(){
        unset($_SESSION["jePrihlasen"]);
        $this->jePrihlasen = false; 
    }
}