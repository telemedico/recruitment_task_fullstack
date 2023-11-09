Fullstack Developer - Tasks
==========

Wprowadzenie
------------
1. Zapoznaj się z poniższymi wytycznymi do pracy.
2. Konkretną treść zadania/zadań do wykonania przesłaliśmy mailem.

Jak zacząć pracę
------------
Należy zrobić Fork z tego repozytorium [Jak forkować repozytorium w GitHub](https://docs.github.com/en/get-started/quickstart/fork-a-repo), w ten sposób tworząc sobie prywatne miejsce do pracy.

### Setup środowiska

  1. **Jeśli masz Dockera**, zacznij od zbudowania dockera (w repozytorium jest Dockerfile) i zaloguj się na kontener:
     1. **(@todo wpisać komendy dockera)**
  1. **Nie masz Dockera?** Nie szkodzi - skonfiguruj sobie lokalny serwer vHosta tak, żeby Twój lokalny serwer (np. Apache) pod domeną wskazaną w zadaniu pokazywał na odpowiedni katalog na dysku (tj. katalog `public/` z repo)
  1. Zainstaluj paczki composera i npm (`$ composer install && npm install`)
  1. Zbuduj appkę frontową w trybie watch (`$ npm run watch`)
  1. …i do dzieła! :)


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
  1. **Utwórz Pull Request do oryginalnego repozytorium**, na podstawie swojego Forka: [Jak tworzyć Pull Request na podstawie forka](https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/proposing-changes-to-your-work-with-pull-requests/creating-a-pull-request-from-a-fork)
  1. **Poza implementacją zależy nam też na informacjach zwrotnych, które posłużą nam w poprawie jakości zadań.** Dlatego prosimy Cię o umieszczenie dodatkowo informacji w opisie tworzonego Pull Requesta:
     1. Faktycznie poświęconego czasu na zadanie (po zakończeniu implementacji)
     1. Feedbacku do samego zadania 
     1. Twoich komentarzy dot. podejścia do zadania itd 
        1. np. _“Robiąc X miałem na względzie Y, zastosowałem podejście Z”_ 
  1. Prosimy, potwierdź nam mailowo wykonanie zadania, wysyłając link do utworzonego Pull Requesta

test