<?php

namespace ZarulIzham\DuitNowPayment\Traits;

use Illuminate\Support\Facades\Storage;

trait SignMessage
{
    public function sign($message)
    {
        $privateKey = Storage::disk(config('duitnow.certificates.uat.disk'))->get(config('duitnow.certificates.uat.dir') . 'pvt.key');
        $pvtKeyRes = openssl_pkey_get_private($privateKey);
        openssl_pkey_export($pvtKeyRes, $pvtKey);

        $publicKey = Storage::disk(config('duitnow.certificates.uat.disk'))->get(config('duitnow.certificates.uat.dir') . 'pub.key');
        $pubKeyRes = openssl_pkey_get_public($publicKey);

        $sigAlgo = 'sha256WithRSAEncryption';
        $mdMethods = openssl_get_md_methods(true);
        $found = false;

        foreach ($mdMethods as $mdMethod) {
            if ($sigAlgo === $mdMethod) {
                $found = true;
                break;
            }
        }

        if ($found === false) {
            throw new \Exception("method=[$sigAlgo] not found");
        }

        if (openssl_sign($message, $signatureBytes, $pvtKeyRes, $sigAlgo) === false) {
            throw new \Exception('fail to sign message');
        }

        $signature = base64_encode($signatureBytes);

        $signatureBytes = base64_decode($signature);
        $verify = openssl_verify($message, $signatureBytes, $pubKeyRes, $sigAlgo);

        if ($verify === -1) {
            echo 'error while verifying' . PHP_EOL;
        } elseif ($verify === 0) {
            echo 'Wrong signature' . PHP_EOL;
        } elseif ($verify === 1) {
            // echo 'Correct signature' . PHP_EOL;
        } else {
            throw new \Exception('fail to verify message');
        }

        return $signature;
    }
}
