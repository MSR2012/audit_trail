<?php

namespace App\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;

class ExpiredBlacklistedTokenRemovalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blacklist_token:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove expired blacklisted tokens';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $storage = Cache::getStore();
        $filesystem = $storage->getFilesystem();
        $dir = storage_path('framework/cache');

        $this->removeExpiredCacheFiles($filesystem, $dir);
        $this->removeDirectories($filesystem, $dir);

        return CommandAlias::SUCCESS;
    }

    private function removeExpiredCacheFiles(
        Filesystem $filesystem,
        string     $directory
    ): void
    {
        foreach ($filesystem->allFiles($directory) as $cacheFile) {
            if ($cacheFile == '.gitignore') {
                continue;
            }

            try {
                $contents = $filesystem->get($cacheFile);
                $expire = substr($contents, 0, 10);
                if (Carbon::now()->timestamp < $expire) {
                    continue;
                }

                $filesystem->delete($cacheFile);
            } catch (FileNotFoundException $e) {
                continue;
            }
        }
    }

    private function removeDirectories(
        Filesystem $filesystem,
        string     $directory
    ): void
    {
        foreach ($filesystem->directories($directory) as $directory) {
            try {
                $directoryFiles = $filesystem->allFiles($directory);
                if (count($directoryFiles)) {
                    $this->removeDirectories($filesystem, $directory);
                    continue;
                }

                $filesystem->deleteDirectory($directory);
            } catch (DirectoryNotFoundException $e) {
                continue;
            }
        }
    }
}
