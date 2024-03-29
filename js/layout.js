let userIcon = document.querySelector(".user-icon-wrapper");
let dialogPrihlasitRegistrovat = document.querySelector(".prihlasit_registrovat_dialog");
let dialogPrihlasitSeBtn = document.querySelector(".dialogPrihlasitSeBtn");
let dialogRegistrovatSeBtn = document.querySelector(".dialogRegistrovatSeBtn");
let prihlasitSeForm = document.querySelector(".prihlasitSeForm");
let registrovatSeForm = document.querySelector(".registrovatSeForm");
let logo = document.querySelector(".logoWrapper");

userIcon.addEventListener("click", ()=>{
    dialogPrihlasitRegistrovat.showModal();
});

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



/*

modalBtnZprava.addEventListener("click",(event)=>{
    dialogOdeslatZpravu.showModal();
});

modalBtnOdpoved.addEventListener("click",(event)=>{
    dialogOdeslatOdpoved.showModal();
});


dialogOdeslatZpravu.addEventListener("click", (event)=>{
    let souradniceDialogOkna = dialogOdeslatZpravu.getBoundingClientRect();
    if(
        event.clientX < souradniceDialogOkna.left ||
        event.clientX > souradniceDialogOkna.right ||
        event.clientY < souradniceDialogOkna.top ||
        event.clientY > souradniceDialogOkna.bottom
    ){
        dialogOdeslatZpravu.close();
    }
});

dialogOdeslatOdpoved.addEventListener("click", (event)=>{
    let souradniceDialogOkna = dialogOdeslatOdpoved.getBoundingClientRect();
    if(
        event.clientX < souradniceDialogOkna.left ||
        event.clientX > souradniceDialogOkna.right ||
        event.clientY < souradniceDialogOkna.top ||
        event.clientY > souradniceDialogOkna.bottom
    ){
        dialogOdeslatOdpoved.close();
    }
});



*/