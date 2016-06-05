# PHP-Translator
Little weight PHP class to string translate using Microsoft Translator

#### How to use free Microsoft Translator?
1. Subscribe to the Microsoft Translator API [here](https://datamarket.azure.com/dataset/1899a118-d202-492c-aa16-ba21c33c06cb)
2. Register your application with Azure DataMarket [here](https://datamarket.azure.com/developer/applications/)

#### Usage

```PHP
include 'php-translator.php';
$translator = new PHPTranslator;

$clientID = "id";
$clientSecret = "secret";
$translator->addClient($clientID, $clientSecret);

$translator->debug(true);

// function translate ($stringToTranslate, $toLanguage, [$fromLanguage = "autoDetection"])
echo $translator->translate("W Szczebrzeszynie chrząszcz brzmi w trzcinie.", "en");
```

When 200.000 free chars is not enough, you can add more accounts by `addClient ($clientID, $clientSecret)`. PHPTranslator will use all of them.

The third parameter of `translate` is optional, Microsoft Translator can autodetect language.




By [Fireveined](http://fireveined.pl/)
