window.addEventListener('load', () => {
    const tempo = document.querySelector('#tempo');
    if(!tempo) {
        return;
    }

    const LINK = document.querySelector('#LINK').value;
    let timeleft = 10;
    const downloadTimer = setInterval(function(){
        if(timeleft <= 0){
            clearInterval(downloadTimer);
        }

        tempo.innerHTML = timeleft + " segundos";
        timeleft -= 1;
        if(timeleft === 0){
                window.location.href = LINK;
        }
    }, 1000);
});
