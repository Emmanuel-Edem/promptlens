<?php

namespace PromptLens\Services\Metadata;

class PngChunkParser
{
    public function parse(string $file): array
    {
        if (!file_exists($file)) {
            throw new \Exception("PNG file not found: {$file}");
        }

        $chunks = [];

        $fp = fopen($file, 'rb');

        if (!$fp) {
            throw new \Exception('Unable to open PNG file.');
        }

        // Read PNG signature (8 bytes)
        $signature = fread($fp, 8);

        // Validate PNG signature
        if ($signature !== "\x89PNG\r\n\x1a\n") {
            fclose($fp);
            throw new \Exception('Invalid PNG file.');
        }

        while (!feof($fp)) {

            // Read chunk length
            $lengthData = fread($fp, 4);

            if (strlen($lengthData) !== 4) {
                break;
            }

            $length = unpack('N', $lengthData)[1];

            // Read chunk type
            $type = fread($fp, 4);

            if (strlen($type) !== 4) {
                break;
            }

            // Read chunk data (only if it has data)
            $data = '';

            if ($length > 0) {
                $data = fread($fp, $length);

                if (strlen($data) !== $length) {
                    break;
                }
            }

            // Skip CRC (4 bytes)
            $crc = fread($fp, 4);

            if (strlen($crc) !== 4) {
                break;
            }

            $chunks[] = [
                'type'   => $type,
                'length' => $length,
                'data'   => $data
            ];

            // Stop at the end of the PNG
            if ($type === 'IEND') {
                break;
            }
        }

        fclose($fp);

        return $chunks;
    }

    /**
     * Return only text-related PNG chunks.
     */
    public function getTextChunks(array $chunks): array
    {
        $textChunks = [];

        foreach ($chunks as $chunk) {

            if (in_array($chunk['type'], ['tEXt', 'zTXt', 'iTXt'], true)) {

                $textChunks[] = [
                    'type' => $chunk['type'],
                    'data' => $chunk['data']
                ];
            }
        }

        return $textChunks;
    }

    /**
     * Decode standard PNG tEXt chunks.
     */
    public function decodeTextChunks(array $textChunks): array
    {
        $decoded = [];

        foreach ($textChunks as $chunk) {

            // Only decode standard tEXt chunks
            if ($chunk['type'] !== 'tEXt') {
                continue;
            }

            // Split at the first NULL byte
            $parts = explode("\0", $chunk['data'], 2);

            $decoded[] = [
                'keyword' => $parts[0] ?? '',
                'value'   => $parts[1] ?? ''
            ];
        }

        return $decoded;
    }
}