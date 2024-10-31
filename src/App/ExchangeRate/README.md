Exchange Rate - Opis Implementacji
==========
---

### Ogólne informacje
Zadanie z pozoru wydaje się dość proste, stąd moja implementacja może wydawać się dość rozległa
i być może przekombinowana, jednak chciałem zapewnić dalszą rozszerzalność i dość prostą konfigurowalność
chociażby przy użyciu Dependendcy Injection.

Tak jak wspomniałem w CV - nie mam zaawansowanej wiedzy z Symfony, gdyż pracuję na co dzień w innym frameworku
(jeśli można tak nazwać np. pracę w Magento). Jeśli jednak zacznę komercyjną pracę w Symfony np. właśnie
dla Państwa - jestem przekonany, że szybko ogarnę smaczki Symfony i będę w nim efektywnie pracować. Na szczęscie
dobre praktyki (którymi staram się posługiwać) są rzeczą uniwersalną i wykraczają poza framework, stąd wierzę, że w tym zadaniu poradziłem sobie całkiem znośnie. 

Frontend: jestem głównie backendowcem, choć jak wspomniałem w CV - nie było dla mnie problemu jeśli mogłem
przyspieszyć pracę zespołu i np. być zasobem dla wykonania jakiegoś zadania frontendowego. Niestety nigdy nie był
to React, w tym zadaniu miałem z nim pierwszy raz styczność (choć długie lata temu pracowałem np. w Angular).
Jednak tu również jestem przekonany, że pracując komercyjnie przy React - w krótkim czasie będę się nim dobrze posługiwać.

### Klient Pobierający Rate
Aby dodać nowego klienta, który będzie nam serwować kursy walut wystarczy utworzyć jego klasę, która
będzie implementować interfejs
```
App\ExchangeRate\CurrencyExchangeClientInterface
```
Następnie klasę wystarczy podać/podmienić zależność ``CurrencyExchangeClientInterface $exchangeRateRequest``
w klasie fabryki
```
App\ExchangeRate\CurrencyExchangeClientFactory
```
Fabryka ta jest użyta do wstępnego skonfigurowania naszego klienta tak, aby był gotowy do pracy 
np. w naszym kontrolerze ``App\Controller\ExchangeRatesController``

Na potrzeby zadania  mamy utworzonego klienta ``App\ExchangeRate\Http\NBPRestClient``, który serwuje
nam dane z RESTowego api NBP. Nic nie stoi na przeszkodzie aby takim klientem stał się np. jakiś twór
SOAPowy, który bierze dane ze źródła X. Jeśli zaimplementuje odpowiednio wspomniany wcześniej interfejs
klienta - powinien działać bez zarzutu i być totalnie obojętny np. dla używającego go kontrolera.

### Cache klienta
Tu nie byłem przekonany czy cache robić na poziomie klienta, czy gdzieś  wyżej. Jednak to, że każdy klient
może troszeczkę inaczej się zachowywać, bo np. NBP generuje nowe kursy o 12.00 sprawiło, że uznałem cache
za sprawę indywidualna dla danego klienta i zaimplementowałem go już w konkretnej klasie. Działa on tak, że 
gdy pobierane są stawki z aktualnego dnia, ale godzina jest jeszcze przed 12 - lifetime cache ustawiany jest
do 12, bo później prawdopodobnie dostanie świeże dane i cache musi być wygenerowany od nowa. Jeśli jednak
pobieramy dane z dnia innego niż dzisiejszy - cache jest nielimitowany.


### Manipulowanie danymi zwracanymi przez klienta
Zauważyłem w zadaniu, że może nastać potrzeba generowani własnych cen kupna/sprzedaży waluty i bazują one
na danych, które są już zawarte w obiekcie rate. Dlatego też zaimplementowałem coś co nazwałem modifierem.
Implementuje on interfejs:
```
App\ExchangeRate\ExchangeRatesRequestDataModifierInterface
```
Modyfikatory można dodawać przez metodę ``App\ExchangeRate\CurrencyExchangeClientInterface::addDataModifier``

Metody tej użyłem we wspomnianej wcześniej fabryce, która generuje klienta, a która ma modyfikatorów serwowanych
przez DependencyInjection. Na nasze potrzeby posiadamy tylko jeden modyfikator: 
```
App\ExchangeRate\Trade\TradeRateModifier
```

### Ustawianie cen kupna/sprzedaży
Wspomniany wcześniej modyfikator ustawia ceny kupna i sprzedaży dla obiektu ``App\ExchangeRate\DTO\ExchangeRate``. 
Modyfikator ten jest prostą implementacją wzorca Strategia. Poprzez zależności ma wstrzykiwane modyfikacje
jakie mają nastąpić dla danych walut. Strategie te bazują na interfejsie ``\App\ExchangeRate\Trade\CurrencyTradeRateCalculationInterface``.
W ``services.yaml`` można podejrzeć jak wstrzyknięte są strategie wyliczania ceny sprzedaży/kupna dla danych walut.
Nazwy tych strategii są dość enigmatyczne, bo nie znam za dobrze zasad, które stoją za ustawianiem danych stawek.
Zapewne będąc bliżej danej domeny wiedziałbym, że np. dodawanie 0.07 do ceny sprzedaży to "Stadardowa Marża" i tak też
bym nazwał daną strategię wyliczania.

### Format zwracanych danych
Chciałem zapewnić jednolity sposób zwracania danych poprzez API, stąd też utworzyłem klasę, która dane te zwraca
w ściśle określony sposób. Jest to ``App\ExchangeRate\ApiResponse``. Używając jej zawsze dostaniemy zwrotkę w formacie
```json
{
    "message": "",
    "data": {
        
    }
}
```

Nadpisałem też trochę domyślny mechanizm serwowania błędów od Symfony, aby nie zwracał odpowiedzi w formie HTML,
a także używał wspomnianego wyżej formatu.

### Obsługa wyjątków
Wszystkie wyjątki przechwytywane są w klasie kontrollera ``App\Controller\ExchangeRatesController``.
Poza wyjątkiem, który informuje nas o wyjściu poza zakres w datach, wyrzucamy klientowi ogólny komunikat
błędu aby nie dotarło do niego zbyt dużo informacji "od zaplecza", które to są słane do logów celem ewentualnego
debugowania 

