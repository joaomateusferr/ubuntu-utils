<?php

$PkiDir = isset($argv[1]) ? $argv[1] : exit;
$OrganizationName = isset($argv[2]) ? $argv[2] : exit;

if (!is_dir($PkiDir)) {
    echo "Error: $PkiDir is not a directory\n";
    exit(2);
}

if (substr($PkiDir, -1) === '/')
    $PkiDir = substr($PkiDir, 0, -1);

$DNRoot = [
    //"countryName"            => "BR",
    //"stateOrProvinceName"    => "SP",
    //"localityName"           => "Jundiaí",
    "organizationName"       => "$OrganizationName",
    "commonName"             => "$OrganizationName Root CA",
];

$DNIntermediate = array_merge($DNRoot, ["commonName" => "$OrganizationName Intermediate CA"]);
$DNIssuing      = array_merge($DNRoot, ["commonName" => "$OrganizationName Issuing CA"]);

$KeyConfig = ["private_key_bits" => 4096,"private_key_type" => OPENSSL_KEYTYPE_RSA];

@mkdir("$PkiDir/secrets", 0777, true);
@mkdir("$PkiDir/certs", 0777, true);
@mkdir("$PkiDir/passwords", 0777, true);
@mkdir("$PkiDir/step", 0777, true);

$CNFFilePath = "$PkiDir/temp.cnf";

$CNFFileContent = "[ v3_ca ]
basicConstraints = critical, CA:true, pathlen:2
keyUsage = critical, cRLSign, keyCertSign

[ v3_intermediate_ca ]
basicConstraints = critical, CA:true, pathlen:1
keyUsage = critical, cRLSign, keyCertSign

[ v3_issuing_ca ]
basicConstraints = critical, CA:true, pathlen:0
keyUsage = critical, cRLSign, keyCertSign";

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

openssl_pkey_export_to_file($RootPrivateKey, "$PkiDir/secrets/root_ca_key", $RootKeyPassphrase);
file_put_contents("$PkiDir/passwords/root_ca_password",$RootKeyPassphrase);
openssl_x509_export_to_file($RootCert, "$PkiDir/certs/root_ca.crt");

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

openssl_pkey_export_to_file($IntermediatePrivateKey, "$PkiDir/secrets/intermediate_ca_key", $IntermediateKeyPassphrase);
file_put_contents("$PkiDir/passwords/intermediate_ca_password", $IntermediateKeyPassphrase);
openssl_x509_export_to_file($IntermediateCert, "$PkiDir/certs/intermediate_ca.crt");

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

openssl_pkey_export_to_file($IssuingPrivateKey, "$PkiDir/secrets/issuing_ca_key", $IssuingKeyPassphrase);
file_put_contents("$PkiDir/passwords/issuing_ca_password", $IssuingKeyPassphrase);
openssl_x509_export_to_file($IssuingCert, "$PkiDir/certs/issuing_ca.crt");

$RootPem = file_get_contents("$PkiDir/certs/root_ca.crt");
$IntermediatePem = file_get_contents("$PkiDir/certs/intermediate_ca.crt");
$IssuingPem = file_get_contents("$PkiDir/certs/issuing_ca.crt");

file_put_contents("$PkiDir/step/root_ca.crt", $RootPem);
file_put_contents("$PkiDir/step/intermediate_ca.crt", $IntermediatePem.$IssuingPem);

echo "Paths:\n$PkiDir/step/root_ca.crt\n$PkiDir/step/intermediate_ca.crt\n";
echo "Issuing password: $IssuingKeyPassphrase\n";

function generatePassword() : ?string {

    $Output = [];
    $ResultCode = 0;
    exec("openssl rand -base64 45 | tr -dc 'A-Za-z0-9-().!@?#,/;+' | head -c30", $Output, $ResultCode);

    if(!empty($ResultCode) || !isset($Output[0]))
        return null;

    return trim($Output[0]);

}