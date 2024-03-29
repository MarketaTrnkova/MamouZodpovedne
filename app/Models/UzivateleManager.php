<?php
declare(strict_types=1);

namespace App\Models;

use Exception;
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
                $this->prihlasMe($form, $dataFomulare);
                return 'Registrace proběhla v pořádku';
            }
        } catch(UniqueConstraintViolationException $e){
            $form->addError('Nevalidní data.');
        }
    }

    public function prihlasMe(Form $form, \stdClass $dataFomulare){
        try{
            $radekZDb = $this->explorer->table('Uzivatele')->where('Email', $dataFomulare->email)->fetch();
            if ($radekZDb){
                $jeShoda = password_verify($dataFomulare->heslo, $radekZDb->HashHeslo);
                if ($jeShoda){
                    $_SESSION["email"] = $dataFomulare->email;
                    $_SESSION["prezdivka"] = $radekZDb->Prezdivka;
                    $_SESSION["jePrihalsen"] = true;
                    $this->jePrihlasen = true; 
                    return;
                }else{
                    $form->addError('Neplatné údaje.');
                }
            }else{
                $this->odhlasMe();
                $form->addError('Uživatel s daným emailem nebyl nalezen');
            }
        }catch(Exception $e){
            $form->addError('Neplatné údaje.');
            $this->jePrihlasen = false; 
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
        unset($_SESSION["email"]);
        unset($_SESSION["prezdivka"]);
        unset($_SESSION["jePrihlasen"]);
        $this->jePrihlasen = false; 
    }
}