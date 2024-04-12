<?php

namespace Nxu\Inas\Config;

use Bayfront\ArrayHelpers\Arr;

class Config
{
    /** @param InstalledSite[] $sites */
    public function __construct(
        public array $sites = [],
    ) {
    }

    public function hasSite(string $name): bool
    {
        foreach ($this->sites as $site) {
            if ($site->name == $name) {
                return true;
            }
        }

        return false;
    }

    public function save(string $path): void
    {
        file_put_contents($path, json_encode([
            'sites' => $this->sites,
        ]));
    }

    public static function read(string $path): self
    {
        $content = file_get_contents($path);
        $content = json_decode($content, true);

        $sites = Arr::get($content, 'sites', []);
        $sites = array_map(fn ($site) => InstalledSite::fromConfig($site), $sites);

        return new self(
            sites: $sites,
        );
    }
}
