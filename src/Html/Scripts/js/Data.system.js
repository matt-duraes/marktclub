const dataBr = data => {
    const explode = data.split(' ');
    const hora = explode.length == 2 ? ' ' + explode[1] : '';
    data = explode[0];
    if (/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}/.test(data)) {
        const dataExplode = data.split('-');
        data = dataExplode[2] + '/' + dataExplode[1] + '/' + dataExplode[0];
    }
    return data + hora;
};
const dataBanco = data => {
    const explode = data.split(' ');
    const hora = explode.length == 2 ? ' ' + explode[1] : '';
    data = explode[0];
    if (/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}/.test(data)) {
        const dataExplode = data.split('/');
        data = dataExplode[2] + '-' + dataExplode[1] + '-' + dataExplode[0];
    }
    return data + hora;
};
