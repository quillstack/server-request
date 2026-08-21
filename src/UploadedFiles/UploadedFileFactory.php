<?php

declare(strict_types=1);

namespace Quillstack\ServerRequest\UploadedFiles;

use Psr\Http\Message\UploadedFileInterface;

/**
 * Turns the $_FILES array into the tree of uploaded files PSR-7 asks for.
 *
 * PHP writes a form field holding several files inside out: instead of one entry per file it
 * writes one entry per property, each holding a list. This puts them back the right way
 * round.
 */
class UploadedFileFactory
{
    /**
     * @param array<string, mixed> $files
     *
     * @return array<string, UploadedFileInterface|array<mixed>>
     */
    public function createFromGlobals(array $files): array
    {
        $uploaded = [];

        foreach ($files as $field => $entry) {
            if (!is_array($entry)) {
                continue;
            }

            /** @var array<string, mixed> $entry */
            $uploaded[(string) $field] = $this->createEntry($entry);
        }

        return $uploaded;
    }

    /**
     * @param array<string, mixed> $entry
     *
     * @return UploadedFileInterface|array<mixed>
     */
    private function createEntry(array $entry): UploadedFileInterface|array
    {
        if (!isset($entry['tmp_name'])) {
            return [];
        }

        return is_array($entry['tmp_name'])
            ? $this->createMany($entry, array_keys($entry['tmp_name']))
            : $this->createOne($entry);
    }

    /**
     * @param array<string, mixed> $entry
     * @param array<int, array-key> $keys
     *
     * @return array<array-key, UploadedFileInterface|array<mixed>>
     */
    private function createMany(array $entry, array $keys): array
    {
        $files = [];

        foreach ($keys as $key) {
            $files[$key] = $this->createEntry([
                'tmp_name' => $this->at($entry, 'tmp_name', $key),
                'size' => $this->at($entry, 'size', $key),
                'error' => $this->at($entry, 'error', $key),
                'name' => $this->at($entry, 'name', $key),
                'type' => $this->at($entry, 'type', $key),
            ]);
        }

        return $files;
    }

    /**
     * @param array<string, mixed> $entry
     */
    private function createOne(array $entry): UploadedFileInterface
    {
        return new UploadedFile(
            is_string($entry['tmp_name']) ? $entry['tmp_name'] : '',
            is_numeric($entry['size'] ?? null) ? (int) $entry['size'] : null,
            is_numeric($entry['error'] ?? null) ? (int) $entry['error'] : UPLOAD_ERR_NO_FILE,
            is_string($entry['name'] ?? null) ? $entry['name'] : null,
            is_string($entry['type'] ?? null) ? $entry['type'] : null,
        );
    }

    /**
     * @param array<string, mixed> $entry
     */
    private function at(array $entry, string $property, string|int $key): mixed
    {
        $values = $entry[$property] ?? null;

        return is_array($values) ? ($values[$key] ?? null) : null;
    }
}
