<?php

namespace App\Providers;

use App\Contracts\Filesystem;
use Pimple\{Container, ServiceProviderInterface};
use Symfony\Component\Filesystem\Filesystem as SymfonyFileSystem;

class FilesystemServiceProvider implements ServiceProviderInterface
{

    /**
     * Register service provider
     *
     * @param Container $pimple
     * @return void
     */
    public function register(Container $pimple): void
    {
        /**
         * We use a fake anon class just to ensure that the Contract was resolved.
         * Of course we can replace the SymfonyFilesystem with another provider, and it shall not break the structure.
         * (as far as DI Container will check abstract to implementation bindings, which Pimple does not do :( )
         * */
        $pimple[Filesystem::class] = new class extends SymfonyFileSystem implements Filesystem {};
    }

}