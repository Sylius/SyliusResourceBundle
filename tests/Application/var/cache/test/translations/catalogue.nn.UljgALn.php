<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('nn', array (
  'validators' => 
  array (
    'This value should be false.' => 'Verdien skulle ha vore tom/nei.',
    'This value should be true.' => 'Verdien skulla ha vore satt/ja.',
    'This value should be of type {{ type }}.' => 'Verdien må vere av typen {{ type }}.',
    'This value should be blank.' => 'Verdien skal vere blank.',
    'The value you selected is not a valid choice.' => 'Verdien du valde er ikkje gyldig.',
    'You must select at least {{ limit }} choice.|You must select at least {{ limit }} choices.' => 'Du må gjere minst {{ limit }} val.',
    'You must select at most {{ limit }} choice.|You must select at most {{ limit }} choices.' => 'Du kan maksimalt gjere {{ limit }} val.',
    'One or more of the given values is invalid.' => 'Ein eller fleire av dei opplyste verdiane er ugyldige.',
    'This field was not expected.' => 'Dette feltet var ikkje forventa.',
    'This field is missing.' => 'Dette feltet mangler.',
    'This value is not a valid date.' => 'Verdien er ikkje ein gyldig dato.',
    'This value is not a valid datetime.' => 'Verdien er ikkje ein gyldig dato og tid.',
    'This value is not a valid email address.' => 'Verdien er ikkje ei gyldig e-postadresse.',
    'The file could not be found.' => 'Fila er ikkje funnen.',
    'The file is not readable.' => 'Fila kan ikkje lesast.',
    'The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.' => 'Fila er for stor ({{ size }} {{ suffix }}). Maksimal storleik er {{ limit }} {{ suffix }}.',
    'The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}.' => 'Mime-typen av fila er ugyldig ({{ type }}). Tillatne mime-typar er {{ types }}.',
    'This value should be {{ limit }} or less.' => 'Verdien må vere {{ limit }} eller mindre.',
    'This value is too long. It should have {{ limit }} character or less.|This value is too long. It should have {{ limit }} characters or less.' => 'Verdien er for lang. Den må vere {{ limit }} bokstavar eller mindre.',
    'This value should be {{ limit }} or more.' => 'Verdien må vere {{ limit }} eller meir.',
    'This value is too short. It should have {{ limit }} character or more.|This value is too short. It should have {{ limit }} characters or more.' => 'Verdien er for kort. Den må ha {{ limit }} teikn eller fleire.',
    'This value should not be blank.' => 'Verdien kan ikkje vere blank.',
    'This value should not be null.' => 'Verdien kan ikkje vere tom (null).',
    'This value should be null.' => 'Verdien må vere tom (null).',
    'This value is not valid.' => 'Verdien er ikkje gyldig.',
    'This value is not a valid time.' => 'Verdien er ikkje ei gyldig tidseining.',
    'This value is not a valid URL.' => 'Verdien er ikkje ein gyldig URL.',
    'The two values should be equal.' => 'Dei to verdiane må vere like.',
    'The file is too large. Allowed maximum size is {{ limit }} {{ suffix }}.' => 'Fila er for stor. Den maksimale storleiken er {{ limit }} {{ suffix }}.',
    'The file is too large.' => 'Fila er for stor.',
    'The file could not be uploaded.' => 'Fila kunne ikkje bli lasta opp.',
    'This value should be a valid number.' => 'Verdien må vere eit gyldig tal.',
    'This file is not a valid image.' => 'Fila er ikkje eit gyldig bilete.',
    'This is not a valid IP address.' => 'Dette er ikkje ei gyldig IP-adresse.',
    'This value is not a valid language.' => 'Verdien er ikkje eit gyldig språk.',
    'This value is not a valid locale.' => 'Verdien er ikkje ein gyldig lokalitet (språk/region).',
    'This value is not a valid country.' => 'Verdien er ikkje eit gyldig land.',
    'This value is already used.' => 'Verdien er allereie i bruk.',
    'The size of the image could not be detected.' => 'Storleiken på biletet kunne ikkje oppdagast.',
    'The image width is too big ({{ width }}px). Allowed maximum width is {{ max_width }}px.' => 'Biletbreidda er for stor, ({{ width }} pikslar). Tillaten maksimumsbreidde er {{ max_width }} pikslar.',
    'The image width is too small ({{ width }}px). Minimum width expected is {{ min_width }}px.' => 'Biletbreidda er for liten, ({{ width }} pikslar). Forventa minimumsbreidde er {{ min_width }} pikslar.',
    'The image height is too big ({{ height }}px). Allowed maximum height is {{ max_height }}px.' => 'Bilethøgda er for stor, ({{ height }} pikslar). Tillaten maksimumshøgde er {{ max_height }} pikslar.',
    'The image height is too small ({{ height }}px). Minimum height expected is {{ min_height }}px.' => 'Billethøgda er for låg, ({{ height }} pikslar). Forventa minimumshøgde er {{ min_height }} pikslar.',
    'This value should be the user\'s current password.' => 'Verdien må vere brukaren sitt noverande passord.',
    'This value should have exactly {{ limit }} character.|This value should have exactly {{ limit }} characters.' => 'Verdien må vere nøyaktig {{ limit }} teikn.',
    'The file was only partially uploaded.' => 'Fila vart berre delvis lasta opp.',
    'No file was uploaded.' => 'Inga fil vart lasta opp.',
    'No temporary folder was configured in php.ini.' => 'Førebels mappe (tmp) er ikkje konfigurert i php.ini.',
    'Cannot write temporary file to disk.' => 'Kan ikkje skrive førebels fil til disk.',
    'A PHP extension caused the upload to fail.' => 'Ei PHP-udviding forårsaka feil under opplasting.',
    'This collection should contain {{ limit }} element or more.|This collection should contain {{ limit }} elements or more.' => 'Denne samlinga må innehalde {{ limit }} element eller meir.|Denne samlinga må innehalde {{ limit }} element eller meir.',
    'This collection should contain {{ limit }} element or less.|This collection should contain {{ limit }} elements or less.' => 'Denne samlinga må innehalde {{ limit }} element eller færre.|Denne samlinga må innehalde {{ limit }} element eller færre.',
    'This collection should contain exactly {{ limit }} element.|This collection should contain exactly {{ limit }} elements.' => 'Denne samlinga må innehalde nøyaktig {{ limit }} element.|Denne samlinga må innehalde nøyaktig {{ limit }} element.',
    'Invalid card number.' => 'Ugyldig kortnummer.',
    'Unsupported card type or invalid card number.' => 'Korttypen er ikkje støtta, eller kortnummeret er ugyldig.',
    'This is not a valid International Bank Account Number (IBAN).' => 'Dette er ikkje eit gyldig internasjonalt bankkontonummer (IBAN).',
    'This value is not a valid ISBN-10.' => 'Verdien er ikkje eit gyldig ISBN-10.',
    'This value is not a valid ISBN-13.' => 'Verdien er ikkje eit gyldig ISBN-13.',
    'This value is neither a valid ISBN-10 nor a valid ISBN-13.' => 'Verdien er verken eit gyldig ISBN-10 eller eit gyldig ISBN-13.',
    'This value is not a valid ISSN.' => 'Verdien er ikkje eit gyldig ISSN.',
    'This value is not a valid currency.' => 'Verdien er ikkje ein gyldig valuta.',
    'This value should be equal to {{ compared_value }}.' => 'Verdien bør vera eins med {{ compared_value }}.',
    'This value should be greater than {{ compared_value }}.' => 'Verdien bør vera større enn {{ compared_value }}.',
    'This value should be greater than or equal to {{ compared_value }}.' => 'Verdien bør vera større enn eller eins med {{ compared_value }}.',
    'This value should be identical to {{ compared_value_type }} {{ compared_value }}.' => 'Verdien bør vera eins med {{ compared_value_type }} {{ compared_value }}.',
    'This value should be less than {{ compared_value }}.' => 'Verdien bør vera mindre enn {{ compared_value }}.',
    'This value should be less than or equal to {{ compared_value }}.' => 'Verdi bør vera mindre enn eller eins med {{ compared_value }}.',
    'This value should not be equal to {{ compared_value }}.' => 'Verdi bør ikkje vera eins med {{ compared_value }}.',
    'This value should not be identical to {{ compared_value_type }} {{ compared_value }}.' => 'Denne verdien bør ikkje vera eins med {{ compared_value_type }} {{ compared_value }}.',
    'The image ratio is too big ({{ ratio }}). Allowed maximum ratio is {{ max_ratio }}.' => 'Sideforholdet til biletet er for stort ({{ ratio }}). Sideforholdet kan ikkje vere større enn {{ max_ratio }}.',
    'The image ratio is too small ({{ ratio }}). Minimum ratio expected is {{ min_ratio }}.' => 'Sideforholdet til biletet er for lite ({{ ratio }}). Sideforholdet kan ikkje vere mindre enn {{ min_ratio }}.',
    'The image is square ({{ width }}x{{ height }}px). Square images are not allowed.' => 'Biletet er kvadratisk ({{ width }}x{{ height }}px). Kvadratiske bilete er ikkje tillatne.',
    'The image is landscape oriented ({{ width }}x{{ height }}px). Landscape oriented images are not allowed.' => 'Biletet er landskapsorientert ({{ width }}x{{ height }}px). Landskapsorienterte bilete er ikkje tillatne.',
    'The image is portrait oriented ({{ width }}x{{ height }}px). Portrait oriented images are not allowed.' => 'Biletet er portrettorientert ({{ width }}x{{ height }}px). Portrettorienterte bilete er ikkje tillatne.',
    'An empty file is not allowed.' => 'Ei tom fil er ikkje tillate.',
    'The host could not be resolved.' => 'Verten kunne ikkje finnast.',
    'This value does not match the expected {{ charset }} charset.' => 'Verdien stemmer ikkje med forventa {{ charset }} charset.',
    'This is not a valid Business Identifier Code (BIC).' => 'Dette er ikkje ein gyldig Business Identifier Code (BIC).',
    'Error' => 'Feil',
    'This is not a valid UUID.' => 'Dette er ikkje ein gyldig UUID.',
    'This value should be a multiple of {{ compared_value }}.' => 'Verdien bør vera eit multipel av {{ compared_value }}.',
    'This Business Identifier Code (BIC) is not associated with IBAN {{ iban }}.' => 'Denne Business Identifier Code (BIC) er ikkje kopla til IBAN {{ iban }}.',
    'This value should be valid JSON.' => 'Verdien bør vera gyldig JSON.',
    'This collection should contain only unique elements.' => 'Denne samlinga bør berre innehalda unike element.',
    'This value should be positive.' => 'Verdien bør vera positiv.',
    'This value should be either positive or zero.' => 'Verdien bør vera anten positiv eller null.',
    'This value should be negative.' => 'Verdien bør vera negativ.',
    'This value should be either negative or zero.' => 'Verdien bør vera negativ eller null.',
    'This value is not a valid timezone.' => 'Verdien er ikkje ei gyldig tidssone.',
    'This password has been leaked in a data breach, it must not be used. Please use another password.' => 'Dette passordet har lekt ut ved eit datainnbrot, det får ikkje nyttast. Gje opp eit anna passord.',
    'This value should be between {{ min }} and {{ max }}.' => 'Denne verdien bør liggje mellom {{ min }} og {{ max }}.',
    'This value is not a valid hostname.' => 'Verdien er ikkje eit gyldig vertsnamn.',
    'The number of elements in this collection should be a multiple of {{ compared_value }}.' => 'Talet på element i denne samlinga bør vera eit multippel av {{ compared_value }}.',
    'This value should satisfy at least one of the following constraints:' => 'Verdien burde oppfylla minst ein av følgjande avgrensingar:',
    'Each element of this collection should satisfy its own set of constraints.' => 'Kvart element i denne samlinga bør oppfylla sine eigne avgrensingar.',
    'This value is not a valid International Securities Identification Number (ISIN).' => 'Verdien er ikkje eit gyldig International Securities Identification Number (ISIN).',
    'This value should be a valid expression.' => 'Denne verdien skal være et gyldig uttrykk.',
    'This value is not a valid CSS color.' => 'Denne verdien er ikke en gyldig CSS-farge.',
    'This value is not a valid CIDR notation.' => 'Denne verdien er ikke en gyldig CIDR-notasjon.',
    'The value of the netmask should be between {{ min }} and {{ max }}.' => 'Verdien av nettmasken skal være mellom {{ min }} og {{ max }}.',
    'This form should not contain extra fields.' => 'Feltgruppa kan ikkje innehalde ekstra felt.',
    'The uploaded file was too large. Please try to upload a smaller file.' => 'Fila du lasta opp var for stor. Last opp ei mindre fil.',
    'The CSRF token is invalid.' => 'CSRF-nøkkelen er ikkje gyldig.',
    'This value is not a valid HTML5 color.' => 'Verdien er ikkje ein gyldig HTML5-farge.',
    'Please enter a valid birthdate.' => 'Gje opp ein gyldig fødselsdato.',
    'The selected choice is invalid.' => 'Valget du gjorde er ikkje gyldig.',
    'The collection is invalid.' => 'Samlinga er ikkje gyldig.',
    'Please select a valid color.' => 'Gje opp ein gyldig farge.',
    'Please select a valid country.' => 'Gje opp eit gyldig land.',
    'Please select a valid currency.' => 'Gje opp ein gyldig valuta.',
    'Please choose a valid date interval.' => 'Gje opp eit gyldig datointervall.',
    'Please enter a valid date and time.' => 'Gje opp ein gyldig dato og tid.',
    'Please enter a valid date.' => 'Gje opp ein gyldig dato.',
    'Please select a valid file.' => 'Velg ei gyldig fil.',
    'The hidden field is invalid.' => 'Det skjulte feltet er ikkje gyldig.',
    'Please enter an integer.' => 'Gje opp eit heiltal.',
    'Please select a valid language.' => 'Gje opp eit gyldig språk.',
    'Please select a valid locale.' => 'Gje opp eit gyldig locale.',
    'Please enter a valid money amount.' => 'Gje opp ein gyldig sum pengar.',
    'Please enter a number.' => 'Gje opp eit nummer.',
    'The password is invalid.' => 'Passordet er ikkje gyldig.',
    'Please enter a percentage value.' => 'Gje opp ein prosentverdi.',
    'The values do not match.' => 'Verdiane er ikkje eins.',
    'Please enter a valid time.' => 'Gje opp ei gyldig tid.',
    'Please select a valid timezone.' => 'Gje opp ei gyldig tidssone.',
    'Please enter a valid URL.' => 'Gje opp ein gyldig URL.',
    'Please enter a valid search term.' => 'Gje opp gyldige søkjeord.',
    'Please provide a valid phone number.' => 'Gje opp eit gyldig telefonnummer.',
    'The checkbox has an invalid value.' => 'Sjekkboksen har ein ugyldig verdi.',
    'Please enter a valid email address.' => 'Gje opp ei gyldig e-postadresse.',
    'Please select a valid option.' => 'Velg eit gyldig vilkår.',
    'Please select a valid range.' => 'Velg eit gyldig spenn.',
    'Please enter a valid week.' => 'Gje opp ei gyldig veke.',
  ),
  'security' => 
  array (
    'An authentication exception occurred.' => 'Innlogginga har feila.',
    'Authentication credentials could not be found.' => 'Innloggingsinformasjonen vart ikkje funnen.',
    'Authentication request could not be processed due to a system problem.' => 'Innlogginga vart ikkje fullført på grunn av ein systemfeil.',
    'Invalid credentials.' => 'Ugyldig innloggingsinformasjon.',
    'Cookie has already been used by someone else.' => 'Informasjonskapselen er allereie brukt av ein annan brukar.',
    'Not privileged to request the resource.' => 'Du har ikkje åtgang til å be om denne ressursen.',
    'Invalid CSRF token.' => 'Ugyldig CSRF-teikn.',
    'No authentication provider found to support the authentication token.' => 'Fann ingen innloggingstilbydar som støttar dette innloggingsteiknet.',
    'No session available, it either timed out or cookies are not enabled.' => 'Ingen sesjon tilgjengeleg. Sesjonen er anten ikkje lenger gyldig, eller informasjonskapslar er ikkje skrudd på i nettlesaren.',
    'No token could be found.' => 'Fann ingen innloggingsteikn.',
    'Username could not be found.' => 'Fann ikkje brukarnamnet.',
    'Account has expired.' => 'Brukarkontoen er utgjengen.',
    'Credentials have expired.' => 'Innloggingsinformasjonen er utgjengen.',
    'Account is disabled.' => 'Brukarkontoen er sperra.',
    'Account is locked.' => 'Brukarkontoen er sperra.',
    'Too many failed login attempts, please try again later.' => 'For mange innloggingsforsøk har feila, prøv igjen seinare.',
    'Invalid or expired login link.' => 'Innloggingslenka er ugyldig eller utgjengen.',
    'Too many failed login attempts, please try again in %minutes% minute.' => 'For mange mislykkede påloggingsforsøk, prøv igjen om %minutes% minutt.',
    'Too many failed login attempts, please try again in %minutes% minutes.' => 'For mange mislykkede påloggingsforsøk, prøv igjen om %minutes% minutter.',
  ),
));

$catalogueNo = new MessageCatalogue('no', array (
  'validators' => 
  array (
    'This value should be false.' => 'Verdien må være usann.',
    'This value should be true.' => 'Verdien må være sann.',
    'This value should be of type {{ type }}.' => 'Verdien skal ha typen {{ type }}.',
    'This value should be blank.' => 'Verdien skal være blank.',
    'The value you selected is not a valid choice.' => 'Den valgte verdien er ikke gyldig.',
    'You must select at least {{ limit }} choice.|You must select at least {{ limit }} choices.' => 'Du må velge minst {{ limit }} valg.',
    'You must select at most {{ limit }} choice.|You must select at most {{ limit }} choices.' => 'Du kan maks velge {{ limit }} valg.',
    'One or more of the given values is invalid.' => 'En eller flere av de oppgitte verdiene er ugyldige.',
    'This field was not expected.' => 'Dette feltet var ikke forventet.',
    'This field is missing.' => 'Dette feltet mangler.',
    'This value is not a valid date.' => 'Verdien er ikke en gyldig dato.',
    'This value is not a valid datetime.' => 'Verdien er ikke en gyldig dato/tid.',
    'This value is not a valid email address.' => 'Verdien er ikke en gyldig e-postadresse.',
    'The file could not be found.' => 'Filen kunne ikke finnes.',
    'The file is not readable.' => 'Filen er ikke lesbar.',
    'The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.' => 'Filen er for stor ({{ size }} {{ suffix }}). Tilatte maksimale størrelse {{ limit }} {{ suffix }}.',
    'The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}.' => 'Mimetypen av filen er ugyldig ({{ type }}). Tilatte mimetyper er {{ types }}.',
    'This value should be {{ limit }} or less.' => 'Verdien må være {{ limit }} tegn lang eller mindre.',
    'This value is too long. It should have {{ limit }} character or less.|This value is too long. It should have {{ limit }} characters or less.' => 'Verdien er for lang. Den må ha {{ limit }} tegn eller mindre.',
    'This value should be {{ limit }} or more.' => 'Verdien må være {{ limit }} eller mer.',
    'This value is too short. It should have {{ limit }} character or more.|This value is too short. It should have {{ limit }} characters or more.' => 'Verdien er for kort. Den må ha {{ limit }} tegn eller flere.',
    'This value should not be blank.' => 'Verdien kan ikke være blank.',
    'This value should not be null.' => 'Verdien kan ikke være tom (null).',
    'This value should be null.' => 'Verdien skal være tom (null).',
    'This value is not valid.' => 'Verdien er ugyldig.',
    'This value is not a valid time.' => 'Verdien er ikke en gyldig tid.',
    'This value is not a valid URL.' => 'Verdien er ikke en gyldig URL.',
    'The two values should be equal.' => 'Verdiene skal være identiske.',
    'The file is too large. Allowed maximum size is {{ limit }} {{ suffix }}.' => 'Filen er for stor. Den maksimale størrelsen er {{ limit }} {{ suffix }}.',
    'The file is too large.' => 'Filen er for stor.',
    'The file could not be uploaded.' => 'Filen kunne ikke lastes opp.',
    'This value should be a valid number.' => 'Verdien skal være et gyldig tall.',
    'This file is not a valid image.' => 'Denne filen er ikke et gyldig bilde.',
    'This is not a valid IP address.' => 'Dette er ikke en gyldig IP adresse.',
    'This value is not a valid language.' => 'Verdien er ikke et gyldig språk.',
    'This value is not a valid locale.' => 'Verdien er ikke en gyldig lokalitet.',
    'This value is not a valid country.' => 'Verdien er ikke et gyldig navn på land.',
    'This value is already used.' => 'Verdien er allerede brukt.',
    'The size of the image could not be detected.' => 'Bildestørrelsen kunne ikke oppdages.',
    'The image width is too big ({{ width }}px). Allowed maximum width is {{ max_width }}px.' => 'Bildebredden er for stor ({{ width }} piksler). Tillatt maksimumsbredde er {{ max_width }} piksler.',
    'The image width is too small ({{ width }}px). Minimum width expected is {{ min_width }}px.' => 'Bildebredden er for liten ({{ width }} piksler). Forventet minimumsbredde er {{ min_width }} piksler.',
    'The image height is too big ({{ height }}px). Allowed maximum height is {{ max_height }}px.' => 'Bildehøyden er for stor ({{ height }} piksler). Tillatt maksimumshøyde er {{ max_height }} piksler.',
    'The image height is too small ({{ height }}px). Minimum height expected is {{ min_height }}px.' => 'Bildehøyden er for liten ({{ height }} piksler). Forventet minimumshøyde er {{ min_height }} piksler.',
    'This value should be the user\'s current password.' => 'Verdien skal være brukerens sitt nåværende passord.',
    'This value should have exactly {{ limit }} character.|This value should have exactly {{ limit }} characters.' => 'Verdien skal være nøyaktig {{ limit }} tegn.',
    'The file was only partially uploaded.' => 'Filen var kun delvis opplastet.',
    'No file was uploaded.' => 'Ingen fil var lastet opp.',
    'No temporary folder was configured in php.ini.' => 'Den midlertidige mappen (tmp) er ikke konfigurert i php.ini.',
    'Cannot write temporary file to disk.' => 'Kan ikke skrive midlertidig fil til disk.',
    'A PHP extension caused the upload to fail.' => 'En PHP-utvidelse forårsaket en feil under opplasting.',
    'This collection should contain {{ limit }} element or more.|This collection should contain {{ limit }} elements or more.' => 'Denne samlingen må inneholde {{ limit }} element eller flere.|Denne samlingen må inneholde {{ limit }} elementer eller flere.',
    'This collection should contain {{ limit }} element or less.|This collection should contain {{ limit }} elements or less.' => 'Denne samlingen må inneholde {{ limit }} element eller færre.|Denne samlingen må inneholde {{ limit }} elementer eller færre.',
    'This collection should contain exactly {{ limit }} element.|This collection should contain exactly {{ limit }} elements.' => 'Denne samlingen må inneholde nøyaktig {{ limit }} element.|Denne samlingen må inneholde nøyaktig {{ limit }} elementer.',
    'Invalid card number.' => 'Ugyldig kortnummer.',
    'Unsupported card type or invalid card number.' => 'Korttypen er ikke støttet eller kortnummeret er ugyldig.',
    'This is not a valid International Bank Account Number (IBAN).' => 'Dette er ikke et gyldig IBAN-nummer.',
    'This value is not a valid ISBN-10.' => 'Verdien er ikke en gyldig ISBN-10.',
    'This value is not a valid ISBN-13.' => 'Verdien er ikke en gyldig ISBN-13.',
    'This value is neither a valid ISBN-10 nor a valid ISBN-13.' => 'Verdien er hverken en gyldig ISBN-10 eller ISBN-13.',
    'This value is not a valid ISSN.' => 'Verdien er ikke en gyldig ISSN.',
    'This value is not a valid currency.' => 'Verdien er ikke gyldig valuta.',
    'This value should be equal to {{ compared_value }}.' => 'Verdien skal være lik {{ compared_value }}.',
    'This value should be greater than {{ compared_value }}.' => 'Verdien skal være større enn {{ compared_value }}.',
    'This value should be greater than or equal to {{ compared_value }}.' => 'Verdien skal være større enn eller lik {{ compared_value }}.',
    'This value should be identical to {{ compared_value_type }} {{ compared_value }}.' => 'Verdien skal være identisk med {{ compared_value_type }} {{ compared_value }}.',
    'This value should be less than {{ compared_value }}.' => 'Verdien skal være mindre enn {{ compared_value }}.',
    'This value should be less than or equal to {{ compared_value }}.' => 'Verdien skal være mindre enn eller lik {{ compared_value }}.',
    'This value should not be equal to {{ compared_value }}.' => 'Verdien skal ikke være lik {{ compared_value }}.',
    'This value should not be identical to {{ compared_value_type }} {{ compared_value }}.' => 'Verdien skal ikke være identisk med {{ compared_value_type }} {{ compared_value }}.',
    'The image ratio is too big ({{ ratio }}). Allowed maximum ratio is {{ max_ratio }}.' => 'Bildeforholdet er for stort ({{ ratio }}). Tillatt bildeforhold er maks {{ max_ratio }}.',
    'The image ratio is too small ({{ ratio }}). Minimum ratio expected is {{ min_ratio }}.' => 'Bildeforholdet er for lite ({{ ratio }}). Forventet bildeforhold er minst {{ min_ratio }}.',
    'The image is square ({{ width }}x{{ height }}px). Square images are not allowed.' => 'Bildet er en kvadrat ({{ width }}x{{ height }}px). Kvadratiske bilder er ikke tillatt.',
    'The image is landscape oriented ({{ width }}x{{ height }}px). Landscape oriented images are not allowed.' => 'Bildet er i liggende retning ({{ width }}x{{ height }}px). Bilder i liggende retning er ikke tillatt.',
    'The image is portrait oriented ({{ width }}x{{ height }}px). Portrait oriented images are not allowed.' => 'Bildet er i stående retning ({{ width }}x{{ height }}px). Bilder i stående retning er ikke tillatt.',
    'An empty file is not allowed.' => 'Tomme filer er ikke tilatt.',
    'The host could not be resolved.' => 'Vertsnavn kunne ikke løses.',
    'This value does not match the expected {{ charset }} charset.' => 'Verdien samsvarer ikke med forventet tegnsett {{ charset }}.',
    'This is not a valid Business Identifier Code (BIC).' => 'Dette er ikke en gyldig BIC.',
    'Error' => 'Feil',
    'This is not a valid UUID.' => 'Dette er ikke en gyldig UUID.',
    'This value should be a multiple of {{ compared_value }}.' => 'Verdien skal være flertall av {{ compared_value }}.',
    'This Business Identifier Code (BIC) is not associated with IBAN {{ iban }}.' => 'Business Identifier Code (BIC) er ikke tilknyttet en IBAN {{ iban }}.',
    'This value should be valid JSON.' => 'Verdien er ikke gyldig JSON.',
    'This collection should contain only unique elements.' => 'Samlingen kan kun inneholde unike elementer.',
    'This value should be positive.' => 'Denne verdien må være positiv.',
    'This value should be either positive or zero.' => 'Denne verdien må være positiv eller null.',
    'This value should be negative.' => 'Denne verdien må være negativ.',
    'This value should be either negative or zero.' => 'Denne verdien må være negativ eller null.',
    'This value is not a valid timezone.' => 'Verdien er ikke en gyldig tidssone.',
    'This password has been leaked in a data breach, it must not be used. Please use another password.' => 'Dette passordet er lekket i et datainnbrudd, det må ikke tas i bruk. Vennligst bruk et annet passord.',
    'This value should be between {{ min }} and {{ max }}.' => 'Verdien må være mellom {{ min }} og {{ max }}.',
    'This value is not a valid hostname.' => 'Denne verdien er ikke et gyldig vertsnavn.',
    'The number of elements in this collection should be a multiple of {{ compared_value }}.' => 'Antall elementer i denne samlingen bør være et multiplum av {{ compared_value }}.',
    'This value should satisfy at least one of the following constraints:' => 'Denne verdien skal tilfredsstille minst en av følgende begrensninger:',
    'Each element of this collection should satisfy its own set of constraints.' => 'Hvert element i denne samlingen skal tilfredsstille sitt eget sett med begrensninger.',
    'This value is not a valid International Securities Identification Number (ISIN).' => 'Denne verdien er ikke et gyldig International Securities Identification Number (ISIN).',
    'This value should be a valid expression.' => 'Denne verdien skal være et gyldig uttrykk.',
    'This value is not a valid CSS color.' => 'Denne verdien er ikke en gyldig CSS-farge.',
    'This value is not a valid CIDR notation.' => 'Denne verdien er ikke en gyldig CIDR-notasjon.',
    'The value of the netmask should be between {{ min }} and {{ max }}.' => 'Verdien på nettmasken skal være mellom {{ min }} og {{ max }}.',
    'This form should not contain extra fields.' => 'Feltgruppen må ikke inneholde ekstra felter.',
    'The uploaded file was too large. Please try to upload a smaller file.' => 'Den opplastede filen var for stor. Vennligst last opp en mindre fil.',
    'The CSRF token is invalid.' => 'CSRF nøkkelen er ugyldig.',
    'This value is not a valid HTML5 color.' => 'Denne verdien er ikke en gyldig HTML5-farge.',
    'Please enter a valid birthdate.' => 'Vennligst oppgi gyldig fødselsdato.',
    'The selected choice is invalid.' => 'Det valgte valget er ugyldig.',
    'The collection is invalid.' => 'Samlingen er ugyldig.',
    'Please select a valid color.' => 'Velg en gyldig farge.',
    'Please select a valid country.' => 'Vennligst velg et gyldig land.',
    'Please select a valid currency.' => 'Vennligst velg en gyldig valuta.',
    'Please choose a valid date interval.' => 'Vennligst velg et gyldig datointervall.',
    'Please enter a valid date and time.' => 'Vennligst angi en gyldig dato og tid.',
    'Please enter a valid date.' => 'Vennligst oppgi en gyldig dato.',
    'Please select a valid file.' => 'Vennligst velg en gyldig fil.',
    'The hidden field is invalid.' => 'Det skjulte feltet er ugyldig.',
    'Please enter an integer.' => 'Vennligst skriv inn et heltall.',
    'Please select a valid language.' => 'Vennligst velg et gyldig språk.',
    'Please select a valid locale.' => 'Vennligst velg et gyldig sted.',
    'Please enter a valid money amount.' => 'Vennligst angi et gyldig pengebeløp.',
    'Please enter a number.' => 'Vennligst skriv inn et nummer.',
    'The password is invalid.' => 'Passordet er ugyldig.',
    'Please enter a percentage value.' => 'Vennligst angi en prosentverdi.',
    'The values do not match.' => 'Verdiene stemmer ikke overens.',
    'Please enter a valid time.' => 'Vennligst angi et gyldig tidspunkt.',
    'Please select a valid timezone.' => 'Vennligst velg en gyldig tidssone.',
    'Please enter a valid URL.' => 'Vennligst skriv inn en gyldig URL.',
    'Please enter a valid search term.' => 'Vennligst angi et gyldig søketerm.',
    'Please provide a valid phone number.' => 'Vennligst oppgi et gyldig telefonnummer.',
    'The checkbox has an invalid value.' => 'Avkrysningsboksen har en ugyldig verdi.',
    'Please enter a valid email address.' => 'Vennligst skriv inn en gyldig e-post adresse.',
    'Please select a valid option.' => 'Vennligst velg et gyldig alternativ.',
    'Please select a valid range.' => 'Vennligst velg et gyldig område.',
    'Please enter a valid week.' => 'Vennligst skriv inn en gyldig uke.',
  ),
  'security' => 
  array (
    'An authentication exception occurred.' => 'En autentiseringsfeil har skjedd.',
    'Authentication credentials could not be found.' => 'Påloggingsinformasjonen kunne ikke bli funnet.',
    'Authentication request could not be processed due to a system problem.' => 'Autentiserings forespørselen kunne ikke bli prosessert grunnet en system feil.',
    'Invalid credentials.' => 'Ugyldig påloggingsinformasjon.',
    'Cookie has already been used by someone else.' => 'Cookie har allerede blitt brukt av noen andre.',
    'Not privileged to request the resource.' => 'Ingen tilgang til å be om gitt ressurs.',
    'Invalid CSRF token.' => 'Ugyldig CSRF token.',
    'No authentication provider found to support the authentication token.' => 'Ingen autentiserings tilbyder funnet som støtter gitt autentiserings token.',
    'No session available, it either timed out or cookies are not enabled.' => 'Ingen sesjon tilgjengelig, sesjonen er enten utløpt eller cookies ikke skrudd på.',
    'No token could be found.' => 'Ingen token kunne bli funnet.',
    'Username could not be found.' => 'Brukernavn kunne ikke bli funnet.',
    'Account has expired.' => 'Brukerkonto har utgått.',
    'Credentials have expired.' => 'Påloggingsinformasjon har utløpt.',
    'Account is disabled.' => 'Brukerkonto er deaktivert.',
    'Account is locked.' => 'Brukerkonto er sperret.',
    'Too many failed login attempts, please try again later.' => 'For mange mislykkede påloggingsforsøk. Prøv igjen senere.',
    'Invalid or expired login link.' => 'Ugyldig eller utløpt påloggingskobling.',
    'Too many failed login attempts, please try again in %minutes% minute.' => 'For mange mislykkede påloggingsforsøk, prøv igjen om %minutes% minutt.',
    'Too many failed login attempts, please try again in %minutes% minutes.' => 'For mange mislykkede påloggingsforsøk, prøv igjen om %minutes% minutter.',
  ),
  'flashes' => 
  array (
    'sylius.resource.create' => '%resource% er opprettet.',
    'sylius.resource.update' => '%resource% er oppdatert.',
    'sylius.resource.delete' => '%resource% er slettet.',
    'sylius.resource.move' => '%resource% har blitt flyttet.',
    'sylius.resource.generate' => '%resource%s har blitt generert.',
    'sylius.resource.revert' => '%resource% har blitt tilbakestilt.',
    'sylius.resource.restore_deleted' => '%resource% har blitt gjenopprettet.',
    'sylius.resource.enable' => '%resource% er aktivert.',
    'sylius.resource.disable' => '%resource% er deaktivert.',
    'sylius.resource.delete_error' => 'Kan ikke slette, %resource% er i bruk.',
    'sylius.resource.race_condition_error' => 'Kan ikke oppdatere, %resource% ble endret.',
    'sylius.resource.something_went_wrong_error' => 'Det har oppstått en feil. Vennligst prøv igjen.',
  ),
  'messages' => 
  array (
    'sylius.form.collection.add' => 'Legg til',
    'sylius.form.collection.delete' => 'Slett',
  ),
  'pagerfanta' => 
  array (
    'Previous' => 'Forrige',
    'Next' => 'Neste',
  ),
));
$catalogue->addFallbackCatalogue($catalogueNo);
$catalogueEn_US = new MessageCatalogue('en_US', array (
));
$catalogueNo->addFallbackCatalogue($catalogueEn_US);

return $catalogue;