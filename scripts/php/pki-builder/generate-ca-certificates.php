<?php

$PkiDir = isset($argv[1]) ? $argv[1] : null;

if (empty($PkiDir)) {
    echo "Usage: php generate-ca-certificates.php <pki-dir>\n";
    exit(2);
}

if (!is_dir($PkiDir)) {
    echo "Error: $PkiDir is not a directory\n";
    exit(2);
}

if (substr($PkiDir, -1) === '/')
    $PkiDir = substr($PkiDir, 0, -1);

$DNRoot = [
    "countryName"            => "BR",
    "stateOrProvinceName"    => "SP",
    "localityName"           => "Jundiaí",
    "organizationName"       => "Joao",
    "commonName"             => "Joao Root CA",
];

$DNIntermediate = array_merge($DNRoot, ["commonName" => "Joao Intermediate CA"]);
$DNIssuing      = array_merge($DNRoot, ["commonName" => "Joao Issuing CA"]);

$KeyConfig = ["private_key_bits" => 4096,"private_key_type" => OPENSSL_KEYTYPE_RSA];

foreach (['root', 'intermediate', 'issuing'] as $CA) {

    @mkdir("$PkiDir/$CA/private", 0777, true);
    @mkdir("$PkiDir/$CA/certs", 0777, true);

}

$CNFFilePath = "$PkiDir/temp.cnf";

$CNFFileContent = "[ v3_ca ]
basicConstraints = critical, CA:true
keyUsage = critical, digitalSignature, cRLSign, keyCertSign

[ v3_intermediate_ca ]
basicConstraints = critical, CA:true, pathlen:1
keyUsage = critical, digitalSignature, cRLSign, keyCertSign

[ v3_issuing_ca ]
basicConstraints = critical, CA:true, pathlen:0
keyUsage = critical, digitalSignature, cRLSign, keyCertSign";

file_put_contents($CNFFilePath, $CNFFileContent);

$RootDuration = 7300; // 20 years
$RootKeyPassphrase = generatePassword();

if(empty($RootKeyPassphrase)) {
    echo "Error: Failed to generate root key passphrase\n";
    exit(1);
}

$RootPrivateKey = openssl_pkey_new($KeyConfig);
$RootCsr = openssl_csr_new($DNRoot, $RootPrivateKey, ["digest_alg" => "sha256"]);
$RootConfig = ["digest_alg" => "sha256", "x509_extensions" => "v3_ca", "config" => $CNFFilePath];
$RootCert = openssl_csr_sign($RootCsr, null, $RootPrivateKey, $RootDuration, $RootConfig);

openssl_pkey_export_to_file($RootPrivateKey, "$PkiDir/root/private/root_ca.key", $RootKeyPassphrase);
openssl_x509_export_to_file($RootCert, "$PkiDir/root/certs/root_ca.crt");

$IntermediateDuration = 3650; // 10 years

$IntermediateKeyPassphrase = generatePassword();

if(empty($IntermediateKeyPassphrase)) {
    echo "Error: Failed to generate intermediate key passphrase\n";
    exit(1);
}

$IntermediatePrivateKey = openssl_pkey_new($KeyConfig);
$IntermediateCsr = openssl_csr_new($DNIntermediate, $IntermediatePrivateKey, ["digest_alg" => "sha256"]);
$IntermediateConfig = ["digest_alg" => "sha256", "x509_extensions" => "v3_intermediate_ca", "config" => $CNFFilePath];
$IntermediateCert = openssl_csr_sign($IntermediateCsr, $RootCert, $RootPrivateKey, $IntermediateDuration, $IntermediateConfig);

openssl_pkey_export_to_file($IntermediatePrivateKey, "$PkiDir/intermediate/private/intermediate_ca.key", $IntermediateKeyPassphrase);
openssl_x509_export_to_file($IntermediateCert, "$PkiDir/intermediate/certs/intermediate_ca.crt");

$IssuingDuration = 1825; // 5 years

$IssuingKeyPassphrase = generatePassword();

if(empty($IssuingKeyPassphrase)) {
    echo "Error: Failed to generate issuing key passphrase\n";
    exit(1);
}

$IssuingPrivateKey = openssl_pkey_new($KeyConfig);
$IssuingCsr = openssl_csr_new($DNIssuing, $IssuingPrivateKey, ["digest_alg" => "sha256"]);
$IssuingConfig = ["digest_alg" => "sha256", "x509_extensions" => "v3_issuing_ca", "config" => $CNFFilePath];
$IssuingCert = openssl_csr_sign($IssuingCsr, $IntermediateCert, $IntermediatePrivateKey, $IssuingDuration, $IssuingConfig);


openssl_pkey_export_to_file($IssuingPrivateKey, "$PkiDir/issuing/private/issuing_ca.key", $IssuingKeyPassphrase);
openssl_x509_export_to_file($IssuingCert, "$PkiDir/issuing/certs/issuing_ca.crt");

function generatePassword() : ?string {

    $Output = [];
    $ResultCode = 0;
    exec("openssl rand -base64 45 | tr -dc 'A-Za-z0-9-().!@?#,/;+' | head -c30", $Output, $ResultCode);

    if(!empty($ResultCode) || !isset($Output[0]))
        return null;

    return trim($Output[0]);

}