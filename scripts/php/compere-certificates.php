<?php

function loadCertificates($Certificates) : array {

    $OpenSSLCertificates = [];

    foreach($Certificates as $Key => $Certificate){

        $X509 = openssl_x509_read($Certificate);

        if(empty($X509))
            continue;

        $OpenSSLCertificates[] = $X509;

    }

    return $OpenSSLCertificates;

}

function areCertificatesEqual(OpenSSLCertificate $Certificate1, OpenSSLCertificate $Certificate2) : ?bool { //both certificate have to be an object from openssl_x509_read

    //we use sha256 instead of sha1 to avoid hash collisions, for more details see the link below
    //https://security.stackexchange.com/questions/35691/what-is-the-difference-between-serial-number-and-thumbprint
    $EqualFingerprintAlgorithm = 'sha256';

    if(empty($Certificate1Fingerprint) || empty($Certificate2Fingerprint))
        return null;

    $Certificate1Fingerprint = openssl_x509_fingerprint($Certificate1, $EqualFingerprintAlgorithm);
    $Certificate2Fingerprint = openssl_x509_fingerprint($Certificate2, $EqualFingerprintAlgorithm);

    if($Certificate1Fingerprint == $Certificate2Fingerprint)
        return true;

    return false;

}

$MyCertificates[0] = '-----BEGIN CERTIFICATE-----
MIICFDCCAbsCFAsZUOsIYY8tvQzNyF8qFJBw3YXGMAoGCCqGSM49BAMCMIGMMQsw
CQYDVQQGEwJCUjESMBAGA1UECAwJU2FvIFBhdWxvMRAwDgYDVQQHDAdKdW5kaWFp
MQ0wCwYDVQQKDARKb2FvMQswCQYDVQQLDAJNZTESMBAGA1UEAwwJam9hby10ZXN0
MScwJQYJKoZIhvcNAQkBFhhqb2FvbWF0ZXVzZmVyckBnbWFpbC5jb20wHhcNMjQw
MjA0MTMzNTU5WhcNMjUwMjAzMTMzNTU5WjCBjDELMAkGA1UEBhMCQlIxEjAQBgNV
BAgMCVNhbyBQYXVsbzEQMA4GA1UEBwwHSnVuZGlhaTENMAsGA1UECgwESm9hbzEL
MAkGA1UECwwCTWUxEjAQBgNVBAMMCWpvYW8tdGVzdDEnMCUGCSqGSIb3DQEJARYY
am9hb21hdGV1c2ZlcnJAZ21haWwuY29tMFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcD
QgAE/WFv9TXN1bfpIbq1K7vIIsSNvWjodjRozrqoYP0AccaNJ+B+1wDthaZGSR0t
EqByoiEGmFW7d77+D8j8ruhtyzAKBggqhkjOPQQDAgNHADBEAiAzGGgLcTnevmyN
6QdxQsn60LI+99Kq7owzxqmPE2BiqQIgUNIK7RCt3WiaTArN0ZnhzC0ePkRebNw5
2sYVg/jK+bQ=
-----END CERTIFICATE-----';

$MyCertificates = loadCertificates($MyCertificates);

$Result = areCertificatesEqual($MyCertificates[0], $MyCertificates[0]);

if(is_null($Result))
    echo "Unable to compere certificates\n";

var_dump($Result);exit;