<?php
class ContatoService
{
    // Atributos da classe
    public function cadastrarContatoService($contato)
    {
        // Verificar se os campos foram preenchidos
        $campo = $this->verificarCampo($contato->nome, "nome");
        if (!$campo['sucesso']) return $campo;
    
        // Validação para garantir que o nome completo tenha ao menos duas palavras e não seja muito curto
        if (strlen($contato->nome) < 5 || count(explode(' ', trim($contato->nome))) < 2) {
            return array(
                'mensagem' => "Por favor, insira o nome completo (ao menos um sobrenome).",
                'campo' => "#nome",
                'sucesso' => false
            );
        }
    
        $campo = $this->verificarCampo($contato->sobrenome, "apelido");
        if (!$campo['sucesso']) return $campo;
    
        // Validação para garantir que o email contém '@'
        if (!filter_var($contato->email, FILTER_VALIDATE_EMAIL)) {
            return array(
                'mensagem' => "E-mail inválido. Por favor, insira um e-mail válido.",
                'campo' => "#email",
                'sucesso' => false
            );
        }
    
        $campo = $this->verificarCampo($contato->email, "email");
        if (!$campo['sucesso']) return $campo;
    
        $campo = $this->verificarCampo($contato->senha, "senha");
        if (!$campo['sucesso']) return $campo;
    
        // Remove todos os caracteres não numéricos do telefone
        $telefoneNumerico = preg_replace('/\D/', '', $contato->telefone);
    
        // Validação de número de telefone: 10 ou 11 dígitos e não pode ser repetitivo
        if (!preg_match('/^\d{10,11}$/', $telefoneNumerico) || preg_match('/(\d)\1{9,10}/', $telefoneNumerico)) {
            return array(
                'mensagem' => "Número de telefone inválido. Por favor, insira um número de telefone válido.",
                'campo' => "#telefone",
                'sucesso' => false
            );
        }
    
        $campo = $this->verificarCampo($contato->telefone, "telefone");
        if (!$campo['sucesso']) return $campo;
    
        $resultado = $this->buscarContatoService($contato);
    
        // Verificar se o email já está em uso
        if ($resultado != null) {
            return array(
                "mensagem" => "Este e-mail já está sendo utilizado.",
                "campo" => "#email",
                "sucesso" => false
            );
        }
    
        // Criptografar a senha SHA256
        $contato->senha = $this->criptografarSHA256($contato->senha);
    
        include_once "ContatoDAO.php";
        $dao = new ContatoDAO();
        $cadastrou = $dao->cadastrarContatoDAO($contato);
    
        if ($cadastrou) {
            return array(
                'mensagem' => "Cadastro efetuado com sucesso!",
                'sucesso' => true
            );
        } else {
            return array(
                'mensagem' => "Erro ao efetuar o cadastro.",
                'campo' => "",
                'sucesso' => false
            );
        }
    }

    public function editarContatoService($contato)
    {
        // Verificar se os campos foram preenchidos
        $campo = $this->verificarCampo($contato->nome, "nome");
        if (!$campo['sucesso']) return $campo;

        $campo = $this->verificarCampo($contato->sobrenome, "sobrenome");
        if (!$campo['sucesso']) return $campo;

        $campo = $this->verificarCampo($contato->email, "email");
        if (!$campo['sucesso']) return $campo;

        $campo = $this->verificarCampo($contato->telefone, "telefone");
        if (!$campo['sucesso']) return $campo;

        $campo = $this->verificarCampo($contato->senha, "senha");
        if ($campo['sucesso']) $contato->senha = $this->criptografarSHA256($contato->senha);;



        include_once "ContatoDAO.php";
        $dao = new ContatoDAO();
        $cadastrou = $dao->editarContatoDAO($contato);

        if ($cadastrou) {
            return array (
                'mensagem' => "Cadastro efetuado com sucesso!",
                'sucesso' => true
            );
        } else {
            return array (
                'mensagem' => "Erro ao efetuar o cadastro.",
                'campo' => "",
                'sucesso' => false
            );
        }
    }

    public function atualizarContatoService($contato)
    {
        // Verificar se os campos foram preenchidos
        $campo = $this->verificarCampo($contato->id, "id");
        if (!$campo['sucesso']) return $campo;

        include_once "ContatoDAO.php";
        $dao = new ContatoDAO();
        $cadastrou = $dao->salvarFuncionarioDAO($contato);

        if ($cadastrou) {
            return array (
                'mensagem' => "Cadastro efetuado com sucesso!",
                'sucesso' => true
            );
        } else {
            return array (
                'mensagem' => "Erro ao efetuar o cadastro.",
                'campo' => "",
                'sucesso' => false
            );
        }
    }

    private function buscarContatoService($contato)
    {
        // incluir o arquivo contatoDAO
        include_once "ContatoDAO.php";

        $dao = new ContatoDAO();

        return $dao->buscarContatoDAO($contato);
    }

    public function efetuarLoginService($contato)
    {
        // Verificar se os campos foram preenchidos
        $campo = $this->verificarCampo($contato->email, "email");
        if (!$campo['sucesso']) return $campo;

        $campo = $this->verificarCampo($contato->senha, "senha");
        if (!$campo['sucesso']) return $campo;

        // Busca o contato pelo email informado
        $resultado = $this->buscarContatoService($contato);

        // se houver resultado na busca
        if ($resultado != NULL) {
            // Recebe os campos do DB
            $senhaDB = $resultado['senha'];

            // Criptografar a senha SHA256
            $contato->senha = $this->criptografarSHA256($contato->senha);

            if (strcmp($senhaDB, $contato->senha) == 0) {
                return array (
                    "mensagem" => "Login efetuado com sucesso!",
                    "resultado" => $resultado,
                    "sucesso" => true
                );
            } else {
                return array (
                    'mensagem' => "Cadastro ou senha inválidos.",
                    'campo' => "",
                    'sucesso' => false
                );
            }
        } else {
            return array (
                'mensagem' => "Cadastro ou senha inválidos.",
                'campo' => "",
                'sucesso' => false
            );
        }
    }

    private function buscarSenhaService($contato)
    {
        // incluir o arquivo contatoDAO
        include_once "ContatoDAO.php";

        $dao = new ContatoDAO();

        return $dao->buscarSenhaDAO($contato);
    }

    public function recuperarSenhaService($contato)
    {
        // Verificar se os campos foram preenchidos
        $campo = $this->verificarCampo($contato->email, "email");
        if (!$campo['sucesso']) return $campo;
    
        $campo = $this->verificarCampo($contato->nome, "nome");
        if (!$campo['sucesso']) return $campo;
    
        $campo = $this->verificarCampo($contato->sobrenome, "sobrenome");
        if (!$campo['sucesso']) return $campo;
    
        // Busca o contato pelo email, nome e apelido (sobrenome) informados
        $resultado = $this->buscarSenhaService($contato);

        if ($resultado != NULL) {
            return array(
                "mensagem" => "Validação realizada com sucesso!",
                "senha" => $resultado['senha'],
                "sucesso" => true
            );
        } else {
            return array(
                'mensagem' => "Dados inválidos.",
                'campo' => "",
                'sucesso' => false
            );
        }
    }

    private function criptografarSHA256($senhaInformada) 
    {
        $senhaNova = hash('sha256', $senhaInformada);

        $salt = hash('sha256', "Criptografa senha");
        $senhaNova = hash('sha256', $senhaNova.$salt);

        return $senhaNova;
    }

    private function verificarCampo($valorCampo, $nomeCampo)
    {
        // Verifica se o campo foi preenchido
        if (strcmp($valorCampo, "") == 0) {
            return array (
                'mensagem' => "Preencha o campo $nomeCampo",
                'campo' => "#$nomeCampo",
                'sucesso' => false
            );
        }
        return array (
            'sucesso' => true
        );
    }
}