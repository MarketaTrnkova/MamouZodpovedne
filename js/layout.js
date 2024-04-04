let userIcon = document.querySelector(".user-icon-wrapper");
let vytvoritDiskusiBtn = document.querySelector(".vytvoritDiskusiBtn");
let dialogPrihlasitRegistrovat = document.querySelector(".prihlasit_registrovat_dialog");
let napsatReakciNaKomentarBtn = document.querySelector(".reagovat-btn");
let napsatKomentarBtn = document.querySelector(".napsat-komentar");
let dialogNapsatKomentar = document.querySelector(".dialogNapsatKomentar");
let dialogVytvoritDiskusi = document.querySelector(".vytvoritDiskusiDialog");
let hlavniKomentarIdInput = document.querySelector("input[name='hlavniKomentarId']");
let dialogPrihlasitSeBtn = document.querySelector(".dialogPrihlasitSeBtn");
let dialogRegistrovatSeBtn = document.querySelector(".dialogRegistrovatSeBtn");
let prihlasitSeForm = document.querySelector(".prihlasitSeForm");
let registrovatSeForm = document.querySelector(".registrovatSeForm");
let logo = document.querySelector(".logoWrapper");
let prihlasitSeBtn = document.querySelector(".prihlasit-se-btn");
let kategorieBtn = document.querySelectorAll("[kod-kategorie]");
let kategorieForm = document.querySelector("#frm-vyberKategorii");
let kategorieInput = document.querySelector("input[name='vybranaKategorie']");



function otevriDialogoveOkno(){
    dialogPrihlasitRegistrovat.showModal();
}

   
function napsatReakciNaKomentar(IdFormulare){
        let dialogReakce = "dialogReakce-" + IdFormulare;
        let dialogReakceElement =  document.getElementById(dialogReakce);
        let hiddenInput = document.querySelector(`[id="${IdFormulare}"] input[name="hlavniKomentarId"]`);
        hiddenInput.value = IdFormulare;
        if(dialogReakceElement){
            dialogReakceElement.showModal();

            dialogReakceElement.addEventListener("click", (event)=>{
                let souradniceDialogOkna = dialogReakceElement.getBoundingClientRect();
                if(
                    event.clientX < souradniceDialogOkna.left ||
                    event.clientX > souradniceDialogOkna.right ||
                    event.clientY < souradniceDialogOkna.top ||
                    event.clientY > souradniceDialogOkna.bottom
                ){
                    dialogReakceElement.close();
                }
            })
        }
}
   


if (vytvoritDiskusiBtn){
    vytvoritDiskusiBtn.addEventListener("click", (event)=>{
        dialogVytvoritDiskusi.showModal();
    })

    dialogVytvoritDiskusi.addEventListener("click", (event)=>{
        let souradniceDialogOkna = dialogVytvoritDiskusi.getBoundingClientRect();
        if(
            event.clientX < souradniceDialogOkna.left ||
            event.clientX > souradniceDialogOkna.right ||
            event.clientY < souradniceDialogOkna.top ||
            event.clientY > souradniceDialogOkna.bottom
        ){
            dialogVytvoritDiskusi.close();
        }
    })
}

if(napsatKomentarBtn){
    napsatKomentarBtn.addEventListener("click", (event)=>{
        dialogNapsatKomentar.showModal();
    })
    dialogNapsatKomentar.addEventListener("click", (event)=>{
        let souradniceDialogOkna = dialogNapsatKomentar.getBoundingClientRect();
        if(
            event.clientX < souradniceDialogOkna.left ||
            event.clientX > souradniceDialogOkna.right ||
            event.clientY < souradniceDialogOkna.top ||
            event.clientY > souradniceDialogOkna.bottom
        ){
            dialogNapsatKomentar.close();
        }
    })
}

dialogPrihlasitRegistrovat.addEventListener("click", (event)=>{
    let souradniceDialogOkna = dialogPrihlasitRegistrovat.getBoundingClientRect();
    if(
        event.clientX < souradniceDialogOkna.left ||
        event.clientX > souradniceDialogOkna.right ||
        event.clientY < souradniceDialogOkna.top ||
        event.clientY > souradniceDialogOkna.bottom
    ){
        dialogPrihlasitRegistrovat.close();
    }

});

dialogRegistrovatSeBtn.addEventListener("click", (event)=>{
    prihlasitSeForm.classList.remove('show');
    prihlasitSeForm.classList.add('hidden');
    dialogPrihlasitSeBtn.classList.remove('activeBtn');
    dialogRegistrovatSeBtn.classList.add('activeBtn');
    registrovatSeForm.classList.remove('hidden');
    registrovatSeForm.classList.add('show');
});

dialogPrihlasitSeBtn.addEventListener("click", (event)=>{
    registrovatSeForm.classList.remove('show');
    registrovatSeForm.classList.add('hidden');
    dialogRegistrovatSeBtn.classList.remove('activeBtn');
    dialogPrihlasitSeBtn.classList.add('activeBtn');
    prihlasitSeForm.classList.remove('hidden');
    prihlasitSeForm.classList.add('show');
});

function vyberKategorii ($kod){
    kategorieInput.value = $kod;
    kategorieForm.submit();
}
