JPConsole
=========

JPConsole jest Konsolą PHP która pozwala pisać oraz uruchamiać kod.

**Version:** 0.1 alpha

**Uwaga:** Oprogramowanie zostało przygotowane pod Symfony 3.1

Instalacja:
-----------

```sh
composer require jonkiszp/console-bundle:master-dev
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

Przydatne skróty klawiszowe

Ctrl+Alt+H wyświetlenie wszystkich skrótów klawiszowych.<br/>
Alt+R     załadowanie podpowiedzi z plików znajdujących się w systemie.<br/>
Ctrl+Enter uruchomienie napisanego kodu