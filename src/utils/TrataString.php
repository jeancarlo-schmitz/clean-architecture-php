<?php
/**
 * Created by PhpStorm.
 * User: jean.schmitz
 * Date: 18/09/2023
 * Time: 16:56
 */

 namespace Strolker\CleanArchitecture\utils;

class TrataString{

    var $array_tags_html = array("a", "b", "u", "i", "br", "s", "e", "p", "font", "font size[\=][0-9]+", "span", "center", "label", "hr", "div", "table", "tr", "td", "th", "tf", "footer", "header", "h[1-9]{1,2}", "html", "title", "budy", "ul", "ol", "li", "dl", "dt", "dd", "pre", "adress", "img");

    public function retiraAcentos($var){

        /* --> TRATAMENTO DA LETRA 'A' <-- */
        $var = str_replace("�", "A", $var);
        $var = str_replace("�", "A", $var);
        $var = str_replace("�", "A", $var);
        $var = str_replace("�", "A", $var);
        $var = str_replace("�", "A", $var);
        $var = str_replace("�", "a", $var);
        $var = str_replace("�", "a", $var);
        $var = str_replace("�", "a", $var);
        $var = str_replace("�", "a", $var);
        $var = str_replace("�", "a", $var);
        $var = str_replace("�", "a", $var);

        /* --> TRATAMENTO DA LETRA 'E' <-- */

        $var = str_replace("�", "E", $var);
        $var = str_replace("�", "E", $var);
        $var = str_replace("�", "E", $var);
        $var = str_replace("�", "E", $var);
        $var = str_replace("�", "e", $var);
        $var = str_replace("�", "e", $var);
        $var = str_replace("�", "e", $var);
        $var = str_replace("�", "e", $var);

        /* --> TRATAMENTO DA LETRA 'I' <-- */

        $var = str_replace("�", "I", $var);
        $var = str_replace("�", "I", $var);
        $var = str_replace("�", "I", $var);
        $var = str_replace("�", "I", $var);
        $var = str_replace("�", "i", $var);
        $var = str_replace("�", "i", $var);
        $var = str_replace("�", "i", $var);
        $var = str_replace("�", "i", $var);

        /* --> TRATAMENTO DA LETRA 'O' <-- */

        $var = str_replace("�", "O", $var);
        $var = str_replace("�", "O", $var);
        $var = str_replace("�", "O", $var);
        $var = str_replace("�", "O", $var);
        $var = str_replace("�", "O", $var);
        $var = str_replace("�", "o", $var);
        $var = str_replace("�", "o", $var);
        $var = str_replace("�", "o", $var);
        $var = str_replace("�", "o", $var);
        $var = str_replace("�", "o", $var);
        $var = str_replace("�", "o", $var);
        $var = str_replace("�", "o", $var);

        /* --> TRATAMENTO DA LETRA 'U' <-- */

        $var = str_replace("�", "U", $var);
        $var = str_replace("�", "U", $var);
        $var = str_replace("�", "U", $var);
        $var = str_replace("�", "U", $var);
        $var = str_replace("�", "u", $var);
        $var = str_replace("�", "u", $var);
        $var = str_replace("�", "u", $var);
        $var = str_replace("�", "u", $var);

        /* --> TRATAMENTO DA LETRA '�' <-- */

        $var = str_replace("�", "c", $var);
        $var = str_replace("�", "C", $var);

        $var = str_replace("�", "2", $var);
        $var = str_replace("�", "3", $var);

        $var = str_replace("�", "N", $var);
        $var = str_replace("�", "n", $var);
        $var = str_replace("�", "x", $var);

        $var = str_replace("�", "ae", $var);

        return $var;
    }

    public function retiraCaracteresEspeciais($var, $outrosCaracteresParaManter = ""){
        $var = preg_replace("/[^A-Za-z0-9�-��-����{$outrosCaracteresParaManter} ]/", " ", $var);
        return $var;
    }

    public function retiraCaracteresEspeciaisXML($var, $outrosCaracteresParaManter = ""){
        $var = preg_replace("/[^����-�����-���&@%��\?\-\{\[\}\]\+\:\;\,\.\\\r\\\n[:print:]{$outrosCaracteresParaManter}]/", " ", $var);
        return $var;
    }

    public function tudoMaiusculo($var){
        $var = strtoupper($var);
        return $var;
    }

    public function tudoMinusculo($var){
        $var = strtolower($var);
        return $var;
    }

    public function retiraTodosEspacos($var){
        $var = str_replace(" ", "", $var);
        return $var;
    }

    public function retiraEspacoComposto($var){
        $var = preg_replace("/[ ]+/", " ", $var);
        return $var;
    }

    public function retiraTags($var){
        $exp = "(" . implode(")|(", $this->array_tags_html) . ")";
        $var = preg_replace("�<( )*(/)?( )*($exp)( )*(/)?( )*>�i", "", $var);
        return $var;
    }

    public function getConteudoHtml($conteudo, $tag, $target, $somenteConteudo = false, $posicaoEmDiante = 1, $breakNaPosicao = -1, $retornaArray = false){

        //		echo htmlentities($conteudo);
        //		exit;
        //		$tag		 = "span";
        //		$target		 = "";
        //		$conteudo	 = "<span>teste</span><span>teste2</span><span>teste3</span>";

        $listaTagQuebrada                   = preg_split("/[<]{$tag}/imsU", $conteudo);
        $conteudoEncontrado                 = "";
        $conteudoEncontradoPorTagEncontrada = "";
        $conteudoEncontradoArray            = array();
        $acheiFimTag                        = false;
        $tagSimples                         = false;
        $contadorFechamentoTag              = 999;
        $contadorVezesEncontrado            = 0;

        if (stripos($tag, "input") !== false){
            $tagSimples = true;
        }

        $searchTarget = "";
        if (isset($target[0]) && $target[0] == "."){
            //class
            $target       = substr($target, 1);
            $searchTarget = "(class[=][\'\"]{0,1}(.{0,50})\b{$target}\b(.{0,50})[\'\"])";
        }
        else if (isset($target[0]) && $target[0] == "#"){
            //id
            $target       = substr($target, 1);
            $searchTarget = "(id[=][\'\"]{0,1}(.{0,50})\b{$target}\b(.{0,50})[\'\"])";
        }
        else{
            $searchTarget = $target;
        }

        /**
         * Removo a 1� posi��o porque no "explode" a 1� posi��o sempre � antes da tag que eu quero e � conte�do sem interesse
         */
        if (isset($listaTagQuebrada[0])){
            unset($listaTagQuebrada[0]);
        }
        //        dd(array_map("htmlentities", $listaTagQuebrada));
        //        exit;

        foreach ($listaTagQuebrada as $conteudoDaTag){

            $conteudoDaTag = $this->getConteudoHtml($conteudoDaTag, $tag, $tagSimples);

            if ($tagSimples){
                $conteudoDaTag = substr($conteudoDaTag, 0, strpos($conteudoDaTag, ">") + 1);
            }

            if (empty($searchTarget) || preg_match("/{$searchTarget}/imsU", $conteudoDaTag)){
                $contadorVezesEncontrado++;
                if ($contadorVezesEncontrado >= $posicaoEmDiante){
                    $conteudoEncontrado                 = $conteudoEncontrado . "<{$tag}" . $conteudoDaTag;
                    $conteudoEncontradoPorTagEncontrada = $conteudoEncontradoPorTagEncontrada . "<{$tag}" . $conteudoDaTag;
                    $contadorFechamentoTag              = 0;
                    $acheiFimTag                        = false;
                }
            }

            if (!empty($conteudoEncontrado) && !$acheiFimTag){
                if ($contadorFechamentoTag > 0){
                    $conteudoEncontrado                 = $conteudoEncontrado . "<{$tag}" . $conteudoDaTag;
                    $conteudoEncontradoPorTagEncontrada = $conteudoEncontradoPorTagEncontrada . "<{$tag}" . $conteudoDaTag;
                }
                if ($tagSimples || substr_count($conteudoDaTag, "</{$tag}>") > $contadorFechamentoTag){
                    $acheiFimTag = true;
                    if ($somenteConteudo){
                        $conteudoEncontradoPorTagEncontrada = strip_tags($conteudoEncontradoPorTagEncontrada);
                        $conteudoEncontradoPorTagEncontrada = trim($conteudoEncontradoPorTagEncontrada);
                        $conteudoEncontradoArray[]          = $conteudoEncontradoPorTagEncontrada;
                    }
                    else{
                        $conteudoEncontradoArray[] = $conteudoEncontradoPorTagEncontrada;
                    }
                    $conteudoEncontradoPorTagEncontrada = "";
                    if ($breakNaPosicao >= 0){
                        if ($contadorVezesEncontrado == $breakNaPosicao){
                            break;
                        }
                    }
                }
                else{
                    $contadorFechamentoTag++;
                    $contadorFechamentoTag = $contadorFechamentoTag - substr_count($conteudoDaTag, "</{$tag}>");
                }
            }
        }

        if ($somenteConteudo){
            $conteudoEncontrado = strip_tags($conteudoEncontrado);
            $conteudoEncontrado = trim($conteudoEncontrado);
        }

        //		echo "<hr>";
        //		echo "<hr>";
        //		echo "<hr>";
        //		echo $conteudoEncontrado;
        //		echo "<hr>";
        //		echo "<hr>";
        //		echo "<hr>";

        if ($retornaArray){
            return $conteudoEncontradoArray;
        }
        else{
            return $conteudoEncontrado;
        }
    }

    public function retiraCgi($var){
        $var = str_replace("&quot;", " ", $var);
        $var = str_replace("&gt;", " ", $var);
        $var = str_replace("&nbsp;", " ", $var);
        $var = str_replace("&amp;", "&", $var);

        $var = str_replace("&Quot;", " ", $var);
        $var = str_replace("&Gt;", " ", $var);
        $var = str_replace("&Nbsp;", " ", $var);
        $var = str_replace("&Amp;", "&", $var);

        $var = str_replace("&NBSP;", " ", $var);
        $var = str_replace("&AMP;", "&", $var);
        $var = str_replace("&QUOT;", " ", $var);
        $var = str_replace("&GT;", " ", $var);
        return $var;
    }

    public function retiraQuebraLinha($var){
        $var = str_replace("\t", " ", $var);
        $var = str_replace("\r", " ", $var);
        $var = str_replace("\n", " ", $var);
        $var = str_replace("\r\n", " ", $var);
        return $var;
    }

    public function primeiraMaiuscula($var){
        $var = $this->tudoMinusculo($var);
        $var = ucwords($var);
        $var = str_replace(" Da ", " da ", $var);
        $var = str_replace(" Das ", " das ", $var);
        $var = str_replace(" Do ", " do ", $var);
        $var = str_replace(" Dos ", " dos ", $var);
        $var = str_replace(" De ", " de ", $var);
        return $var;
    }

    public function __construct(){

    }

    public function trataNomeJob($var){
        $var = $this->retiraAcentos($var);
        $var = $this->retiraQuebraLinha($var);
        $var = $this->retiraTags($var);

        $var = $this->retiraCgi($var);
        $var = $this->retiraEspacoComposto($var);
        $var = $this->tudoMinusculo($var);

        $var = trim($var);
        $var = str_replace(" ", "_", $var);

        return $var;
    }

    public function trataString($var, $sem_espacos = false, $carac_especiais = true, $outrosCaracteresParaManter = ""){
        $var = $this->retiraAcentos($var);
        $var = $this->retiraQuebraLinha($var);
        $var = $this->retiraTags($var);

        $var = $this->retiraCgi($var);
        if ($carac_especiais){
            $var = $this->retiraCaracteresEspeciais($var, $outrosCaracteresParaManter);
        }
        $var = $this->tudoMinusculo($var);
        if ($sem_espacos){
            $var = $this->retiraTodosEspacos($var);
        }
        else{
            $var = $this->retiraEspacoComposto($var);
            $var = trim($var);
        }
        return $var;
    }

    public function trataStringGrifagem($var){
        $var = $this->trataString($var);
        $var = str_replace(" ", "�", $var);
        return $var;
    }

    public function trataVariante($var, $sem_espacos = false, $carac_especiais = true, $outrosCaracteresParaManter = ""){
        return $this->trataString($var, $sem_espacos, $carac_especiais, $outrosCaracteresParaManter);
    }

    /**
     * M�todo criado exclusivamente para o tratamento do conte�do da
     * publica��o para o arquivo TXT do cliente Rocha Calderon.
     *
     * @param string $texto
     * @return string
     */
    public function trataConteudoPublicacaoRochaCalderon($texto){
        $aux_quebra_linha_conteudo = "\\r\\n";
        $pub_conteudo_trat         = trim($texto);
        $pub_conteudo_trat         = str_replace("�", "c", $pub_conteudo_trat);
        $pub_conteudo_trat         = str_replace("\r\n", $aux_quebra_linha_conteudo, $pub_conteudo_trat);
        $pub_conteudo_trat         = str_replace("\r", $aux_quebra_linha_conteudo, $pub_conteudo_trat);
        $pub_conteudo_trat         = str_replace("\n", $aux_quebra_linha_conteudo, $pub_conteudo_trat);
        $pub_conteudo_trat         = str_replace("<br />", $aux_quebra_linha_conteudo, $pub_conteudo_trat);
        return $pub_conteudo_trat;
    }

    /**
     * Trata o conteudo exclusivamente para escritas
     * @param string $string
     * @return string
     * */
    public function trataConteudoEscrita($string){
        $string = str_replace('----------------------------------------------', "\n ---------------------------------------------- \n", $string);
        $string = str_replace("\r\n", "<br />", $string);
        $string = str_replace("\n", "<br />", $string);
        $string = str_replace("\r", "<br />", $string);
        $string = str_replace("\line", "<br />", $string);
        $string = str_replace("\newline", "<br />", $string);
        $string = str_replace("<br>", "<br />", $string);
        $string = str_replace("<br />", "\r\n", $string);
        return $string;
    }

    /**
     * Trata o conteudo exclusivamente para o csv
     * @param string $string
     * @return string
     * */
    public function trataConteudoEscritaCsv($string, $tiraQuebraDeLinha = true){
        $string = str_replace("\t", " ", $string);
        $string = str_replace(";", " ", $string);
        if ($tiraQuebraDeLinha){
            $string = str_replace("\r\n", " ", $string);
        }
        $string = preg_replace("/[ ]+/", " ", $string);
        $string = str_replace("�", "C", $string);
        return $string;
    }

    /**
     * Em 07/04/2011 as 18:09h por Jonathan Fabra Gomez:
     *  - Fun��o para retirar caractere da quebra de linha
     *    Ex: 1� linha e 3� linha tem tra�os na quebra de linha.
     * 		� assim:
     *    		1A. VARA CIVEL DE JOAO PESSOA NF 023/11 (INTI-
    MACAO: ART. 236 DO CPC).
    00001 Processo: 2001997074750-3-BUSCA E APRE-
    ENSAO AUTOR: BANCO DIBENS S/A ADV:
    MARIA CAROLINA DE G C ALVES DESOUZA,
    FABIO HENRIQUE CAETANO, DRA. MARIA
    LUCILIA GOMES. REU: JULIA FERREIRA DA
    SILVA Sentenca: Processo extinto Art 267 CPC
    Fica assim:
    1A. VARA CIVEL DE JOAO PESSOA NF 023/11 (INTI
    MACAO: ART. 236 DO CPC).
    00001 Processo: 2001997074750-3-BUSCA E APRE
    ENSAO AUTOR: BANCO DIBENS S/A ADV:
    MARIA CAROLINA DE G C ALVES DESOUZA,
    FABIO HENRIQUE CAETANO, DRA. MARIA
    LUCILIA GOMES. REU: JULIA FERREIRA DA
    SILVA Sentenca: Processo extinto Art 267 CPC
     *
     * O parametro $replacement por default � "\$2" porque isso no pregmatch retorna o sgundo
     * grupo encontrado, que no caso � a quebra de linha.
     * */
    public function retiraCharNaQuebraDeLinha($string, $char = "-", $replacement = "\$2"){
        $string = preg_replace("/([{$char}]+[\t ]*)([\r\n]+|\<[BbrR][ ]*[\/]+\>)/", $replacement, $string);
        return $string;
    }

    /**
     * Copiado do REMESSA - BRADESCO  (EXPM_auxiliar.php)
     * RETIRA TODO O LIXO DA PUBLICA��O E RETORNA UM ARRAY COM OS POSS�VEIS N�MEROS DE PROCESSO DA PUBLICA��O
     *
     * @param  String $pub
     * @return Array
     */
    function trata_conteudo_para_num_processo($pub){

        $array_excluir_tratamento   = array();
        $array_excluir_tratamento[] = "lei";
        $array_excluir_tratamento[] = "portaria";
        $array_excluir_tratamento[] = "agencia";
        $array_excluir_tratamento[] = "agancia";
        $array_excluir_tratamento[] = "decreto";
        $array_excluir_tratamento[] = "artigo";
        $array_excluir_tratamento[] = "p";
        $array_excluir_tratamento[] = "art";
        $array_excluir_tratamento[] = "arts";
        $array_excluir_tratamento[] = "relacao";
        $array_excluir_tratamento[] = "fls";
        $array_excluir_tratamento[] = "voto";

        $array_excluir_mes_tratamento   = array();
        $array_excluir_mes_tratamento[] = "janeiro";
        $array_excluir_mes_tratamento[] = "fevereiro";
        $array_excluir_mes_tratamento[] = "marco";
        $array_excluir_mes_tratamento[] = "abril";
        $array_excluir_mes_tratamento[] = "maio";
        $array_excluir_mes_tratamento[] = "junho";
        $array_excluir_mes_tratamento[] = "julho";
        $array_excluir_mes_tratamento[] = "agosto";
        $array_excluir_mes_tratamento[] = "setembro";
        $array_excluir_mes_tratamento[] = "outubro";
        $array_excluir_mes_tratamento[] = "novembro";
        $array_excluir_mes_tratamento[] = "dezembro";
        $array_excluir_mes_tratamento[] = "anos";
        $array_excluir_mes_tratamento[] = "codigo civil";

        $pub_original = $pub;
        $pub          = preg_replace("/[0-9]{1,}[��]{1}/", " ", $pub); //substitui "12�" por espaco
        $pub          = $this->retiraAcentos($pub);
        $pub          = $this->tudoMinusculo($pub);
        $pub          = str_replace("\t", " ", $pub);

        /**
         * Retira a quebra do numero de processo e depois
         * tira o resto das quebras de linha.
         * Feito porque quando vinha o exemplo 01, nao pegava o numero de processo
         * correto.
         *
         * Ex 01:
         * 0003102-53.2010.8.26.0269; Apela��o; Comarca: Itapetininga; Vara: 1�. Vara C�vel; N� origem: 269.01.2010.003102-
        8/000000-000; Assunto: Expurgos Inflacion�rios / Planos Econ�micos; Apelante: Banco Bradesco S/A; Advogado: RODRIGO
         *
         * O Ex 01, depois de tratado a quebra de linha ficava na parte do numero separado com espa�o.
         * "... N� origem: 269.01.2010.003102- 8/000000-000; Assunto: ..."
         *
         * Att Jonathan Fabra Gomez (Joca) em 24/01/2011
         *
         * */
        //	$pub = str_replace("\r"," ",$pub);
        //	$pub = str_replace("\n"," ",$pub);
        //	$pub = str_replace("\r\n"," ",$pub);
        //	$pub = nl2br($pub);
        //	$pub = str_replace("<br />"," ",$pub);
        $pub = preg_replace("/[\r\n]+/", "�", $pub);
        $pub = nl2br($pub);
        $pub = preg_replace("/[<br[ ]?[\/]?>]+/", "�", $pub);
        $pub = preg_replace("/([0-9]{3,})([\-\/][�])([0-9])/", '${1}${3}', $pub);

        $pub = trim(preg_replace("/[ ]{2,}/", " ", $pub));

        $pub = preg_replace("/((" . implode(")|(", $array_excluir_tratamento) . "))( )?[n]?[\.]?[o]?[\.]?[ ]?[0-9\/\. ]{1,13}/", " ", $pub); //substitui lei por espaco "lei no. 25.745 / 2005"
        $pub = trim(preg_replace("/[ ]{2,}/", " ", $pub));

        $pub = preg_replace("/((" . implode(")|(", $array_excluir_mes_tratamento) . "))[ ]{0,}(de|\/)[ ]{0,}[0-9]{4}([ ]{0,}e[ ]{0,}[0-9]{4})?/", " ", $pub); //substitui marco de 1990 e outras variacoes
        $pub = trim(preg_replace("/[ ]{2,}/", " ", $pub));

        $pub = preg_replace("/(\\$)[0-9\. ]{1,16}/", " ", $pub); //substitui "$ 999.999.999,00" por espaco
        $pub = trim(preg_replace("/[ ]{2,}/", " ", $pub));

        $pub = preg_replace("/\b([0-3]?[0-9][\/][0-3]?[0-9][\/]([0-9]{2}|[0-9]{4}))\b/", " ", $pub); //substitui "xx/xx/xxxx" por espaco
        $pub = trim(preg_replace("/[ ]{2,}/", " ", $pub));

//		echo wordwrap($pub, 100, "<br>");

        $pub = preg_replace("/\b(oab[\/\\�\-\: ]{0,6}([a-z]{2})?[\/\\�\-\: ]{0,6}[0-9\.]+)/", " ", $pub); //substitui "OAB/BA 912 | OAB 912" por espaco
        $pub = trim(preg_replace("/[ ]{2,}/", " ", $pub));

//		echo "<hr>";
//		echo wordwrap($pub, 100, "<br>");

        $pub = preg_replace("/[^0-9\\ \.\/\-]+/", " ", $pub); //deixa so numeros, pontos barras tracos e espacos
        $pub = trim(preg_replace("/[ ]{2,}/", " ", $pub));

        $pub = preg_replace("/[^0-9\-\.]{1}[0-9]{2}[\.]{0,1}[0-9]{3}[\-][0-9]{3}[^0-9\-\.]{1}/", " ", $pub); //substitui cep por espaco
        $pub = trim(preg_replace("/[ ]{2,}/", " ", $pub));

        $pub = preg_replace("/[\\\.\/\-]+/", "", $pub); //deixa so numeros e espacos
        $pub = trim(preg_replace("/[ ]{2,}/", " ", $pub));


        $pub2 = array();
        $pub3 = array();
        if (!empty($pub)){
            $pub2 = explode(" ", $pub);
            //nao retorna numeros menores que 3 caracteres
            foreach ($pub2 as $i => $pub_aux){
                if (strlen($pub_aux) > 2){
                    $pub3[$pub_aux] = $pub_aux;
                }
            }
        }

        return $pub3;
    }

    function retiraZerosDasExtremidades($var, $esquerda = true, $direita = true){
        $expressao = array();
        if ($esquerda){
            $expressao[] = "(^([0]+))";
        }
        if ($direita){
            $expressao[] = "(([0]+)$)";
        }
        if (!empty($expressao)){
            $expressao = implode("|", $expressao);
            $var       = preg_replace("/{$expressao}/", "", $var);
        }
        return $var;
    }

    function preencheCharExtremidade($string, $char, $tamanhoMaximo, $esquerdaDireita = "E"){
        while (strlen($string) < $tamanhoMaximo){
            if ($esquerdaDireita == "E"){
                $string = $char . $string;
            }
            else if ($esquerdaDireita == "D"){
                $string = $string . $char;
            }
            else{
                $string = $char . $string;
            }
        }
        return $string;
    }

    /* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
    /* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */

    function retornaNovoValueEntreChar($value, $CHAR){
        $novo_value  = "";
        $grava       = true;
        $array       = array();
        $array["/"]  = "/";
        $array["\\"] = "\\";
        $array["]"]  = "]";
        $array["}"]  = "}";
        for ($i = 0; $i < strlen($value); $i++){
            $char     = $value[$i];
            $ja_gravo = false;
            if ($char == "["){
                $grava = false;
                $novo_value .= $CHAR . $char;
            }
            else if ($char == "{"){
                $grava = false;
            }
            else if ($char == "]"){
                $grava = true;
            }
            else if ($char == "}"){
                $grava    = true;
                $ja_gravo = true;
                $novo_value .= $char . $CHAR;
            }

            if ($grava){
                if ($char == "(" || $char == ")" || $char == "."){
                    $novo_value .= "[" . $char . "]";
                }
                else if ($i == 0 || isset($array[$char])){
                    if (!$ja_gravo){
                        $novo_value .= $char;
                    }
                }
                else{
                    $novo_value .= $CHAR . $char;
                }
            }
            else{
                $novo_value .= $char;
            }
        }
        return $novo_value;
    }

    function insereCharEntreChar($FONTE, $CHAR = "(?:[ �-]*)"){
        if (is_array($FONTE)){
            foreach ($FONTE as $x => $value){
                $FONTE[$x] = $this->retornaNovoValueEntreChar($value, $CHAR);
            }
        }
        else{
            $FONTE = $this->retornaNovoValueEntreChar($FONTE, $CHAR);
        }
        return $FONTE;
    }

    /* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */
    /* :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::: */

    /**
     * Fun��o para grifar uma lista de palavras(variante/numeros...) em um conteudo.
     * @param STRING onde deve ser grifado -> $conteudo
     * @param ARRAY que tem as varia��es -> $arrayGrifagem
     * @param BOOLEAN pra dizer se grifa somente uma variante -> $grifaSomenteUmaVariante
     * @param INT para dizer quantas vezes deve grifar -> $totalGrifaOcorrencias
     * @return STRING $conteudo GRIFADO
     *
     * @author Jonathan Fabra Gomez
     * @version 2012.12.11.1
     */
    public function grifaVariantesNoConteudo($conteudo, $arrayGrifagem, $grifaSomenteUmaVariante = false, $totalGrifaOcorrencias = 20, $ehEspacada = false){
        $arrayGrifagem = array_flip($arrayGrifagem);
        $arrayGrifagem = array_flip($arrayGrifagem);

        $conteudoTratado = preg_replace("/[^0-9a-zA-Z�-��-��Ǫ��]/", "�", strtolower($conteudo));
        $conteudoTratado = strtr($conteudoTratado, "����������������������窺�", "aeiouaeiouaoaeiouaeioucaoo");

        foreach ($arrayGrifagem as $x => $variante){

            $contador        = 0;
            $varianteTratada = preg_replace("/[^0-9a-zA-Z�-��-��Ǫ��]/", "", strtolower($variante));
            $varianteTratada = strtr($varianteTratada, "����������������������窺�", "aeiouaeiouaoaeiouaeioucaoo");
            $varianteTratada = str_split($varianteTratada);
            $varianteTratada = implode("[�]*", $varianteTratada);

            if ($ehEspacada){
                $varianteTratada = "[�]" . $varianteTratada . "[�]";
            }

            while (
                /*             * */ preg_match("/$varianteTratada/", $conteudoTratado, $saida, PREG_OFFSET_CAPTURE) && $contador < $totalGrifaOcorrencias && $contadorMaximo < 50 // por seguran�a
            ){
                $contador++;
                $contadorMaximo++;

                $posicaoEncontrada  = $saida[0][1];
                $varianteEncontrada = $saida[0][0];


                if ($ehEspacada){
                    // Ve se o 1� char � "�" que significa que � espa�ada e remove o "�" porque ja achou
                    if ($varianteEncontrada[0] == "�"){
                        $posicaoEncontrada++;
                        $varianteEncontrada = substr($varianteEncontrada, 1);
                    }
                    // Ve se o ultimo char � "�" que significa que � espa�ada e remove o "�" porque ja achou
                    if ($varianteEncontrada[strlen($varianteEncontrada) - 1] == "�"){
                        $varianteEncontrada = substr($varianteEncontrada, 0, -1);
                    }
                }
                $tamanhoVarianteEncontrada = strlen($varianteEncontrada);


                if ($tamanhoVarianteEncontrada > 0){

                    /**
                     * grifa no conteudo original
                     * */
                    $conteudoAntesDaVariante  = substr($conteudo, 0, $posicaoEncontrada);
                    $conteudoDaVariante       = "<u><b>" . substr($conteudo, $posicaoEncontrada, $tamanhoVarianteEncontrada) . "</b></u>";
                    $conteudoDepoisDaVariante = substr($conteudo, $posicaoEncontrada + $tamanhoVarianteEncontrada);
                    $conteudo                 = $conteudoAntesDaVariante . $conteudoDaVariante . $conteudoDepoisDaVariante;

                    /**
                     * ajusta o conteudo tratado para coloca "lixo" no lugar da grifagem.
                     * assim o conteudo tratado fica do mesmo tamanho do original s� que ja tratado.
                     * */
                    $conteudoAntesDaVariante  = substr($conteudoTratado, 0, $posicaoEncontrada);
                    $conteudoDaVariante       = "######" . substr($conteudoTratado, $posicaoEncontrada, $tamanhoVarianteEncontrada) . "########";
                    $conteudoDepoisDaVariante = substr($conteudoTratado, $posicaoEncontrada + $tamanhoVarianteEncontrada);
                    $conteudoTratado          = $conteudoAntesDaVariante . $conteudoDaVariante . $conteudoDepoisDaVariante;

                    $replaceLixo = "";
                    for ($i = 1; $i <= $tamanhoVarianteEncontrada; $i++){
                        $replaceLixo .= "#";
                    }
                    /**
                     * remove do conteudo tratado o que foi encontrado porque na proxima intera��o
                     * nao encontrar o que ja foi encontrado.
                     * */
                    $conteudoTratado = preg_replace("/($varianteEncontrada)/", $replaceLixo, $conteudoTratado, 1);

                    //					pre($varianteEncontrada);
                    //					pre($replaceLixo);
                    //					pre($saida);
                    //					pre($conteudoTratado);
                    //					echo "<hr><hr><hr>";

                    if ($grifaSomenteUmaVariante){
                        break 2;
                    }
                }
            }
        }
        return $conteudo;
    }


    public function removeAcentuacaoDoArray($data){
        if(is_array($data)) {
            return $this->trataStringRecursivo($data);
        }else{
            return $this->retiraAcentos($data);
        }
    }

    public function trataStringRecursivo($data){
        foreach ($data as $key => $value){
            if(empty($value)){
                continue;
            }

            if(is_array($value)){
                $data[$key] = $this->trataStringRecursivo($value);
            }else{
                $data[$key] = $this->retiraAcentos($this->detectaEncoding($value));
            }
        }

        return $data;
    }

    public function detectaEncoding($value){
        if (mb_check_encoding($value, 'UTF-8') && !mb_check_encoding($value, 'ISO-8859-1')) {
            return utf8_decode($value);
        }

        return $value;
    }



    function escaparAspasSimples($string)
    {
        return str_replace("'", "\\'", $string);
    }

    function escaparDadosBancoDados($string)
    {
        $string = str_replace("\"", "", $string);
        $string = str_replace("'", "''", $string);

        return $string;
    }

    function tratamentoDadosArray($dados)
    {
        $dadosTratados = [];

        foreach ($dados as $chave => $valor) {
            if (is_array($valor)) {
                $dadosTratados[$chave] = $this->tratamentoDadosArray($valor);
            } else {
                $dadosTratados[$chave] = $this->escaparAspasSimples($valor);
            }
        }

        return $dadosTratados;
    }

    function tratamentoDadosArrayParaBancoDeDados($dados)
    {
        $dadosTratados = [];

        foreach ($dados as $chave => $valor) {
            if (is_array($valor)) {
                $dadosTratados[$chave] = $this->tratamentoDadosArrayParaBancoDeDados($valor);
            } else {
                $dadosTratados[$chave] = $this->escaparDadosBancoDados($valor);
            }
        }

        return $dadosTratados;
    }
}
?>