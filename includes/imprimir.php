<script>
    document.getElementById('botaoImprimir').addEventListener('click', function() {
        // Seleciona apenas o conteúdo da div que será impressa
        const conteudo = document.getElementById('conteudo').innerHTML;

        // Cria uma nova janela para impressão
        const janelaImpressao = window.open('', '', 'width=800,height=600');
        janelaImpressao.document.write(`
            <html>
                <head>
                    <title>Impressão</title>
                    <style>
                        /* Reset básico */
                        * {
                            margin: 0;
                            padding: 0;
                            box-sizing: border-box;
                            font-family: Arial, sans-serif;
                        }

                        /* Container da ficha */
                        .container-relatorio {
                            background-color: #ffffff;
                            display: flex;
                            flex-direction: column;
                            gap: 20px;
                            border-radius: 8px;
                            padding: 20px;
                            width: 100%; /* Garante que ocupe toda a largura disponível */
                            max-width: 1200px; /* Define um limite maior */
                            min-width: 900px; /* Define uma largura mínima */
                            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
                            margin: 0 auto; /* Centraliza na tela */
                        }

                        .titulo-relatorio {
                            font-size: 26px;
                            color: #003366;
                            text-align: center;
                            margin-bottom: 20px;
                        }

                        /* Seções de dados */
                        .informacoes-gerais h2,
                        .informacoes-servicos h2,
                        .informacoes-pecas h2,
                        .informacoes-totais h2 {
                            font-size: 18px;
                            color: #333333;
                            margin-bottom: 8px;
                            border-bottom: 1px solid #ddd;
                            padding-bottom: 5px;
                        }

                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 10px;
                        }

                        table th, table td {
                            padding: 10px;
                            border: 1px solid #dddddd;
                            text-align: left;
                            font-size: 14px;
                        }

                        table th {
                            background-color: #f0f0f0;
                            font-weight: bold;
                        }

                        .dados-totais {
                            margin-top: 20px;
                            padding-top: 10px;
                            border-top: 1px solid #ddd;
                        }

                        .valor-final {
                            font-size: 18px;
                            font-weight: bold;
                            color: #28a745;
                            text-align: right;
                        }
                            @media print {
                        .btn-imprimir {
                            display: none;
                        }
                    }

                        /* Reset Básico */
                        * {
                            margin: 0;
                            padding: 0;
                            box-sizing: border-box;
                        }

                        body {
                            font-family: 'Roboto', sans-serif;
                            background: #fff; /* Fundo branco para impressão */
                            color: #333;
                            line-height: 1.6;
                            padding: 20px;
                        }

                        /* Container Principal */
                        .container {
                            max-width: 1000px; /* Reduzido para impressão */
                            margin: 0 auto;
                            padding: 20px;
                            background: #fff; /* Fundo branco */
                            border-radius: 0; /* Sem bordas arredondadas para impressão */
                            box-shadow: none; /* Sem sombra na impressão */
                            border: none; /* Sem bordas */
                        }

                        /* Título da Página */
                        .page-title {
                            text-align: center;
                            font-size: 28px;
                            color: #0D3587;
                            margin-bottom: 30px;
                            font-weight: bold;
                            text-transform: uppercase;
                            letter-spacing: 1px;
                        }

                        /* Visão Geral do Veículo */
                        .vehicle-overview {
                            display: flex;
                            justify-content: space-between;
                            gap: 20px;
                            margin-bottom: 30px;
                            flex-wrap: wrap;
                        }

                        .vehicle-info, .last-maintenance {
                            flex: 1;
                            background: #fff; /* Fundo branco */
                            padding: 20px;
                            border-radius: 0; /* Sem bordas arredondadas */
                            border: 1px solid #ddd; /* Bordas suaves para separação */
                            box-shadow: none; /* Sem sombras */
                        }

                        .vehicle-title {
                            font-size: 22px;
                            color: #0D3587;
                            font-weight: bold;
                            margin-bottom: 15px;
                        }

                        .vehicle-info p, .last-maintenance p {
                            font-size: 14px;
                            color: #555;
                            margin-bottom: 10px;
                            line-height: 1.5;
                        }

                        .last-maintenance h3 {
                            font-size: 18px;
                            color: #0D3587;
                            margin-bottom: 10px;
                            font-weight: bold;
                        }

                        /* Histórico */
                        .vehicle-history, .vehicle-parts-history {
                            margin-bottom: 30px;
                        }

                        .vehicle-history h3, .vehicle-parts-history h3 {
                            font-size: 20px;
                            color: #0D3587;
                            margin-bottom: 15px;
                            font-weight: bold;
                            text-align: left;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                        }

                        .history-table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-bottom: 20px;
                            overflow: hidden;
                            border-radius: 0; /* Sem bordas arredondadas */
                            background: #fff;
                            box-shadow: none; /* Sem sombras */
                        }

                        .history-table th, .history-table td {
                            padding: 12px;
                            text-align: left;
                            border: 1px solid #ddd;
                            font-size: 14px;
                            color: #555;
                        }

                        .history-table th {
                            background: #f1f5f9;
                            color: #0D3587;
                            font-weight: bold;
                            text-transform: uppercase;
                        }

                        .history-table tr:nth-child(even) td {
                            background: #f9f9f9;
                        }

                        /* Botões */
                        .actions {
                            display: none; /* Esconde botões na impressão */
                        }

                        /* Responsividade */
                        @media (max-width: 768px) {
                            .vehicle-overview {
                                flex-direction: column;
                            }

                            .vehicle-info, .last-maintenance {
                                width: 100%;
                            }

                            .history-table th, .history-table td {
                                font-size: 12px;
                            }
                        }

                        /* Estilos específicos para impressão */
                        @media print {
                            body {
                                padding: 0;
                                color: #000;
                            }

                            .container {
                                max-width: 100%;
                                padding: 0;
                                border: none;
                                box-shadow: none;
                            }

                            .page-title {
                                font-size: 24px;
                                margin-bottom: 20px;
                            }

                            .vehicle-info, .last-maintenance {
                                padding: 15px;
                                border: 1px solid #000;
                            }

                            .history-table th, .history-table td {
                                font-size: 12px;
                                padding: 8px;
                            }

                            .actions {
                                display: none; /* Garante que os botões não sejam exibidos */
                            }
                        }


                            
                    </style>
                </head>
                <body>${conteudo}</body>
            </html>
        `);
        janelaImpressao.document.close(); // Fecha o fluxo de escrita

        // Inicia o processo de impressão
        janelaImpressao.onload = function() {
            janelaImpressao.print();

            // Configura o fechamento da janela imediatamente após a impressão
            janelaImpressao.onafterprint = function() {
                janelaImpressao.close();
            };

            // Força o fechamento após 2 segundos (se necessário)
            setTimeout(() => {
                if (!janelaImpressao.closed) {
                    janelaImpressao.close();
                }
            }, 1000); // Tempo reduzido para 2 segundos
        };
    });
</script>