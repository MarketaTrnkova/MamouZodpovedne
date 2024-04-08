<?php
declare(strict_types=1);

namespace App\Models;

use Exception;
use Nete;

use Nette\Database\Explorer;
use Nette\Application\UI\Form;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Http\Session;

class UzivateleManager
{
    public $jePrihlasen;
    public function __construct(
        private Explorer $explorer,
        protected Session $session){
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
                $this->prihlasMe($form, $dataFomulare);
                return true;
            } else{
                return false;
            }
        }catch(UniqueConstraintViolationException $e){
            $form->addError('Nevalidní data.');
            return false;
        }
    }

    public function prihlasMe(Form $form, \stdClass $dataFomulare){
        try{
            $radekZDb = $this->explorer->table('Uzivatele')->where('Email', $dataFomulare->email)->fetch();
            if ($radekZDb){
                $jeShoda = password_verify($dataFomulare->heslo, $radekZDb->HashHeslo);
                if ($jeShoda){
                    $sessionSection = $this->session->getSection('Uzivatel');
                    $sessionSection->email = $dataFomulare->email;
                    $sessionSection->prezdivka = $radekZDb->Prezdivka;
                    $sessionSection->uzivatelId = $radekZDb->UzivateleId;
                    $sessionSection->jePrihlasen = true;
                    $this->jePrihlasen = true; 
                    return true;
                }else{
                    $form->addError('Neplatné údaje.');
                    return false;
                }
            }else{
                $this->odhlasMe();
                $form->addError('Uživatel s daným emailem nebyl nalezen');
                return false;
            }
        }catch(Exception $e){
            $form->addError('Neplatné údaje.');
            $this->jePrihlasen = false; 
            return false;
        }
    }
    public function zkontrolujPrezdivku($input, $data){
        try{
            $radekZDb = $this->explorer->table('Uzivatele')->where('Prezdivka', $data)->fetch();
            if ($radekZDb){
                return false; // Přezdívka již existuje
            }else {
                return true; // Přezdívka je dostupná
            }
        }catch(Exception $e){
            return false;
        }
    }

    public function zkontrolujEmail ($input, $data){
        try{
           $radekZDb = $this->explorer->table('Uzivatele')->where('Email', $data)->fetch();
            if ($radekZDb){
                return false; // Email již existuje
            }else {
                return true; // Email je dostupný
            }
        }catch(Exception $e){
            return false;
        }
    }
    public function odhlasMe(){
        $sessionSection = $this->session->getSection('Uzivatel');
        $sessionSection->remove('email');
        $sessionSection->remove('prezdivka');
        $sessionSection->remove('jePrihlasen');
        $this->jePrihlasen = false; 
        return true;
    }
}