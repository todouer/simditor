<?php

namespace Tudouer\Simditor;

use Illuminate\Support\ServiceProvider;
use RuntimeException;

class SimditorServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(SimditorExtension $extension)
    {
        if (! SimditorExtension::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'laravel-admin-simditor');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/laravel-admin-ext/simditor')],
                'laravel-admin-simditor'
            );
        }

        $this->resolveAdminClass()::booting(function () {
            $this->resolveFormClass()::extend('simditor', Editor::class);
        });
    }

    /**
     * Resolve the Laravel-admin Admin facade class for the current installation.
     */
    protected function resolveAdminClass(): string
    {
        return $this->resolveAdminComponent('Admin');
    }

    /**
     * Resolve the Laravel-admin Form class for the current installation.
     */
    protected function resolveFormClass(): string
    {
        return $this->resolveAdminComponent('Form');
    }

    /**
     * Resolve Laravel-admin classes for the php-casbin/laravel-admin package.
     *
     * @throws RuntimeException
     */
    protected function resolveAdminComponent(string $component): string
    {
        $namespaces = [
            'Casbin\\Admin',
        ];

        foreach ($namespaces as $namespace) {
            $class = sprintf('%s\\%s', $namespace, $component);

            if (class_exists($class)) {
                return $class;
            }
        }

        throw new RuntimeException(sprintf('Unable to locate the Laravel-admin %s class.', $component));
    }
}
