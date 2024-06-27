function uuid() {
    const array = new Uint8Array(16);
    crypto.getRandomValues(array);

    array[6] = (array[6] & 0x0f) | 0x40;
    array[8] = (array[8] & 0x3f) | 0x80;

    const uuid = [...array]
        .map((byte, index) => {
            const hex = byte.toString(16).padStart(2, '0');
            return index === 4 || index === 6 || index === 8 || index === 10 ? '-' + hex : hex;
        })
        .join('');

    return uuid;
}

function cpf() {
    function randomDigit() {
        return Math.floor(Math.random() * 10);
    }

    function calculateDigit(numbers) {
        let sum = 0;
        for (let i = 0; i < numbers.length; i++) {
            sum += numbers[i] * (numbers.length + 1 - i);
        }
        let remainder = sum % 11;
        return remainder < 2 ? 0 : 11 - remainder;
    }

    let cpf = [];
    for (let i = 0; i < 9; i++) {
        cpf.push(randomDigit());
    }

    cpf.push(calculateDigit(cpf));
    cpf.push(calculateDigit(cpf));

    return cpf.join('');
}

function gerarEmailAleatorio() {
    const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    const domains = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com'];

    function randomString(length) {
        let result = '';
        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    }

    const username = randomString(8);
    const domain = domains[Math.floor(Math.random() * domains.length)];

    return `${username}@${domain}`;
}

module.exports = { uuid, cpf, gerarEmailAleatorio };
