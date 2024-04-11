<?php

namespace Nxu\Nap56\Config;

class Config
{
    public function __construct(
        public string $projectsFolder = '~/code'
    ) {
        if (str_contains($this->projectsFolder, '~')) {
            // Hacky as fuck, but easier than assembling manually 🤷‍
            $this->projectsFolder = exec("echo $this->projectsFolder");
        }
    }

    public static function read(string $path): self
    {
        $content = file_get_contents($path);
        $content = json_decode($content, true);

        return new self(
            projectsFolder: $content['projectsFolder']
        );
    }

    public function save(string $path): void
    {
        file_put_contents($path, json_encode([
            'projectsFolder' => $this->projectsFolder,
        ]));
    }
}
