<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validasi bahwa link yang diinput dosen benar-benar link Google Drive/Docs.
 * Mencegah dosen asal input link sembarangan (mis. link pribadi, shortlink
 * tidak jelas, dsb) untuk bukti luaran, luaran tambahan, dan link inovasi/produk.
 *
 * Pola yang diterima:
 * - https://drive.google.com/file/d/...
 * - https://drive.google.com/drive/folders/...
 * - https://drive.google.com/open?id=...
 * - https://docs.google.com/document/d/...
 * - https://docs.google.com/spreadsheets/d/...
 * - https://docs.google.com/presentation/d/...
 */
class GoogleDriveLink implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (blank($value)) {
            return; // biarkan rule 'required'/'nullable' lain yang urus wajib-tidaknya
        }

        $value = trim($value);

        // Wajib https (bukan http) dan wajib domain drive.google.com atau docs.google.com
        $pattern = '/^https:\/\/(drive|docs)\.google\.com\/.+/i';

        if (! preg_match($pattern, $value)) {
            $fail('Link yang diisi harus berupa link Google Drive/Docs yang valid (diawali https://drive.google.com/ atau https://docs.google.com/).');
        }
    }
}
