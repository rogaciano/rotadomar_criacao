<?php
/**
 * Gera ícones PNG para a PWA a partir do logo da empresa
 * Executar no servidor: php /var/www/html/motorista-app/icons/generate-icons.php
 */

$logoPath = '/var/www/html/criacao/public/logos/rota_logo.png';

// Fallback: procurar qualquer logo disponível
if (!file_exists($logoPath)) {
    $logos = glob('/var/www/html/criacao/public/logos/*rota*logo*.png');
    if (!empty($logos)) {
        $logoPath = $logos[0];
    } else {
        // Gerar ícone simples com texto
        generateSimpleIcon(192, __DIR__ . '/icon-192.png');
        generateSimpleIcon(512, __DIR__ . '/icon-512.png');
        echo "Ícones gerados com fallback (sem logo encontrado).\n";
        exit;
    }
}

echo "Usando logo: $logoPath\n";

$source = imagecreatefrompng($logoPath);
if (!$source) {
    echo "Erro ao abrir logo. Gerando ícone simples...\n";
    generateSimpleIcon(192, __DIR__ . '/icon-192.png');
    generateSimpleIcon(512, __DIR__ . '/icon-512.png');
    exit;
}

$srcW = imagesx($source);
$srcH = imagesy($source);

foreach ([192, 512] as $size) {
    $icon = imagecreatetruecolor($size, $size);

    // Fundo indigo (#4f46e5)
    $bg = imagecolorallocate($icon, 79, 70, 229);
    imagefill($icon, 0, 0, $bg);

    // Manter alpha
    imagealphablending($icon, true);
    imagesavealpha($icon, true);

    // Redimensionar logo centralizado com padding
    $padding = (int)($size * 0.15);
    $targetSize = $size - ($padding * 2);

    $scale = min($targetSize / $srcW, $targetSize / $srcH);
    $newW = (int)($srcW * $scale);
    $newH = (int)($srcH * $scale);
    $x = (int)(($size - $newW) / 2);
    $y = (int)(($size - $newH) / 2);

    imagecopyresampled($icon, $source, $x, $y, 0, 0, $newW, $newH, $srcW, $srcH);

    $outPath = __DIR__ . "/icon-{$size}.png";
    imagepng($icon, $outPath);
    imagedestroy($icon);
    echo "Gerado: $outPath ({$size}x{$size})\n";
}

imagedestroy($source);
echo "Pronto!\n";

function generateSimpleIcon($size, $path) {
    $icon = imagecreatetruecolor($size, $size);
    $bg = imagecolorallocate($icon, 79, 70, 229);
    $white = imagecolorallocate($icon, 255, 255, 255);
    imagefill($icon, 0, 0, $bg);

    // Desenhar um caminhão simples
    $cx = $size / 2;
    $cy = $size / 2;
    $s = $size * 0.3;

    // Corpo do caminhão
    imagefilledrectangle($icon, (int)($cx - $s), (int)($cy - $s * 0.5), (int)($cx + $s * 0.3), (int)($cy + $s * 0.5), $white);
    // Cabine
    imagefilledrectangle($icon, (int)($cx + $s * 0.3), (int)($cy - $s * 0.2), (int)($cx + $s * 0.7), (int)($cy + $s * 0.5), $white);

    // Rodas
    $wheelR = (int)($s * 0.2);
    imagefilledellipse($icon, (int)($cx - $s * 0.5), (int)($cy + $s * 0.5 + $wheelR / 2), $wheelR, $wheelR, $white);
    imagefilledellipse($icon, (int)($cx + $s * 0.5), (int)($cy + $s * 0.5 + $wheelR / 2), $wheelR, $wheelR, $white);

    // Texto
    $fontSize = (int)($size * 0.08);
    $text = "ROTA";
    $textWidth = imagefontwidth($fontSize) * strlen($text);
    imagestring($icon, 5, (int)($cx - $textWidth / 2), (int)($cy + $s), $text, $white);

    imagepng($icon, $path);
    imagedestroy($icon);
}
