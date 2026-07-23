<?php

namespace PromptLens\Services;

class MetadataExtractor
{
    public function extract(string $imagePath): array
    {
        $metadata = [];

        // Image Size
        if ($size = @getimagesize($imagePath)) {

            $metadata['width'] = $size[0];
            $metadata['height'] = $size[1];
            $metadata['mime'] = $size['mime'];
        }

        // EXIF (JPEG)
        if (function_exists('exif_read_data')) {

            $exif = @exif_read_data($imagePath);

            if ($exif) {
                $metadata['exif'] = $exif;
            }

        }

        // File Information

        $metadata['filename'] = basename($imagePath);

        $metadata['extension'] = pathinfo($imagePath, PATHINFO_EXTENSION);

        $metadata['filesize'] = filesize($imagePath);

        $metadata['sha256'] = hash_file('sha256', $imagePath);

        return $metadata;
    }
}