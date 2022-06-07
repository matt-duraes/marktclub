class Grafico {
    titulo(titulo) {
        this._tituloHtml = `<div class="fw_grafico_titulo">${titulo}</div>`;
        return this;
    }
    dado(dado) {
        if (dado.dado != undefined) {
            dado = [dado];
        }
        let maiorValor = 0;
        let maiorValorTemp = 0;
        let menorValor = Infinity;
        let menorValorTemp = Infinity;
        let dadoFinal;
        dado.forEach(item => {
            if (item.dado) {
                maiorValorTemp = item.dado.reduce((a, b) => {
                    return Math.max(a, b);
                }, -Infinity);
                if (maiorValorTemp > maiorValor) {
                    maiorValor = maiorValorTemp;
                }
                menorValorTemp = item.dado.reduce((a, b) => {
                    return Math.min(a, b);
                }, Infinity);
                if (menorValorTemp < menorValor) {
                    menorValor = menorValorTemp;
                }
            }

            dadoFinal = {};
            if (item.dado) {
                dadoFinal.dado = item.dado;
            }
            if (item.label) {
                dadoFinal.label = item.label;
            }
            if (item.tipo) {
                dadoFinal.tipo = item.tipo;
            }
            if (item.cor) {
                dadoFinal.cor = this._montarCor(item.cor, item.dado == undefined ? [0] : item.dado);
            }
            if (item.icone) {
                dadoFinal.icone = item.icone;
            }
            if (item.imagem) {
                dadoFinal.imagem = item.imagem;
            }
            this._dado.push(dadoFinal);
        });
        this._maiorValor = maiorValor;
        this._maiorValorComSobra = parseInt(maiorValor + (maiorValor * 10) / 100);
        this._menorValorComSobra = menorValor == 0 ? 0 : parseInt(menorValor - (menorValor * 10) / 100);

        return this;
    }
    _montarCor(cor, dado) {
        return new Promise(resolve => {
            if (typeof cor == 'string') {
                cor = [cor];
            } else if (typeof cor != 'object') {
                cor = [this._corBase.azul];
            }

            let ultimaCor = cor[0];
            let corFinal = [];
            let corUso;
            dado = dado.length == 0 ? [0] : dado;
            dado.forEach((v, i) => {
                corUso = cor[i] == undefined ? ultimaCor : cor[i];
                corUso = this._corBase[corUso] == undefined ? corUso : this._corBase[corUso];
                ultimaCor = corUso;
                corFinal.push(corUso);
            });
            resolve(corFinal);
        });
    }
    label(label) {
        this._label = label;
        return this;
    }
    media(media) {
        this._media = media;
        return this;
    }
    header(header) {
        let html = '<div class="fw_grafico_header">';
        header.forEach(item => {
            html += `
                <div class="fw_grafico_header_item">
                    <div class="fw_grafico_header_item_valor">${item[1]}</div>
                    <div class="fw_grafico_header_item_texto">${item[0]}</div>
                </div>
            `;
        });
        html += '</div>';
        this._headerHtml = html;
        return this;
    }
    footer(footer) {
        let html = '<div class="fw_grafico_footer">';
        let icone;
        footer.forEach(item => {
            icone = '';
            if (item.length == 3 && item[2] == '<') {
                icone = `
                    <div class="fw_grafico_footer_item_icone">
                        <svg height="5" fill="#FF6C60" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30 16" style="enable-background:new 0 0 30 16;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path d="M15.3,968.3c0.6-0.1,1.1-0.3,1.6-0.7l12.1-10.2c1.2-1,1.4-2.8,0.4-4c-1-1.2-2.8-1.4-4-0.4c0,0-0.1,0.1-0.1,0.1L15,961.8 l-10.2-8.7c-1.2-1.1-3-1-4.1,0.2s-1,3,0.2,4c0.1,0,0.1,0.1,0.1,0.1l12.1,10.2C13.7,968.2,14.5,968.4,15.3,968.3L15.3,968.3z"/></g></svg>
                    </div>
                `;
            } else if (item.length == 3 && item[2] == '>') {
                icone = `
                    <div class="fw_grafico_footer_item_icone">
                        <svg height="5" fill="#169e91" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30 16" style="enable-background:new 0 0 30 16;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path d="M14.7,952.4c-0.6,0.1-1.1,0.3-1.6,0.7L1.1,963.3c-1.2,1-1.4,2.8-0.4,4c1,1.2,2.8,1.4,4,0.4c0,0,0.1-0.1,0.1-0.1l10.2-8.7 l10.2,8.7c1.2,1.1,3,1,4.1-0.2s1-3-0.2-4c0,0-0.1-0.1-0.1-0.1L16.9,953C16.3,952.5,15.5,952.3,14.7,952.4L14.7,952.4z"/></g></svg>
                    </div>
                `;
            }
            html += `
                <div class="fw_grafico_footer_item">
                    <div class="fw_grafico_footer_item_texto">
                        ${item[0]}
                    </div>
                    <div class="fw_grafico_footer_item_valor">
                        ${icone}
                        ${item[1]}
                    </div>
                </div>
            `;
        });
        html += '</div>';
        this._footerHtml = html;
        return this;
    }

    /*
    |--------------------------------------------------------------------------
    | EMPILHADO
    |--------------------------------------------------------------------------
    */
    async empilhado() {
        const config = {
            type: 'bar',
            options: {
                scales: {
                    xAxes: {
                        stacked: true,
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#CCC',
                        },
                        grid: {
                            display: false,
                        },
                        suggestedMax: this._maiorValorComSobra,
                    },
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                responsive: false,
            },
        };

        this._executarGrafico('empilhado', config);
        return this;
    }

    async barra() {
        const config = {
            type: 'bar',
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: this._maiorValorComSobra,
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: '#CCC',
                        },
                    },
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                responsive: false,
            },
        };

        this._executarGrafico('barra', config);
        return this;
    }

    /*
    |--------------------------------------------------------------------------
    | BARRA LINHA
    |--------------------------------------------------------------------------
    */
    async mix() {
        const dado = await this._setarDataGeral();
        const config = {
            type: 'scatter',
            data: dado.data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: '#CCC',
                        },
                        suggestedMax: this._maiorValorComSobra,
                    },
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                responsive: false,
            },
        };
        const canvas = await this._montarHtml('barra_linha', dado.label);
        this._Grafico = new Chart(canvas, config);
    }

    /*
    |--------------------------------------------------------------------------
    | TEMPO REAL
    |--------------------------------------------------------------------------
    */
    async tempoReal(callback) {
        let min = Infinity,
            max = -Infinity;
        const config = {
            type: 'line',
            options: {
                scales: {
                    x: {
                        type: 'realtime',
                        realtime: {
                            duration: 20000,
                            refresh: 1000,
                            delay: 2000,
                            onRefresh: async chart => {
                                const valor = await callback();
                                const now = Date.now();
                                let mudou = false;
                                chart.data.datasets.forEach((dataset, i) => {
                                    dataset.data.push({
                                        x: now,
                                        y: valor[i] || 0,
                                    });
                                    if (valor[i] > max) {
                                        max = valor[i];
                                        mudou = true;
                                    }
                                    if (valor[i] < min) {
                                        min = valor[i];
                                        mudou = true;
                                    }
                                });

                                if (mudou) {
                                    const sobra = (max * 10) / 100;
                                    chart.options.scales.y.suggestedMax = max + sobra;
                                    chart.options.scales.y.suggestedMin = min - sobra;
                                }
                            },
                        },
                    },
                    y: {
                        stacked: false,
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: '#CCC',
                        },
                    },
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                responsive: false,
            },
        };

        this._executarGrafico('tempo_real', config);
        return this;
    }
    /*
    |--------------------------------------------------------------------------
    | LINHA
    |--------------------------------------------------------------------------
    */
    async linha() {
        const config = {
            type: 'line',
            options: {
                scales: {
                    y: {
                        stacked: false,
                        suggestedMax: this._maiorValorComSobra,
                        suggestedMin: this._menorValorComSobra,
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: '#CCC',
                        },
                    },
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                responsive: false,
            },
        };

        this._executarGrafico('linha', config);
        return this;
    }

    /*
    |--------------------------------------------------------------------------
    | PIZZA
    |--------------------------------------------------------------------------
    */
    async pizza() {
        const config = {
            type: 'pie',
            options: {
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                layout: {
                    padding: 10,
                },
                responsive: false,
            },
        };
        this._executarGrafico('pizza', config);
        return this;
    }

    /*
    |--------------------------------------------------------------------------
    | ROSCA
    |--------------------------------------------------------------------------
    */
    async rosca() {
        const config = {
            type: 'doughnut',
            options: {
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                layout: {
                    padding: 10,
                },
                responsive: false,
            },
        };

        this._executarGrafico('rosca', config);
        return this;
    }
    /*
    |--------------------------------------------------------------------------
    | META
    |--------------------------------------------------------------------------
    */
    async meta(valor, meta) {
        let data, label;
        if (valor >= meta) {
            data = {
                data: [valor],
                backgroundColor: [this._corBase.verde],
                borderWidth: 0,
            };
            label = ['Atingido'];
        } else {
            data = {
                data: [valor, parseInt(meta - valor)],
                backgroundColor: [this._corBase.verde, '#EEE'],
                hoverOffset: 10,
                borderWidth: 0,
            };
            label = ['Atingido', 'Faltando'];
        }
        const config = {
            type: 'doughnut',
            data: {
                labels: label,
                datasets: [data],
            },
            options: {
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                layout: {
                    padding: 10,
                },
                responsive: false,
            },
        };

        const canvas = await this._montarHtml('meta', []);
        const porcentagem = parseFloat((valor * 100) / meta).toFixed(2);
        canvas.insertAdjacentHTML('beforebegin', `<div class="fw_grafico_porcentagem">${porcentagem}%</div>`);
        this._Grafico = new Chart(canvas, config);
        return this;
    }

    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR
    |--------------------------------------------------------------------------
    */
    atualizarIndice(label, indice, valor) {
        this._Grafico.data.datasets[label].data[indice] = valor;
        this._Grafico.update();
        return this;
    }
    async atualizar() {
        const dado = await this._setarDadoLinha();
        this._Grafico.data = dado;
        this._Grafico.update();
        return this;
    }

    /*
    |--------------------------------------------------------------------------
    | GERAL
    |--------------------------------------------------------------------------
    */
    async _executarGrafico(tipo, config) {
        const data = await this._setarDataGeral(tipo);
        config.data = data.data;
        const canvas = await this._montarHtml(tipo, data.label);
        this._Grafico = new Chart(canvas, config);
    }

    _setarDataGeral(tipo) {
        return new Promise(async resolve => {
            const dataset = {
                datasets: [],
                labels: this._label,
            };

            const label = [];
            let cor, data;
            const tipoBarra = { linha: 'line', barra: 'bar', rosca: 'doughnut', pizza: 'pie' };
            for (const item of this._dado) {
                cor = await item.cor;
                if (item.tipo == 'linha' || tipo == 'linha') {
                    data = {
                        label: item.label,
                        data: item.dado,
                        backgroundColor: cor,
                        borderColor: cor,
                        tension: 0.3,
                    };
                } else if (tipo == 'tempo_real') {
                    data = {
                        label: item.label,
                        backgroundColor: cor[0],
                        borderColor: cor[0],
                        data: [],
                    };
                } else if (item.tipo == 'barra' || tipo == 'barra' || tipo == 'empilhado') {
                    data = {
                        label: item.label,
                        data: item.dado,
                        backgroundColor: cor,
                        borderRadius: 5,
                    };
                } else if (tipo == 'rosca' || tipo == 'pizza') {
                    data = {
                        label: item.label,
                        data: item.dado,
                        backgroundColor: cor,
                        hoverOffset: 10,
                        borderWidth: 0,
                    };
                } else {
                    data = {
                        label: item.label,
                        data: item.dado,
                        backgroundColor: cor,
                    };
                }
                if (item.tipo != undefined) {
                    data.type = tipoBarra[item.tipo];
                }
                dataset.datasets.push(data);
                if (tipo == 'rosca' || tipo == 'pizza') {
                    item.dado.forEach((v, i) => {
                        label.push([this._label[i], cor[i]]);
                    });
                    break;
                }
                label.push([item.label, cor[0]]);
            }
            resolve({
                data: dataset,
                label,
            });
        });
    }
    _montarHtml(tipo, label) {
        return new Promise(async resolve => {
            let labelHtml = '';
            if (label.length > 1) {
                labelHtml += '<div class="fw_grafico_label">';
                label.forEach(item => {
                    labelHtml += `
                        <div class="fw_grafico_label_botao">
                            <div class="fw_grafico_label_botao_bola" style="
                                background-color: ${item[1]};
                            "></div>
                            <div class="fw_grafico_label_botao_texto">${item[0]}</div>
                        </div>
                    `;
                });
                labelHtml += '</div>';
            }

            let tabelaHtml = '';
            if (this._tabela == 'porcentagem' && this._dado.length == 1) {
                tabelaHtml += '<div class="fw_grafico_tabela">';

                let tabelaNome, tabelaValor, tabelaCor, tabelaIcone, tabelaImagem, tabelaPorcentagem, classeIconeImagem;
                const tabelaDado = this._dado[0];
                const quantidade = this._dado[0].dado.length;
                let valorTotal = 0;
                let i = 0;
                for (; i < quantidade; ++i) {
                    valorTotal += tabelaDado.dado[i];
                }
                i = 0;
                for (; i < quantidade; ++i) {
                    classeIconeImagem = '';
                    tabelaIcone = '';
                    tabelaImagem = '';

                    tabelaValor = tabelaDado.dado[i];
                    tabelaPorcentagem = parseFloat((tabelaValor * 100) / valorTotal).toFixed(2);
                    tabelaValor = `<span>(${tabelaPorcentagem}%)</span>` + tabelaValor;
                    tabelaNome = this._label[i];
                    tabelaCor = await tabelaDado.cor;
                    tabelaCor = tabelaCor[i];

                    if (tabelaDado.icone != undefined) {
                        tabelaIcone = `<div class="fw_grafico_tabela_icone">${tabelaDado.icone[i]}</div>`;
                        classeIconeImagem = 'fw_grafico_tabela_icone_imagem';
                    } else if (tabelaDado.imagem != undefined) {
                        tabelaImagem = `
                            <div class="fw_grafico_tabela_imagem" style="background-image: url(${tabelaDado.imagem[i]})"></div>
                        `;
                        classeIconeImagem = 'fw_grafico_tabela_icone_imagem';
                    }
                    tabelaHtml += `
                        <div class="fw_grafico_tabela_lista ${classeIconeImagem}">
                            ${tabelaIcone}
                            ${tabelaImagem}
                            <div class="fw_grafico_tabela_nome">${tabelaNome}</div>
                            <div class="fw_grafico_tabela_valor">${tabelaValor}</div>
                            <div class="fw_grafico_tabela_porcentagem">
                                <span style="width: ${tabelaPorcentagem}%; background-color: ${tabelaCor};"></span>
                            </div>
                        </div>
                    `;
                }
                tabelaHtml += '</div>';
            } else if (this._tabela == 'porcentagem' && this._dado.length > 1) {
            }

            this._bloco.innerHTML = '';
            this._bloco.insertAdjacentHTML(
                'afterbegin',
                `
                ${this._tituloHtml}
                <div class="fw_grafico_grafico">
                    ${this._headerHtml}
                    <div class="fw_grafico_canvas">
                        <canvas></canvas>
                    </div>
                    ${labelHtml}
                    ${this._footerHtml}
                </div>
                ${tabelaHtml}
            `
            );
            this._bloco.classList.add('fw_grafico_' + tipo);
            if (tabelaHtml != '') {
                this._bloco.classList.add('fw_grafico_com_tabela');
            }
            const canvas = this._bloco.querySelector('canvas');
            const dimensao = this._bloco.querySelector('.fw_grafico_canvas').getBoundingClientRect();

            let canvasWidth = dimensao.width - 80;
            let canvasHeight = 280;
            if (tipo == 'pizza' || tipo == 'rosca') {
                canvasWidth = canvasWidth > 300 ? 300 : canvasWidth;
                canvasHeight = canvasWidth;
            }
            canvas.setAttribute('width', canvasWidth);
            canvas.setAttribute('height', canvasHeight);

            resolve(canvas);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CONSTRUTOR
    |--------------------------------------------------------------------------
    */
    constructor(bloco, tabela) {
        this._dado = [];
        this._label = [];
        this._tabela = tabela;
        this._media = 0;
        this._tituloHtml = '';
        this._headerHtml = '';
        this._footerHtml = '';
        this._tabelaHtml = '';
        this._bloco = document.querySelector(bloco);

        this._corBase = {
            vermelho: 'rgba(255, 99, 132, 1)',
            azul: 'rgba(54, 162, 235, 1)',
            amarelo: 'rgba(255, 206, 86, 1)',
            verde: 'rgba(75, 192, 192, 1)',
            roxo: 'rgba(153, 102, 255, 1)',
            laranja: 'rgba(255, 159, 64, 1)',
        };
    }
}
