
window.addEventListener('load', () => {
    const div_standby = document.querySelector('.bloco_standby');
    const blocoFieldset = div_standby.parentNode.parentNode;
    blocoFieldset.style.display = 'none';

    div_standby.childNodes.forEach((item) => {
        const span = item.querySelector('p > span');
        if (span.textContent !== 'Dado não informado') {
            blocoFieldset.style.display = '';
        }
    })
})
