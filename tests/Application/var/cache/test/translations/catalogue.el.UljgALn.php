<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('el', array (
  'validators' => 
  array (
    'This value should be false.' => 'Αυτή η τιμή πρέπει να είναι ψευδής.',
    'This value should be true.' => 'Αυτή η τιμή πρέπει να είναι αληθής.',
    'This value should be of type {{ type }}.' => 'Αυτή η τιμή πρέπει να είναι τύπου {{ type }}.',
    'This value should be blank.' => 'Αυτή η τιμή πρέπει να είναι κενή.',
    'The value you selected is not a valid choice.' => 'Η τιμή που επιλέχθηκε δεν αντιστοιχεί σε έγκυρη επιλογή.',
    'You must select at least {{ limit }} choice.|You must select at least {{ limit }} choices.' => 'Πρέπει να επιλέξτε τουλάχιστον {{ limit }} επιλογή.|Πρέπει να επιλέξτε τουλάχιστον {{ limit }} επιλογές.',
    'You must select at most {{ limit }} choice.|You must select at most {{ limit }} choices.' => 'Πρέπει να επιλέξτε το πολύ {{ limit }} επιλογή.|Πρέπει να επιλέξτε το πολύ {{ limit }} επιλογές.',
    'One or more of the given values is invalid.' => 'Μια ή περισσότερες τιμές δεν είναι έγκυρες.',
    'This field was not expected.' => 'Αυτό το πεδίο δεν ήταν αναμενόμενο.',
    'This field is missing.' => 'Λείπει αυτό το πεδίο.',
    'This value is not a valid date.' => 'Η τιμή δεν αντιστοιχεί σε έγκυρη ημερομηνία.',
    'This value is not a valid datetime.' => 'Η τιμή δεν αντιστοιχεί σε έγκυρη ημερομηνία και ώρα.',
    'This value is not a valid email address.' => 'Η τιμή δεν αντιστοιχεί σε έγκυρο email.',
    'The file could not be found.' => 'Το αρχείο δε μπορεί να βρεθεί.',
    'The file is not readable.' => 'Το αρχείο δεν είναι αναγνώσιμο.',
    'The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.' => 'Το αρχείο είναι πολύ μεγάλο ({{ size }} {{ suffix }}). Το μέγιστο επιτρεπτό μέγεθος είναι {{ limit }} {{ suffix }}.',
    'The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}.' => 'Ο τύπος mime του αρχείου δεν είναι έγκυρος ({{ type }}). Οι έγκυροι τύποι mime είναι {{ types }}.',
    'This value should be {{ limit }} or less.' => 'Αυτή η τιμή θα έπρεπε να είναι {{ limit }} ή λιγότερο.',
    'This value is too long. It should have {{ limit }} character or less.|This value is too long. It should have {{ limit }} characters or less.' => 'Αυτή η τιμή είναι πολύ μεγάλη. Θα έπρεπε να έχει {{ limit }} χαρακτήρα ή λιγότερο.|Αυτή η τιμή είναι πολύ μεγάλη. Θα έπρεπε να έχει {{ limit }} χαρακτήρες ή λιγότερο.',
    'This value should be {{ limit }} or more.' => 'Αυτή η τιμή θα έπρεπε να είναι {{ limit }} ή περισσότερο.',
    'This value is too short. It should have {{ limit }} character or more.|This value is too short. It should have {{ limit }} characters or more.' => 'Αυτή η τιμή είναι πολύ μικρή. Θα έπρεπε να έχει {{ limit }} χαρακτήρα ή περισσότερο.|Αυτή η τιμή είναι πολύ μικρή. Θα έπρεπε να έχει {{ limit }} χαρακτήρες ή περισσότερο.',
    'This value should not be blank.' => 'Αυτή η τιμή δεν πρέπει να είναι κενή.',
    'This value should not be null.' => 'Αυτή η τιμή δεν πρέπει να είναι μηδενική.',
    'This value should be null.' => 'Αυτή η τιμή πρέπει να είναι μηδενική.',
    'This value is not valid.' => 'Αυτή η τιμή δεν είναι έγκυρη.',
    'This value is not a valid time.' => 'Αυτή η τιμή δεν αντιστοιχεί σε έγκυρη ώρα.',
    'This value is not a valid URL.' => 'Αυτή η τιμή δεν αντιστοιχεί σε έγκυρο URL.',
    'The two values should be equal.' => 'Οι δύο τιμές θα πρέπει να είναι ίδιες.',
    'The file is too large. Allowed maximum size is {{ limit }} {{ suffix }}.' => 'Το αρχείο είναι πολύ μεγάλο. Το μέγιστο επιτρεπτό μέγεθος είναι {{ limit }} {{ suffix }}.',
    'The file is too large.' => 'Το αρχείο είναι πολύ μεγάλο.',
    'The file could not be uploaded.' => 'Το αρχείο δε μπορεί να ανέβει.',
    'This value should be a valid number.' => 'Αυτή η τιμή θα πρέπει να είναι ένας έγκυρος αριθμός.',
    'This file is not a valid image.' => 'Το αρχείο δεν αποτελεί έγκυρη εικόνα.',
    'This is not a valid IP address.' => 'Αυτό δεν είναι μια έγκυρη διεύθυνση IP.',
    'This value is not a valid language.' => 'Αυτή η τιμή δεν αντιστοιχεί σε μια έγκυρη γλώσσα.',
    'This value is not a valid locale.' => 'Αυτή η τιμή δεν αντιστοιχεί σε έγκυρο κωδικό τοποθεσίας.',
    'This value is not a valid country.' => 'Αυτή η τιμή δεν αντιστοιχεί σε μια έγκυρη χώρα.',
    'This value is already used.' => 'Αυτή η τιμή χρησιμοποιείται ήδη.',
    'The size of the image could not be detected.' => 'Το μέγεθος της εικόνας δεν ήταν δυνατό να ανιχνευθεί.',
    'The image width is too big ({{ width }}px). Allowed maximum width is {{ max_width }}px.' => 'Το πλάτος της εικόνας είναι πολύ μεγάλο ({{ width }}px). Το μέγιστο επιτρεπτό πλάτος είναι {{ max_width }}px.',
    'The image width is too small ({{ width }}px). Minimum width expected is {{ min_width }}px.' => 'Το πλάτος της εικόνας είναι πολύ μικρό ({{ width }}px). Το ελάχιστο επιτρεπτό πλάτος είναι {{ min_width }}px.',
    'The image height is too big ({{ height }}px). Allowed maximum height is {{ max_height }}px.' => 'Το ύψος της εικόνας είναι πολύ μεγάλο ({{ height }}px). Το μέγιστο επιτρεπτό ύψος είναι {{ max_height }}px.',
    'The image height is too small ({{ height }}px). Minimum height expected is {{ min_height }}px.' => 'Το ύψος της εικόνας είναι πολύ μικρό ({{ height }}px). Το ελάχιστο επιτρεπτό ύψος είναι {{ min_height }}px.',
    'This value should be the user\'s current password.' => 'Αυτή η τιμή θα έπρεπε να είναι ο τρέχων κωδικός.',
    'This value should have exactly {{ limit }} character.|This value should have exactly {{ limit }} characters.' => 'Αυτή η τιμή θα έπρεπε να έχει ακριβώς {{ limit }} χαρακτήρα.|Αυτή η τιμή θα έπρεπε να έχει ακριβώς {{ limit }} χαρακτήρες.',
    'The file was only partially uploaded.' => 'Το αρχείο δεν ανέβηκε ολόκληρο.',
    'No file was uploaded.' => 'Δεν ανέβηκε κανένα αρχείο.',
    'No temporary folder was configured in php.ini.' => 'Κανένας προσωρινός φάκελος δεν έχει ρυθμιστεί στο php.ini.',
    'Cannot write temporary file to disk.' => 'Αδυναμία εγγραφής προσωρινού αρχείου στο δίσκο.',
    'A PHP extension caused the upload to fail.' => 'Μια επέκταση PHP προκάλεσε αδυναμία ανεβάσματος.',
    'This collection should contain {{ limit }} element or more.|This collection should contain {{ limit }} elements or more.' => 'Αυτή η συλλογή θα πρέπει να περιέχει {{ limit }} στοιχείο ή περισσότερα.|Αυτή η συλλογή θα πρέπει να περιέχει {{ limit }} στοιχεία ή περισσότερα.',
    'This collection should contain {{ limit }} element or less.|This collection should contain {{ limit }} elements or less.' => 'Αυτή η συλλογή θα πρέπει να περιέχει {{ limit }} στοιχείo ή λιγότερα.|Αυτή η συλλογή θα πρέπει να περιέχει {{ limit }} στοιχεία ή λιγότερα.',
    'This collection should contain exactly {{ limit }} element.|This collection should contain exactly {{ limit }} elements.' => 'Αυτή η συλλογή θα πρέπει να περιέχει ακριβώς {{ limit }} στοιχείo.|Αυτή η συλλογή θα πρέπει να περιέχει ακριβώς {{ limit }} στοιχεία.',
    'Invalid card number.' => 'Μη έγκυρος αριθμός κάρτας.',
    'Unsupported card type or invalid card number.' => 'Μη υποστηριζόμενος τύπος κάρτας ή μη έγκυρος αριθμός κάρτας.',
    'This is not a valid International Bank Account Number (IBAN).' => 'Αυτό δεν αντιστοιχεί σε έγκυρο διεθνή αριθμό τραπεζικού λογαριασμού (IBAN).',
    'This value is not a valid ISBN-10.' => 'Αυτό δεν είναι έγκυρος κωδικός ISBN-10.',
    'This value is not a valid ISBN-13.' => 'Αυτό δεν είναι έγκυρος κωδικός ISBN-13.',
    'This value is neither a valid ISBN-10 nor a valid ISBN-13.' => 'Αυτό δεν είναι ούτε έγκυρος κωδικός ISBN-10 ούτε έγκυρος κωδικός ISBN-13.',
    'This value is not a valid ISSN.' => 'Αυτό δεν είναι έγκυρος κωδικός ISSN.',
    'This value is not a valid currency.' => 'Αυτό δεν αντιστοιχεί σε έγκυρο νόμισμα.',
    'This value should be equal to {{ compared_value }}.' => 'Αυτή η τιμή θα πρέπει να είναι ίση με {{ compared_value }}.',
    'This value should be greater than {{ compared_value }}.' => 'Αυτή η τιμή θα πρέπει να είναι μεγαλύτερη από {{ compared_value }}.',
    'This value should be greater than or equal to {{ compared_value }}.' => 'Αυτή η τιμή θα πρέπει να είναι μεγαλύτερη ή ίση με {{ compared_value }}.',
    'This value should be identical to {{ compared_value_type }} {{ compared_value }}.' => 'Αυτή η τιμή θα πρέπει να είναι πανομοιότυπη με {{ compared_value_type }} {{ compared_value }}.',
    'This value should be less than {{ compared_value }}.' => 'Αυτή η τιμή θα πρέπει να είναι μικρότερη από {{ compared_value }}.',
    'This value should be less than or equal to {{ compared_value }}.' => 'Αυτή η τιμή θα πρέπει να είναι μικρότερη ή ίση με {{ compared_value }}.',
    'This value should not be equal to {{ compared_value }}.' => 'Αυτή η τιμή δεν θα πρέπει να είναι ίση με {{ compared_value }}.',
    'This value should not be identical to {{ compared_value_type }} {{ compared_value }}.' => 'Αυτή η τιμή δεν πρέπει να είναι πανομοιότυπη με {{ compared_value_type }} {{ compared_value }}.',
    'The image ratio is too big ({{ ratio }}). Allowed maximum ratio is {{ max_ratio }}.' => 'Η αναλογία πλάτους-ύψους της εικόνας είναι πολύ μεγάλη ({{ ratio }}). Μέγιστη επιτρεπτή αναλογία {{ max_ratio }}.',
    'The image ratio is too small ({{ ratio }}). Minimum ratio expected is {{ min_ratio }}.' => 'Η αναλογία πλάτους-ύψους της εικόνας είναι πολύ μικρή ({{ ratio }}). Ελάχιστη επιτρεπτή αναλογία {{ min_ratio }}.',
    'The image is square ({{ width }}x{{ height }}px). Square images are not allowed.' => 'Η εικόνα είναι τετράγωνη ({{ width }}x{{ height }}px). Δεν επιτρέπονται τετράγωνες εικόνες.',
    'The image is landscape oriented ({{ width }}x{{ height }}px). Landscape oriented images are not allowed.' => 'Η εικόνα έχει οριζόντιο προσανατολισμό ({{ width }}x{{ height }}px). Δεν επιτρέπονται εικόνες με οριζόντιο προσανατολισμό.',
    'The image is portrait oriented ({{ width }}x{{ height }}px). Portrait oriented images are not allowed.' => 'Η εικόνα έχει κάθετο προσανατολισμό ({{ width }}x{{ height }}px). Δεν επιτρέπονται εικόνες με κάθετο προσανατολισμό.',
    'An empty file is not allowed.' => 'Δεν επιτρέπεται κενό αρχείο.',
    'The host could not be resolved.' => 'Η διεύθυνση δεν μπόρεσε να επιλυθεί.',
    'This value does not match the expected {{ charset }} charset.' => 'Αυτή η τιμή δεν ταιριάζει στο αναμενόμενο {{ charset }} σύνολο χαρακτήρων.',
    'This is not a valid Business Identifier Code (BIC).' => 'Αυτός δεν είναι ένας έγκυρος κωδικός BIC.',
    'Error' => 'Σφάλμα',
    'This is not a valid UUID.' => 'Αυτό δεν είναι ένα έγκυρο UUID.',
    'This value should be a multiple of {{ compared_value }}.' => 'Αυτή η τιμή θα έπρεπε να είναι πολλαπλάσιο του {{ compared_value }}.',
    'This Business Identifier Code (BIC) is not associated with IBAN {{ iban }}.' => 'Αυτός ο κωδικός BIC δεν σχετίζεται με το IBAN {{ iban }}.',
    'This value should be valid JSON.' => 'Αυτή η τιμή θα πρέπει να είναι έγκυρο JSON.',
    'This collection should contain only unique elements.' => 'Αυτή η συλλογή θα πρέπει να περιέχει μόνο μοναδικά στοιχεία.',
    'This value should be positive.' => 'Αυτή η τιμή θα πρέπει να είναι θετική.',
    'This value should be either positive or zero.' => 'Αυτή η τιμή θα πρέπει να είναι θετική ή μηδενική.',
    'This value should be negative.' => 'Αυτή η τιμή θα πρέπει να είναι αρνητική.',
    'This value should be either negative or zero.' => 'Αυτή η τιμή θα πρέπει να είναι αρνητική ή μηδενική.',
    'This value is not a valid timezone.' => 'Αυτή η τιμή θα δεν είναι έγκυρη ζώνη ώρας.',
    'This password has been leaked in a data breach, it must not be used. Please use another password.' => 'Αυτός ο κωδικός πρόσβασης έχει διαρρεύσει σε παραβίαση δεδομένων. Παρακαλούμε να χρησιμοποιήσετε έναν άλλο κωδικό.',
    'This value should be between {{ min }} and {{ max }}.' => 'Αυτή η τιμή θα πρέπει να είναι μεταξύ {{ min }} και {{ max }}.',
    'This value is not a valid hostname.' => 'Αυτή η τιμή δεν είναι έγκυρο όνομα υποδοχής.',
    'The number of elements in this collection should be a multiple of {{ compared_value }}.' => 'Το νούμερο των στοιχείων σε αυτή τη συλλογή θα πρέπει να είναι πολλαπλάσιο του {{ compared_value }}.',
    'This value should satisfy at least one of the following constraints:' => 'Αυτή η τιμή θα πρέπει να ικανοποιεί τουλάχιστον έναν από τους παρακάτω περιορισμούς: ',
    'Each element of this collection should satisfy its own set of constraints.' => 'Κάθε στοιχείο σε αυτή τη συλλογή θα πρέπει να ικανοποιεί το δικό του σύνολο περιορισμών.',
    'This value is not a valid International Securities Identification Number (ISIN).' => 'Αυτή η τιμή δεν είναι έγκυρο International Securities Identification Number (ISIN).',
    'This value should be a valid expression.' => 'Αυτή η τιμή θα πρέπει να είναι μία έγκυρη έκφραση.',
    'This value is not a valid CSS color.' => 'Αυτή η τιμή δεν είναι έγκυρο χρώμα CSS.',
    'This value is not a valid CIDR notation.' => 'Αυτή η τιμή δεν είναι έγκυρη CIDR σημειογραφία.',
    'The value of the netmask should be between {{ min }} and {{ max }}.' => 'Η τιμή του netmask πρέπει να είναι ανάμεσα σε {{ min }} και {{ max }}.',
    'This form should not contain extra fields.' => 'Αυτή η φόρμα δεν πρέπει να περιέχει επιπλέον πεδία.',
    'The uploaded file was too large. Please try to upload a smaller file.' => 'Το αρχείο είναι πολύ μεγάλο. Παρακαλούμε προσπαθήστε να ανεβάσετε ένα μικρότερο αρχείο.',
    'The CSRF token is invalid. Please try to resubmit the form.' => 'Το CSRF token δεν είναι έγκυρο. Παρακαλούμε δοκιμάστε να υποβάλετε τη φόρμα ξανά.',
    'This value is not a valid HTML5 color.' => 'Αυτή η τιμή δέν έναι έγκυρο χρώμα HTML5.',
    'Please enter a valid birthdate.' => 'Παρακαλόυμε ειχάγεται μία έγκυρη ημερομηνία γέννησης.',
    'The selected choice is invalid.' => 'Η επιλεγμένη επιλογή δέν είναι έγκυρη.',
    'The collection is invalid.' => 'Η συλλογή δέν είναι έγκυρη.',
    'Please select a valid color.' => 'Παρακαλούμε επιλέξτε ένα έγκυρο χρώμα.',
    'Please select a valid country.' => 'Παρακαλούμε επιλέξτε μία έγκυρη χώρα.',
    'Please select a valid currency.' => 'Παρακαλούμε επιλέξτε ένα έγυρο νόμισμα.',
    'Please choose a valid date interval.' => 'Παρακαλούμε επιλέξτε ένα έγκυρο διάστημα ημερομηνίας.',
    'Please enter a valid date and time.' => 'Παρακαλούμε εισαγάγετε μια έγκυρη ημερομηνία και ώρα.',
    'Please enter a valid date.' => 'Παρακαλούμε εισάγετε μία έγκυρη ημερομηνία.',
    'Please select a valid file.' => 'Παρακαλούμε επιλέξτε ένα έγκυρο αρχείο.',
    'The hidden field is invalid.' => 'Το κρυφό πεδίο δέν είναι έγκυρο.',
    'Please enter an integer.' => 'Παρακαλούμε εισάγετε έναν ακέραιο αριθμό.',
    'Please select a valid language.' => 'Παρακαλούμε επιλέξτε μία έγκυρη γλώσσα.',
    'Please select a valid locale.' => 'Παρακαλούμε επιλέξτε μία έγκυρη τοπικοποίηση.',
    'Please enter a valid money amount.' => 'Παρακαλούμε εισάγετε ένα έγκυρο χρηματικό ποσό.',
    'Please enter a number.' => 'Παρακαλούμε εισάγετε έναν αριθμό.',
    'The password is invalid.' => 'Ο κωδικός δέν είναι έγκυρος.',
    'Please enter a percentage value.' => 'Παρακαλούμε εισάγετε μία ποσοστιαία τιμή.',
    'The values do not match.' => 'Οι τιμές δέν ταιριάζουν.',
    'Please enter a valid time.' => 'Παρακαλούμε εισάγετε μία έγκυρη ώρα.',
    'Please select a valid timezone.' => 'Παρακαλούμε επιλέξτε μία έγυρη ζώνη ώρας.',
    'Please enter a valid URL.' => 'Παρακαλούμε εισάγετε μια έγκυρη διεύθυνση URL.',
    'Please enter a valid search term.' => 'Παρακαλούμε εισάγετε έναν έγκυρο όρο αναζήτησης.',
    'Please provide a valid phone number.' => 'Παρακαλούμε καταχωρίστε έναν έγκυρο αριθμό τηλεφώνου.',
    'The checkbox has an invalid value.' => 'Το πλαίσιο ελέγχου έχει μή έγκυρη τιμή.',
    'Please enter a valid email address.' => 'Παρακαλούμε εισάγετε μία έγκυρη ηλεκτρονική διεύθυνση.',
    'Please select a valid option.' => 'Παρακαλούμε επιλέξτε μία έγκυρη επιλογή.',
    'Please select a valid range.' => 'Παρακαλούμε επιλέξτε ένα έγυρο εύρος.',
    'Please enter a valid week.' => 'Παρακαλούμε εισάγετε μία έγκυρη εβδομάδα.',
    'sylius.resource.not_enabled' => 'Το συγκεκριμένο αντικείμενο είναι απενεργοποιημένο',
    'sylius.resource.not_disabled' => 'Το συγκεκριμένο αντικείμενο είναι ενεργοποιημένο',
  ),
  'security' => 
  array (
    'An authentication exception occurred.' => 'Συνέβη ένα σφάλμα πιστοποίησης.',
    'Authentication credentials could not be found.' => 'Τα στοιχεία πιστοποίησης δε βρέθηκαν.',
    'Authentication request could not be processed due to a system problem.' => 'Το αίτημα πιστοποίησης δε μπορεί να επεξεργαστεί λόγω σφάλματος του συστήματος.',
    'Invalid credentials.' => 'Λανθασμένα στοιχεία σύνδεσης.',
    'Cookie has already been used by someone else.' => 'Το Cookie έχει ήδη χρησιμοποιηθεί από κάποιον άλλο.',
    'Not privileged to request the resource.' => 'Δεν είστε εξουσιοδοτημένος για πρόσβαση στο συγκεκριμένο περιεχόμενο.',
    'Invalid CSRF token.' => 'Μη έγκυρο CSRF token.',
    'No authentication provider found to support the authentication token.' => 'Δε βρέθηκε κάποιος πάροχος πιστοποίησης που να υποστηρίζει το token πιστοποίησης.',
    'No session available, it either timed out or cookies are not enabled.' => 'Δεν υπάρχει ενεργή σύνοδος (session), είτε έχει λήξει ή τα cookies δεν είναι ενεργοποιημένα.',
    'No token could be found.' => 'Δεν ήταν δυνατόν να βρεθεί κάποιο token.',
    'Username could not be found.' => 'Το όνομα χρήστη δε βρέθηκε.',
    'Account has expired.' => 'Ο λογαριασμός έχει λήξει.',
    'Credentials have expired.' => 'Τα στοιχεία σύνδεσης έχουν λήξει.',
    'Account is disabled.' => 'Ο λογαριασμός είναι απενεργοποιημένος.',
    'Account is locked.' => 'Ο λογαριασμός είναι κλειδωμένος.',
    'Too many failed login attempts, please try again later.' => 'Πολλαπλές αποτυχημένες απόπειρες σύνδεσης, παρακαλούμε ξαναδοκιμάστε αργότερα.',
    'Invalid or expired login link.' => 'Μη έγκυρος ή ληγμένος σύνδεσμος σύνδεσης.',
    'Too many failed login attempts, please try again in %minutes% minute.' => 'Πολλαπλές αποτυχημένες απόπειρες σύνδεσης, παρακαλούμε ξαναδοκιμάστε σε %minutes% λεπτό.',
    'Too many failed login attempts, please try again in %minutes% minutes.' => 'Πολλαπλές αποτυχημένες απόπειρες σύνδεσης, παρακαλούμε ξαναδοκιμάστε σε %minutes% λεπτά.',
  ),
  'flashes' => 
  array (
    'sylius.resource.create' => 'Το %resource% δημιουργήθηκε με επιτυχία.',
    'sylius.resource.update' => 'Το %resource% ενημερώθηκε με επιτυχία.',
    'sylius.resource.delete' => 'Το %resource% διαγράφηκε με επιτυχία.',
    'sylius.resource.move' => '%resource% μεταφέρθηκε με επιτυχία.',
    'sylius.resource.generate' => '%resource% δημιουργήθηκε με επιτυχία.',
    'sylius.resource.revert' => '%resource% έχει επανέλθει με επιτυχία.',
    'sylius.resource.restore_deleted' => '%resource%. Επιτυχής επαναφορά.',
    'sylius.resource.enable' => '%resource% ενεργοποιήθηκε με επιτυχία.',
    'sylius.resource.disable' => '%resource% απενεργοποιήθηκε με επιτυχία.',
    'sylius.resource.delete_error' => 'Αδύνατη η διαγραφή, %resource% είναι σε χρήση.',
    'sylius.resource.race_condition_error' => 'Δεν είναι δυνατή η ενημέρωση, %resource% έχει προηγουμένως τροποποιηθεί.',
    'sylius.resource.something_went_wrong_error' => 'Κάτι πήγε στραβά, παρακαλώ προσπαθήστε ξανά.',
  ),
  'messages' => 
  array (
    'sylius.form.collection.add' => 'Προσθήκη',
    'sylius.form.collection.delete' => 'Διαγραφή',
  ),
));

$catalogueEn_US = new MessageCatalogue('en_US', array (
));
$catalogue->addFallbackCatalogue($catalogueEn_US);

return $catalogue;
