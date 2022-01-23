<?php 

//Pegando os dados do formulários

$nome = $_POST['name'];
$idade = $_POST['idade'];
$quantidade = $_POST['username'];
$registro = $_POST['pass'];

//Tratando o arquivo prices.json

$json = file_get_contents("prices.json");
$data_price = json_decode($json);

//Tratando o arquivo plans.jason

$json = file_get_contents("plans.json");
$data2 = json_decode($json);

//Regra de procurar pelo registro existente

$meusplanos=array();

foreach ($data2 as $regs) {
    array_push($meusplanos, $regs->registro);
}

if(!in_array($registro, $meusplanos)){
    echo "plano não existe";
}else{

    //Gravando dados em beneficiários.json

    $data = array(
        "nome" => $nome,
        "idade" => $idade,
        "quantidadedeassociados" => $quantidade,
        "registro" => $registro
    );

    $arquivo = 'beneficiarios.json';
    $json = json_encode($data);
    $file = fopen(__DIR__ . '/' . $arquivo,'a');
    fwrite($file, $json);
    fclose($file);

    $codigofinal = 0;

    //Regra de escolher pelo código do plano a faixa de preço por idade

    foreach ($data2 as $regs) {
        if($registro==$regs->registro){
            $codigofinal=$regs->codigo;
            $nomeplano=$regs->nome;
        }
    }

    $precofaixa1=0;
    $precofaixa2=0;
    $precofaixa3=0;
    foreach ($data_price as $regs2){
        if ($codigofinal==$regs2->codigo){
            if($regs2->minimo_vidas <= $quantidade){

                $precofaixa1=$regs2->faixa1;
                $precofaixa2=$regs2->faixa2;
                $precofaixa3=$regs2->faixa3;
            }


        }
    }
    //Imprimir o resultado da proposta na tela

    if ($idade < 18){
        echo "Idade: " . $idade . "<br>";
        echo "Valor por pessoa: " . $precofaixa1 . "<br>";
        echo "Valor total: " . $precofaixa1*$quantidade . "<br>";
        $precofaixafinal = $precofaixa1;
        $precototal = $precofaixa1*$quantidade;

    }else if($idade > 40){
        echo "Idade: " . $idade . "<br>";
        echo "Valor por pessoa: " . $precofaixa3 . "<br>";
        echo "Valor total: " . $precofaixa3*$quantidade . "<br>";
        $precofaixafinal = $precofaixa3;
        $precototal = $precofaixa3*$quantidade;

    }else{
        echo "Idade: " . $idade . "<br>";
        echo "Valor por pessoa: " . $precofaixa2 . "<br>";
        echo "Valor total: " . $precofaixa2*$quantidade . "<br>";
        $precofaixafinal = $precofaixa2;
        $precototal = $precofaixa2*$quantidade;
    }

}

//Gravando os dados da proposta em proposta.json

$datafinal = array(
        "nome" => $nome,
        "idade" => $idade,
        "quantidade de associados" => $quantidade,
        "registro" => $registro,
        "valorporpessoa" => $precofaixafinal,
        "valortotal" => $precototal,
        "nomedoplano" => $nomeplano
    );

$arquivo = 'proposta.json';
$json = json_encode($datafinal);
$file = fopen(__DIR__ . '/' . $arquivo,'a');
fwrite($file, $json);
fclose($file);
var_dump($datafinal);
?>