<?php

declare(strict_types=1);

namespace App\Support;

use App\Exceptions\EncryptionException;

final readonly class EncryptionHelper
{
    private const string CIPHER_ALGO = 'AES-256-CBC';

    public function __construct(private string $encryptionKey)
    {
        // Validate the key length immediately upon instantiation
        if (strlen($this->encryptionKey) !== 32) {
            throw new EncryptionException('Encryption key must be 32 bytes.');
        }
    }

    /**
     * Encrypt a string using AES-256-CBC.
     *
     * @param string $value The string value to encrypt.
     * @return string The encrypted and base64-encoded string.
     */
    public function encrypt(string $value): string
    {
        // Generate a random 16-byte IV
        $iv = random_bytes(16);

        // Encrypt the string using AES-256-CBC
        $encrypted = openssl_encrypt($value, self::CIPHER_ALGO, $this->encryptionKey, 0, $iv);

        if ($encrypted === false) {
            throw new EncryptionException('Encryption failed.');
        }

        // Return the IV + encrypted value, base64 encoded
        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypt a string that was encrypted with AES-256-CBC.
     *
     * @param string $encryptedData The base64-encoded encrypted string to decrypt.
     * @return string The decrypted string.
     */
    public function decrypt(string $encryptedData): string
    {
        // Decode the base64-encoded string and extract the IV and encrypted value
        $decoded = base64_decode($encryptedData, true);

        if ($decoded === false) {
            throw new EncryptionException('Invalid base64 encoding.');
        }

        // Extract the IV (first 16 bytes) and the encrypted value
        $iv = substr($decoded, 0, 16);
        $encryptedValue = substr($decoded, 16);

        // Decrypt the string using AES-256-CBC
        $decrypted = openssl_decrypt($encryptedValue, self::CIPHER_ALGO, $this->encryptionKey, 0, $iv);

        if ($decrypted === false) {
            throw new EncryptionException('Decryption failed.');
        }

        return $decrypted;
    }
}
