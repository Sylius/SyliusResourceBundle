<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('pl', array (
  'validators' => 
  array (
    'This value should be false.' => 'Ta wartość powinna być fałszem.',
    'This value should be true.' => 'Ta wartość powinna być prawdą.',
    'This value should be of type {{ type }}.' => 'Ta wartość powinna być typu {{ type }}.',
    'This value should be blank.' => 'Ta wartość powinna być pusta.',
    'The value you selected is not a valid choice.' => 'Ta wartość powinna być jedną z podanych opcji.',
    'You must select at least {{ limit }} choice.|You must select at least {{ limit }} choices.' => 'Powinieneś wybrać co najmniej {{ limit }} opcję.|Powinieneś wybrać co najmniej {{ limit }} opcje.|Powinieneś wybrać co najmniej {{ limit }} opcji.',
    'You must select at most {{ limit }} choice.|You must select at most {{ limit }} choices.' => 'Powinieneś wybrać maksymalnie {{ limit }} opcję.|Powinieneś wybrać maksymalnie {{ limit }} opcje.|Powinieneś wybrać maksymalnie {{ limit }} opcji.',
    'One or more of the given values is invalid.' => 'Jedna lub więcej z podanych wartości jest nieprawidłowa.',
    'This field was not expected.' => 'Tego pola się nie spodziewano.',
    'This field is missing.' => 'Tego pola brakuje.',
    'This value is not a valid date.' => 'Ta wartość nie jest prawidłową datą.',
    'This value is not a valid datetime.' => 'Ta wartość nie jest prawidłową datą i czasem.',
    'This value is not a valid email address.' => 'Ta wartość nie jest prawidłowym adresem email.',
    'The file could not be found.' => 'Plik nie mógł zostać odnaleziony.',
    'The file is not readable.' => 'Nie można odczytać pliku.',
    'The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.' => 'Plik jest za duży ({{ size }} {{ suffix }}). Maksymalny dozwolony rozmiar to {{ limit }} {{ suffix }}.',
    'The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}.' => 'Nieprawidłowy typ mime pliku ({{ type }}). Dozwolone typy mime to {{ types }}.',
    'This value should be {{ limit }} or less.' => 'Ta wartość powinna wynosić {{ limit }} lub mniej.',
    'This value is too long. It should have {{ limit }} character or less.|This value is too long. It should have {{ limit }} characters or less.' => 'Ta wartość jest zbyt długa. Powinna mieć {{ limit }} lub mniej znaków.|Ta wartość jest zbyt długa. Powinna mieć {{ limit }} lub mniej znaków.|Ta wartość jest zbyt długa. Powinna mieć {{ limit }} lub mniej znaków.',
    'This value should be {{ limit }} or more.' => 'Ta wartość powinna wynosić {{ limit }} lub więcej.',
    'This value is too short. It should have {{ limit }} character or more.|This value is too short. It should have {{ limit }} characters or more.' => 'Ta wartość jest zbyt krótka. Powinna mieć {{ limit }} lub więcej znaków.|Ta wartość jest zbyt krótka. Powinna mieć {{ limit }} lub więcej znaków.|Ta wartość jest zbyt krótka. Powinna mieć {{ limit }} lub więcej znaków.',
    'This value should not be blank.' => 'Ta wartość nie powinna być pusta.',
    'This value should not be null.' => 'Ta wartość nie powinna być nullem.',
    'This value should be null.' => 'Ta wartość powinna być nullem.',
    'This value is not valid.' => 'Ta wartość jest nieprawidłowa.',
    'This value is not a valid time.' => 'Ta wartość nie jest prawidłowym czasem.',
    'This value is not a valid URL.' => 'Ta wartość nie jest prawidłowym adresem URL.',
    'The two values should be equal.' => 'Obie wartości powinny być równe.',
    'The file is too large. Allowed maximum size is {{ limit }} {{ suffix }}.' => 'Plik jest za duży. Maksymalny dozwolony rozmiar to {{ limit }} {{ suffix }}.',
    'The file is too large.' => 'Plik jest za duży.',
    'The file could not be uploaded.' => 'Plik nie mógł być wgrany.',
    'This value should be a valid number.' => 'Ta wartość powinna być prawidłową liczbą.',
    'This file is not a valid image.' => 'Ten plik nie jest obrazem.',
    'This is not a valid IP address.' => 'To nie jest prawidłowy adres IP.',
    'This value is not a valid language.' => 'Ta wartość nie jest prawidłowym językiem.',
    'This value is not a valid locale.' => 'Ta wartość nie jest prawidłową lokalizacją.',
    'This value is not a valid country.' => 'Ta wartość nie jest prawidłową nazwą kraju.',
    'This value is already used.' => 'Ta wartość jest już wykorzystywana.',
    'The size of the image could not be detected.' => 'Nie można wykryć rozmiaru obrazka.',
    'The image width is too big ({{ width }}px). Allowed maximum width is {{ max_width }}px.' => 'Szerokość obrazka jest zbyt duża ({{ width }}px). Maksymalna dopuszczalna szerokość to {{ max_width }}px.',
    'The image width is too small ({{ width }}px). Minimum width expected is {{ min_width }}px.' => 'Szerokość obrazka jest zbyt mała ({{ width }}px). Oczekiwana minimalna szerokość to {{ min_width }}px.',
    'The image height is too big ({{ height }}px). Allowed maximum height is {{ max_height }}px.' => 'Wysokość obrazka jest zbyt duża ({{ height }}px). Maksymalna dopuszczalna wysokość to {{ max_height }}px.',
    'The image height is too small ({{ height }}px). Minimum height expected is {{ min_height }}px.' => 'Wysokość obrazka jest zbyt mała ({{ height }}px). Oczekiwana minimalna wysokość to {{ min_height }}px.',
    'This value should be the user\'s current password.' => 'Ta wartość powinna być aktualnym hasłem użytkownika.',
    'This value should have exactly {{ limit }} character.|This value should have exactly {{ limit }} characters.' => 'Ta wartość powinna mieć dokładnie {{ limit }} znak.|Ta wartość powinna mieć dokładnie {{ limit }} znaki.|Ta wartość powinna mieć dokładnie {{ limit }} znaków.',
    'The file was only partially uploaded.' => 'Plik został wgrany tylko częściowo.',
    'No file was uploaded.' => 'Żaden plik nie został wgrany.',
    'No temporary folder was configured in php.ini.' => 'Nie skonfigurowano folderu tymczasowego w php.ini lub skonfigurowany folder nie istnieje.',
    'Cannot write temporary file to disk.' => 'Nie można zapisać pliku tymczasowego na dysku.',
    'A PHP extension caused the upload to fail.' => 'Rozszerzenie PHP spowodowało błąd podczas wgrywania.',
    'This collection should contain {{ limit }} element or more.|This collection should contain {{ limit }} elements or more.' => 'Ten zbiór powinien zawierać {{ limit }} lub więcej elementów.',
    'This collection should contain {{ limit }} element or less.|This collection should contain {{ limit }} elements or less.' => 'Ten zbiór powinien zawierać {{ limit }} lub mniej elementów.',
    'This collection should contain exactly {{ limit }} element.|This collection should contain exactly {{ limit }} elements.' => 'Ten zbiór powinien zawierać dokładnie {{ limit }} element.|Ten zbiór powinien zawierać dokładnie {{ limit }} elementy.|Ten zbiór powinien zawierać dokładnie {{ limit }} elementów.',
    'Invalid card number.' => 'Nieprawidłowy numer karty.',
    'Unsupported card type or invalid card number.' => 'Nieobsługiwany rodzaj karty lub nieprawidłowy numer karty.',
    'This is not a valid International Bank Account Number (IBAN).' => 'Nieprawidłowy międzynarodowy numer rachunku bankowego (IBAN).',
    'This value is not a valid ISBN-10.' => 'Ta wartość nie jest prawidłowym numerem ISBN-10.',
    'This value is not a valid ISBN-13.' => 'Ta wartość nie jest prawidłowym numerem ISBN-13.',
    'This value is neither a valid ISBN-10 nor a valid ISBN-13.' => 'Ta wartość nie jest prawidłowym numerem ISBN-10 ani ISBN-13.',
    'This value is not a valid ISSN.' => 'Ta wartość nie jest prawidłowym numerem ISSN.',
    'This value is not a valid currency.' => 'Ta wartość nie jest prawidłową walutą.',
    'This value should be equal to {{ compared_value }}.' => 'Ta wartość powinna być równa {{ compared_value }}.',
    'This value should be greater than {{ compared_value }}.' => 'Ta wartość powinna być większa niż {{ compared_value }}.',
    'This value should be greater than or equal to {{ compared_value }}.' => 'Ta wartość powinna być większa bądź równa {{ compared_value }}.',
    'This value should be identical to {{ compared_value_type }} {{ compared_value }}.' => 'Ta wartość powinna być identycznego typu {{ compared_value_type }} oraz wartości {{ compared_value }}.',
    'This value should be less than {{ compared_value }}.' => 'Ta wartość powinna być mniejsza niż {{ compared_value }}.',
    'This value should be less than or equal to {{ compared_value }}.' => 'Ta wartość powinna być mniejsza bądź równa {{ compared_value }}.',
    'This value should not be equal to {{ compared_value }}.' => 'Ta wartość nie powinna być równa {{ compared_value }}.',
    'This value should not be identical to {{ compared_value_type }} {{ compared_value }}.' => 'Ta wartość nie powinna być identycznego typu {{ compared_value_type }} oraz wartości {{ compared_value }}.',
    'The image ratio is too big ({{ ratio }}). Allowed maximum ratio is {{ max_ratio }}.' => 'Proporcje obrazu są zbyt duże ({{ ratio }}). Maksymalne proporcje to {{ max_ratio }}.',
    'The image ratio is too small ({{ ratio }}). Minimum ratio expected is {{ min_ratio }}.' => 'Proporcje obrazu są zbyt małe ({{ ratio }}). Minimalne proporcje to {{ min_ratio }}.',
    'The image is square ({{ width }}x{{ height }}px). Square images are not allowed.' => 'Obraz jest kwadratem ({{ width }}x{{ height }}px). Kwadratowe obrazy nie są akceptowane.',
    'The image is landscape oriented ({{ width }}x{{ height }}px). Landscape oriented images are not allowed.' => 'Obraz jest panoramiczny ({{ width }}x{{ height }}px). Panoramiczne zdjęcia nie są akceptowane.',
    'The image is portrait oriented ({{ width }}x{{ height }}px). Portrait oriented images are not allowed.' => 'Obraz jest portretowy ({{ width }}x{{ height }}px). Portretowe zdjęcia nie są akceptowane.',
    'An empty file is not allowed.' => 'Plik nie może być pusty.',
    'The host could not be resolved.' => 'Nazwa hosta nie została rozpoznana.',
    'This value does not match the expected {{ charset }} charset.' => 'Ta wartość nie pasuje do oczekiwanego zestawu znaków {{ charset }}.',
    'This is not a valid Business Identifier Code (BIC).' => 'Ta wartość nie jest poprawnym kodem BIC (Business Identifier Code).',
    'Error' => 'Błąd',
    'This is not a valid UUID.' => 'To nie jest poprawne UUID.',
    'This value should be a multiple of {{ compared_value }}.' => 'Ta wartość powinna być wielokrotnością {{ compared_value }}.',
    'This Business Identifier Code (BIC) is not associated with IBAN {{ iban }}.' => 'Ten kod BIC (Business Identifier Code) nie jest powiązany z międzynarodowym numerem rachunku bankowego (IBAN) {{ iban }}.',
    'This value should be valid JSON.' => 'Ta wartość powinna być prawidłowym formatem JSON.',
    'This collection should contain only unique elements.' => 'Ten zbiór powinien zawierać tylko unikalne elementy.',
    'This value should be positive.' => 'Ta wartość powinna być dodatnia.',
    'This value should be either positive or zero.' => 'Ta wartość powinna być dodatnia lub równa zero.',
    'This value should be negative.' => 'Ta wartość powinna być ujemna.',
    'This value should be either negative or zero.' => 'Ta wartość powinna być ujemna lub równa zero.',
    'This value is not a valid timezone.' => 'Ta wartość nie jest prawidłową strefą czasową.',
    'This password has been leaked in a data breach, it must not be used. Please use another password.' => 'To hasło wyciekło w wyniku naruszenia danych i nie może być użyte. Proszę użyć innego hasła.',
    'This value should be between {{ min }} and {{ max }}.' => 'Ta wartość powinna być pomiędzy {{ min }} a {{ max }}.',
    'This value is not a valid hostname.' => 'Ta wartość nie jest prawidłową nazwą hosta.',
    'The number of elements in this collection should be a multiple of {{ compared_value }}.' => 'Liczba elementów w tym zbiorze powinna być wielokrotnością {{ compared_value }}.',
    'This value should satisfy at least one of the following constraints:' => 'Ta wartość powinna spełniać co najmniej jedną z następujących reguł:',
    'Each element of this collection should satisfy its own set of constraints.' => 'Każdy element w tym zbiorze powinien spełniać własny zestaw reguł.',
    'This value is not a valid International Securities Identification Number (ISIN).' => 'Ta wartość nie jest prawidłowym Międzynarodowym Numerem Identyfikacyjnym Papierów Wartościowych (ISIN).',
    'This value should be a valid expression.' => 'Ta wartość powinna być prawidłowym wyrażeniem.',
    'This value is not a valid CSS color.' => 'Ta wartość nie jest prawidłowym kolorem CSS.',
    'This value is not a valid CIDR notation.' => 'Ta wartość nie jest prawidłową notacją CIDR.',
    'The value of the netmask should be between {{ min }} and {{ max }}.' => 'Wartość maski podsieci powinna być pomiędzy {{ min }} i {{ max }}.',
    'This form should not contain extra fields.' => 'Ten formularz nie powinien zawierać dodatkowych pól.',
    'The uploaded file was too large. Please try to upload a smaller file.' => 'Wgrany plik był za duży. Proszę spróbować wgrać mniejszy plik.',
    'The CSRF token is invalid. Please try to resubmit the form.' => 'Token CSRF jest nieprawidłowy. Proszę spróbować wysłać formularz ponownie.',
    'This value is not a valid HTML5 color.' => 'Ta wartość nie jest prawidłowym kolorem HTML5.',
    'Please enter a valid birthdate.' => 'Proszę wprowadzić prawidłową datę urodzenia.',
    'The selected choice is invalid.' => 'Wybrana wartość jest nieprawidłowa.',
    'The collection is invalid.' => 'Zbiór jest nieprawidłowy.',
    'Please select a valid color.' => 'Proszę wybrać prawidłowy kolor.',
    'Please select a valid country.' => 'Proszę wybrać prawidłowy kraj.',
    'Please select a valid currency.' => 'Proszę wybrać prawidłową walutę.',
    'Please choose a valid date interval.' => 'Proszę wybrać prawidłowy przedział czasowy.',
    'Please enter a valid date and time.' => 'Proszę wprowadzić prawidłową datę i czas.',
    'Please enter a valid date.' => 'Proszę wprowadzić prawidłową datę.',
    'Please select a valid file.' => 'Proszę wybrać prawidłowy plik.',
    'The hidden field is invalid.' => 'Ukryte pole jest nieprawidłowe.',
    'Please enter an integer.' => 'Proszę wprowadzić liczbę całkowitą.',
    'Please select a valid language.' => 'Proszę wybrać prawidłowy język.',
    'Please select a valid locale.' => 'Proszę wybrać prawidłową lokalizację.',
    'Please enter a valid money amount.' => 'Proszę wybrać prawidłową ilość pieniędzy.',
    'Please enter a number.' => 'Proszę wprowadzić liczbę.',
    'The password is invalid.' => 'Hasło jest nieprawidłowe.',
    'Please enter a percentage value.' => 'Proszę wprowadzić wartość procentową.',
    'The values do not match.' => 'Wartości się nie zgadzają.',
    'Please enter a valid time.' => 'Proszę wprowadzić prawidłowy czas.',
    'Please select a valid timezone.' => 'Proszę wybrać prawidłową strefę czasową.',
    'Please enter a valid URL.' => 'Proszę wprowadzić prawidłowy adres URL.',
    'Please enter a valid search term.' => 'Proszę wprowadzić prawidłowy termin wyszukiwania.',
    'Please provide a valid phone number.' => 'Proszę wprowadzić prawidłowy numer telefonu.',
    'The checkbox has an invalid value.' => 'Pole wyboru posiada nieprawidłową wartość.',
    'Please enter a valid email address.' => 'Proszę wprowadzić prawidłowy adres email.',
    'Please select a valid option.' => 'Proszę wybrać prawidłową opcję.',
    'Please select a valid range.' => 'Proszę wybrać prawidłowy zakres.',
    'Please enter a valid week.' => 'Proszę wybrać prawidłowy tydzień.',
    'sylius.resource.not_enabled' => 'Podany zasób jest wyłączony',
    'sylius.resource.not_disabled' => 'Podany zasób jest włączony',
  ),
  'security' => 
  array (
    'An authentication exception occurred.' => 'Wystąpił błąd uwierzytelniania.',
    'Authentication credentials could not be found.' => 'Dane uwierzytelniania nie zostały znalezione.',
    'Authentication request could not be processed due to a system problem.' => 'Żądanie uwierzytelniania nie mogło zostać pomyślnie zakończone z powodu problemu z systemem.',
    'Invalid credentials.' => 'Nieprawidłowe dane.',
    'Cookie has already been used by someone else.' => 'To ciasteczko jest używane przez kogoś innego.',
    'Not privileged to request the resource.' => 'Brak uprawnień dla żądania wskazanego zasobu.',
    'Invalid CSRF token.' => 'Nieprawidłowy token CSRF.',
    'No authentication provider found to support the authentication token.' => 'Nie znaleziono mechanizmu uwierzytelniania zdolnego do obsługi przesłanego tokenu.',
    'No session available, it either timed out or cookies are not enabled.' => 'Brak danych sesji, sesja wygasła lub ciasteczka nie są włączone.',
    'No token could be found.' => 'Nie znaleziono tokenu.',
    'Username could not be found.' => 'Użytkownik o podanej nazwie nie istnieje.',
    'Account has expired.' => 'Konto wygasło.',
    'Credentials have expired.' => 'Dane uwierzytelniania wygasły.',
    'Account is disabled.' => 'Konto jest wyłączone.',
    'Account is locked.' => 'Konto jest zablokowane.',
    'Too many failed login attempts, please try again later.' => 'Zbyt dużo nieudanych prób logowania, proszę spróbować ponownie później.',
    'Invalid or expired login link.' => 'Nieprawidłowy lub wygasły link logowania.',
    'Too many failed login attempts, please try again in %minutes% minute.' => 'Zbyt wiele nieudanych prób logowania, spróbuj ponownie po upływie %minutes% minut.',
    'Too many failed login attempts, please try again in %minutes% minutes.' => 'Zbyt wiele nieudanych prób logowania, spróbuj ponownie po upływie %minutes% minut.',
  ),
  'flashes' => 
  array (
    'sylius.resource.create' => '%resource% został pomyślnie utworzony.',
    'sylius.resource.update' => '%resource% został pomyślnie uaktualniony.',
    'sylius.resource.delete' => '%resource% został pomyślnie usunięty.',
    'sylius.resource.move' => '%resource% został pomyślnie przeniesiony.',
    'sylius.resource.generate' => '%resource%s zostały pomyślnie wygenerowane.',
    'sylius.resource.revert' => '%resource% został pomyślnie przywrócony.',
    'sylius.resource.restore_deleted' => '%resource% został pomyślnie odzyskany.',
    'sylius.resource.enable' => '%resource% został pomyślnie włączony.',
    'sylius.resource.disable' => '%resource% został pomyślnie wyłączony.',
    'sylius.resource.delete_error' => 'Nie można usunąć, %resource% jest w użyciu.',
    'sylius.resource.race_condition_error' => 'Nie można uaktualnić, %resource% został już wcześniej zmodyfikowany.',
    'sylius.resource.something_went_wrong_error' => 'Coś poszło nie tak, spróbuj ponownie.',
  ),
  'messages' => 
  array (
    'sylius.form.collection.add' => 'Dodaj',
    'sylius.form.collection.delete' => 'Usuń',
  ),
  'pagerfanta' => 
  array (
    'Previous' => 'Poprzednia',
    'Next' => 'Następna',
  ),
));

$catalogueEn_US = new MessageCatalogue('en_US', array (
));
$catalogue->addFallbackCatalogue($catalogueEn_US);

return $catalogue;
