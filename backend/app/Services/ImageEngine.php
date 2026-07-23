<?php

namespace PromptLens\Services;

class ImageEngine
{
    public function detect(array $metadata): array
    {
        $text = strtolower(json_encode($metadata));

        $engine = "Unknown";
        $confidence = 0;

        if (str_contains($text, "midjourney")) {
            $engine = "Midjourney";
            $confidence = 99;
        }
        elseif (str_contains($text, "chatgpt")) {
            $engine = "ChatGPT";
            $confidence = 99;
        }
        elseif (str_contains($text, "gpt-image")) {
            $engine = "GPT Image";
            $confidence = 99;
        }
        elseif (str_contains($text, "flux")) {
            $engine = "FLUX";
            $confidence = 99;
        }
        elseif (str_contains($text, "comfyui")) {
            $engine = "ComfyUI";
            $confidence = 98;
        }
        elseif (str_contains($text, "automatic1111")) {
            $engine = "AUTOMATIC1111";
            $confidence = 98;
        }
        elseif (str_contains($text, "stable diffusion")) {
            $engine = "Stable Diffusion";
            $confidence = 98;
        }
        elseif (str_contains($text, "sdxl")) {
            $engine = "SDXL";
            $confidence = 98;
        }
        elseif (str_contains($text, "forge")) {
            $engine = "Forge";
            $confidence = 97;
        }
        elseif (str_contains($text, "fooocus")) {
            $engine = "Fooocus";
            $confidence = 97;
        }
        elseif (str_contains($text, "swarmui")) {
            $engine = "SwarmUI";
            $confidence = 97;
        }

        return [
            'engine' => $engine,
            'confidence' => $confidence
        ];
    }
}
