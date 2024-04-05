<?php
$paragraph = "Insert your paragraph"; // Paragraph with at least 30 words

$words = str_word_count($paragraph);
if ($words < 30) {
    throw new Exception("The paragraph must be at least 30 words long.");
}

$entropy = hash('sha256', $paragraph); // Hash the paragraph

$hash = hash('sha256', hex2bin($entropy));
$checksum = substr($hash, 0, 2);
$entropy_checksum = $entropy . $checksum;

$bits = '';
for ($i = 0; $i < strlen($entropy_checksum); $i += 2) {
    $bits .= str_pad(base_convert($entropy_checksum[$i] . $entropy_checksum[$i + 1], 16, 2), 8, '0', STR_PAD_LEFT);
}

$wordlist = file('english.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // Load the BIP39 English wordlist
$mnemonic = '';
for ($i = 0; $i < strlen($bits); $i += 11) {
    $index = bindec(substr($bits, $i, 11));
    $mnemonic .= $wordlist[$index] . ' ';
}

$mnemonic = trim($mnemonic); // Remove the trailing space
echo "BIP39 wallet: $mnemonic\n";
?>
