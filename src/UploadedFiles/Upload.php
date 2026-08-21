<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\UploadedFiles;

/**
 * What PHP says went wrong with an upload, in words.
 */
final class Upload
{
    /**
     * @var array<int, string>
     */
    public const REASONS = [
        UPLOAD_ERR_OK => 'the file arrived',
        UPLOAD_ERR_INI_SIZE => 'the file is larger than upload_max_filesize allows',
        UPLOAD_ERR_FORM_SIZE => 'the file is larger than the form allowed',
        UPLOAD_ERR_PARTIAL => 'only part of the file arrived',
        UPLOAD_ERR_NO_FILE => 'no file was sent',
        UPLOAD_ERR_NO_TMP_DIR => 'there is nowhere to put it',
        UPLOAD_ERR_CANT_WRITE => 'it could not be written to disk',
        UPLOAD_ERR_EXTENSION => 'an extension stopped the upload',
    ];

    public static function describe(int $error): string
    {
        return self::REASONS[$error] ?? "unknown reason ({$error})";
    }
}
