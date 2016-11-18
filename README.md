JPConsole
=========

JPConsole jest Konsolą PHP która pozwala pisać oraz uruchamiać kod.

**Version:** 0.1 alpha

**Uwaga:** Oprogramowanie zostało przygotowane pod Symfony 3.1

Instalacja:
-----------

```sh
composer require jonkiszp/console-bundle
```

Aby uruchomić konsole należy dodać do pliku app/AppKernel.php  następujący kod ```new JP\ConsoleBundle\JPConsoleBundle(),``` jak na przykładzie poniżej.

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            ...
            
            new jonkiszp\ConsoleBundle\ConsoleBundle(),
            
            ...
        ];
        ...
```

oraz do pliku app/config/routing.yml dodać poniższy wpis

```yml
jonkiszp_console:
    resource: "@ConsoleBundle/Resources/config/routing.xml"
    prefix: /
    
```