<?php

namespace PromptLens\Services\Metadata;

class PngTextExtractor
{
    /**
     * Extract all textual metadata from a PNG image.
     *
     * Supports:
     * - tEXt
     * - iTXt
     * - zTXt (placeholder for future decompression)
     */
    public function extract(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception("PNG file not found.");
        }

        $handle = fopen($filePath, 'rb');

        if (!$handle) {
            throw new \Exception("Unable to open PNG.");
        }

        // Skip PNG signature
        fread($handle, 8);

        $metadata = [];

        while (!feof($handle)) {

            $lengthData = fread($handle, 4);

            if (strlen($lengthData) !== 4) {
                break;
            }

            $length = unpack("N", $lengthData)[1];

            $chunkType = fread($handle, 4);

            $chunkData = fread($handle, $length);

            // Skip CRC
            fread($handle, 4);

            switch ($chunkType) {

                case "tEXt":

                    $parts = explode("\0", $chunkData, 2);

                    if (count($parts) === 2) {
                        $metadata[$parts[0]] = $parts[1];
                    }

                    break;

                case "iTXt":

                    $parts = explode("\0", $chunkData);

                    if (count($parts) >= 6) {

                        $keyword = $parts[0];

                        $text = end($parts);

                        $metadata[$keyword] = $text;
                    }

                    break;

                case "zTXt":

                    $parts = explode("\0", $chunkData, 2);

                    if (count($parts) === 2) {
                        $metadata[$parts[0]] = "[Compressed text detected]";
                    }

                    break;

            }

            if ($chunkType === "IEND") {
                break;
            }
        }

        fclose($handle);

        return $metadata;
    }
}
