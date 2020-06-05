<?php

use Twlve\Gojek\Gojek;

require 'vendor/autoload.php';

ReRun:
$gojek = new Gojek();

$fileAccessToken = @file_get_contents('access_token.txt');

AccessToken:
if (!file_exists("access_token.txt")) {
    echo "\n[*] Login\n";
    echo "------------------\n";

    ResendPhone:
    echo "\n[+] Masukkan Nomor HP (e.g 89123456789) : ";

    $phone = trim(fgets(STDIN));

    $loginPhone = $gojek->loginPhone($phone);

    if (!$loginPhone->success) {
        checkConnection($loginPhone);
        echo "\n";
        echo "[!] ERROR : " . $loginPhone->error_message . "\n";
        sleep(2);
        goto ResendPhone;
    }

    $loginToken = $loginPhone->data->login_token;

    ResendOTP:
    echo "[+] Masukkan OTP : ";

    $otp = trim(fgets(STDIN));

    $loginAuth = $gojek->loginAuth($loginToken, $otp);

    if (!$loginAuth->success) {
        checkConnection($loginAuth);
        echo "\n";
        echo "[!] ERROR : " . $loginAuth->error_message . "\n";
        sleep(2);
        goto ResendOTP;
    }

    $accessToken = $loginAuth->data->access_token;
    @file_put_contents('access_token.txt', $accessToken);

    echo "\n";
    echo "[!] SUCCESS : Anda Berhasil Login!\n";
    sleep(2);

    goto ReRun;
}

$gojek->setAccessToken($fileAccessToken);

MyAccount:
$customer = $gojek->customer();
if (!$customer->success) {
    checkConnection($customer);
    echo "\n";
    echo "[!] ERROR : " . $customer->error_message . "\n";
    unlink('access_token.txt');
    sleep(2);
    goto AccessToken;
}

$gopayDetail = $gojek->gopayDetail();
if (!$gopayDetail->success) {
    checkConnection($gopayDetail);
    echo "\n";
    echo "[!] ERROR : " . $gopayDetail->error_message . "\n";
    unlink('access_token.txt');
    sleep(2);
    goto AccessToken;
}

echo "\n";
echo "[*] Detail Akun\n";
echo "------------------\n";
echo "[-] Nama          : {$customer->data->name}\n";
echo "[-] Email         : {$customer->data->email}\n";
echo "[-] No. HP        : {$customer->data->phone}\n";
echo "[-] Saldo Gopay   : Rp." . number_format($gopayDetail->data->balance) . "\n";
echo "[-] Tipe Akun     : " . ($gopayDetail->data->kyc_detail_status == 'APPROVED' ? 'PREMIUM' : 'BASIC') . "\n";
echo "------------------\n";
echo "\n";
echo "[*] Aksi\n";
echo "------------------\n";
echo "[1] Ubah Akun\n";
echo "[2] Kirim Uang\n";
echo "[3] Keluar Akun\n";
echo "[4] Keluar Aplikasi\n";
echo "------------------\n";

echo "\n";
echo "\n";
echo "[+] Masukkan Aksi : ";

$action = trim(fgets(STDIN));

if ($action == 1) {
    echo "\n";
    echo "\n";
    echo "[-] Nama Sebelumnya   : {$customer->data->name}\n";
    echo "[+] Masukkan Nama Baru / Kosongkan jika tidak ingin mengubahnya : ";
    $newName = trim(fgets(STDIN));

    echo "\n";
    echo "[-] Email Sebelumnya  : {$customer->data->email}\n";
    echo "[+] Masukkan Email Baru / Kosongkan jika tidak ingin mengubahnya : ";
    $newEmail = trim(fgets(STDIN));

    echo "\n";
    echo "[-] No. HP Sebelumnya : {$customer->data->phone}\n";
    echo "[+] Masukkan No. HP Baru / Kosongkan jika tidak ingin mengubahnya (e.g 89123456789) : ";
    $newPhone = trim(fgets(STDIN));

    $newName    = $newName == null ? $customer->data->name : $newName;
    $newEmail   = $newEmail == null ? $customer->data->email : $newEmail;
    $newPhone   = $newPhone == null ? $customer->data->number : $newPhone;

    $updateCustomer = $gojek->updateCustomer($newName, $newEmail, $newPhone);

    echo "\n";
    if ($updateCustomer->success) {
        echo "[!] SUCCESS : Berhasil memperbarui akun\n";
    } else {
        checkConnection($updateCustomer);
        echo "[!] ERROR : " . $updateCustomer->error_message . "\n";
    }
    sleep(2);
    goto MyAccount;
} else if ($action == 2) {
    ResendMoney:
    echo "\n";
    echo "[+] Masukkan Nomor HP Tujuan (e.g 89123456789) : ";
    $phoneTo = trim(fgets(STDIN));

    $checkWalletCode = $gojek->checkWalletCode($phoneTo);

    if (!$checkWalletCode->success) {
        checkConnection($checkWalletCode);
        echo "\n";
        echo "[!] ERROR : " . $checkWalletCode->error_message . "\n";

        sleep(2);
        if (strtolower($checkWalletCode->error_message) == "we couldn't find your wallet.") {
            goto ResendMoney;
        }
        goto MyAccount;
    }

    $QRID = $checkWalletCode->data->qr_id;

    echo "[+] Masukkan Nominal : ";
    $amount = trim(fgets(STDIN));

    echo "[+] Masukkan Deskripsi (Enter untuk mengosongi) : ";
    $desctiption = trim(fgets(STDIN));

    ReinsertPIN:
    echo "[+] Masukkan PIN : ";
    $PIN = trim(fgets(STDIN));

    $gopayTransfer = $gojek->gopayTransfer($QRID, $PIN, $amount, $desctiption);

    echo "\n";
    if ($gopayTransfer->success) {
        echo "[!] SUCCESS : Berhasil mengirim uang ke nomor " . $phoneTo . "\n";
    } else {
        checkConnection($gopayTransfer);
        echo "[!] ERROR : " . $gopayTransfer->error_message . "\n";
        if (strtolower($gopayTransfer->error_message) == "you entered an incorrect pin. try again.") {
            echo "\n";
            sleep(2);
            goto ReinsertPIN;
        }
    }
    sleep(2);
    goto MyAccount;
} else if ($action == 3) {
    $logout = $gojek->logout();

    echo "\n";
    if ($logout->success) {
        unlink('access_token.txt');
        echo "[!] SUCCESS : Berhasil Keluar\n";
        sleep(2);
        goto AccessToken;
    } else {
        checkConnection($logout);
        echo "[!] ERROR : " . $logout->error_message . "\n";
        sleep(2);
        goto MyAccount;
    }
} else if ($action == 4) {
    echo "\n";
    echo " ______     ________ ______     ________ _ _ _ \n";
    echo "|  _ \ \   / /  ____|  _ \ \   / /  ____| | | |\n";
    echo "| |_) \ \_/ /| |__  | |_) \ \_/ /| |__  | | | |\n";
    echo "|  _ < \   / |  __| |  _ < \   / |  __| | | | |\n";
    echo "| |_) | | |  | |____| |_) | | |  | |____|_|_|_|\n";
    echo "|____/  |_|  |______|____/  |_|  |______(_|_|_)\n";
    echo "  _____ _    _ _    _ _______ _____   ______          ___   _ _ _ _ \n";
    echo " / ____| |  | | |  | |__   __|  __ \ / __ \ \        / / \ | | | | |\n";
    echo "| (___ | |__| | |  | |  | |  | |  | | |  | \ \  /\  / /|  \| | | | |\n";
    echo " \___ \|  __  | |  | |  | |  | |  | | |  | |\ \/  \/ / | . ` | | | |\n";
    echo " ____) | |  | | |__| |  | |  | |__| | |__| | \  /\  /  | |\  |_|_|_|\n";
    echo "|_____/|_|  |_|\____/   |_|  |_____/ \____/   \/  \/   |_| \_(_|_|_)\n";
    sleep(2);
    die();
} else {
    goto MyAccount;
}

function checkConnection($data)
{
    if (strtolower($data->error_message) == 'no connection') {
        echo "\n";
        echo "[!] ERROR : " . $data->error_message . "\n";
        echo " ______     ________ ______     ________ _ _ _ \n";
        echo "|  _ \ \   / /  ____|  _ \ \   / /  ____| | | |\n";
        echo "| |_) \ \_/ /| |__  | |_) \ \_/ /| |__  | | | |\n";
        echo "|  _ < \   / |  __| |  _ < \   / |  __| | | | |\n";
        echo "| |_) | | |  | |____| |_) | | |  | |____|_|_|_|\n";
        echo "|____/  |_|  |______|____/  |_|  |______(_|_|_)\n";
        echo "  _____ _    _ _    _ _______ _____   ______          ___   _ _ _ _ \n";
        echo " / ____| |  | | |  | |__   __|  __ \ / __ \ \        / / \ | | | | |\n";
        echo "| (___ | |__| | |  | |  | |  | |  | | |  | \ \  /\  / /|  \| | | | |\n";
        echo " \___ \|  __  | |  | |  | |  | |  | | |  | |\ \/  \/ / | . ` | | | |\n";
        echo " ____) | |  | | |__| |  | |  | |__| | |__| | \  /\  /  | |\  |_|_|_|\n";
        echo "|_____/|_|  |_|\____/   |_|  |_____/ \____/   \/  \/   |_| \_(_|_|_)\n";
        sleep(2);
        die();
    }
}
