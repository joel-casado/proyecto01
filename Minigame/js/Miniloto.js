document.addEventListener('DOMContentLoaded', function(){
    //Verificar si l'usuari ha visitat la pagina
    if(sessionStorage.getItem('visited', 'false')){
        window.open('web/Miniloto.html', '_blank');
    }
    else {
        //Marcar la pagina com visitada
        sessionStorage.setItem('visited','true');
    }
});
function miniloto(){
    var NumGanador = Math.random(); /*Math generara un numero entre 0 y 1*/

    if (NumGanador <0.5){
        alert("No tens premi :(")
    }
    else if (NumGanador < 0.85){
        alert("Has guanyat un descompte del 30%!")
    }
    else{
        alert("HAS CONSEGUIT UN CURS GRATIS!")
    }
    window.close();
}