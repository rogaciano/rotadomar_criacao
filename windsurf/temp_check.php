$produtosComEspaco = App\Models\Produto::whereRaw('referencia != TRIM(referencia)')->get(['id', 'referencia']);
echo "Corrigindo produtos com espaços na referência:\n";
foreach($produtosComEspaco as $p) {
    $refTrimmed = trim($p->referencia);
    $existeOutro = App\Models\Produto::where('referencia', $refTrimmed)->where('id', '!=', $p->id)->first();
    if($existeOutro) {
        $novaRef = $refTrimmed . '-DUP';
        $p->referencia = $novaRef;
        $p->save();
        echo "ID: {$p->id} - Referência alterada para: [{$novaRef}]\n";
    } else {
        $p->referencia = $refTrimmed;
        $p->save();
        echo "ID: {$p->id} - Espaços removidos: [{$refTrimmed}]\n";
    }
}
echo "Correção concluída!\n";