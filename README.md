Fullstack Developer - Tasks
==========
<!-- Zadanie rekrutacyjne
Fullstack Developer @ Telemedi

Zapraszamy Cię do wykonania zadania rekrutacyjnego 🙂 Napisany przez Ciebie kod będzie użyty wyłącznie w celach rekrutacyjnych i nie będzie wykorzystany nigdzie indziej.

Przygotowaliśmy dla Ciebie repozytorium kodu, w którym znajdziesz: bazę kodową, na której należy się oprzeć,  podstawowe wskazówki jak zacząć pracę, jak również wytyczne do implementacji i sposobu oddania zadania.

Repozytorium: https://github.com/telemedico/recruitment_task_fullstack

Zadanie nr 1: tablica z kursami walut
Wyobraźmy sobie, że dla pracowników sieci kantorów wymiany walut mamy przygotować prostą aplikację, prezentującą tabelę z informacjami o kursach kupna i sprzedaży waluty, dla wybranej przez użytkownika daty dziennej.

Przygotuj zarówno frontend (React), jak i backend (PHP Symfony, w formie API), opierając się o istniejące już fragmenty kodu w repozytorium.

Wskazówki:
Kursy walut (kupno+sprzedaż) są ustalane względem średniego kursu waluty w NBP
NBP udostępnia średnie kursy po API - pełna dokumentacja API: https://api.nbp.pl/ (kurs na dany dzień pojawia się w południe!)
Przydatne mogą być endpointy:
https://api.nbp.pl/api/exchangerates/tables/A/?format=json
https://api.nbp.pl/api/exchangerates/rates/A/USD?format=json
Oczekujemy implementacji API backendowego, więc przyjmujemy, że API NBP jest dostępne wyłącznie z poziomu serwera PHP, a nie przeglądarki.

Wymagania:
Wyświetlanie kursów walut nie powinno być pod głównym route aplikacji - należy zaimplementować link w menu kierujący do stworzonego przez siebie route’a, np http://telemedi-zadanie.localhost/exchange-rates
Waluty, których kursy obsługuje kantor, to: euro (EUR), dolar amerykański (USD), korona czeska (CZK), rupia indonezyjska (IDR), real brazylijski (BRL)
ew. zmiana listy walut obsługiwanych może wiązać się z koniecznością wprowadzenia niedużej zmiany w kodzie
Kursy kupna i sprzedaży waluty jest ustalane względem kursów średnich NBP:
dla walut EUR i USD kurs:
kupna jest mniejszy o 0.05 PLN względem kursu średniego
sprzedaży jest większy o 0.07 PLN względem kursu średniego
dla pozostałych walut kurs:
kupna jest pusty - tj. kantor nie prowadzi kupowania danej waluty
sprzedaży jest większy o 0.15 PLN względem kursu średniego
Dane mają prezentować kursy walut względem daty wybranej przez użytkownika (domyślnie ma być wybrana data dzisiejsza)
Zmiana daty ma być możliwa dla użytkownika w UI (dopuszczamy daty od początku 2023 roku)
Zmiana wybranej daty ma powodować sparametryzowanie linku, by można było przesłać komuś skopiowany link do widoku kursów z konkretnej daty.
Na jednym ekranie/widoku, dla wszystkich walut obsługiwanych, mają być jednocześnie prezentowane następujące dane:
kod waluty + jej nazwa
kursy: NBP, kupna i sprzedaży z wybranej przez użytkownika daty (to najważniejsza wartość na stronie)
kursy: NBP, kupna i sprzedaży z dnia dzisiejszego (jako punkt odniesienia do kursu historycznego)
Sposób prezentacji danych zostawiamy Tobie - ale zależy nam na przejrzystym i przemyślanym UI, którego nie trzeba nikomu tłumaczyć, jak działa. -->

------------

### :warning: Zapoznaj się z poniższymi wytycznymi do pracy.
### :warning: Treść zadań do wykonania przesłaliśmy mailem.

------------

Jak zacząć pracę
------------
1. Należy zrobić Fork z tego repozytorium [Jak forkować repozytorium w GitHub](https://docs.github.com/en/get-started/quickstart/fork-a-repo), w ten sposób tworząc sobie prywatne miejsce do pracy.
1. Następnie w stworzonym przez siebie forku repozytorium stwórz branch od gałęzi master, na którym będziesz pracować, np: ` $ git checkout -b MojeZadanieJanKowalski `

### Setup środowiska

  1. Skonfiguruj sobie lokalny serwer (np. Apache) pod development; ustaw vHosta tak, żeby pod wybraną domeną pokazywał na odpowiedni katalog na dysku (tj. katalog `public/` z repo) - przykład poniżej:

        ```
        <VirtualHost *:80>
            # Root - katalog /public z repozytorium z Github
            DocumentRoot "C:/xampp/htdocs/recruitment_task_fullstack/public/"
            # domena lokalna
            ServerName telemedi-zadanie.localhost
        </VirtualHost>
        ```
  1. Jeśli Twoja skonfigurowana domena jest inna niż `telemedi-zadanie.localhost` - zmień ją w pliku `assets/js/components/SetupCheck.js` w metodzie getBaseUrl()
  1. Zainstaluj paczki composera i npm (`$ composer install && npm install`)
  1. Zbuduj appkę frontową w trybie watch (`$ npm run watch --dev`)
  1. …i już, do dzieła! :)

### Setup środowiska za pomocą dockera

  1. Uruchom komendę:
  
        ```
        docker compose up -d
        ```
  1. Pod adresem  `http://telemedi-zadanie.localhost` powinna uruchomić się aplikacja 

------------
_FYI: tak wygląda działająca aplikacja, gotowa do developmentu:_

![Working_app_image](https://github.com/telemedico/recruitment_task_fullstack/blob/master/assets/img/working_app_preview.png?raw=true)

------------

Wytyczne dot. implementacji
------------

**Głównym celem implementacji powinno być pokazanie się z dobrej strony jako programista, czyli nie ma jednego słusznego podejścia! :)**

  1. W ramach implementacji nie należy dodawać nowych paczek do composer’a/npm’a. Zachęcamy do korzystania z tych, które już są dodane.
  1. Development należy prowadzić pod kątem kompatybilności PHP z wersją 7.2.5 (zgodnie z composer.json)
  1. Napisanie testów jest elementem oceny.
  1. **Ocenie podlegać będzie całość podejścia do zadania.**

Niedokończone zadanie też warto podesłać, np. z komentarzem, co by można było dodać - rozumiemy, że czasem nie starcza czasu na wszystko co się chce zrobić!

Zakończenie pracy i wysłanie wyniku
------------
  1. **W swoim forku utwórz Pull Request do brancha master. Nie rób PR do oryginalnego repozytorium** (Pull Requesty do publicznych repo są publiczne)
  1. **Poza implementacją zależy nam też na informacjach zwrotnych, które posłużą nam w poprawie jakości zadań.** Dlatego prosimy Cię o umieszczenie dodatkowo informacji w opisie tworzonego Pull Requesta:
     1. Faktycznie poświęconego czasu na zadanie (po zakończeniu implementacji)
     1. Feedbacku do samego zadania 
     1. Twoich komentarzy dot. podejścia do zadania itd 
        1. np. _“Robiąc X miałem na względzie Y, zastosowałem podejście Z”_ 
  1. **Prosimy, potwierdź nam mailowo wykonanie zadania, wysyłając link do Pull Requesta w swoim forku.**