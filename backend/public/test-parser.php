<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PromptLens\Services\Metadata\PngChunkParser;

$file = __DIR__ . '/../storage/uploads/promptlens_6a62101fda75e9.92285951.png';

$parser = new PngChunkParser();

$chunks = $parser->parse($file);

$textChunks = $parser->getTextChunks($chunks);

$decoded = $parser->decodeTextChunks($textChunks);

echo "<pre>";

print_r($decoded);