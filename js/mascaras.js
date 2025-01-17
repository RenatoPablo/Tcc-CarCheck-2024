
        function mascaraCPF(input) {
            let cpf = input.value;
            
            // Remove qualquer coisa que não seja número
            cpf = cpf.replace(/\D/g, "");
            
            // Adiciona pontos e traço
            cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
            cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2");
            cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            
            input.value = cpf;
        }

        function mascaraRG(input) {
            let rg = input.value;
        
            // Remove qualquer coisa que não seja número ou 'X' no final
            rg = rg.replace(/[^0-9Xx]/g, ""); // Permite números e 'X'
        
            // Adiciona pontos e traço
            rg = rg.replace(/(\d{2})(\d)/, "$1.$2");
            rg = rg.replace(/(\d{3})(\d)/, "$1.$2");
            rg = rg.replace(/(\d{3})([\dXx])$/, "$1-$2"); // Permite o último caractere ser 'X'
        
            input.value = rg.toUpperCase(); // Converte o 'x' minúsculo para 'X' maiúsculo, se for o caso
        }
        
        

        function mascaraCNPJ(input) {
            let cnpj = input.value;
        
            // Remove qualquer coisa que não seja número
            cnpj = cnpj.replace(/\D/g, "");
            
            // Limita o CNPJ a no máximo 14 dígitos
            if (cnpj.length > 14) {
                cnpj = cnpj.substring(0, 14);
            }
        
            // Adiciona pontos, barra e traço no formato: 00.000.000/0000-00
            cnpj = cnpj.replace(/^(\d{2})(\d)/, "$1.$2");
            cnpj = cnpj.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
            cnpj = cnpj.replace(/\.(\d{3})(\d)/, ".$1/$2");
            cnpj = cnpj.replace(/(\d{4})(\d{2})$/, "$1-$2");
        
            input.value = cnpj;
        }
        
        function mascaraIE(input) {
            let ie = input.value;
        
            // Remove qualquer coisa que não seja número
            ie = ie.replace(/\D/g, "");
        
            // Adiciona pontos e traço (formato exemplo: 000.000.000.000)
            ie = ie.replace(/(\d{2})(\d)/, "$1.$2");
            ie = ie.replace(/(\d{3})(\d)/, "$1.$2");
            ie = ie.replace(/(\d{3})(\d{1,2})$/, "$1.$2");
        
            input.value = ie;
        }

        function mascaraTelefone(input) {
            let telefone = input.value;
        
            // Remove qualquer caractere que não seja número
            telefone = telefone.replace(/\D/g, "");
        
            // Aplica a máscara para telefone no formato (00) 00000-0000
            telefone = telefone.replace(/^(\d{2})(\d)/, "($1) $2"); // Coloca o DDD entre parênteses
            telefone = telefone.replace(/(\d{5})(\d{4})$/, "$1-$2"); // Coloca o traço no número principal
        
            input.value = telefone;
        }

        function mascaraPlacaVeiculo(input) {

            let value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');

            // Variáveis para armazenar letras e números separados
            let letters = '';
            let numbers = '';
        
            // Separar letras e números
            for (let char of value) {
                if (/[A-Z]/.test(char)) {
                    letters += char;
                } else if (/[0-9]/.test(char)) {
                    numbers += char;
                }
            }
        
            // Aplicar o formato com base na quantidade de letras e números
            if (letters.length >= 4) {
                // Formato AAA1A12
                if (numbers.length >= 3) {
                    input.value = `${letters.substring(0, 3)}${numbers[0]}${letters[3]}${numbers.substring(1, 3)}`;
                } else {
                    input.value = `${letters.substring(0, 3)}${letters[3]}${numbers}`;
                }
            } else if (letters.length === 3 && numbers.length >= 4) {
                // Formato AAA-1111
                input.value = `${letters.substring(0, 3)}-${numbers.substring(0, 4)}`;
            } else {
                // Se não corresponder a nenhum formato, apenas concatena
                input.value = letters + numbers;
            }
        

        }

        function mascaraValor(input) {
            let valor = input.value.replace(/\D/g, ''); // Remove qualquer caractere não numérico
            valor = (valor / 100).toFixed(2) + ''; // Divide por 100 para simular o formato decimal
            valor = valor.replace('.', ','); // Substitui ponto por vírgula
            valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Adiciona os pontos como separadores de milhar
            input.value = valor; // Atualiza o valor do input
        }
        
        
        
        
        